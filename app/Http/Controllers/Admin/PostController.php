<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPostRequest;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Comment;
use App\Models\Media;
use App\Models\Post;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('post_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Post::with(['user'])->select(sprintf('%s.*', (new Post)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'post_show';
                $editGate      = 'post_edit';
                $deleteGate    = 'post_delete';
                $viewLikes    = 'like_access';
                $viewComments    = 'comment_access';
                $crudRoutePart = 'posts';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'viewLikes',
                    'viewComments',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            });

            $table->editColumn('body', function ($row) {
                return $row->body ? $row->body : '';
            });
            $table->editColumn('status', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->status ? 'checked' : null) . '>';
            });
            $table->editColumn('visibility', function ($row) {
                return $row->visibility ? $row->visibility : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user', 'status']);

            return $table->make(true);
        }

        return view('admin.posts.index');
    }

    public function create()
    {
        abort_if(Gate::denies('post_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $visibilityOptions = Post::VISIBLITY_SELECT;
        return view('admin.posts.create', compact('visibilityOptions'));
    }

    protected function getMediaTypeFromUrl($url)
    {
        $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);

        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
        $videoExtensions = ['mp4', 'mkv', 'mov', 'avi', 'wmv', 'flv', 'webm'];

        if (in_array(strtolower($extension), $imageExtensions)) {
            return 'image';
        }

        if (in_array(strtolower($extension), $videoExtensions)) {
            return 'video';
        }

        return 'image';
    }

    public function store(StorePostRequest $request)
    {

        $request['user_id'] = auth()->id();
        $post = Post::create($request->all());

        foreach ($request->input('media', []) as $file) {
            $imagePath = storage_path('tmp/uploads/' . basename($file));
            $image = new \Illuminate\Http\UploadedFile($imagePath, basename($imagePath));
            $folder_name = 'posts';
            $path = $image->store($folder_name, env('FILESYSTEM_DRIVER', 'public'));
            $fullUrl = Storage::disk(env('FILESYSTEM_DRIVER'))->url($path);
            $type = $this->getMediaTypeFromUrl($fullUrl);
            Media::create([
                'model_id' => $post->id,
                'model_type' => 'App\Models\Post',
                'media_link' => $fullUrl,
                'type' => $type,
            ]);
        }

        return redirect()->route('admin.posts.index');
    }

    public function edit(Post $post)
    {
        abort_if(Gate::denies('post_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $visibilityOptions = Post::VISIBLITY_SELECT;

        $post->load('user');

        return view('admin.posts.edit', compact('post', 'visibilityOptions'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update($request->all());
        if (count($post->media) > 0) {
            foreach ($post->media as $media) {
                if (! in_array($media->media_link, $request->input('media', []))) {
                    $disk = env('FILESYSTEM_DRIVER', 'public');
                    $baseUrl = Storage::disk($disk)->url('');
                    $relativeFilePath = str_replace($baseUrl, '', $media->media_link);
                    if (Storage::disk($disk)->exists($relativeFilePath)) {
                        Storage::disk($disk)->delete($relativeFilePath);
                    }
                    $media->delete();
                }
            }
        }
        $media = $post->media->pluck('media_link')->toArray();
        foreach ($request->input('media', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $imagePath = storage_path('tmp/uploads/' . basename($file));
                $image = new \Illuminate\Http\UploadedFile($imagePath, basename($imagePath));
                $folder_name = 'posts';
                $path = $image->store($folder_name, env('FILESYSTEM_DRIVER', 'public'));
                $fullUrl = Storage::disk(env('FILESYSTEM_DRIVER'))->url($path);
                $type = $this->getMediaTypeFromUrl($fullUrl);
                Media::create([
                    'model_id' => $post->id,
                    'model_type' => 'App\Models\Post',
                    'media_link' => $fullUrl,
                    'type' => $type,
                ]);
            }
        }

        return redirect()->route('admin.posts.index');
    }

    public function show(Post $post)
    {
        abort_if(Gate::denies('post_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $post->load('user', 'media');

        return view('admin.posts.show', compact('post'));
    }

    public function destroy(Post $post)
    {
        abort_if(Gate::denies('post_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (count($post->media) > 0) {
            foreach ($post->media as $media) {
                if ($media->media_link) {
                    $disk = env('FILESYSTEM_DRIVER', 'public');
                    $baseUrl = Storage::disk($disk)->url('');
                    $relativeFilePath = str_replace($baseUrl, '', $media->media_link);
                    if (Storage::disk($disk)->exists($relativeFilePath)) {
                        Storage::disk($disk)->delete($relativeFilePath);
                    }
                    $media->delete();
                }
            }
        }
        $post->delete();

        return back();
    }

    public function massDestroy(MassDestroyPostRequest $request)
    {
        $posts = Post::find(request('ids'));

        foreach ($posts as $post) {
            if (count($post->media) > 0) {
                foreach ($post->media as $media) {
                    if ($media->media_link) {
                        $disk = env('FILESYSTEM_DRIVER', 'public');
                        $baseUrl = Storage::disk($disk)->url('');
                        $relativeFilePath = str_replace($baseUrl, '', $media->media_link);
                        if (Storage::disk($disk)->exists($relativeFilePath)) {
                            Storage::disk($disk)->delete($relativeFilePath);
                        }
                        $media->delete();
                    }
                }
            }
            $post->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('post_create') && Gate::denies('post_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Post();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function commentStore(StoreCommentRequest $request)
    {
        $request->validate([
            'body'=>'required',
        ]);
   
        $input = $request->all();
        $input['user_id'] = auth()->id();
    
        Comment::create($input);
        $message = 'Comment added successfully!';
        if ($request->parent_id && $request->parent_id > 0) {
            $message = 'Reply added successfully!';
        }

        flash()->success($message);

        return back();
    }
}
