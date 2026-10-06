<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();
        $user = $request->user();

        if ($user->status !== 'active') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'This account is inactive. Please contact an administrator.',
            ]);
        }

        if ($user->roles()->doesntExist()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'No role has been assigned to this account.',
            ]);
        }

        return redirect()->intended($this->destinationFor($user));
    }

    private function destinationFor($user): string
    {
        $destinations = [
            'dashboard.view' => 'admin.dashboard',
            'branch.view' => 'admin.branches.index',
            'user.view' => 'admin.users.index',
            'role.view' => 'admin.roles.index',
            'permission.view' => 'admin.permissions.index',
            'setting.view' => 'admin.settings.edit',
        ];

        foreach ($destinations as $permission => $routeName) {
            if ($user->can($permission)) {
                return route($routeName);
            }
        }

        return route('admin.profile.edit');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
