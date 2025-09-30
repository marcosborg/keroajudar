@extends('layouts.admin')
@section('content')
@can('winner_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.winners.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.winner.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.winner.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Winner">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.winner.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.winner.fields.entry') }}
                        </th>
                        <th>
                            {{ trans('cruds.entry.fields.email') }}
                        </th>
                        <th>
                            {{ trans('cruds.entry.fields.first_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.entry.fields.last_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.winner.fields.prize') }}
                        </th>
                        <th>
                            {{ trans('cruds.prize.fields.value') }}
                        </th>
                        <th>
                            {{ trans('cruds.winner.fields.draw_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.winner.fields.notes') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($winners as $key => $winner)
                        <tr data-entry-id="{{ $winner->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $winner->id ?? '' }}
                            </td>
                            <td>
                                {{ $winner->entry->raffle_code ?? '' }}
                            </td>
                            <td>
                                {{ $winner->entry->email ?? '' }}
                            </td>
                            <td>
                                {{ $winner->entry->first_name ?? '' }}
                            </td>
                            <td>
                                {{ $winner->entry->last_name ?? '' }}
                            </td>
                            <td>
                                {{ $winner->prize->name ?? '' }}
                            </td>
                            <td>
                                {{ $winner->prize->value ?? '' }}
                            </td>
                            <td>
                                {{ $winner->draw_date ?? '' }}
                            </td>
                            <td>
                                {{ $winner->notes ?? '' }}
                            </td>
                            <td>
                                @can('winner_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.winners.show', $winner->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('winner_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.winners.edit', $winner->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('winner_delete')
                                    <form action="{{ route('admin.winners.destroy', $winner->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('winner_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.winners.massDestroy') }}",
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
  let table = $('.datatable-Winner:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection