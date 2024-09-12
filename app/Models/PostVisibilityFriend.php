<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostVisibilityFriend extends Model
{
    use HasFactory;

    protected $table = 'post_visibility_friends';

    protected $fillable = [
        'post_id',
        'user_id',
    ];

    /**
     * Get the post associated with the visibility.
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user who is allowed to see the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
