<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\BeneficiaryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebsiteController extends Controller
{
    public function index()
    {
        return view('website.home');
    }

    public function donativo()
    {
        $categories = BeneficiaryCategory::with(['media', 'beneficiaries' => function ($query) {
            $query->where('active', true)->with('media');
        }])->get();

        $beneficiaries = Beneficiary::with(['category', 'media'])
            ->where('active', true)
            ->get();

        return view('website.donativo-list', compact('categories', 'beneficiaries'));
    }

    public function beneficiaryDonation(Beneficiary $beneficiary, $slug = null)
    {
        abort_unless($beneficiary->active, 404);

        $expectedSlug = Str::slug($beneficiary->name);
        if ($slug !== $expectedSlug) {
            return redirect()->route('website.beneficiary.donate', ['beneficiary' => $beneficiary->id, 'slug' => $expectedSlug]);
        }

        return view('website.donativo', [
            'beneficiarySelected' => $beneficiary->load(['category', 'media']),
            'shareUrl' => route('website.beneficiary.donate', ['beneficiary' => $beneficiary->id, 'slug' => $expectedSlug]),
            'categories' => collect(),
            'categoriesForJs' => collect(),
        ]);
    }

    public function quemSomos()
    {
        return view('website.quem-somos');
    }

    public function contactos()
    {
        return view('website.contactos');
    }
}
