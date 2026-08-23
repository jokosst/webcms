<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

class ProfileController extends Controller
{
    public function edit(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $qrCodeSvg = null;
        $secretKey = null;

        // If user is currently setting up 2FA (secret generated but not confirmed yet)
        if ($user->two_factor_secret && !$user->hasTwoFactorEnabled()) {
            $google2fa = new Google2FA();
            $secretKey = $user->two_factor_secret;
            
            $qrCodeUrl = $google2fa->getQRCodeUrl(
                config('app.name', 'Admin CMS'),
                $user->email,
                $secretKey
            );

            $renderer = new ImageRenderer(
                new RendererStyle(200),
                new SvgImageBackEnd()
            );
            $writer = new Writer($renderer);
            $qrCodeSvg = $writer->writeString($qrCodeUrl);
        }

        return view('admin.profile.edit', compact('user', 'qrCodeSvg', 'secretKey'));
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if (!empty($request->input('password'))) {
            if ($request->filled('current_password')) {
                if (!Hash::check($request->input('current_password'), $user->password)) {
                    return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])->withInput();
                }
            }

            $user->password = Hash::make($request->input('password'));
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        return redirect()->route('admin.profile.edit')->with('success', 'Profil akun Anda berhasil diperbarui.');
    }

    public function enableTwoFactor(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('admin.profile.edit')->with('info', '2FA sudah aktif pada akun Anda.');
        }

        $google2fa = new Google2FA();
        $user->two_factor_secret = $google2fa->generateSecretKey();
        
        $recoveryCodes = collect(range(1, 8))->map(function () {
            return Str::random(10) . '-' . Str::random(10);
        })->toArray();

        $user->two_factor_recovery_codes = $recoveryCodes;
        $user->two_factor_confirmed_at = null;
        $user->save();

        return redirect()->route('admin.profile.edit')->with('info', 'Silakan scan QR Code menggunakan aplikasi Google Authenticator / Authy, lalu masukkan 6-digit kode OTP untuk konfirmasi.');
    }

    public function confirmTwoFactor(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        if (!$user->two_factor_secret) {
            return redirect()->route('admin.profile.edit')->withErrors(['code' => 'Silakan klik Aktifkan 2FA terlebih dahulu.']);
        }

        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($user->two_factor_secret, $request->code);

        if (!$valid) {
            return redirect()->route('admin.profile.edit')->withErrors(['code' => 'Kode OTP 6-digit tidak valid atau telah kadaluwarsa. Pastikan penunjuk jam di HP Anda sudah akurat.']);
        }

        $user->two_factor_confirmed_at = now();
        $user->save();

        return redirect()->route('admin.profile.edit')->with('success', 'Selamat! 2FA (Autentikasi Dua Faktor) telah resmi diaktifkan untuk akun Anda.');
    }

    public function disableTwoFactor(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('admin.profile.edit')->withErrors(['current_password_2fa' => 'Password saat ini tidak sesuai.']);
        }

        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->two_factor_confirmed_at = null;
        $user->save();

        return redirect()->route('admin.profile.edit')->with('success', 'Autentikasi Dua Faktor (2FA) berhasil dinonaktifkan.');
    }

    public function regenerateRecoveryCodes(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasTwoFactorEnabled()) {
            return redirect()->route('admin.profile.edit')->withErrors(['2fa' => '2FA belum aktif.']);
        }

        $recoveryCodes = collect(range(1, 8))->map(function () {
            return Str::random(10) . '-' . Str::random(10);
        })->toArray();

        $user->two_factor_recovery_codes = $recoveryCodes;
        $user->save();

        return redirect()->route('admin.profile.edit')->with('show_recovery_codes', true)->with('success', 'Kode Pemulihan Darurat baru berhasil dibuat.');
    }
}
