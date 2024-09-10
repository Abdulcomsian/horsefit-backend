<?php

namespace App\Models;

use App\QueryBuilders\ModuleBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class Module extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'name',
    ];

    public function permissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Permission::class);
    }

    public function newEloquentBuilder($query)
    {
        return new ModuleBuilder($query);
    }
}
