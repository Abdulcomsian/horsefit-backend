<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\QueryBuilders\UserBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, 
        Notifiable,
        HasRoles,
        HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'gender',
        'date_of_birth',
        'password',
        'image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('Super Admin');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function sentFriendRequests()
    {
        return $this->hasMany(FriendRequest::class, 'sender_id')->whereStatus('pending');
    }

    public function receivedFriendRequests()
    {
        return $this->hasMany(FriendRequest::class, 'receiver_id')->whereStatus('pending');
    }

    // i can use this also, but this will be nested (followers->follower)
    // public function followers()
    // {
    //     return $this->hasMany(Follower::class, 'followed_id');
    // }

    // public function following()
    // {
    //     return $this->hasMany(Follower::class, 'follower_id');
    // }

    //Directly get Follower users instead of Followe and then followe from Followe model
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'followed_id', 'follower_id')
        ->select('users.id', 'users.name', 'users.email', 'users.image');
        // if remove above line all columns will be returned
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'followed_id')
        ->select('users.id', 'users.name', 'users.email', 'users.image');
        //->with('roles'); will apply this if required
        // if remove above line all columns will be returned
    }

    // public function friends()
    // {
    // it is not working
    //     return $this->belongsToMany(User::class, 'friend_requests', 'sender_id', 'receiver_id')
    //         ->withPivotPivot('status')
    //         ->wherePivot('status', 'accepted')
    //         ->union(
    //             $this->belongsToMany(User::class, 'friend_requests', 'receiver_id', 'sender_id')
    //                     ->withPivotPivot('status')
    //                     ->wherePivot('status', 'accepted')
    //         );
    // }
    public function friends()
    {
        $friendsSent = DB::table('users')
            ->join('friend_requests', 'users.id', '=', 'friend_requests.receiver_id')
            ->where('friend_requests.sender_id', $this->id)
            ->where('friend_requests.status', 'accepted')
            ->select('users.id', 'users.name', 'users.email', 'users.image');

        $friendsReceived = DB::table('users')
            ->join('friend_requests', 'users.id', '=', 'friend_requests.sender_id')
            ->where('friend_requests.receiver_id', $this->id)
            ->where('friend_requests.status', 'accepted')
            ->select('users.id', 'users.name', 'users.email', 'users.image');

        return $friendsSent->union($friendsReceived)->get();
    }

    public function horses()
    {
        return $this->hasMany(Horse::class);
    }

    public function followedHorses()
    {
        return $this->belongsToMany(Horse::class, 'horse_followers', 'user_id', 'horse_id');
    }

    public function newEloquentBuilder($query)
    {
        return new UserBuilder($query);
    }

}
