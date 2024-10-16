<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'model_type', //App\Models\Post, App\Models\User etc
        'model_id',
        'media_link', 
        'type',
    ];

    // Polymorphic relationship
    public function model()
    {
        return $this->morphTo();
    }

    // $media = Media::find(1);
    // $relatedModel = $media->model; //use this as example
    
}
