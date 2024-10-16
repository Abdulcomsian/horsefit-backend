<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'name' => 'user_management_access',
                'guard_name' => 'web',
            ],
            [
                'id'    => 2,
                'name' => 'permission_create',
                'guard_name' => 'web',
            ],
            [
                'id'    => 3,
                'name' => 'permission_edit',
                'guard_name' => 'web',
            ],
            [
                'id'    => 4,
                'name' => 'permission_show',
                'guard_name' => 'web',
            ],
            [
                'id'    => 5,
                'name' => 'permission_delete',
                'guard_name' => 'web',
            ],
            [
                'id'    => 6,
                'name' => 'permission_access',
                'guard_name' => 'web',
            ],
            [
                'id'    => 7,
                'name' => 'role_create',
                'guard_name' => 'web',
            ],
            [
                'id'    => 8,
                'name' => 'role_edit',
                'guard_name' => 'web',
            ],
            [
                'id'    => 9,
                'name' => 'role_show',
                'guard_name' => 'web',
            ],
            [
                'id'    => 10,
                'name' => 'role_delete',
                'guard_name' => 'web',
            ],
            [
                'id'    => 11,
                'name' => 'role_access',
                'guard_name' => 'web',
            ],
            [
                'id'    => 12,
                'name' => 'user_create',
                'guard_name' => 'web',
            ],
            [
                'id'    => 13,
                'name' => 'user_edit',
                'guard_name' => 'web',
            ],
            [
                'id'    => 14,
                'name' => 'user_show',
                'guard_name' => 'web',
            ],
            [
                'id'    => 15,
                'name' => 'user_delete',
                'guard_name' => 'web',
            ],
            [
                'id'    => 16,
                'name' => 'user_access',
                'guard_name' => 'web',
            ],
            [
                'id'    => 17,
                'name' => 'horse_create',
                'guard_name' => 'web',
            ],
            [
                'id'    => 18,
                'name' => 'horse_edit',
                'guard_name' => 'web',
            ],
            [
                'id'    => 19,
                'name' => 'horse_show',
                'guard_name' => 'web',
            ],
            [
                'id'    => 20,
                'name' => 'horse_delete',
                'guard_name' => 'web',
            ],
            [
                'id'    => 21,
                'name' => 'horse_access',
                'guard_name' => 'web',
            ],
            [
                'id'    => 22,
                'name' => 'post_create',
                'guard_name' => 'web',
            ],
            [
                'id'    => 23,
                'name' => 'post_edit',
                'guard_name' => 'web',
            ],
            [
                'id'    => 24,
                'name' => 'post_show',
                'guard_name' => 'web',
            ],
            [
                'id'    => 25,
                'name' => 'post_delete',
                'guard_name' => 'web',
            ],
            [
                'id'    => 26,
                'name' => 'post_access',
                'guard_name' => 'web',
            ],
            [
                'id'    => 27,
                'name' => 'comment_create',
                'guard_name' => 'web',
            ],
            [
                'id'    => 28,
                'name' => 'comment_edit',
                'guard_name' => 'web',
            ],
            [
                'id'    => 29,
                'name' => 'comment_show',
                'guard_name' => 'web',
            ],
            [
                'id'    => 30,
                'name' => 'comment_delete',
                'guard_name' => 'web',
            ],
            [
                'id'    => 31,
                'name' => 'comment_access',
                'guard_name' => 'web',
            ],
            [
                'id'    => 32,
                'name' => 'like_create',
                'guard_name' => 'web',
            ],
            [
                'id'    => 33,
                'name' => 'like_edit',
                'guard_name' => 'web',
            ],
            [
                'id'    => 34,
                'name' => 'like_show',
                'guard_name' => 'web',
            ],
            [
                'id'    => 35,
                'name' => 'like_delete',
                'guard_name' => 'web',
            ],
            [
                'id'    => 36,
                'name' => 'like_access',
                'guard_name' => 'web',
            ],
            [
                'id'    => 37,
                'name' => 'hf_medium_create',
                'guard_name' => 'web',
            ],
            [
                'id'    => 38,
                'name' => 'hf_medium_edit',
                'guard_name' => 'web',
            ],
            [
                'id'    => 39,
                'name' => 'hf_medium_show',
                'guard_name' => 'web',
            ],
            [
                'id'    => 40,
                'name' => 'hf_medium_delete',
                'guard_name' => 'web',
            ],
            [
                'id'    => 41,
                'name' => 'hf_medium_access',
                'guard_name' => 'web',
            ],
            [
                'id'    => 42,
                'name' => 'profile_password_edit',
                'guard_name' => 'web',
            ],
            [
                'id'    => 43,
                'name' => 'Assign Role To User',
                'guard_name' => 'web',
            ],
            [
                'id'    => 44,
                'name' => 'View User Posts',
                'guard_name' => 'web',
            ],
            [
                'id'    => 45,
                'name' => 'Create Post',
                'guard_name' => 'web',
            ],
            [
                'id'    => 46,
                'name' => 'Create Post Comment',
                'guard_name' => 'web',
            ],
            [
                'id'    => 47,
                'name' => 'Like Post',
                'guard_name' => 'web',
            ],
            [
                'id'    => 48,
                'name' => 'Follow User',
                'guard_name' => 'web',
            ],
            [
                'id'    => 49,
                'name' => 'Get Followers',
                'guard_name' => 'web',
            ],
            [
                'id'    => 50,
                'name' => 'Get Followings',
                'guard_name' => 'web',
            ],
            [
                'id'    => 51,
                'name' => 'Send Friend Request',
                'guard_name' => 'web',
            ],
            [
                'id'    => 52,
                'name' => 'Respond Friend Request',
                'guard_name' => 'web',
            ],
            [
                'id'    => 53,
                'name' => 'Get Friends',
                'guard_name' => 'web',
            ],
            [
                'id'    => 54,
                'name' => 'Get Pending Friend Request',
                'guard_name' => 'web',
            ],
            [
                'id'    => 55,
                'name' => 'Get Received Friend Request',
                'guard_name' => 'web',
            ],
            [
                'id'    => 56,
                'name' => 'Get User List',
                'guard_name' => 'web',
            ],
            [
                'id'    => 57,
                'name' => 'Get Trainers And Owners List',
                'guard_name' => 'web',
            ],
            [
                'id'    => 58,
                'name' => 'Create Horse',
                'guard_name' => 'web',
            ],
            [
                'id'    => 59,
                'name' => 'Edit Horse',
                'guard_name' => 'web',
            ],
            [
                'id'    => 60,
                'name' => 'List My Horses',
                'guard_name' => 'web',
            ],
            [
                'id'    => 61,
                'name' => 'List All Horses',
                'guard_name' => 'web',
            ],
            [
                'id'    => 62,
                'name' => 'Follow Horse',
                'guard_name' => 'web',
            ],
            [
                'id'    => 63,
                'name' => 'Add Horse Trainer',
                'guard_name' => 'web',
            ],
            [
                'id'    => 64,
                'name' => 'Get Horse Trainer',
                'guard_name' => 'web',
            ],
            [
                'id'    => 65,
                'name' => 'Get Horse Trained',
                'guard_name' => 'web',
            ],
        ];

        Permission::insert($permissions);
    }
}
