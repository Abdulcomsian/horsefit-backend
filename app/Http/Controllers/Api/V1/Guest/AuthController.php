<?php

namespace App\Http\Controllers\Api\V1\Guest;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function getRoleList()
    {
        try {
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => Role::where('name', '!=', 'Super Admin')->get(['id', 'name']),
            ], 200);

        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
                'password' => 'required|string',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(), // Can use this if have to return first error only : $validator->errors()->first()
                    'data' => null,
                ], 422);
            }
            $user = User::where('email', $request->email)
            ->first();
            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken(
                        //'HorseFit'
                        'HorseFit', ['*'], now()->addHours(2)
                    )->plainTextToken;
                    $response = [
                        'user' => $user,
                        'token' => $token,
                    ];
                    return response()->json([
                        'status' => true,
                        'message' => 'success',
                        'data' => $response,
                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Password mismatch',
                        'data' => null,
                    ], 406); // Not acceptable
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'User Not found or have been deleted!',
                    'data' => null,
                ], 404);
            }
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
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
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'image' => $request->image ?? '',
            ]);
    
            $user->assignRole((int) $request->role);

            $token = $user->createToken(
                        //'HorseFit'
                        'HorseFit', ['*'], now()->addHours(2),
                    )->plainTextToken;
            $response = [
                'user' => $user,
                'token' => $token,
            ];

            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $response,
            ], 200);
            
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }
    
    public function forgotPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
    
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                    'data' => null,
                ], 422);
            }
            $status = Password::sendResetLink(
                $request->only('email')
            );
            return response()->json([
                'status' => true,
                'message' => 'We have e-mailed you a password reset link!',
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
}
