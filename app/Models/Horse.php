<?php

namespace App\Models;

use App\QueryBuilders\HorseBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Horse extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'horses';

    public const GENDER_SELECT = [
        'Mare'     => 'Mare',
        'Stallion' => 'Stallion',
    ];

    public const TYPE_SELECT = [
        'Trotting' => 'Trotting',
        'Riding'   => 'Riding',
    ];

    protected $dates = [
        'date_of_birth',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const BLOOD_TYPE_SELECT = [
        'Warm blooded' => 'Warm blooded',
        'Cold blooded' => 'Cold blooded',
    ];

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
        'image',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'horse_followers', 'horse_id', 'user_id');
    }

    public function owners()
    {
        return $this->belongsToMany(User::class, 'horse_owner');
    }

    public function trainers()
    {
        return $this->belongsToMany(User::class, 'horse_trainer');
    }

    // public function newEloquentBuilder($query)
    // {
    //     return new HorseBuilder($query);
    // }
}
