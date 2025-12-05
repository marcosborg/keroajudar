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

        return view('website.donativo', compact('categories'));
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
