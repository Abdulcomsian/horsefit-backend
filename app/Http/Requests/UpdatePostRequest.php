<?php

namespace App\Http\Requests;

use App\Models\Post;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePostRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('post_edit');
    }

    public function rules()
    {
        return [
            'body' => [
                'required',
            ],
            'visibility' => [
                'string',
                'nullable',
            ],
            'media' => [
                'array',
            ],
        ];
    }
}
