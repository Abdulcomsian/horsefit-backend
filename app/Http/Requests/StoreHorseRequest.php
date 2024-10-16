<?php

namespace App\Http\Requests;

use App\Models\Horse;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreHorseRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('horse_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'type' => [
                'required',
            ],
            'nationality' => [
                'string',
                'required',
            ],
            'date_of_birth' => [
                'required',
            ],
            'gender' => [
                'required',
            ],
            'blood_type' => [
                'required',
            ],
            'mother_name' => [
                'string',
                'nullable',
            ],
            'father_name' => [
                'string',
                'nullable',
            ],
            'trainers' => [
                'required',
                'array',
            ],
            'trainers.*' => [
                'exists:users,id',
            ],
            'owners' => [
                'required',
                'array',
            ],
            'owners.*' => [
                'exists:users,id',
            ],
        ];
    }
}
