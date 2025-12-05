@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.list') }} {{ trans('cruds.beneficiary.title') }}
    </div>

    <div class="card-body">
        @can('beneficiary_create')
            <div style="margin-bottom: 10px;" class="row">
                <div class="col-lg-12">
                    <a class="btn btn-success" href="{{ route('admin.beneficiaries.create') }}">
                        {{ trans('global.add') }} {{ trans('cruds.beneficiary.title_singular') }}
                    </a>
                </div>
            </div>
        @endcan
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Beneficiary">
                <thead>
                    <tr>
                        <th width="10">
                        </th>
                        <th>
                            {{ trans('cruds.beneficiary.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.beneficiary.fields.photo') }}
                        </th>
                        <th>
                            {{ trans('cruds.beneficiary.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.beneficiary.fields.category') }}
                        </th>
                        <th>
                            {{ trans('cruds.beneficiary.fields.active') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($beneficiaries as $key => $beneficiary)
                        <tr data-entry-id="{{ $beneficiary->id }}">
                            <td>
                            </td>
                            <td>
                                {{ $beneficiary->id ?? '' }}
                            </td>
                            <td>
                                @if($beneficiary->photo)
                                    <img src="{{ $beneficiary->photo->preview }}" alt="" width="120">
                                @endif
                            </td>
                            <td>
                                {{ $beneficiary->name ?? '' }}
                            </td>
                            <td>
                                {{ $beneficiary->category->name ?? '' }}
                            </td>
                            <td>
                                <span class="badge badge-{{ $beneficiary->active ? 'success' : 'secondary' }}">
                                    {{ $beneficiary->active ? trans('global.yes') : trans('global.no') }}
                                </span>
                            </td>
                            <td>
                                @can('beneficiary_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.beneficiaries.show', $beneficiary->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('beneficiary_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.beneficiaries.edit', $beneficiary->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('beneficiary_delete')
                                    <form action="{{ route('admin.beneficiaries.destroy', $beneficiary->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        @method('DELETE')
                                        @csrf
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
@can('beneficiary_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.beneficiaries.massDestroy') }}",
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
    pageLength: 50,
  });
  let table = $('.datatable-Beneficiary:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
})

</script>
@endsection
