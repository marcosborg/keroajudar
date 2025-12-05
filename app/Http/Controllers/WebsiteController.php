<?php

namespace App\Http\Controllers;

use App\Models\BeneficiaryCategory;
use Illuminate\Http\Request;

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

        $categoriesForJs = $categories->map(function ($c) {
            return [
                'id'            => $c->id,
                'name'          => $c->name,
                'description'   => $c->description,
                'image'         => $c->image?->preview ?? $c->image?->url,
                'beneficiaries' => $c->beneficiaries->map(function ($b) {
                    return [
                        'id'          => $b->id,
                        'name'        => $b->name,
                        'description' => $b->description,
                        'image'       => $b->photo?->preview ?? $b->photo?->url,
                    ];
                })->values(),
            ];
        })->values();

        return view('website.donativo', compact('categories', 'categoriesForJs'));
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
