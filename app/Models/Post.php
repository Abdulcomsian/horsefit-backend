<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public $table = 'posts';

    protected $fillable = [
        'body',
        'user_id',
        'status',
        'visibility', //'public', 'friends', 'friends_followers', 'selected_friends, private
    ];

    public const VISIBLITY_SELECT = [
        '' => 'Select',
        'public' => 'public',
        'friends'   => 'friends',
        'friends_followers'   => 'friends_followers',
        'selected_friends'   => 'selected_friends',
        'private'   => 'private',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
   
    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->latest();
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }
    
    // post visible to these friends if selected_friends selected
    public function friends()
    {
        return $this->belongsToMany(User::class, 'post_visibility_friends', 'post_id', 'user_id');
    }

}
