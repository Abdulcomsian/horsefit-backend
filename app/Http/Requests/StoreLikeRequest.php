<?php

namespace App\Http\Requests;

use App\Models\Like;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreLikeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('like_create');
    }

    public function rules()
    {
        return [
            'post_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
