<?php

namespace Tests\Feature;

use App\Models\AdCampaign;
use App\Models\AdDailyMetric;
use App\Models\AdPosition;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Services\AdvertisingTokenService;
use App\Services\HomeAdvertisingService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdvertisingCenterTest extends TestCase
{
    use DatabaseTransactions;

    public function test_home_renders_the_ten_fixed_positions_with_available_fallback(): void
    {
        config(['advertising.enabled' => true, 'advertising.adsense.enabled' => false]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Centro de Publicidade');
        $response->assertSeeInOrder(['advertising-slot-home-01', 'advertising-slot-home-10'], false);
        $this->assertSame(10, substr_count($response->getContent(), 'Anuncie aqui'));
    }

    public function test_default_view_excludes_a_segmented_campaign_but_selected_filters_include_it(): void
    {
        Storage::fake('public');
        $position = AdPosition::where('code', 'home-01')->firstOrFail();
        $campaign = AdCampaign::create([
            'ad_position_id' => $position->id,
            'advertiser_name' => 'Patrocinador local',
            'title' => 'Campanha animais Lisboa',
            'destination_url' => 'https://example.com/campanha',
            'status' => AdCampaign::STATUS_ACTIVE,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
            'targets_all_categories' => false,
            'target_categories' => ['animals'],
            'targets_all_regions' => false,
            'target_regions' => ['lisboa'],
        ]);
        $this->attachCreatives($campaign);

        $service = app(HomeAdvertisingService::class);
        $defaultRequest = $this->requestWithSession('/');
        $default = $service->slots($defaultRequest, 'all', 'all')->firstWhere('position.id', $position->id);
        $selectedRequest = $this->requestWithSession('/?ad_category=animals&ad_region=lisboa');
        $selected = $service->slots($selectedRequest, 'animals', 'lisboa')->firstWhere('position.id', $position->id);

        $this->assertSame('available', $default->type);
        $this->assertSame('campaign', $selected->type);
        $this->assertTrue($selected->campaign->is($campaign));
    }

    public function test_home_renders_an_eligible_direct_campaign_before_the_fallback(): void
    {
        Storage::fake('public');
        $position = AdPosition::where('code', 'home-03')->firstOrFail();
        $campaign = AdCampaign::create([
            'ad_position_id' => $position->id,
            'advertiser_name' => 'Empresa Solidária',
            'title' => 'Campanha nacional',
            'destination_url' => 'https://example.com/nacional',
            'status' => AdCampaign::STATUS_ACTIVE,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
            'targets_all_categories' => true,
            'targets_all_regions' => true,
        ]);
        $this->attachCreatives($campaign);

        $this->get('/')->assertOk()
            ->assertSee('Empresa Solidária — Campanha nacional')
            ->assertSee('data-impression-token', false)
            ->assertSee('/advertising/click/', false);
    }

    public function test_impression_and_click_tokens_update_daily_aggregates(): void
    {
        $position = AdPosition::where('code', 'home-02')->firstOrFail();
        $campaign = AdCampaign::create([
            'ad_position_id' => $position->id,
            'advertiser_name' => 'Anunciante',
            'title' => 'Campanha de métricas',
            'destination_url' => 'https://example.com/landing',
            'status' => AdCampaign::STATUS_ACTIVE,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
            'targets_all_categories' => true,
            'targets_all_regions' => true,
        ]);
        $tokens = app(AdvertisingTokenService::class);

        $impression = $tokens->issue('impression', $campaign, $position, 'animals', 'lisboa');
        $click = $tokens->issue('click', $campaign, $position, 'animals', 'lisboa');

        $this->withSession(['_token' => 'advertising-test-token'])
            ->withHeader('X-CSRF-TOKEN', 'advertising-test-token')
            ->postJson(route('advertising.impressions'), ['tokens' => [$impression, $impression]])
            ->assertOk()->assertJson(['accepted' => 1]);
        $this->withHeader('X-CSRF-TOKEN', 'advertising-test-token')
            ->postJson(route('advertising.impressions'), ['tokens' => [$impression]])
            ->assertOk()->assertJson(['accepted' => 0]);
        $this->get(route('advertising.click', ['token' => $click]))
            ->assertRedirect('https://example.com/landing');

        $metric = AdDailyMetric::where('ad_campaign_id', $campaign->id)->firstOrFail();
        $this->assertSame(1, $metric->impressions);
        $this->assertSame(1, $metric->clicks);
        $this->assertSame('animals', $metric->category_code);
        $this->assertSame('lisboa', $metric->region_code);
    }

    public function test_invalid_click_token_is_rejected(): void
    {
        $this->get(route('advertising.click', ['token' => 'invalid-token']))->assertNotFound();
    }

    public function test_administration_requires_authentication(): void
    {
        $this->get(route('admin.ad-campaigns.index'))->assertRedirect(route('login'));
        $this->get(route('admin.ad-positions.index'))->assertRedirect(route('login'));
        $this->get(route('admin.ad-reports.index'))->assertRedirect(route('login'));
    }

    public function test_authorized_administration_pages_render(): void
    {
        $role = Role::create(['title' => 'Gestor publicidade']);
        $role->permissions()->sync(Permission::where('title', 'like', 'ad_campaign_%')->pluck('id'));
        $user = User::create(['name' => 'Gestor', 'email' => 'gestor-ads@example.test', 'password' => 'secret-test-password']);
        $user->roles()->attach($role);

        $this->actingAs($user)->get(route('admin.ad-campaigns.index'))->assertOk()->assertSee('Campanhas do centro publicitário');
        $this->actingAs($user)->get(route('admin.ad-campaigns.create'))->assertOk()->assertSee('Criativo desktop');
        $this->actingAs($user)->get(route('admin.ad-positions.index'))->assertOk()->assertSee('Posições fixas da home');
        $this->actingAs($user)->get(route('admin.ad-reports.index'))->assertOk()->assertSee('Relatório de publicidade');
    }

    private function requestWithSession(string $uri): Request
    {
        $request = Request::create($uri);
        $request->setLaravelSession(app('session')->driver());
        $request->session()->start();

        return $request;
    }

    private function attachCreatives(AdCampaign $campaign): void
    {
        $pixel = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=');
        $campaign->addMediaFromString($pixel)->usingFileName('desktop.png')->toMediaCollection('desktop_creative', 'public');
        $campaign->addMediaFromString($pixel)->usingFileName('mobile.png')->toMediaCollection('mobile_creative', 'public');
    }
}
