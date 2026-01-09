@extends('layouts.admin')
@section('content')
@can('raffle_game_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.raffle-games.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.raffleGame.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.raffleGame.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-RaffleGame">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('cruds.raffleGame.fields.id') }}</th>
                        <th>{{ trans('cruds.raffleGame.fields.name') }}</th>
                        <th>{{ trans('cruds.raffleGame.fields.prize') }}</th>
                        <th>{{ trans('cruds.raffleGame.fields.starts_at') }}</th>
                        <th>{{ trans('cruds.raffleGame.fields.ends_at') }}</th>
                        <th>{{ trans('cruds.raffleGame.fields.active') }}</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($raffleGames as $key => $raffleGame)
                        <tr data-entry-id="{{ $raffleGame->id }}">
                            <td></td>
                            <td>{{ $raffleGame->id ?? '' }}</td>
                            <td>{{ $raffleGame->name ?? '' }}</td>
                            <td>{{ $raffleGame->prize->name ?? '' }}</td>
                            <td>{{ $raffleGame->starts_at ?? '' }}</td>
                            <td>{{ $raffleGame->ends_at ?? '' }}</td>
                            <td>
                                <span style="display:none">{{ $raffleGame->active ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $raffleGame->active ? 'checked' : '' }}>
                            </td>
                            <td>
                                @can('raffle_game_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.raffle-games.show', $raffleGame->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                                @can('raffle_game_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.raffle-games.edit', $raffleGame->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan
                                @can('raffle_game_delete')
                                    <form action="{{ route('admin.raffle-games.destroy', $raffleGame->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('raffle_game_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.raffle-games.massDestroy') }}",
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
  let table = $('.datatable-RaffleGame:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
