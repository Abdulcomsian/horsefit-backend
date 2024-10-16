@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.horse.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.horses.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.horse.fields.id') }}
                        </th>
                        <td>
                            {{ $horse->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.horse.fields.image') }}
                        </th>
                        <td>
                            @if($horse->image)
                                <a href="{{ $horse->image }}" target="_blank" style="display: inline-block">
                                    <img height="100" width="100" src="{{ $horse->image }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.horse.fields.user') }}
                        </th>
                        <td>
                            {{ $horse->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.horse.fields.name') }}
                        </th>
                        <td>
                            {{ $horse->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.horse.fields.type') }}
                        </th>
                        <td>
                            {{ App\Models\Horse::TYPE_SELECT[$horse->type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.horse.fields.nationality') }}
                        </th>
                        <td>
                            {{ $horse->nationality }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.horse.fields.date_of_birth') }}
                        </th>
                        <td>
                            {{ $horse->date_of_birth }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.horse.fields.blood_type') }}
                        </th>
                        <td>
                            {{ $horse->blood_type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.horse.fields.gender') }}
                        </th>
                        <td>
                            {{ App\Models\Horse::GENDER_SELECT[$horse->gender] ?? '' }}
                        </td>
                    </tr>
                    @if($horse->mother_name)
                        <tr>
                            <th>
                                {{ trans('cruds.horse.fields.mother_name') }}
                            </th>
                            <td>
                                {{ $horse->mother_name }}
                            </td>
                        </tr>
                    @endif
                    @if($horse->father_name)
                        <tr>
                            <th>
                                {{ trans('cruds.horse.fields.father_name') }}
                            </th>
                            <td>
                                {{ $horse->father_name }}
                            </td>
                        </tr>
                    @endif
                    @if($horse->trainers)
                        <tr>
                            <th>
                                {{ trans('cruds.horse.fields.trainer') }}s
                            </th>
                            <td>
                                @foreach ($horse->trainers as $trainer)
                                    <span class="badge badge-info label-many">{{ $trainer->name }}</span>
                                @endforeach
                            </td>
                        </tr>
                    @endif
                    @if($horse->owners)
                        <tr>
                            <th>
                                {{ trans('cruds.horse.fields.owner') }}s
                            </th>
                            <td>
                                    @foreach ($horse->owners as $owner)
                                        <span class="badge badge-info label-many">{{ $owner->name }}</span>
                                    @endforeach
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.horses.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection