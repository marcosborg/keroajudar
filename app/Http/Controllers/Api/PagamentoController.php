<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EuPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PagamentoController extends Controller
{
    protected EuPagoService $euPago;

    public function __construct(EuPagoService $euPago)
    {
        $this->euPago = $euPago;
    }

    public function criarMultibanco(Request $request)
    {
        $data = $this->validateRequest($request, false);
        $this->validateSomaBeneficiarios($data['valor'], $data['beneficiarios']);

        $result = $this->euPago->createMultibancoSplitPayment($data);

        return response()->json($result, 201);
    }

    public function criarMbway(Request $request)
    {
        $data = $this->validateRequest($request, true);
        $this->validateSomaBeneficiarios($data['valor'], $data['beneficiarios']);

        $result = $this->euPago->createMbwaySplitPayment($data);

        return response()->json($result, 201);
    }

    public function callback(Request $request)
    {
        return $this->euPago->validateWebhook($request);
    }

    protected function validateRequest(Request $request, bool $isMbway): array
    {
        $rules = [
            'valor'         => ['required', 'numeric', 'min:0.01'],
            'identificador' => ['required', 'string', 'max:64', 'regex:/^[A-Za-z0-9_-]+$/'],
            'beneficiarios' => ['required', 'array', 'min:1'],
            'beneficiarios.*.externKey'  => ['required', 'string', 'max:64'],
            'beneficiarios.*.valor_part' => ['required', 'numeric', 'min:0.01'],
        ];

        if ($isMbway) {
            $rules['alias'] = ['required', 'digits:9'];
            $rules['descricao'] = ['nullable', 'string', 'max:140'];
        } else {
            $rules['data_limite'] = ['nullable', 'date'];
            $rules['permite_multiplos'] = ['nullable', 'boolean'];
        }

        $validated = $request->validate($rules);

        return $validated;
    }

    protected function validateSomaBeneficiarios($valor, array $beneficiarios): void
    {
        $soma = collect($beneficiarios)->sum('valor_part');
        if (bccomp((string) $soma, (string) $valor, 2) !== 0) {
            Log::channel('eupago')->error('Soma de beneficiários diferente do valor', ['valor' => $valor, 'soma' => $soma, 'beneficiarios' => $beneficiarios]);
            abort(response()->json(['message' => 'Soma dos beneficiários tem de ser igual ao valor'], 422));
        }
    }
}
