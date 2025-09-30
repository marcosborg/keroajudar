<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyEntryRequest;
use App\Http\Requests\StoreEntryRequest;
use App\Http\Requests\UpdateEntryRequest;
use App\Models\Country;
use App\Models\Entry;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EntrieController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('entry_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entries = Entry::with(['country'])->get();

        return view('admin.entries.index', compact('entries'));
    }

    public function create()
    {
        abort_if(Gate::denies('entry_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $countries = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.entries.create', compact('countries'));
    }

    public function store(StoreEntryRequest $request)
    {
        $entry = Entry::create($request->all());

        return redirect()->route('admin.entries.index');
    }

    public function edit(Entry $entry)
    {
        abort_if(Gate::denies('entry_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $countries = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $entry->load('country');

        return view('admin.entries.edit', compact('countries', 'entry'));
    }

    public function update(UpdateEntryRequest $request, Entry $entry)
    {
        $entry->update($request->all());

        return redirect()->route('admin.entries.index');
    }

    public function show(Entry $entry)
    {
        abort_if(Gate::denies('entry_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entry->load('country');

        return view('admin.entries.show', compact('entry'));
    }

    public function destroy(Entry $entry)
    {
        abort_if(Gate::denies('entry_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entry->delete();

        return back();
    }

    public function massDestroy(MassDestroyEntryRequest $request)
    {
        $entries = Entry::find(request('ids'));

        foreach ($entries as $entry) {
            $entry->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
