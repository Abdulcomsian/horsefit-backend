<?php

namespace App\Http\Requests;

use App\Models\HfMedium;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyHfMediumRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('hf_medium_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:hf_media,id',
        ];
    }
}
