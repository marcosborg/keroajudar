@extends('layouts.admin')
@section('content')
@can('prize_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.prizes.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.prize.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.prize.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Prize">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.prize.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.prize.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.prize.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.prize.fields.value') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prizes as $key => $prize)
                        <tr data-entry-id="{{ $prize->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $prize->id ?? '' }}
                            </td>
                            <td>
                                {{ $prize->name ?? '' }}
                            </td>
                            <td>
                                {{ $prize->description ?? '' }}
                            </td>
                            <td>
                                {{ $prize->value ?? '' }}
                            </td>
                            <td>
                                @can('prize_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.prizes.show', $prize->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('prize_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.prizes.edit', $prize->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('prize_delete')
                                    <form action="{{ route('admin.prizes.destroy', $prize->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

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
@can('prize_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.prizes.massDestroy') }}",
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
    pageLength: 100,
  });
  let table = $('.datatable-Prize:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection