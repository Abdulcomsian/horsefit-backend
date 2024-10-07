<?php

namespace App\Http\Controllers\Api\V1\Guest;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function socialRegister(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'token' => 'required|string',
                'provider' => 'required|string|in:google,apple,facebook',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'code' => 422,
                    'message' => $validator->errors(),
                    'data' => null,
                ], 422);
            }

            $provider = $request->provider;

            try {
                $socialUser = Socialite::driver($provider)->userFromToken($request->token);
            } catch (Exception $e) {
                return response()->json([
                    'status' => false,
                    'code' => 400,
                    'message' => 'Invalid token or failed to authenticate with the provider.',
                    'error' => $e->getMessage(),
                ], 400);
            }

            $user = User::where('email', $socialUser->getEmail())->first();

            \Log::info([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'provider_id' => $socialUser->getId(),
                'provider' => $provider,
                'image' => $socialUser->getAvatar(),
                'password' => bcrypt(uniqid()),
            ]);
            if (!$user) {
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'provider_id' => $socialUser->getId(),
                    'provider' => $provider,
                    'image' => $socialUser->getAvatar(),
                    'password' => bcrypt(uniqid()),
                ]);

                $role = Role::where('name', 'User')->first();

                if (!$role) {
                    $role = Role::firstOrCreate(['name' => 'User', 'status' => 1, 'description' => 'User role', 'guard_name' => 'web']);
                }

                $user->assignRole((int) $request->role);
            }

            $token = $user->createToken(
                'HorseFit', ['*'], now()->addHours(2),
            )->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token,
            ];

            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => 'success',
                'data' => $response,
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
