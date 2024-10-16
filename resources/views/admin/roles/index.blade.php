@extends('layouts.admin')
@section('content')
    <div class="d-flex align-items-center mb-4 justify-content-between">
        <h2>{{ trans('cruds.role.title_singular') }} {{ trans('global.list') }}</h2>
        @can('role_create')
            <a class="btn btn-success" href="{{ route('admin.roles.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.role.title_singular') }}
            </a>
        @endcan
    </div>
<div class="card">

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Role">
                <thead>
                    <tr>
                        <th>
                            {{ trans('cruds.role.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.role.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.role.fields.permissions') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $key => $role)
                        <tr data-entry-id="{{ $role->id }}">

                            <td>
                                {{ $role->id ?? '' }}
                            </td>
                            <td>
                                {{ $role->name ?? '' }}
                            </td>
                            <td>
                                @foreach($role->permissions as $key => $item)
                                    <span class="badge badge-info">{{ $item->name }}</span>
                                @endforeach
                            </td>
                            <td>
                            <div class="dropdown-menu-right">
                                <button class="btn btn-dark dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right dropdown-menu-dark bg-light">
                                <li>
                                @can('role_show')
                                    <a href="{{ route('admin.roles.show', $role->id) }}" class="dropdown-item btn btn-icon" style="border:none; font-size:13px; font-weight:300; background-color:transparent; color:#3b4559; margin-left:-12px;" >
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                                </li>
                                <li>
                                @can('role_edit')
                                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="dropdown-item btn btn-icon" style="border:none; font-size:13px; font-weight:300; background-color:transparent; color:#3b4559; margin-left:-12px;" >
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan
                                </li>
                                <li>
                                    @can('role_delete')
                                        <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="dropdown-item btn btn-icon" style="border:none; font-size:13px; font-weight:300; background-color:transparent; color:#3b4559; margin-left:-12px;" value="{{ trans('global.delete') }}">
                                        </form>
                                    @endcan
                                </li>
                            </ul>
                            </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('role_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-Role:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
