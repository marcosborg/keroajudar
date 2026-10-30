<?php

namespace Tests\Feature;

use App\Models\AdCampaign;
use App\Models\AdOrder;
use App\Models\AdPosition;
use App\Models\Beneficiary;
use App\Models\BeneficiaryCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdvertisingOrderTest extends TestCase
{
    use RefreshDatabase;

    private Beneficiary $beneficiary;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'advertising.booking_enabled' => true,
            'eupago.api_key' => 'test-api-key',
            'eupago.advertising_extern_key' => 'KERO-ADS',
        ]);
        Storage::fake('public');

        $category = BeneficiaryCategory::create(['name' => 'Apoio animal']);
        $this->beneficiary = Beneficiary::create([
            'beneficiary_category_id' => $category->id,
            'name' => 'Associação Patas Felizes',
            'active' => true,
        ]);
    }

    /** @test */
    public function a_customer_can_request_a_position_and_start_multibanco_payment(): void
    {
        Http::fake(['*' => Http::response([
            'entidade' => '12345',
            'referencia' => '123456789',
            'codigo' => 0,
            'mp' => 'PC:PT',
        ])]);

        $position = AdPosition::query()->where('code', 'home-01')->firstOrFail();
        $position->update(['daily_price' => 10]);

        $response = $this->post(route('advertising.orders.store', $position), $this->validPayload($position));

        $order = AdOrder::query()->firstOrFail();
        $response->assertRedirect(route('advertising.orders.show', $order));
        $this->assertSame(3, $order->billable_days);
        $this->assertSame('30.00', $order->amount);
        $this->assertTrue($order->beneficiary->is($this->beneficiary));
        $this->assertCount(1, $order->getMedia('desktop_creative'));
        $this->assertCount(1, $order->getMedia('mobile_creative'));
        $this->assertDatabaseHas('pedidos', [
            'identificador' => $order->payment_identifier,
            'entidade' => '12345',
            'referencia' => '123456789',
        ]);
    }

    /** @test */
    public function eupago_callback_marks_the_order_paid_and_creates_a_draft_campaign_once(): void
    {
        Http::fake(['*' => Http::response([
            'transacao' => 'mw-123',
            'codigo' => 0,
            'mp' => 'MW:PT',
        ])]);

        $position = AdPosition::query()->where('code', 'home-02')->firstOrFail();
        $position->update(['daily_price' => 5]);
        $payload = $this->validPayload($position) + ['payment_method' => 'mbway', 'mbway_phone' => '911111111'];
        $payload['payment_method'] = 'mbway';
        $payload['mbway_phone'] = '911111111';

        $this->post(route('advertising.orders.store', $position), $payload)->assertRedirect();
        $order = AdOrder::query()->firstOrFail();

        $callback = route('api.eupago.callback', [], false).'?'.http_build_query([
            'chave_api' => 'test-api-key',
            'identificador' => $order->payment_identifier,
            'transacao' => 'mw-123',
            'mp' => 'MW:PT',
        ]);

        $this->get($callback)->assertOk();
        $this->get($callback)->assertOk();

        $order->refresh();
        $this->assertSame(AdOrder::STATUS_PAID, $order->status);
        $this->assertNotNull($order->paid_at);
        $this->assertDatabaseCount('ad_campaigns', 1);
        $this->assertSame(AdCampaign::STATUS_DRAFT, $order->campaign->status);
        $this->assertSame('Empresa Exemplo', $order->campaign->advertiser_name);
        $this->assertTrue($order->campaign->beneficiary->is($this->beneficiary));
        $this->assertCount(1, $order->campaign->getMedia('desktop_creative'));
        $this->assertCount(1, $order->campaign->getMedia('mobile_creative'));
    }

    /** @test */
    public function payment_is_blocked_until_the_position_price_and_eupago_account_are_configured(): void
    {
        $position = AdPosition::query()->where('code', 'home-03')->firstOrFail();
        $position->update(['daily_price' => null]);

        $this->post(route('advertising.orders.store', $position), $this->validPayload($position))
            ->assertStatus(503);

        $this->assertDatabaseCount('ad_orders', 0);
    }

    /** @test */
    public function creatives_must_match_the_exact_desktop_and_mobile_dimensions(): void
    {
        $position = AdPosition::query()->where('code', 'home-04')->firstOrFail();
        $position->update(['daily_price' => 10]);
        $payload = $this->validPayload($position);
        $payload['desktop_creative'] = UploadedFile::fake()->image('wrong.png', 301, 250);
        $payload['mobile_creative'] = UploadedFile::fake()->image('wrong-mobile.png', 320, 99);

        $this->post(route('advertising.orders.store', $position), $payload)
            ->assertSessionHasErrors(['desktop_creative', 'mobile_creative']);

        $this->assertDatabaseCount('ad_orders', 0);
    }

    /** @test */
    public function the_customer_must_choose_an_active_association(): void
    {
        $position = AdPosition::query()->where('code', 'home-05')->firstOrFail();
        $position->update(['daily_price' => 10]);
        $this->beneficiary->update(['active' => false]);

        $this->post(route('advertising.orders.store', $position), $this->validPayload($position))
            ->assertSessionHasErrors('beneficiary_id');

        $this->assertDatabaseCount('ad_orders', 0);
    }

    /** @test */
    public function an_association_page_links_to_the_position_with_a_locked_beneficiary(): void
    {
        $position = AdPosition::query()->where('code', 'home-06')->firstOrFail();
        $position->update(['daily_price' => 12.50]);

        $this->get(route('website.beneficiary.donate', [
            'beneficiary' => $this->beneficiary,
            'slug' => Str::slug($this->beneficiary->name),
        ]))->assertOk()
            ->assertSee('Comprar uma posição publicitária')
            ->assertSee($position->desktopDimensions());

        $signedUrl = URL::signedRoute('advertising.orders.create', [
            'position' => $position,
            'beneficiary' => $this->beneficiary,
        ]);

        $this->get($signedUrl)->assertOk()
            ->assertSee($this->beneficiary->name)
            ->assertSee('não pode ser alterada')
            ->assertDontSee('Escolha uma associação');
    }

    /** @test */
    public function a_signed_association_referral_cannot_be_changed_during_checkout(): void
    {
        Http::fake(['*' => Http::response([
            'entidade' => '12345',
            'referencia' => '123456789',
            'codigo' => 0,
            'mp' => 'PC:PT',
        ])]);

        $other = Beneficiary::create([
            'beneficiary_category_id' => $this->beneficiary->beneficiary_category_id,
            'name' => 'Outra Associação',
            'active' => true,
        ]);
        $position = AdPosition::query()->where('code', 'home-07')->firstOrFail();
        $position->update(['daily_price' => 10]);
        $signedUrl = URL::signedRoute('advertising.orders.create', [
            'position' => $position,
            'beneficiary' => $this->beneficiary,
        ]);
        $payload = $this->validPayload($position);
        $payload['beneficiary_id'] = $other->id;

        $this->post($signedUrl, $payload)->assertRedirect();

        $this->assertTrue(AdOrder::query()->firstOrFail()->beneficiary->is($this->beneficiary));
    }

    /** @test */
    public function a_direct_visitor_can_choose_an_association(): void
    {
        $position = AdPosition::query()->where('code', 'home-08')->firstOrFail();

        $this->get(route('advertising.orders.create', $position))->assertOk()
            ->assertSee('Escolha uma associação')
            ->assertSee($this->beneficiary->name);
    }

    /** @test */
    public function the_association_can_copy_a_signed_link_that_tracks_visits_without_personal_data(): void
    {
        $pageUrl = route('website.beneficiary.donate', [
            'beneficiary' => $this->beneficiary,
            'slug' => Str::slug($this->beneficiary->name),
        ]);
        $trackedUrl = URL::signedRoute('website.beneficiary.referral', [
            'beneficiary' => $this->beneficiary,
        ]);

        $this->get($pageUrl)->assertOk()->assertSee('Copiar link com rastreio');
        $this->get($trackedUrl)->assertRedirect($pageUrl.'?'.http_build_query([
            'utm_source' => 'association',
            'utm_medium' => 'referral',
            'utm_campaign' => 'beneficiary-'.$this->beneficiary->id,
        ]));
        $this->get($trackedUrl)->assertRedirect();

        $this->assertDatabaseHas('beneficiary_referral_metrics', [
            'beneficiary_id' => $this->beneficiary->id,
            'metric_date' => now()->toDateString(),
            'visits' => 2,
        ]);
        $this->assertNotContains('ip_address', Schema::getColumnListing('beneficiary_referral_metrics'));
    }

    /** @test */
    public function the_beneficiary_area_provides_downloadable_tracked_banner_formats(): void
    {
        $this->actingAs($this->beneficiary, 'beneficiary')
            ->get(route('beneficiaries.area'))
            ->assertOk()
            ->assertSee('Banners prontos a instalar')
            ->assertSee('300×250 px')
            ->assertSee('728×90 px')
            ->assertSee('320×100 px')
            ->assertSee('160×600 px')
            ->assertSee('Copiar código HTML');

        $previewUrl = URL::signedRoute('website.beneficiary.banner', [
            'beneficiary' => $this->beneficiary,
            'format' => 'medium-rectangle',
        ]);
        $this->get($previewUrl)->assertOk()
            ->assertHeader('Content-Type', 'image/svg+xml; charset=UTF-8')
            ->assertSee('<svg', false)
            ->assertSee($this->beneficiary->name);

        $downloadUrl = URL::signedRoute('website.beneficiary.banner', [
            'beneficiary' => $this->beneficiary,
            'format' => 'leaderboard',
            'download' => 1,
        ]);
        $this->get($downloadUrl)->assertOk()
            ->assertHeader('Content-Disposition', 'attachment; filename="kero-ajudar-associacao-patas-felizes-728x90.svg"');
    }

    private function validPayload(AdPosition $position): array
    {
        return [
            'customer_type' => 'company',
            'contact_name' => 'Maria Silva',
            'company_name' => 'Empresa Exemplo',
            'tax_number' => '509123456',
            'email' => 'maria@example.com',
            'phone' => '912345678',
            'address' => 'Rua de Teste, 10',
            'postal_code' => '1000-100',
            'city' => 'Lisboa',
            'country' => 'Portugal',
            'campaign_title' => 'Campanha de teste',
            'destination_url' => 'https://example.com/campanha',
            'beneficiary_id' => $this->beneficiary->id,
            'desktop_creative' => UploadedFile::fake()->image('desktop.png', $position->desktop_width, $position->desktop_height),
            'mobile_creative' => UploadedFile::fake()->image('mobile.png', $position->mobile_width, $position->mobile_height),
            'starts_at' => now()->toDateString(),
            'ends_at' => now()->addDays(2)->toDateString(),
            'targets_all_categories' => '1',
            'targets_all_regions' => '1',
            'payment_method' => 'multibanco',
            'consent_terms' => '1',
        ];
    }
}
