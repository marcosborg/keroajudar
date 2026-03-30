<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBeneficiaryDonationRequest;
use App\Models\Beneficiary;
use App\Models\BeneficiaryCategory;
use App\Models\Entry;
use App\Models\Payment;
use App\Models\RaffleGame;
use App\Models\RaffleNumber;
use App\Models\RaffleRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PublicWebsiteController extends Controller
{
    public function site(): JsonResponse
    {
        return response()->json([
            'brand' => [
                'name' => 'Kero Ajudar',
                'logo_url' => url('/images/logo.png'),
                'banner_url' => url('/images/banner-ajuda.png'),
            ],
            'navigation' => [
                ['label' => 'Inicio', 'path' => '/home'],
                ['label' => 'Donativo', 'path' => '/donativo'],
                ['label' => 'Quem Somos', 'path' => '/quem-somos'],
                ['label' => 'Contactos', 'path' => '/contactos'],
            ],
            'home' => [
                'hero' => [
                    'eyebrow' => 'Solidariedade com impacto real',
                    'title' => 'Bem-vindo a nossa missao de ajuda a quem precisa',
                    'description' => 'O seu donativo faz a diferenca na vida de quem precisa. Juntos promovemos a inclusao e a melhoria da qualidade de vida. E ainda se habilita a um sorteio no final.',
                    'cta_label' => 'Fazer um Donativo',
                    'cta_path' => '/donativo',
                ],
                'project' => [
                    'title' => 'O Projeto',
                    'paragraphs' => [
                        'Esta plataforma foi criada para apoiar iniciativas de apoio humanitario e inclusao social. Aqui podera saber mais sobre as acoes que desenvolvemos, os projetos em curso e como os seus contributos sao utilizados.',
                        'O objetivo e transmitir confianca, proximidade e transparencia a cada pessoa que quer apoiar uma causa com impacto concreto.',
                    ],
                ],
                'highlights' => [
                    [
                        'title' => 'Causas verificadas',
                        'description' => 'Selecao de beneficiarios com identidade clara, contexto visivel e ponto de contacto direto.',
                    ],
                    [
                        'title' => 'Donativos simples',
                        'description' => 'Percurso curto para escolher uma causa, doar e acompanhar o impacto sem burocracia.',
                    ],
                    [
                        'title' => 'Sorteio ativo',
                        'description' => 'Quando existe jogo em curso, os donativos podem gerar numeros de participacao automaticamente.',
                    ],
                ],
            ],
            'about' => [
                'title' => 'Quem Somos',
                'paragraphs' => [
                    'Na Kero Ajudar, acreditamos na forca da solidariedade e na capacidade de pequenas acoes gerarem grandes transformacoes.',
                    'Somos uma plataforma online dedicada a unir coracoes generosos a causas que realmente importam, desde associacoes de protecao animal e apoio a criancas com necessidades especiais ate corporacoes de bombeiros voluntarios e outras instituicoes comunitarias.',
                    'A nossa missao e tornar simples, seguro e transparente o processo de doacao, oferecendo uma experiencia de apoio acessivel e consciente.',
                    'Defendemos valores de respeito, responsabilidade, inclusao e confianca, mantendo sempre visivel como os fundos sao utilizados e qual o impacto gerado.',
                ],
            ],
            'contacts' => [
                'title' => 'Contactos',
                'description' => 'Entre em contacto connosco para obter mais informacoes sobre os nossos projetos ou para esclarecer quaisquer duvidas.',
                'address' => 'Rua Exemplo, 123, 4000-123 Porto',
                'email' => 'info@example.com',
                'phone' => '21 234 5678',
            ],
        ]);
    }

    public function beneficiaries(): JsonResponse
    {
        $categories = BeneficiaryCategory::with(['media', 'beneficiaries' => function ($query) {
            $query->where('active', true)->with('media');
        }])->get();

        $beneficiaries = Beneficiary::with(['category', 'media'])
            ->where('active', true)
            ->get();

        return response()->json([
            'categories' => $categories->map(function (BeneficiaryCategory $category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                    'beneficiaries_count' => $category->beneficiaries->count(),
                    'image_url' => $this->resolveMediaUrl(
                        $category->image?->thumbnail ?? $category->cover_url ?? '/images/banner-ajuda.png'
                    ),
                    'cover_url' => $this->resolveMediaUrl($category->cover_url ?? '/images/banner-ajuda.png'),
                ];
            })->values(),
            'beneficiaries' => $beneficiaries->map(fn (Beneficiary $beneficiary) => $this->mapBeneficiaryCard($beneficiary))->values(),
        ]);
    }

    public function showBeneficiary(Beneficiary $beneficiary): JsonResponse
    {
        abort_unless($beneficiary->active, 404);

        $beneficiary->load(['category', 'media']);

        $activeGame = $this->resolveActiveGame();
        $rules = $activeGame ? $activeGame->rules : collect();

        $entryQuery = Entry::query()->where('beneficiary_id', $beneficiary->id);
        $stats = [
            'donations' => (clone $entryQuery)->count(),
            'total_amount' => (float) (clone $entryQuery)->sum('amount'),
            'numbers' => RaffleNumber::query()->whereIn('entry_id', $entryQuery->pluck('id'))->count(),
        ];

        $slug = Str::slug($beneficiary->name);

        return response()->json([
            'beneficiary' => $this->mapBeneficiaryDetail($beneficiary, $stats),
            'share_url' => route('website.beneficiary.donate', ['beneficiary' => $beneficiary->id, 'slug' => $slug]),
            'commission_percent' => $this->resolveCommissionPercent($beneficiary, $activeGame),
            'active_game' => $activeGame ? [
                'id' => $activeGame->id,
                'name' => $activeGame->name,
                'starts_at' => optional($activeGame->starts_at)->toIso8601String(),
                'ends_at' => optional($activeGame->ends_at)->toIso8601String(),
                'commission_percent' => (float) $activeGame->commission_percent,
                'prize' => $activeGame->prize ? [
                    'id' => $activeGame->prize->id,
                    'name' => $activeGame->prize->name,
                ] : null,
                'rules' => $rules->map(fn (RaffleRule $rule) => [
                    'amount' => (float) $rule->amount,
                    'numbers' => (int) $rule->numbers,
                ])->values(),
            ] : null,
            'content' => [
                'impact' => [
                    [
                        'title' => 'Projetos concluidos',
                        'description' => 'Apoio a familias locais, melhoria de infraestruturas e apoio veterinario.',
                    ],
                    [
                        'title' => 'Impacto imediato',
                        'description' => 'Cada donativo ajuda a garantir recursos basicos e acompanhamento especializado.',
                    ],
                    [
                        'title' => 'Proximos passos',
                        'description' => 'Expansao da rede de apoio e novas campanhas de sensibilizacao.',
                    ],
                ],
                'stories' => [
                    [
                        'title' => 'Uma ajuda que chegou a tempo',
                        'description' => 'Uma familia recebeu apoio essencial durante uma fase critica.',
                    ],
                    [
                        'title' => 'Mais oportunidades',
                        'description' => 'Equipamentos novos permitiram continuar um projeto comunitario.',
                    ],
                ],
                'transparency' => [
                    'Relatorios regulares com resultados das campanhas.',
                    'Equipa local com acompanhamento direto das necessidades.',
                    'Objetivos claros e metas visiveis para cada causa.',
                ],
                'faq' => [
                    [
                        'question' => 'Como funciona o sorteio?',
                        'answer' => 'Depois de doar, recebe numeros de participacao para o sorteio quando existe um jogo ativo.',
                    ],
                    [
                        'question' => 'Como sei que o donativo chegou?',
                        'answer' => 'Recebe confirmacao imediata e o codigo do sorteio apos o pagamento sandbox.',
                    ],
                ],
            ],
        ]);
    }

    public function donate(StoreBeneficiaryDonationRequest $request, Beneficiary $beneficiary): JsonResponse
    {
        abort_unless($beneficiary->active, 404);

        $amount = (float) $request->input('amount');
        $activeGame = $this->resolveActiveGame();

        $payload = DB::transaction(function () use ($request, $beneficiary, $amount, $activeGame) {
            $numbers = [];
            $commissionPercent = $this->resolveCommissionPercent($beneficiary, $activeGame);
            $commissionAmount = round($amount * ($commissionPercent / 100), 2);
            $beneficiaryAmount = round($amount - $commissionAmount, 2);

            if ($activeGame) {
                RaffleGame::query()->whereKey($activeGame->id)->lockForUpdate()->first();

                $numbersCount = $this->resolveRaffleNumbersCount($amount, $activeGame);
                $numbers = $this->generateRaffleNumbers($numbersCount, $activeGame);
            }

            $entry = Entry::create([
                'beneficiary_id' => $beneficiary->id,
                'raffle_game_id' => $activeGame?->id,
                'raffle_code' => $numbers ? (string) $numbers[0] : 'no-game',
                'has_raffle_numbers' => !empty($numbers),
                'email' => $request->input('email'),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'phone' => $request->input('phone'),
                'amount' => $amount,
                'commission_percent' => $commissionPercent,
                'commission_amount' => $commissionAmount,
                'beneficiary_amount' => $beneficiaryAmount,
                'is_company' => $request->boolean('is_company'),
                'nif' => $request->input('nif'),
                'nipc' => $request->input('nipc'),
                'address' => $request->input('address'),
                'postal_code' => $request->input('postal_code'),
                'city' => $request->input('city'),
                'country_id' => $request->input('country_id'),
                'consent_privacy' => $request->boolean('consent_privacy'),
                'contact_via' => null,
                'source_page' => $request->fullUrl(),
                'client_ip' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 255),
            ]);

            if ($numbers) {
                foreach ($numbers as $number) {
                    RaffleNumber::create([
                        'entry_id' => $entry->id,
                        'raffle_game_id' => $activeGame->id,
                        'number' => $number,
                    ]);
                }
            }

            $payment = Payment::create([
                'entry_id' => $entry->id,
                'provider' => 'sandbox',
                'transaction' => 'SBX-' . Str::upper(Str::random(10)),
                'amount' => $amount,
                'currency' => 'EUR',
                'status' => 'confirmed',
                'method' => 'cartao',
                'paid_at' => now(),
                'raw_response' => json_encode([
                    'sandbox' => true,
                    'numbers' => $numbers,
                    'raffle_game_id' => $activeGame?->id,
                    'mode' => $activeGame ? 'raffle' : 'regular',
                    'split' => [
                        'commission_percent' => $commissionPercent,
                        'commission_amount' => $commissionAmount,
                        'beneficiary_amount' => $beneficiaryAmount,
                    ],
                ], JSON_UNESCAPED_SLASHES),
            ]);

            return [
                'entry_id' => $entry->id,
                'amount' => $amount,
                'numbers' => $numbers,
                'transaction' => $payment->transaction,
                'game_active' => (bool) $activeGame,
                'commission_percent' => $commissionPercent,
                'commission_amount' => $commissionAmount,
                'beneficiary_amount' => $beneficiaryAmount,
            ];
        });

        return response()->json([
            'message' => 'Donativo confirmado com sucesso.',
            'donation' => $payload,
        ], 201);
    }

    private function mapBeneficiaryCard(Beneficiary $beneficiary): array
    {
        $beneficiary->loadMissing(['category', 'media']);

        return [
            'id' => $beneficiary->id,
            'slug' => Str::slug($beneficiary->name),
            'name' => $beneficiary->name,
            'description' => $beneficiary->description,
            'city' => $beneficiary->city,
            'country' => $beneficiary->country,
            'category_id' => $beneficiary->beneficiary_category_id,
            'category_name' => $beneficiary->category->name ?? null,
            'avatar_url' => $this->resolveMediaUrl(
                $beneficiary->logo_square?->thumbnail
                ?? $beneficiary->photo?->thumbnail
                ?? $beneficiary->cover_url
                ?? '/images/banner-ajuda.png'
            ),
            'cover_url' => $this->resolveMediaUrl($beneficiary->cover_url ?? '/images/banner-ajuda.png'),
        ];
    }

    private function mapBeneficiaryDetail(Beneficiary $beneficiary, array $stats): array
    {
        return [
            'id' => $beneficiary->id,
            'slug' => Str::slug($beneficiary->name),
            'name' => $beneficiary->name,
            'description' => $beneficiary->description,
            'about' => $beneficiary->about,
            'city' => $beneficiary->city,
            'country' => $beneficiary->country,
            'address' => $beneficiary->address,
            'website' => $beneficiary->website,
            'contact_email' => $beneficiary->contact_email,
            'contact_phone' => $beneficiary->contact_phone,
            'category' => $beneficiary->category ? [
                'id' => $beneficiary->category->id,
                'name' => $beneficiary->category->name,
            ] : null,
            'logo_url' => $this->resolveMediaUrl(
                $beneficiary->logo_square?->preview
                ?? $beneficiary->logo_square?->url
                ?? $beneficiary->photo?->preview
                ?? $beneficiary->cover_url
                ?? '/images/banner-ajuda.png'
            ),
            'cover_url' => $this->resolveMediaUrl($beneficiary->cover_url ?? '/images/banner-ajuda.png'),
            'stats' => $stats,
        ];
    }

    private function resolveMediaUrl(?string $url): string
    {
        $url = $url ?: '/images/banner-ajuda.png';

        if (Str::startsWith($url, ['http://', 'https://'])) {
            return $url;
        }

        return url($url);
    }

    private function resolveCommissionPercent(Beneficiary $beneficiary, ?RaffleGame $activeGame): float
    {
        $percent = $activeGame
            ? (float) $activeGame->commission_percent
            : (float) $beneficiary->default_commission_percent;

        if ($percent < 0) {
            return 0.0;
        }

        if ($percent > 100) {
            return 100.0;
        }

        return round($percent, 2);
    }

    private function resolveRaffleNumbersCount(float $amount, RaffleGame $game): int
    {
        $rule = RaffleRule::query()
            ->where('raffle_game_id', $game->id)
            ->where('active', true)
            ->where('amount', '<=', $amount)
            ->orderByDesc('amount')
            ->first();

        if (!$rule) {
            return 1;
        }

        $units = (int) floor($amount / (float) $rule->amount);
        $count = $units * (int) $rule->numbers;

        return max(1, $count);
    }

    private function resolveActiveGame(): ?RaffleGame
    {
        $now = now();

        return RaffleGame::query()
            ->where('active', true)
            ->where('starts_at', '<=', $now)
            ->where('ends_at', '>=', $now)
            ->with(['prize', 'rules' => function ($query) {
                $query->where('active', true)->orderBy('amount');
            }])
            ->orderByDesc('starts_at')
            ->first();
    }

    private function generateRaffleNumbers(int $count, RaffleGame $game): array
    {
        $max = RaffleNumber::query()
            ->where('raffle_game_id', $game->id)
            ->max('number');

        $start = $max === null ? 0 : ((int) $max + 1);
        $end = $start + max(0, $count - 1);

        return $count > 0 ? range($start, $end) : [];
    }
}
