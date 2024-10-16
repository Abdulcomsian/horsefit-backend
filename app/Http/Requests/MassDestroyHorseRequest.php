<?php

namespace App\Http\Requests;

use App\Models\Horse;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyHorseRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('horse_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:horses,id',
        ];
    }
}
