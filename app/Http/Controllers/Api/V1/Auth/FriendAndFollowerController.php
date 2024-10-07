<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\Follower;
use App\Models\FriendRequest;
use App\Models\Sanctum\PersonalAccessToken;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class FriendAndFollowerController extends Controller implements HasMiddleware
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
                    throw new AuthenticationException('Token not valid or user not found');
                }
            } else {
                throw new AuthenticationException('Token not provided');
            }
        } catch (Exception $e) {
            throw new AuthenticationException('Token not valid or user not found');
        }
    }

    public static function middleware(): array
    {
        return [
            'auth:sanctum',
            new Middleware('permission:Send Friend Request', only: ['sendFriendRequest']),
            new Middleware('permission:Respond Friend Request', only: ['respondToFriendRequest']),
            new Middleware('permission:Follow User', only: ['togglefollowUser']),
            new Middleware('permission:Get Followers', only: ['getAllFollowers']),
            new Middleware('permission:Get Followings', only: ['getAllFollowings']),
            new Middleware('permission:Get Friends', only: ['getUserFriends']),
            new Middleware('permission:Get Pending Friend Request', only: ['getUserPendingFriendRequest']),
            new Middleware('permission:Get Received Friend Request', only: ['getUserReceivedFriendRequest']),
        ];
    }

    public function sendFriendRequest(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'receiver_id' => 'required|exists:users,id',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'code' => 422,
                    'message' => $validator->errors()->first(),
                    'data' => null,
                ], 422);
            }
            if ($this->user?->id == $request->receiver_id) {
                return response()->json([
                    'status' => false,
                    'code' => 405,
                    'message' => "You can't friend request to yourself",
                    'data' => null,
                ], 405); // method not Aallowed
            }
    
            // Check if a request already exists
            $friendRequest = FriendRequest::where('sender_id', $this->user?->id)
                ->where('receiver_id', $request->receiver_id)
                ->first();
            if ($friendRequest) {
                return response()->json([
                    'status' => false,
                    'code' => 405,
                    'message' => 'Friend request already sent or received.',
                    'data' => null,
                ], 405); // method not Aallowed
            }
            $receivedFriendRequest = FriendRequest::where('receiver_id', $this->user?->id)
                ->where('sender_id', $request->receiver_id)
                ->first();
            if ($receivedFriendRequest) {
                return response()->json([
                    'status' => false,
                    'code' => 405,
                    'message' => 'Friend request already sent or received.',
                    'data' => null,
                ], 405); // method not Aallowed
            }

            FriendRequest::create([
                'sender_id' => $this->user?->id,
                'receiver_id' => $request->receiver_id,
                'status' => 'pending',
            ]);
            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => 'Friend request sent successfully',
                'data' => null,
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function respondToFriendRequest(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:accepted,declined',
                'friend_request_id' => 'required|exists:friend_requests,id'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'code' => 422,
                    'message' => $validator->errors()->first(),
                    'data' => null,
                ], 422);
            }
    
            $friendRequest = FriendRequest::findOrFail($request->friend_request_id);
            
            if ($friendRequest->receiver_id !== $this->user?->id) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 403);
            }
            if ($friendRequest->status === 'accepted') {
                return response()->json([
                    'status' => false,
                    'code' => 403,
                    'message' => "Accepted request can't be declined or acctepted",
                    'data' => null
                ], 403);
            }

            if ($friendRequest->status === 'declined') {
                $friendRequest->delete();
            } else {
                $friendRequest->update([
                    'status' => $request->status,
                ]);
            }
            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => "Friend request {$request->status}",
                'data' => null,
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function togglefollowUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'following_id' => 'required|exists:users,id',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'code' => 422,
                    'message' => $validator->errors()->first(),
                    'data' => null,
                ], 422);
            }
            if ($request->following_id == $this->user?->id) {
                return response()->json([
                    'status' => false,
                    'code' => 403,
                    'message' => "You can't follow or unfollow yourself",
                    'data' => null
                ], 403);
            }
            $existingFollow = Follower::where('follower_id', $this->user?->id)
                ->where('followed_id', $request->following_id)
                ->first();
    
            if ($existingFollow) {
                $existingFollow->delete();
                return response()->json([
                    'status' => true,
                    'code' => 200,
                    'message' => "Unfollowed the user",
                    'data' => null,
                ], 200);
            } else {
                Follower::create([
                    'follower_id' => $this->user?->id,
                    'followed_id' => $request->following_id,
                ]);
                return response()->json([
                    'status' => true,
                    'code' => 200,
                    'message' => "Followed the user",
                    'data' => null,
                ], 200);
            }
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function getAllFollowers()
    {
        try {
            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => "success",
                'data' => $this->user?->followers,
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function getAllFollowings()
    {
        try {
            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => "success",
                'data' => $this->user?->followings,
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function getUserFriends()
    {
        try {
            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => "success",
                'data' => $this->user?->friends(),
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function getUserPendingFriendRequest()
    {
        try {
            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => "success",
                'data' => $this->user?->sentFriendRequests->load('receiver:id,name,email,image'),
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function getUserReceivedFriendRequest()
    {
        try {
            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => "success",
                'data' => $this->user?->receivedFriendRequests->load('sender:id,name,email,image'),
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

}
