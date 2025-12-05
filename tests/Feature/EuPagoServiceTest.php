<?php

namespace Tests\Feature;

use App\Models\Pedido;
use App\Services\EuPagoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class EuPagoServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_multibanco_split_payment()
    {
        Http::fake([
            '*' => Http::response([
                'entidade'  => '12345',
                'referencia'=> '111222333',
                'valor'     => 10.00,
                'codigo'    => 0,
                'mp'        => 'PC:PT',
            ], 200),
        ]);

        $service = new EuPagoService();

        $result = $service->createMultibancoSplitPayment([
            'valor' => 10.00,
            'identificador' => 'TESTE123',
            'beneficiarios' => [
                ['externKey' => 'A', 'valor_part' => 5],
                ['externKey' => 'B', 'valor_part' => 5],
            ],
        ]);

        $this->assertEquals('pendente', $result['status']);
        $this->assertEquals('12345', $result['entidade']);
        $this->assertEquals('111222333', $result['referencia']);
        $this->assertDatabaseHas('pedidos', [
            'identificador' => 'TESTE123',
            'entidade' => '12345',
            'referencia' => '111222333',
        ]);
    }

    /** @test */
    public function it_handles_mbway_split_payment()
    {
        Http::fake([
            '*' => Http::response([
                'transacao' => 'tx123',
                'codigo'    => 0,
                'mp'        => 'MW:PT',
            ], 200),
        ]);

        $service = new EuPagoService();

        $result = $service->createMbwaySplitPayment([
            'valor' => 4.00,
            'identificador' => 'TESTEMW',
            'alias' => '911111111',
            'beneficiarios' => [
                ['externKey' => 'A', 'valor_part' => 2],
                ['externKey' => 'B', 'valor_part' => 2],
            ],
        ]);

        $this->assertEquals('pendente_callback', $result['status']);
        $this->assertDatabaseHas('pedidos', [
            'identificador' => 'TESTEMW',
            'transacao' => 'tx123',
        ]);
    }
}
