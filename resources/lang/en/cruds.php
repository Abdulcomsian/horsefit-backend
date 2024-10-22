<?php

return [
    'userManagement' => [
        'title'          => 'User management',
        'title_singular' => 'User management',
    ],
    'permission' => [
        'title'          => 'Permissions',
        'title_singular' => 'Permission',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'name'             => 'Name',
            'name_helper'      => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'role' => [
        'title'          => 'Roles',
        'title_singular' => 'Role',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => ' ',
            'name'              => 'Name',
            'name_helper'       => ' ',
            'permissions'        => 'Permissions',
            'permissions_helper' => ' ',
            'created_at'         => 'Created at',
            'created_at_helper'  => ' ',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => ' ',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => ' ',
        ],
    ],
    'user' => [
        'title'          => 'Users',
        'title_singular' => 'User',
        'fields'         => [
            'id'                       => 'ID',
            'id_helper'                => ' ',
            'name'                     => 'Name',
            'name_helper'              => ' ',
            'email'                    => 'Email',
            'email_helper'             => ' ',
            'email_verified_at'        => 'Email verified at',
            'email_verified_at_helper' => ' ',
            'password'                 => 'Password',
            'password_helper'          => ' ',
            'roles'                    => 'Roles',
            'roles_helper'             => ' ',
            'remember_token'           => 'Remember Token',
            'remember_token_helper'    => ' ',
            'created_at'               => 'Created at',
            'created_at_helper'        => ' ',
            'updated_at'               => 'Updated at',
            'updated_at_helper'        => ' ',
            'deleted_at'               => 'Deleted at',
            'deleted_at_helper'        => ' ',
        ],
    ],
    'horse' => [
        'title'          => 'Horse',
        'title_singular' => 'Horse',
        'fields'         => [
            'id'                   => 'ID',
            'id_helper'            => ' ',
            'image'                => 'Image',
            'image_helper'         => ' ',
            'user'                 => 'User',
            'user_helper'          => ' ',
            'name'                 => 'Name',
            'name_helper'          => ' ',
            'type'                 => 'Type',
            'type_helper'          => ' ',
            'nationality'          => 'Nationality',
            'nationality_helper'   => ' ',
            'date_of_birth'        => 'Date Of Birth',
            'date_of_birth_helper' => ' ',
            'gender'               => 'Gender',
            'gender_helper'        => ' ',
            'blood_type'           => 'Blood Type',
            'blood_type_helper'    => ' ',
            'mother_name'          => 'Mother Name',
            'mother_name_helper'   => ' ',
            'father_name'          => 'Father Name',
            'father_name_helper'   => ' ',
            'trainer'              => 'Trainer',
            'trainer_helper'       => ' ',
            'owner'              => 'Owner',
            'owner_helper'       => ' ',
            'created_at'           => 'Created at',
            'created_at_helper'    => ' ',
            'updated_at'           => 'Updated at',
            'updated_at_helper'    => ' ',
            'deleted_at'           => 'Deleted at',
            'deleted_at_helper'    => ' ',
        ],
    ],
    'post' => [
        'title'          => 'Post',
        'title_singular' => 'Post',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'user'              => 'User',
            'user_helper'       => ' ',
            'body'              => 'Body',
            'body_helper'       => ' ',
            'status'            => 'Status',
            'status_helper'     => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
            'visibility'        => 'Visibility',
            'visibility_helper' => ' ',
            'media'             => 'Media',
            'media_helper'      => ' ',
        ],
    ],
    'comment' => [
        'title'          => 'Comment',
        'title_singular' => 'Comment',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'user'              => 'User',
            'user_helper'       => ' ',
            'post'              => 'Post',
            'post_helper'       => ' ',
            'parent'            => 'Parent',
            'parent_helper'     => ' ',
            'body'              => 'Body',
            'body_helper'       => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'like' => [
        'title'          => 'Like',
        'title_singular' => 'Like',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'user'              => 'User',
            'user_helper'       => ' ',
            'post'              => 'Post',
            'post_helper'       => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'hfMedium' => [
        'title'          => 'Hf Media',
        'title_singular' => 'Hf Medium',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'model'             => 'Model',
            'model_helper'      => ' ',
            'model_type'        => 'Model Type',
            'model_type_helper' => ' ',
            'media_link'        => 'Media Link',
            'media_link_helper' => ' ',
            'type'              => 'Type',
            'type_helper'       => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],

];
