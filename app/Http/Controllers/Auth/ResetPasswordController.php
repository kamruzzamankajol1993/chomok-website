<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showResetForm(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('database_reset_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.passwords.reset', [
            'email' => $request->session()->get('database_reset_email'),
        ]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $email = $request->session()->get('database_reset_email');

        if (! $email) {
            return redirect()->route('password.request');
        }

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::query()->where('email', $email)->update([
            'password' => Hash::make($validated['password']),
        ]);

        $request->session()->forget('database_reset_email');
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Password changed successfully. Please sign in.');
    }
}
