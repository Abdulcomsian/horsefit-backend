<?php

namespace App\Models;

use App\QueryBuilders\RoleBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Spatie\Permission\Models\Role as SpatieRole;
class Role extends SpatieRole
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'guard_name',
        'description',
        'status',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function newEloquentBuilder($query)
    {
        return new RoleBuilder($query);
    }

}
