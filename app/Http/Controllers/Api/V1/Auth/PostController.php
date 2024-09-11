<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Media;
use App\Models\Post;
use App\Models\Sanctum\PersonalAccessToken;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    protected $user;

    public function __construct()
    {
        try {
            $token = request()->bearerToken();
            if ($token) {
                $accessToken = PersonalAccessToken::findToken($token);
                if ($accessToken) {
                    $this->user = $accessToken->tokenable;
                } else {
                    $this->user = null;
                }
            } else {
                $this->user = null;
            }
        } catch (Exception $e) {
           $this->user = null;
        }
    }
    public function userNotFound()
    {
        if (!$this->user) {
            return response()->json([
                'status' => false,
                'message' => 'Token not valid or user not found!',
                'data' => null,
            ], 401);
        }
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

        return 'unknown';
    }


    public function storePost(Request $request)
    {
        $this->userNotFound();
        try {
            $validator = Validator::make($request->all(), [
                'body' => 'required',
                'post_media' => 'nullable|array',
    
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                    'data' => null,
                ], 422);
            }
            $post = Post::create([
                'body' => $request->body,
                'user_id' => $this->user?->id,
                'status' => true,
            ]);
            if ($request->has('post_media')) {
                foreach ($request->input('post_media') as $media_link) {
                    $type = $this->getMediaTypeFromUrl($media_link);
                    Media::create([
                        'post_id' => $post->id,
                        'media_link' => $media_link,
                        'type' => $type,
                    ]);
                }
            }
            return response()->json([
                'status' => true,
                'message' => 'Post created successfully',
                'data' => null,
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function togglePostLike(Request $request)
    {
        $this->userNotFound();
        try {
            $validator = Validator::make($request->all(), [
                'post_id' => 'required|exists:posts,id',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                    'data' => null,
                ], 422);
            }
            $existingLike = Like::where([
                'post_id' => $request->post_id,
                'user_id' => $this->user?->id,
            ])->first();

            if ($existingLike) {
                $existingLike->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Post unliked successfully',
                    'data' => null,
                ], 200);
            } else {
                Like::create([
                    'post_id' => $request->post_id,
                    'user_id' => $this->user?->id,
                ]);
                return response()->json([
                    'status' => true,
                    'message' => 'Post liked successfully',
                    'data' => null,
                ], 200);
                return response()->json(['message' => 'Post liked successfully']);
            }
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function postComment(Request $request)
    {
        $this->userNotFound();
        try {
            $validator = Validator::make($request->all(), [
                'post_id' => 'required|exists:posts,id',
                'body' => 'required',
                // 'parent_id' => 'nullable|exists:comments,id',
                'parent_id' => [
                    'nullable',
                    'exists:comments,id',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($value) {
                            $parentComment = Comment::find($value);
                            if ($parentComment && $parentComment->post_id != $request->post_id) {
                                $fail('The parent comment does not belong to the selected post.');
                            }
                        }
                    }
                ],
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                    'data' => null,
                ], 422);
            }
            Comment::create([
                'post_id' => $request->post_id,
                'user_id' => $this->user?->id,
                'body' => $request->body,
                'parent_id' => $request->parent_id ?? NULL,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Comment added successfully',
                'data' => null,
            ], 200);
                return response()->json(['message' => 'Post liked successfully']);
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

}
