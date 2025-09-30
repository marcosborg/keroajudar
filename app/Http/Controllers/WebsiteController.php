<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function index()
    {
        return view('website.home');
    }

    public function donativo()
    {
        return view('website.donativo');
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
