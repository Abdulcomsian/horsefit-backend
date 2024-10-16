@extends('layouts.admin')
@section('content')
    <div class="d-flex align-items-center mb-4 justify-content-between">
        <h2>Permissions list</h2>

            <a class="btn btn-success" href="{{ route('admin.permissions.create') }}">
                Add permission
            </a>

    </div>
<div class="card">

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Permission">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissions as $key => $permission)
                        <tr data-entry-id="{{ $permission->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $permission->id ?? '' }}
                            </td>
                            <td>
                                {{ $permission->name ?? '' }}
                            </td>
                            <td>
                            <div class="dropdown">
                                <button class="btn btn-dark dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right dropdown-menu-dark bg-light">
                                    <li>
                                        @can('permission_show')
                                            <a href="{{ route('admin.permissions.show', $permission->id) }}" class="dropdown-item btn btn-icon" style="border:none; font-size:13px; font-weight:300; background-color:transparent; color:#3b4559; margin-left:-12px;" >
                                                {{ trans('global.view') }}
                                            </a>
                                        @endcan
                                    </li>
                                    <li>
                                        @can('permission_edit')
                                            <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="dropdown-item btn btn-icon" style="border:none; font-size:13px; font-weight:300; background-color:transparent; color:#3b4559; margin-left:-12px;" >
                                                {{ trans('global.edit') }}
                                            </a>
                                        @endcan
                                    </li>
                                    <li>
                                        @can('permission_delete')
                                            <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('permission_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.permissions.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-Permission:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
