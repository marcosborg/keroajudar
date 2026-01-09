@extends('layouts.admin')
@section('content')
@can('raffle_rule_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.raffle-rules.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.raffleRule.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.raffleRule.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-RaffleRule">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('cruds.raffleRule.fields.id') }}</th>
                        <th>{{ trans('cruds.raffleRule.fields.amount') }}</th>
                        <th>{{ trans('cruds.raffleRule.fields.numbers') }}</th>
                        <th>{{ trans('cruds.raffleRule.fields.active') }}</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($raffleRules as $key => $raffleRule)
                        <tr data-entry-id="{{ $raffleRule->id }}">
                            <td></td>
                            <td>{{ $raffleRule->id ?? '' }}</td>
                            <td>{{ $raffleRule->amount ?? '' }}</td>
                            <td>{{ $raffleRule->numbers ?? '' }}</td>
                            <td>
                                <span style="display:none">{{ $raffleRule->active ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $raffleRule->active ? 'checked' : '' }}>
                            </td>
                            <td>
                                @can('raffle_rule_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.raffle-rules.show', $raffleRule->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                                @can('raffle_rule_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.raffle-rules.edit', $raffleRule->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan
                                @can('raffle_rule_delete')
                                    <form action="{{ route('admin.raffle-rules.destroy', $raffleRule->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('raffle_rule_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.raffle-rules.massDestroy') }}",
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
    order: [[ 1, 'asc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-RaffleRule:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
