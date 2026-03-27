<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\BeneficiaryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(), [
            'beneficiary_category_id' => ['required', 'exists:beneficiary_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:beneficiaries,email'],
            'vat_number' => ['required', 'string', 'max:64'],
            'commercial_certificate_code' => ['required', 'string', 'max:20'],
            'iban' => ['required', 'string', 'max:64'],
            'address' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:16'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $validator->after(function ($validator) use ($request) {
            $cert = trim((string) $request->input('commercial_certificate_code'));
            if ($cert !== '' && !preg_match('/^\\d{4}-\\d{4}-\\d{4}$/', $cert)) {
                $validator->errors()->add('commercial_certificate_code', 'O código deve estar no formato 0000-0000-0000.');
            }

            $postal = trim((string) $request->input('postal_code'));
            if ($postal !== '' && !preg_match('/^\\d{4}-\\d{3}$/', $postal)) {
                $validator->errors()->add('postal_code', 'O código postal deve estar no formato 0000-000.');
            }

            $iban = strtoupper(preg_replace('/\\s+/', '', (string) $request->input('iban')));
            if ($iban === '' || !preg_match('/^[A-Z]{2}\\d{2}[A-Z0-9]{11,30}$/', $iban)) {
                $validator->errors()->add('iban', 'O IBAN parece inválido.');
            }
        });

        $data = $validator->validate();

        $beneficiary = Beneficiary::create([
            'beneficiary_category_id' => $data['beneficiary_category_id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'contact_email' => $data['email'],
            'vat_number' => trim((string) $data['vat_number']),
            'commercial_certificate_code' => trim((string) $data['commercial_certificate_code']),
            'iban' => strtoupper(preg_replace('/\\s+/', '', (string) $data['iban'])),
            'address' => isset($data['address']) ? trim((string) $data['address']) : null,
            'postal_code' => trim((string) $data['postal_code']),
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

        $validator = Validator::make($request->all(), [
            'beneficiary_category_id' => ['required', 'exists:beneficiary_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'vat_number' => ['required', 'string', 'max:64'],
            'commercial_certificate_code' => ['required', 'string', 'max:20'],
            'iban' => ['required', 'string', 'max:64'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('beneficiaries', 'email')->ignore($beneficiary->id)],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:16'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string'],
            'password' => ['nullable', 'confirmed', 'min:8'],
            'photo' => ['nullable', 'image', 'max:5120'],
            'logo_square' => ['nullable', 'image', 'max:5120'],
        ]);

        $validator->after(function ($validator) use ($request) {
            $cert = trim((string) $request->input('commercial_certificate_code'));
            if ($cert !== '' && !preg_match('/^\\d{4}-\\d{4}-\\d{4}$/', $cert)) {
                $validator->errors()->add('commercial_certificate_code', 'O código deve estar no formato 0000-0000-0000.');
            }

            $postal = trim((string) $request->input('postal_code'));
            if ($postal !== '' && !preg_match('/^\\d{4}-\\d{3}$/', $postal)) {
                $validator->errors()->add('postal_code', 'O código postal deve estar no formato 0000-000.');
            }

            $iban = strtoupper(preg_replace('/\\s+/', '', (string) $request->input('iban')));
            if ($iban === '' || !preg_match('/^[A-Z]{2}\\d{2}[A-Z0-9]{11,30}$/', $iban)) {
                $validator->errors()->add('iban', 'O IBAN parece inválido.');
            }
        });

        $data = $validator->validate();
        $data['vat_number'] = trim((string) $data['vat_number']);
        $data['commercial_certificate_code'] = trim((string) $data['commercial_certificate_code']);
        $data['iban'] = strtoupper(preg_replace('/\\s+/', '', (string) $data['iban']));
        $data['postal_code'] = trim((string) $data['postal_code']);

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
