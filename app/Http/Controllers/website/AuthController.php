<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

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

        $email = strtolower(trim((string) $data['email']));
        $otp = $this->generateRegistrationOtp();

        $request->session()->put('client_registration_pending', [
            'name' => $data['name'],
            'email' => $email,
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'created_at' => now()->timestamp,
        ]);

        try {
            $this->sendRegistrationOtp($email, (string) $data['name'], $otp);
        } catch (Throwable $exception) {
            report($exception);
            $request->session()->forget(['client_registration_pending', 'client_registration_otp']);

            return back()
                ->withErrors(['email' => 'Verification email could not be sent. Please check the email address and try again.'])
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        $this->storeRegistrationOtp($request, $otp);

        return redirect()->route('client.register.verify')
            ->with('success', 'A 6-digit verification code has been sent to your email.');
    }

    public function showRegistrationVerificationForm(Request $request): View|RedirectResponse
    {
        if (! $this->hasPendingRegistration($request)) {
            return redirect()->route('client.register')
                ->withErrors(['email' => 'Your registration session expired. Please register again.']);
        }

        $pending = (array) $request->session()->get('client_registration_pending', []);
        $otpState = (array) $request->session()->get('client_registration_otp', []);
        $email = (string) ($pending['email'] ?? '');
        $resendAvailableAt = ((int) ($otpState['sent_at'] ?? 0)) + 60;

        return view('website.auth.registration-verify', compact('email', 'resendAvailableAt'));
    }

    public function verifyRegistrationOtp(Request $request): RedirectResponse
    {
        if (! $this->hasPendingRegistration($request)) {
            return redirect()->route('client.register')
                ->withErrors(['email' => 'Your registration session expired. Please register again.']);
        }

        $data = $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $otpState = (array) $request->session()->get('client_registration_otp', []);
        $expiresAt = (int) ($otpState['expires_at'] ?? 0);
        $attempts = (int) ($otpState['attempts'] ?? 0);

        if ($expiresAt <= now()->timestamp) {
            return back()->withErrors(['otp' => 'This verification code has expired. Please request a new code.']);
        }

        if ($attempts >= 5) {
            return back()->withErrors(['otp' => 'Too many incorrect attempts. Please request a new verification code.']);
        }

        $otpHash = (string) ($otpState['hash'] ?? '');
        if ($otpHash === '' || ! Hash::check((string) $data['otp'], $otpHash)) {
            $otpState['attempts'] = $attempts + 1;
            $request->session()->put('client_registration_otp', $otpState);

            return back()->withErrors(['otp' => 'The verification code is incorrect.']);
        }

        $pending = (array) $request->session()->get('client_registration_pending', []);
        $email = (string) ($pending['email'] ?? '');

        if (Client::query()->where('email', $email)->whereNull('deleted_at')->exists()) {
            $request->session()->forget(['client_registration_pending', 'client_registration_otp']);

            return redirect()->route('client.register')
                ->withErrors(['email' => 'An account already exists with this email address.']);
        }

        $branch = Branch::query()->where('status', 'active')->where('accepting_orders', true)->orderBy('id')->first()
            ?? Branch::query()->where('status', 'active')->orderBy('id')->firstOrFail();

        do {
            $code = 'WEB-'.strtoupper(Str::random(10));
        } while (Client::query()->withTrashed()->where('code', $code)->exists());

        $client = Client::query()->create([
            'branch_id' => $branch->id,
            'created_by' => null,
            'code' => $code,
            'name' => (string) ($pending['name'] ?? ''),
            'email' => $email,
            'phone' => (string) ($pending['phone'] ?? ''),
            'can_login' => true,
            'password' => (string) ($pending['password'] ?? ''),
            'status' => 'active',
        ]);

        $request->session()->forget(['client_registration_pending', 'client_registration_otp']);
        Auth::guard('client')->login($client);
        $request->session()->regenerate();

        return redirect()->intended(route('client.dashboard'))->with('success', 'Email verified. Your account has been created successfully.');
    }

    public function resendRegistrationOtp(Request $request): RedirectResponse
    {
        if (! $this->hasPendingRegistration($request)) {
            return redirect()->route('client.register')
                ->withErrors(['email' => 'Your registration session expired. Please register again.']);
        }

        $pending = (array) $request->session()->get('client_registration_pending', []);
        $email = (string) ($pending['email'] ?? '');
        $name = (string) ($pending['name'] ?? 'Customer');
        $otpState = (array) $request->session()->get('client_registration_otp', []);
        $sentAt = (int) ($otpState['sent_at'] ?? 0);
        $remaining = max(0, 60 - (now()->timestamp - $sentAt));

        if ($remaining > 0) {
            return back()->withErrors(['otp' => 'Please wait '.$remaining.' seconds before requesting another code.']);
        }

        if (Client::query()->where('email', $email)->whereNull('deleted_at')->exists()) {
            $request->session()->forget(['client_registration_pending', 'client_registration_otp']);

            return redirect()->route('client.register')
                ->withErrors(['email' => 'An account already exists with this email address.']);
        }

        $otp = $this->generateRegistrationOtp();

        try {
            $this->sendRegistrationOtp($email, $name, $otp);
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors(['otp' => 'A new verification code could not be sent. Please try again.']);
        }

        $this->storeRegistrationOtp($request, $otp);

        return back()->with('success', 'A new 6-digit verification code has been sent to your email.');
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
        $orders = $client->orders()->with(['branch', 'items.addons.addon', 'items.addons.menuItemPriceAddon'])->latest()->paginate(10);
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
        $order = $client->orders()->with(['branch', 'items.addons.addon', 'items.addons.menuItemPriceAddon'])->findOrFail($id);

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

    private function generateRegistrationOtp(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function storeRegistrationOtp(Request $request, string $otp): void
    {
        $request->session()->put('client_registration_otp', [
            'hash' => Hash::make($otp),
            'expires_at' => now()->addMinutes(10)->timestamp,
            'sent_at' => now()->timestamp,
            'attempts' => 0,
        ]);
    }

    private function sendRegistrationOtp(string $email, string $name, string $otp): void
    {
        Mail::send('website.emails.registration-otp', [
            'name' => $name,
            'otp' => $otp,
            'expiresInMinutes' => 10,
        ], function ($message) use ($email, $name): void {
            $message->to($email, $name)
                ->subject('Your Chomok registration verification code');
        });
    }

    private function hasPendingRegistration(Request $request): bool
    {
        $pending = (array) $request->session()->get('client_registration_pending', []);
        $createdAt = (int) ($pending['created_at'] ?? 0);

        return filled($pending['email'] ?? null)
            && filled($pending['password'] ?? null)
            && $createdAt > 0
            && now()->timestamp - $createdAt <= 1800;
    }

    private function hasValidResetSession(Request $request): bool
    {
        $email = $request->session()->get('client_password_reset_email');
        $verifiedAt = (int) $request->session()->get('client_password_reset_verified_at', 0);

        return filled($email) && $verifiedAt > 0 && now()->timestamp - $verifiedAt <= 900;
    }
}
