<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;

class BeneficiaryPasswordResetController extends Controller
{
    protected function broker()
    {
        return Password::broker('beneficiaries');
    }

    protected function guard()
    {
        return Auth::guard('beneficiary');
    }

    public function showLinkRequestForm()
    {
        return view('website.beneficiaries.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $status = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('website.beneficiaries.passwords.reset', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $status = $this->broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($beneficiary, $password) {
                $beneficiary->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(str()->random(60));

                $beneficiary->save();
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('beneficiaries.login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
