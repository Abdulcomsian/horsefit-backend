<?php

namespace App\Http\Requests;

use App\Models\Like;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateLikeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('like_edit');
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
