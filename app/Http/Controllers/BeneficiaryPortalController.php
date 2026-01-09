<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\BeneficiaryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class BeneficiaryPortalController extends Controller
{
    public function index()
    {
        $categories = BeneficiaryCategory::with(['beneficiaries' => function ($query) {
            $query->where('active', true);
        }])->orderBy('name')->get();

        return view('website.beneficiaries.index', compact('categories'));
    }

    public function showRegister()
    {
        $categories = BeneficiaryCategory::orderBy('name')->get();

        return view('website.beneficiaries.register', compact('categories'));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'beneficiary_category_id' => ['required', 'exists:beneficiary_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:beneficiaries,email'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $beneficiary = Beneficiary::create([
            'beneficiary_category_id' => $data['beneficiary_category_id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'contact_email' => $data['email'],
            'contact_phone' => $data['contact_phone'] ?? null,
            'website' => $data['website'] ?? null,
            'city' => $data['city'] ?? null,
            'country' => $data['country'] ?? null,
            'password' => $data['password'],
            'active' => false,
            'approved_at' => null,
        ]);

        return redirect()
            ->route('beneficiaries.login')
            ->with('status', 'Conta criada. Aguarde aprovação para poder aceder.');
    }

    public function showLogin()
    {
        return view('website.beneficiaries.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $beneficiary = Beneficiary::where('email', $credentials['email'])->first();

        if (!$beneficiary || !Hash::check($credentials['password'], $beneficiary->password ?? '')) {
            return back()->withErrors(['email' => 'Credenciais inválidas.']);
        }

        if (!$beneficiary->active || !$beneficiary->approved_at) {
            return back()->withErrors(['email' => 'A conta ainda não foi aprovada.']);
        }

        Auth::guard('beneficiary')->login($beneficiary, $request->boolean('remember'));
        $beneficiary->forceFill(['last_login_at' => now()])->save();

        return redirect()->route('beneficiaries.area')->with('status', 'Bem-vindo de volta!');
    }

    public function logout(Request $request)
    {
        Auth::guard('beneficiary')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('beneficiaries.login');
    }

    public function area()
    {
        $beneficiary = Auth::guard('beneficiary')->user();
        $shareUrl = route('website.donativo', ['beneficiary_id' => $beneficiary->id]);

        return view('website.beneficiaries.area', [
            'beneficiary' => $beneficiary,
            'shareUrl' => $shareUrl,
            'categories' => BeneficiaryCategory::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request)
    {
        /** @var Beneficiary $beneficiary */
        $beneficiary = Auth::guard('beneficiary')->user();

        $data = $request->validate([
            'beneficiary_category_id' => ['required', 'exists:beneficiary_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'vat_number' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('beneficiaries', 'email')->ignore($beneficiary->id)],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string'],
            'password' => ['nullable', 'confirmed', 'min:8'],
            'photo' => ['nullable', 'image', 'max:5120'],
            'logo_square' => ['nullable', 'image', 'max:5120'],
        ]);

        $beneficiary->fill($data);
        if (!empty($data['password'])) {
            $beneficiary->password = $data['password'];
        }
        $beneficiary->contact_email = $data['contact_email'] ?? $data['email'];
        $beneficiary->save();

        if ($request->hasFile('photo')) {
            if ($beneficiary->photo) {
                $beneficiary->photo->delete();
            }
            $beneficiary->addMediaFromRequest('photo')->toMediaCollection('photo');
        }

        if ($request->hasFile('logo_square')) {
            if ($beneficiary->logo_square) {
                $beneficiary->logo_square->delete();
            }
            $beneficiary->addMediaFromRequest('logo_square')->toMediaCollection('logo');
        }

        return back()->with('status', 'Dados atualizados.');
    }
}
