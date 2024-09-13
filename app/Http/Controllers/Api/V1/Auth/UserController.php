<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\AuthenticationException;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
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
            new Middleware('permission:Get User List', only: ['getUserList']),
            new Middleware('permission:Assign Role To User', only: ['assignNewRole']),
            new Middleware('permission:Get Trainers And Owners List', only: ['getTrainersAndOwnersOnly']),
        ];
    }

    public function changePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => ['required', 'current_password'],
                'password' => ['required', Password::defaults(), 'confirmed'],
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                    'data' => null,
                ], 422);
            }
    
            $this->user?->update([
                'password' => Hash::make($request->password),
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'Password changed successfully',
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

    public function updateProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                // 'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user?->id)],
                'role' => 'required|exists:roles,id',
                'date_of_birth' => 'required|date|before:today',
                'gender' => 'required|in:Male,Female',
                'image' => 'required', // Expected image is a string
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(), // Can use this if have to return first error only : $validator->errors()->first()
                    'data' => null,
                ], 422);
            }
            $this->user?->update([
                'name' => $request->name,
                // 'email' => $request->email,
                'password' => Hash::make($request->password),
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'image' => $request->image ?? '',
            ]);
    
            // $this->user?->assignRole((int) $request->role); // use this if you want to add new role and want to keep previous role too
            $this->user?->syncRoles((int) $request->role);
    
            return response()->json([
                'status' => true,
                'message' => 'User Information upddated successfully',
                'data' => $this->user?->load('roles'),
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function getUserList(Request $request)
    {
        try {
            $usersLists = User::filters()->with('roles:id,name')->get(['id', 'name', 'email', 'image']);
            return response()->json([
                'status' => true,
                'message' => 'User Information upddated successfully',
                'data' => $usersLists,
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function assignNewRole(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'role_id' => 'required|exists:roles,id',
                'type' => 'nullable|in:keep,delete',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                    'data' => null,
                ], 422);
            }
            $findUser = User::where('id', $request->user_id)->first(['id', 'name', 'email', 'image']);
            if ($request->type && $request->type === 'keep') {
                $findUser->assignRole((int) $request->role_id);
            } else {
                $findUser->syncRoles((int) $request->role_id);
            }
            return response()->json([
                'status' => true,
                'message' => 'User Information upddated successfully',
                'data' => $findUser->load('roles:id,name'),
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function getTrainersAndOwnersOnly()
    {
        try {
            $usersLists = User::trainerAndOwnerList()->with('roles:id,name')->get(['id', 'name', 'email', 'image']);
            return response()->json([
                'status' => true,
                'message' => 'User Information upddated successfully',
                'data' => $usersLists,
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }
}
