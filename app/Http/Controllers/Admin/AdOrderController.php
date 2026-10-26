<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdOrder;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AdOrderController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('ad_campaign_access'), Response::HTTP_FORBIDDEN);

        $orders = AdOrder::query()
            ->with(['position', 'beneficiary', 'payment'])
            ->latest()
            ->paginate(30);

        return view('admin.adOrders.index', compact('orders'));
    }

    public function show(AdOrder $adOrder)
    {
        abort_if(Gate::denies('ad_campaign_access'), Response::HTTP_FORBIDDEN);
        $adOrder->load(['position', 'beneficiary', 'payment', 'campaign']);

        return view('admin.adOrders.show', compact('adOrder'));
    }
}
