<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

class AuthController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::validate([...$credentials, 'is_active' => true])) {
            /** @var User $user */
            $user = User::where('email', $credentials['email'])->first();

            if ($user && $user->hasTwoFactorEnabled()) {
                $request->session()->put('login.id', $user->id);
                $request->session()->put('login.remember', $request->boolean('remember'));

                return redirect()->route('admin.login.2fa');
            }

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Selamat datang kembali, ' . $user->name);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah, atau akun Anda non-aktif.',
        ])->onlyInput('email');
    }

    public function show2faForm(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        if (!$request->session()->has('login.id')) {
            return redirect()->route('admin.login');
        }

        return view('admin.auth.two-factor-challenge');
    }

    public function verify2fa(Request $request): RedirectResponse
    {
        if (!$request->session()->has('login.id')) {
            return redirect()->route('admin.login');
        }

        /** @var User|null $user */
        $user = User::find($request->session()->get('login.id'));

        if (!$user || !$user->hasTwoFactorEnabled()) {
            $request->session()->forget(['login.id', 'login.remember']);
            return redirect()->route('admin.login');
        }

        // Check if Recovery Code was submitted
        if ($request->filled('recovery_code')) {
            $recoveryCode = trim($request->input('recovery_code'));
            $codes = $user->two_factor_recovery_codes ?? [];

            if (in_array($recoveryCode, $codes)) {
                // Remove used recovery code
                $newCodes = array_values(array_diff($codes, [$recoveryCode]));
                $user->two_factor_recovery_codes = $newCodes;
                $user->save();

                $remember = $request->session()->get('login.remember', false);
                $request->session()->forget(['login.id', 'login.remember']);
                Auth::login($user, $remember);
                $request->session()->regenerate();

                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Login berhasil menggunakan Kode Pemulihan Darurat.');
            }

            return back()->withErrors([
                'recovery_code' => 'Kode Pemulihan Darurat tidak valid.',
            ]);
        }

        // Check 6-digit OTP code
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($user->two_factor_secret, $request->input('code'));

        if ($valid) {
            $remember = $request->session()->get('login.remember', false);
            $request->session()->forget(['login.id', 'login.remember']);
            Auth::login($user, $remember);
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Selamat datang kembali, ' . $user->name);
        }

        return back()->withErrors([
            'code' => 'Kode 2FA 6-digit salah atau telah kadaluwarsa. Pastikan jam di HP Anda sudah akurat.',
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('info', 'Anda telah keluar dari sistem.');
    }
}
