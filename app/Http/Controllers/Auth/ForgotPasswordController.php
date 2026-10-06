<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLinkRequestForm(): View
    {
        return view('auth.passwords.email');
    }

    public function verifyEmail(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'No account was found for this email address.',
        ]);

        $user = User::query()->where('email', $validated['email'])->firstOrFail();

        if ($user->status !== 'active') {
            return back()->withErrors(['email' => 'This account is inactive.']);
        }

        $request->session()->regenerate();
        $request->session()->put('database_reset_email', $user->email);
        $request->session()->regenerateToken();

        return redirect()->route('password.reset.form');
    }
}
