<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\V1\MediaController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyHorseRequest;
use App\Http\Requests\StoreHorseRequest;
use App\Http\Requests\UpdateHorseRequest;
use App\Models\Horse;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class HorseController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('horse_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Horse::with(['user', 'trainers', 'owners'])->select(sprintf('%s.*', (new Horse)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'horse_show';
                $editGate      = 'horse_edit';
                $deleteGate    = 'horse_delete';
                $crudRoutePart = 'horses';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            });

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('type', function ($row) {
                return $row->type ? Horse::TYPE_SELECT[$row->type] : '';
            });
            $table->editColumn('nationality', function ($row) {
                return $row->nationality ? $row->nationality : '';
            });

            $table->editColumn('gender', function ($row) {
                return $row->gender ? Horse::GENDER_SELECT[$row->gender] : '';
            });
            $table->editColumn('blood_type', function ($row) {
                return $row->blood_type ? Horse::BLOOD_TYPE_SELECT[$row->blood_type] : '';
            });
            $table->editColumn('mother_name', function ($row) {
                return $row->mother_name ? $row->mother_name : '';
            });
            $table->editColumn('father_name', function ($row) {
                return $row->father_name ? $row->father_name : '';
            });
            $table->editColumn('trainer', function ($row) {
                $labels = [];
                foreach ($row->trainers as $trainer) {
                    $labels[] = sprintf('<span class="badge badge-info badge-many">%s</span>', $trainer->name);
                }

                return implode(' ', $labels);
            });
            $table->editColumn('owner', function ($row) {
                $labels = [];
                foreach ($row->owners as $owner) {
                    $labels[] = sprintf('<span class="badge badge-info badge-many">%s</span>', $owner->name);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 'user', 'trainer', 'owner']);

            return $table->make(true);
        }

        return view('admin.horses.index');
    }

    public function create()
    {
        abort_if(Gate::denies('horse_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $trainers = User::whereHas('roles', function ($query) {
            $query->where('name', 'Trainer');
        })->pluck('name', 'id');
        $owners = User::whereHas('roles', function ($query) {
            $query->where('name', 'Owner');
        })->pluck('name', 'id');

        return view('admin.horses.create', compact('trainers', 'owners'));
    }

    public function store(StoreHorseRequest $request)
    {
        $fullUrl = null;
        if ($request->input('image', false)) {
            $imagePath = storage_path('tmp/uploads/' . basename($request->input('image')));
            $image = new \Illuminate\Http\UploadedFile($imagePath, basename($imagePath));
            $folder_name = 'horses';
            $path = $image->store($folder_name, env('FILESYSTEM_DRIVER', 'public'));
            $fullUrl = Storage::disk(env('FILESYSTEM_DRIVER'))->url($path);
        }
        $request['user_id'] = auth()->id();
        $request['image'] = $fullUrl;
        $horse = Horse::create($request->all());
        $horse->owners()->sync($request->input('owners', []));
        $horse->trainers()->sync($request->input('trainers', []));
        return redirect()->route('admin.horses.index');
    }


    public function edit(Horse $horse)
    {
        abort_if(Gate::denies('horse_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $trainers = User::whereHas('roles', function ($query) {
            $query->where('name', 'Trainer');
        })->pluck('name', 'id');
        $owners = User::whereHas('roles', function ($query) {
            $query->where('name', 'Owner');
        })->pluck('name', 'id');

        $horse->load('trainers', 'owners');

        return view('admin.horses.edit', compact('horse', 'trainers', 'owners'));
    }

    public function update(UpdateHorseRequest $request, Horse $horse)
    {
        if ($request->input('image', false)) {
            if (!$horse->image || $request->input('image') !== $horse->image) {
                if ($horse->image) {
                    $disk = env('FILESYSTEM_DRIVER', 'public');
                    $baseUrl = Storage::disk($disk)->url('');
                    $relativeFilePath = str_replace($baseUrl, '', $horse->image);
                    if (Storage::disk($disk)->exists($relativeFilePath)) {
                        Storage::disk($disk)->delete($relativeFilePath);
                        $request['image'] = null;
                    }
                }
                $imagePath = storage_path('tmp/uploads/' . basename($request->input('image')));
                $image = new \Illuminate\Http\UploadedFile($imagePath, basename($imagePath));
                $folder_name = 'horses';
                $path = $image->store($folder_name, env('FILESYSTEM_DRIVER', 'public'));
                $fullUrl = Storage::disk(env('FILESYSTEM_DRIVER'))->url($path);
                $request['image'] = $fullUrl;
            }
        } elseif ($horse->image) {
            $disk = env('FILESYSTEM_DRIVER', 'public');
            $baseUrl = Storage::disk($disk)->url('');
            $relativeFilePath = str_replace($baseUrl, '', $horse->image);
            if (Storage::disk($disk)->exists($relativeFilePath)) {
                Storage::disk($disk)->delete($relativeFilePath);
                $request['image'] = null;
            }
        }
        $horse->update($request->all());
        $horse->owners()->sync($request->input('owners', []));
        $horse->trainers()->sync($request->input('trainers', []));

        return redirect()->route('admin.horses.index');
    }

    public function show(Horse $horse)
    {
        abort_if(Gate::denies('horse_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $horse->load('user', 'trainers', 'owners');

        return view('admin.horses.show', compact('horse'));
    }

    public function destroy(Horse $horse)
    {
        abort_if(Gate::denies('horse_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $horse->owners()->detach();
        $horse->trainers()->detach();

        if ($horse->image) {
            $disk = env('FILESYSTEM_DRIVER', 'public');
            $baseUrl = Storage::disk($disk)->url('');
            $relativeFilePath = str_replace($baseUrl, '', $horse->image);
            if (Storage::disk($disk)->exists($relativeFilePath)) {
                Storage::disk($disk)->delete($relativeFilePath);
            }
        }
        $horse->delete();

        return back();
    }

    public function massDestroy(MassDestroyHorseRequest $request)
    {
        $horses = Horse::find(request('ids'));

        foreach ($horses as $horse) {
            $horse->owners()->detach();
            $horse->trainers()->detach();
            if ($horse->image) {
                $disk = env('FILESYSTEM_DRIVER', 'public');
                $baseUrl = Storage::disk($disk)->url('');
                $relativeFilePath = str_replace($baseUrl, '', $horse->image);
                if (Storage::disk($disk)->exists($relativeFilePath)) {
                    Storage::disk($disk)->delete($relativeFilePath);
                }
            }
            $horse->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
