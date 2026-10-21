<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdCampaign;
use App\Models\AdDailyMetric;
use App\Models\AdPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AdReportController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('ad_campaign_access'), Response::HTTP_FORBIDDEN);
        [$from, $to] = $this->dates($request);
        $base = $this->filteredQuery($request, $from, $to);
        $totals = (clone $base)->selectRaw('COALESCE(SUM(impressions), 0) as impressions, COALESCE(SUM(clicks), 0) as clicks')->first();
        $rows = $base->with(['campaign', 'position'])
            ->selectRaw('ad_campaign_id, ad_position_id, SUM(impressions) as impressions, SUM(clicks) as clicks')
            ->groupBy('ad_campaign_id', 'ad_position_id')->orderByDesc('impressions')->get();

        return view('admin.adReports.index', [
            'rows' => $rows, 'totals' => $totals, 'from' => $from, 'to' => $to,
            'campaigns' => AdCampaign::orderBy('title')->get(),
            'positions' => AdPosition::orderBy('sort_order')->get(),
        ]);
    }

    public function export(Request $request)
    {
        abort_if(Gate::denies('ad_campaign_access'), Response::HTTP_FORBIDDEN);
        [$from, $to] = $this->dates($request);
        $rows = $this->filteredQuery($request, $from, $to)->with(['campaign', 'position'])->orderBy('metric_date')->get();

        return response()->streamDownload(function () use ($rows) {
            $stream = fopen('php://output', 'w');
            fwrite($stream, "\xEF\xBB\xBF");
            fputcsv($stream, ['Data', 'Campanha', 'Anunciante', 'Posição', 'Categoria', 'Região', 'Impressões', 'Cliques', 'CTR']);
            foreach ($rows as $row) {
                $ctr = $row->impressions ? round(($row->clicks / $row->impressions) * 100, 2).'%' : '0%';
                fputcsv($stream, [
                    $row->metric_date->format('Y-m-d'), $row->campaign?->title, $row->campaign?->advertiser_name,
                    $row->position?->name,
                    $row->category_code === 'all' ? 'Todas' : (config('advertising.categories')[$row->category_code] ?? $row->category_code),
                    $row->region_code === 'all' ? 'Todo o país' : (config('advertising.regions')[$row->region_code] ?? $row->region_code),
                    $row->impressions, $row->clicks, $ctr,
                ]);
            }
            fclose($stream);
        }, "relatorio-publicidade-{$from}-{$to}.csv", ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function dates(Request $request): array
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'], 'to' => ['nullable', 'date', 'after_or_equal:from'],
            'campaign_id' => ['nullable', 'exists:ad_campaigns,id'], 'position_id' => ['nullable', 'exists:ad_positions,id'],
        ]);

        return [$validated['from'] ?? now()->subDays(29)->toDateString(), $validated['to'] ?? today()->toDateString()];
    }

    private function filteredQuery(Request $request, string $from, string $to)
    {
        return AdDailyMetric::query()->whereBetween('metric_date', [$from, $to])
            ->when($request->filled('campaign_id'), fn ($query) => $query->where('ad_campaign_id', $request->integer('campaign_id')))
            ->when($request->filled('position_id'), fn ($query) => $query->where('ad_position_id', $request->integer('position_id')));
    }
}
