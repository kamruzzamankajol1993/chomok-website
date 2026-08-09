<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('website.auth.login');
    }

    public function showRegistrationForm(): View
    {
        return view('website.auth.registration');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('clients', 'email')->whereNull('deleted_at')],
            'phone' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'terms' => ['accepted'],
        ]);

        $branch = Branch::query()->where('status', 'active')->where('accepting_orders', true)->orderBy('id')->first()
            ?? Branch::query()->where('status', 'active')->orderBy('id')->firstOrFail();

        do {
            $code = 'WEB-'.strtoupper(Str::random(10));
        } while (Client::query()->withTrashed()->where('code', $code)->exists());

        $client = Client::query()->create([
            'branch_id' => $branch->id,
            'created_by' => null,
            'code' => $code,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'can_login' => true,
            'password' => $data['password'],
            'status' => 'active',
        ]);

        Auth::guard('client')->login($client);
        $request->session()->regenerate();

        return redirect()->intended(route('client.dashboard'))->with('success', 'Account created successfully.');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $ok = Auth::guard('client')->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'status' => 'active',
            'can_login' => 1,
        ], $request->boolean('remember'));

        if (! $ok) {
            return back()->withErrors(['email' => 'Email or password is incorrect, or this account cannot log in.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('client.dashboard'))->with('success', 'Welcome back.');
    }

    public function showLinkRequestForm(): View
    {
        return view('website.auth.forget-password');
    }

    public function verifyEmail(Request $request): RedirectResponse
    {
        $data = $request->validate(['email' => ['required', 'email']]);
        $client = Client::query()
            ->where('email', $data['email'])
            ->where('status', 'active')
            ->where('can_login', true)
            ->first();

        if (! $client) {
            return back()->withErrors(['email' => 'No active customer account was found with this email.'])->onlyInput('email');
        }

        $request->session()->put('client_password_reset_email', $client->email);
        $request->session()->put('client_password_reset_verified_at', now()->timestamp);

        return redirect()->route('client.password.reset.form');
    }

    public function showResetForm(Request $request): View|RedirectResponse
    {
        if (! $this->hasValidResetSession($request)) {
            return redirect()->route('client.password.request')->withErrors(['email' => 'Please verify your email again.']);
        }

        return view('website.auth.change-password');
    }

    public function reset(Request $request): RedirectResponse
    {
        if (! $this->hasValidResetSession($request)) {
            return redirect()->route('client.password.request')->withErrors(['email' => 'Password reset session expired. Please verify your email again.']);
        }

        $data = $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $email = (string) $request->session()->get('client_password_reset_email');
        $client = Client::query()->where('email', $email)->where('status', 'active')->where('can_login', true)->firstOrFail();
        $client->password = $data['password'];
        $client->save();

        $request->session()->forget(['client_password_reset_email', 'client_password_reset_verified_at']);

        return redirect()->route('client.login')->with('success', 'Password changed. You can now log in.');
    }

    public function dashboard(Request $request): View
    {
        $client = Auth::guard('client')->user();
        $orders = $client->orders()->with(['branch', 'items.addons'])->latest()->paginate(10);
        $summary = [
            'total' => $client->orders()->count(),
            'pending' => $client->orders()->whereIn('status', ['pending', 'confirmed', 'processing'])->count(),
            'delivered' => $client->orders()->where('status', 'delivered')->count(),
            'spent' => (float) $client->orders()->where('status', 'delivered')->sum('grand_total'),
        ];

        return view('website.auth.dashboard', compact('client', 'orders', 'summary'));
    }

    public function viewOrder(Request $request, int $id): View|JsonResponse
    {
        $client = Auth::guard('client')->user();
        $order = $client->orders()->with(['branch', 'items.addons'])->findOrFail($id);

        if ($request->ajax()) {
            return response()->json([
                'title' => 'Order '.$order->order_number,
                'html' => view('website.auth.partials.order-modal-content', compact('order'))->render(),
            ]);
        }

        return view('website.auth.order-view', compact('client', 'order'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var Client $client */
        $client = Auth::guard('client')->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('clients', 'email')->ignore($client->id)->whereNull('deleted_at')],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:2000'],
        ]);
        $client->update($data);

        return back()->with('success', 'Account information updated.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('client')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home.index');
    }

    public function sessionStatus(): JsonResponse
    {
        return response()->json(['authenticated' => Auth::guard('client')->check()], Auth::guard('client')->check() ? 200 : 401);
    }

    private function hasValidResetSession(Request $request): bool
    {
        $email = $request->session()->get('client_password_reset_email');
        $verifiedAt = (int) $request->session()->get('client_password_reset_verified_at', 0);

        return filled($email) && $verifiedAt > 0 && now()->timestamp - $verifiedAt <= 900;
    }
}
