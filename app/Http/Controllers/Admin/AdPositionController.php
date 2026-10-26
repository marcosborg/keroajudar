<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AdPositionController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('ad_campaign_access'), Response::HTTP_FORBIDDEN);
        $positions = AdPosition::query()
            ->withCount(['campaigns as active_campaigns_count' => fn ($query) => $query->eligible()])
            ->orderBy('sort_order')->get();

        return view('admin.adPositions.index', compact('positions'));
    }

    public function updatePricing(Request $request, AdPosition $position)
    {
        abort_if(Gate::denies('ad_campaign_edit'), Response::HTTP_FORBIDDEN);

        $validated = $request->validate([
            'daily_price' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
        ]);

        $position->update($validated);

        return back()->with('message', 'Preço diário atualizado.');
    }
}
