<?php

namespace App\Models;

use App\QueryBuilders\HorseBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'nationality',
        'date_of_birth',
        'gender',
        'blood_type',
        'mother_name',
        'father_name',
        'image'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'horse_followers', 'horse_id', 'user_id');
    }

    public function newEloquentBuilder($query)
    {
        return new HorseBuilder($query);
    }
}
