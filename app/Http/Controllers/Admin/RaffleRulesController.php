<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRaffleRuleRequest;
use App\Http\Requests\StoreRaffleRuleRequest;
use App\Http\Requests\UpdateRaffleRuleRequest;
use App\Models\RaffleRule;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RaffleRulesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('raffle_rule_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $raffleRules = RaffleRule::orderBy('amount')->get();

        return view('admin.raffleRules.index', compact('raffleRules'));
    }

    public function create()
    {
        abort_if(Gate::denies('raffle_rule_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.raffleRules.create');
    }

    public function store(StoreRaffleRuleRequest $request)
    {
        RaffleRule::create($request->all());

        return redirect()->route('admin.raffle-rules.index');
    }

    public function edit(RaffleRule $raffleRule)
    {
        abort_if(Gate::denies('raffle_rule_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.raffleRules.edit', compact('raffleRule'));
    }

    public function update(UpdateRaffleRuleRequest $request, RaffleRule $raffleRule)
    {
        $raffleRule->update($request->all());

        return redirect()->route('admin.raffle-rules.index');
    }

    public function show(RaffleRule $raffleRule)
    {
        abort_if(Gate::denies('raffle_rule_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.raffleRules.show', compact('raffleRule'));
    }

    public function destroy(RaffleRule $raffleRule)
    {
        abort_if(Gate::denies('raffle_rule_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $raffleRule->delete();

        return back();
    }

    public function massDestroy(MassDestroyRaffleRuleRequest $request)
    {
        $raffleRules = RaffleRule::find(request('ids'));

        foreach ($raffleRules as $raffleRule) {
            $raffleRule->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
