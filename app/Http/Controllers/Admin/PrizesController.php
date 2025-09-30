<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPrizeRequest;
use App\Http\Requests\StorePrizeRequest;
use App\Http\Requests\UpdatePrizeRequest;
use App\Models\Prize;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PrizesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('prize_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $prizes = Prize::all();

        return view('admin.prizes.index', compact('prizes'));
    }

    public function create()
    {
        abort_if(Gate::denies('prize_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.prizes.create');
    }

    public function store(StorePrizeRequest $request)
    {
        $prize = Prize::create($request->all());

        return redirect()->route('admin.prizes.index');
    }

    public function edit(Prize $prize)
    {
        abort_if(Gate::denies('prize_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.prizes.edit', compact('prize'));
    }

    public function update(UpdatePrizeRequest $request, Prize $prize)
    {
        $prize->update($request->all());

        return redirect()->route('admin.prizes.index');
    }

    public function show(Prize $prize)
    {
        abort_if(Gate::denies('prize_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.prizes.show', compact('prize'));
    }

    public function destroy(Prize $prize)
    {
        abort_if(Gate::denies('prize_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $prize->delete();

        return back();
    }

    public function massDestroy(MassDestroyPrizeRequest $request)
    {
        $prizes = Prize::find(request('ids'));

        foreach ($prizes as $prize) {
            $prize->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
