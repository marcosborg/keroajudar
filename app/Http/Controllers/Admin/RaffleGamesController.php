<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRaffleGameRequest;
use App\Http\Requests\StoreRaffleGameRequest;
use App\Http\Requests\UpdateRaffleGameRequest;
use App\Models\Prize;
use App\Models\RaffleGame;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class RaffleGamesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('raffle_game_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $raffleGames = RaffleGame::with('prize')->orderBy('starts_at', 'desc')->get();

        return view('admin.raffleGames.index', compact('raffleGames'));
    }

    public function create()
    {
        abort_if(Gate::denies('raffle_game_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $prizes = Prize::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.raffleGames.create', compact('prizes'));
    }

    public function store(StoreRaffleGameRequest $request)
    {
        RaffleGame::create($request->all());

        return redirect()->route('admin.raffle-games.index');
    }

    public function edit(RaffleGame $raffleGame)
    {
        abort_if(Gate::denies('raffle_game_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $prizes = Prize::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.raffleGames.edit', compact('raffleGame', 'prizes'));
    }

    public function update(UpdateRaffleGameRequest $request, RaffleGame $raffleGame)
    {
        $raffleGame->update($request->all());

        return redirect()->route('admin.raffle-games.index');
    }

    public function show(RaffleGame $raffleGame)
    {
        abort_if(Gate::denies('raffle_game_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $raffleGame->load('prize');

        return view('admin.raffleGames.show', compact('raffleGame'));
    }

    public function destroy(RaffleGame $raffleGame)
    {
        abort_if(Gate::denies('raffle_game_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $raffleGame->delete();

        return back();
    }

    public function massDestroy(MassDestroyRaffleGameRequest $request)
    {
        $raffleGames = RaffleGame::find(request('ids'));

        foreach ($raffleGames as $raffleGame) {
            $raffleGame->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
