@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.role.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.roles.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <td>
                            {{ trans('cruds.role.fields.id') }}
                        </td>
                        <td>
                            {{ $role->id }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{ trans('cruds.role.fields.name') }}
                        </td>
                        <td>
                            {{ $role->name }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{ trans('cruds.role.fields.permissions') }}
                        </td>
                        <td>
                            @foreach($role->permissions as $key => $permissions)
                                <span class="badge badge-info">{{ $permissions->name }} &nbsp;</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.roles.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection