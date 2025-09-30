<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWinnerRequest;
use App\Http\Requests\StoreWinnerRequest;
use App\Http\Requests\UpdateWinnerRequest;
use App\Models\Entry;
use App\Models\Prize;
use App\Models\Winner;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WinnerController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('winner_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $winners = Winner::with(['entry', 'prize'])->get();

        return view('admin.winners.index', compact('winners'));
    }

    public function create()
    {
        abort_if(Gate::denies('winner_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entries = Entry::pluck('raffle_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $prizes = Prize::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.winners.create', compact('entries', 'prizes'));
    }

    public function store(StoreWinnerRequest $request)
    {
        $winner = Winner::create($request->all());

        return redirect()->route('admin.winners.index');
    }

    public function edit(Winner $winner)
    {
        abort_if(Gate::denies('winner_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entries = Entry::pluck('raffle_code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $prizes = Prize::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $winner->load('entry', 'prize');

        return view('admin.winners.edit', compact('entries', 'prizes', 'winner'));
    }

    public function update(UpdateWinnerRequest $request, Winner $winner)
    {
        $winner->update($request->all());

        return redirect()->route('admin.winners.index');
    }

    public function show(Winner $winner)
    {
        abort_if(Gate::denies('winner_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $winner->load('entry', 'prize');

        return view('admin.winners.show', compact('winner'));
    }

    public function destroy(Winner $winner)
    {
        abort_if(Gate::denies('winner_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $winner->delete();

        return back();
    }

    public function massDestroy(MassDestroyWinnerRequest $request)
    {
        $winners = Winner::find(request('ids'));

        foreach ($winners as $winner) {
            $winner->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
