@extends('layouts.admin')
@section('content')
    <div class="d-flex align-items-center mb-4 justify-content-between">
        <h2>{{ trans('cruds.user.title_singular') }} {{ trans('global.list') }}</h2>
        @can('user_create')
            <a class="btn btn-success d-none" href="{{ route('admin.users.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
            </a>
        @endcan
    </div>
<div class="card">

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>


                        <th>
                            {{ trans('cruds.user.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.email') }}
                        </th>
                        <th>
                            Gender
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.roles') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                        <tr data-entry-id="{{ $user->id }}">

                            <td>
                                {{ $user->id ?? '' }}
                            </td>
                            <td>
                                {{ $user->name ?? '' }}
                            </td>
                            <td>
                                {{ $user->email ?? '' }}
                            </td>
                            <td>
                                {{ $user->gender ?? '' }}
                            </td>
                            <td>
                                @php
                                $labels = [];
                                    foreach($user->roles as $key => $item) {
                                        $labels[] = $item->name;
                                    }
                                @endphp
                                @foreach($labels as $label)
                                <span class="badge badge-info">{{ $label }}</span>
                                @endforeach
                            </td>
                            <td>
                            <div class="dropdown-menu-right">
                                <button class="btn btn-dark dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right dropdown-menu-dark bg-light">
                                {{-- @if(in_array('Trainer', $labels))
                                <li>
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="dropdown-item btn btn-icon" style="border:none; font-size:13px; font-weight:300; background-color:transparent; color:#3b4559; margin-left:-12px;" >
                                        Trained Horses
                                    </a>
                                </li>
                                @endif
                                @if(in_array('Owner', $labels))
                                <li>
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="dropdown-item btn btn-icon" style="border:none; font-size:13px; font-weight:300; background-color:transparent; color:#3b4559; margin-left:-12px;" >
                                        My Horses
                                    </a>
                                </li>
                                @endif
                                --}}
                                <li>
                                    @can('user_show')
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="dropdown-item btn btn-icon" style="border:none; font-size:13px; font-weight:300; background-color:transparent; color:#3b4559; margin-left:-12px;" >
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan
                                </li>
                                <li>
                                    @can('user_edit')
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="dropdown-item btn btn-icon" style="border:none; font-size:13px; font-weight:300; background-color:transparent; color:#3b4559; margin-left:-12px;" >
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan
                                </li>
                                <li>
                                    @can('user_delete')
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="dropdown-item btn btn-icon" style="border:none; font-size:13px; font-weight:300; background-color:transparent; color:#3b4559; margin-left:-12px;"  value="{{ trans('global.delete') }}">
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
@can('user_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 0, 'asc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-User:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
