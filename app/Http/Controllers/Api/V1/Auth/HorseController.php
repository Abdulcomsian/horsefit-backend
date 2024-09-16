<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\Horse;
use App\Models\Sanctum\PersonalAccessToken;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class HorseController extends Controller implements HasMiddleware
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
            new Middleware('permission:Create Horse', only: ['storeHorse']),
            new Middleware('permission:Edit Horse', only: ['updateHorse']),
            new Middleware('permission:List My Horses', only: ['myHorses']),
            new Middleware('permission:List All Horses', only: ['allHorses']),
            new Middleware('permission:Follow Horse', only: ['toggleFollowHorse']),
            new Middleware('permission:Add Horse Trainer', only: ['assignTrainerToHorse']),
            new Middleware('permission:Get Horse Trainer', only: ['getHorseTrainer']),
            new Middleware('permission:Get Horses Trained', only: ['getHorsesTrainedByUser']),
        ];
    }

    public function storeHorse(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'type' => 'required|in:Trotting,Riding',
                'nationality' => 'required|string|max:255',
                'date_of_birth' => 'required|date|before:today',
                'gender' => 'required|in:Mare,Stallion',
                'blood_type' => 'required|in:Warm blooded,Cold blooded',
                'mother_name' => 'required|string|max:255',
                'father_name' => 'required|string|max:255',
                // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'image' => 'required|string',
    
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                    'data' => null,
                ], 422);
            }

            $request['user_id'] = $this->user?->id;

            $horse = Horse::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Horse created successfully',
                'data' => $horse,
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function updateHorse(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'horse_id' => 'required|exists:horses,id',
                'name' => 'required|string|max:255',
                'type' => 'required|in:Trotting,Riding',
                'nationality' => 'required|string|max:255',
                'date_of_birth' => 'required|date|before:today',
                'gender' => 'required|in:Mare,Stallion',
                'blood_type' => 'required|in:Warm blooded,Cold blooded',
                'mother_name' => 'required|string|max:255',
                'father_name' => 'required|string|max:255',
                // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'image' => 'required|string',
    
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                    'data' => null,
                ], 422);
            }
            $horse = Horse::findOrFail($request->horse_id);

            $horse->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Horse updated successfully',
                'data' => $horse,
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function myHorses(Request $request)
    {
        try {
            $horses = Horse::filters()->where('user_id', $this->user?->id)->get();
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $horses,
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function allHorses(Request $request)
    {
        try {
            $horses = Horse::filters()->get();
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $horses,
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function toggleFollowHorse(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'horse_id' => 'required|exists:horses,id',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                    'data' => null,
                ], 422);
            }
            if ($this->user?->followedHorses()->where('horse_id', $request->horse_id)->exists()) {
                $this->user?->followedHorses()->detach($request->horse_id);
                return response()->json([
                    'status' => true,
                    'message' => 'Unfollowed the horse successfully',
                    'data' => null
                ], 200);
            } else {
                $this->user?->followedHorses()->attach($request->horse_id);
                return response()->json([
                    'status' => true,
                    'message' => 'Followed the horse successfully',
                    'data' => null
                ], 200);
            }
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function assignTrainerToHorse(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'horse_id' => 'required|exists:horses,id',
                'trainer_id' => 'required|exists:users,id',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                    'data' => null,
                ], 422);
            }

            $trainer = User::where('id', $request->trainer_id)->whereHas('roles', function ($query) {
                $query->where('name', 'Trainer');
            })->first();

            if (!$trainer) {
                return response()->json([
                    'status' => false,
                    'message' => 'Selected user is not a trainer',
                    'data' => null,
                ], 422);
            }
            $horse = Horse::findOrFail($request->horse_id);
            $currentTrainerID = null;
            if ($horse->trainer_id) {
                $currentTrainerID = $horse->trainer_id;
            }
            $horse->trainer_id = $trainer->id;
            $horse->save();
            return response()->json([
                'status' => true,
                'message' => $currentTrainerID ? 'Horse trainer updated successfully' : 'Horse trainer added successfully',
                'data' => null
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function getHorsesTrainedByUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'trainer_id' => 'required|exists:users,id',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                    'data' => null,
                ], 422);
            }

            $trainer = User::with('horsesTrained')->where('id', $request->trainer_id)->whereHas('roles', function ($query) {
                $query->where('name', 'Trainer');
            })->first();

            if (!$trainer) {
                return response()->json([
                    'status' => false,
                    'message' => 'Selected user is not a trainer',
                    'data' => null,
                ], 422);
            }
    
            if ($trainer->horsesTrained->isNotEmpty()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Horses trained by the user retrieved successfully',
                    'data' => $trainer->horsesTrained,
                ], 200);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'This user is not training any horses currently',
                    'data' => null,
                ], 200);
            }
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    public function getHorseTrainer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'horse_id' => 'required|exists:horses,id',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                    'data' => null,
                ], 422);
            }
            $horse = Horse::with('trainer')->findOrFail($request->horse_id);

            if ($horse->trainer) {
                return response()->json([
                    'status' => true,
                    'message' => 'Trainer retrieved successfully',
                    'data' => $horse->trainer,
                ], 200);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'This horse does not have a trainer assigned yet',
                    'data' => null,
                ], 200);
            }
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }


}
