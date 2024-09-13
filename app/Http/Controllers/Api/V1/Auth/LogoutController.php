<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Laravel\Sanctum\PersonalAccessToken;

class LogoutController extends Controller
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

    public function logout()
    {
        try {
            $this->user?->tokens()->delete();
            return response()->json([
                'status' => true,
                'message' => 'User logged out successfully',
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
