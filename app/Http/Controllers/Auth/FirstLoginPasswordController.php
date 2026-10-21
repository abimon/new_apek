<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class FirstLoginPasswordController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('auth/first-login-password');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::min(12)->letters()->numbers()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
            'force_password_change' => false,
        ]);
        $request->session()->regenerate();

        return to_route($request->user()->portfolio_enabled ? 'portfolio.profile.edit' : 'home')
            ->with('success', 'Your password has been updated.');
    }
}
