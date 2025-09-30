@extends('layouts.admin')
@section('content')
@can('entry_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.entries.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.entry.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.entry.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Entry">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.entry.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.entry.fields.raffle_code') }}
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
                            {{ trans('cruds.entry.fields.phone') }}
                        </th>
                        <th>
                            {{ trans('cruds.entry.fields.amount') }}
                        </th>
                        <th>
                            {{ trans('cruds.entry.fields.is_company') }}
                        </th>
                        <th>
                            {{ trans('cruds.entry.fields.nif') }}
                        </th>
                        <th>
                            {{ trans('cruds.entry.fields.nipc') }}
                        </th>
                        <th>
                            {{ trans('cruds.entry.fields.address') }}
                        </th>
                        <th>
                            {{ trans('cruds.entry.fields.postal_code') }}
                        </th>
                        <th>
                            {{ trans('cruds.entry.fields.city') }}
                        </th>
                        <th>
                            {{ trans('cruds.entry.fields.country') }}
                        </th>
                        <th>
                            {{ trans('cruds.entry.fields.consent_privacy') }}
                        </th>
                        <th>
                            {{ trans('cruds.entry.fields.contact_via') }}
                        </th>
                        <th>
                            {{ trans('cruds.entry.fields.source_page') }}
                        </th>
                        <th>
                            {{ trans('cruds.entry.fields.client_ip') }}
                        </th>
                        <th>
                            {{ trans('cruds.entry.fields.user_agent') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entries as $key => $entry)
                        <tr data-entry-id="{{ $entry->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $entry->id ?? '' }}
                            </td>
                            <td>
                                {{ $entry->raffle_code ?? '' }}
                            </td>
                            <td>
                                {{ $entry->email ?? '' }}
                            </td>
                            <td>
                                {{ $entry->first_name ?? '' }}
                            </td>
                            <td>
                                {{ $entry->last_name ?? '' }}
                            </td>
                            <td>
                                {{ $entry->phone ?? '' }}
                            </td>
                            <td>
                                {{ $entry->amount ?? '' }}
                            </td>
                            <td>
                                <span style="display:none">{{ $entry->is_company ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $entry->is_company ? 'checked' : '' }}>
                            </td>
                            <td>
                                {{ $entry->nif ?? '' }}
                            </td>
                            <td>
                                {{ $entry->nipc ?? '' }}
                            </td>
                            <td>
                                {{ $entry->address ?? '' }}
                            </td>
                            <td>
                                {{ $entry->postal_code ?? '' }}
                            </td>
                            <td>
                                {{ $entry->city ?? '' }}
                            </td>
                            <td>
                                {{ $entry->country->name ?? '' }}
                            </td>
                            <td>
                                <span style="display:none">{{ $entry->consent_privacy ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $entry->consent_privacy ? 'checked' : '' }}>
                            </td>
                            <td>
                                {{ App\Models\Entry::CONTACT_VIA_RADIO[$entry->contact_via] ?? '' }}
                            </td>
                            <td>
                                {{ $entry->source_page ?? '' }}
                            </td>
                            <td>
                                {{ $entry->client_ip ?? '' }}
                            </td>
                            <td>
                                {{ $entry->user_agent ?? '' }}
                            </td>
                            <td>
                                @can('entry_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.entries.show', $entry->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('entry_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.entries.edit', $entry->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('entry_delete')
                                    <form action="{{ route('admin.entries.destroy', $entry->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('entry_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.entries.massDestroy') }}",
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
  let table = $('.datatable-Entry:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection