<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $inputs = $request->except(['_token', '_method']);

        // Handle checkboxes (if not present in request, set to '0')
        if (!isset($inputs['chatbot_enabled'])) {
            $inputs['chatbot_enabled'] = '0';
        }
        if (!isset($inputs['chatbot_ai_enabled'])) {
            $inputs['chatbot_ai_enabled'] = '0';
        }

        foreach ($inputs as $key => $value) {
            SiteSetting::setKey($key, $value);
        }

        return back()->with('success', 'Pengaturan situs dan AI chatbot berhasil diperbarui.');
    }

    /**
     * Test connection to Google Gemini API.
     */
    public function testGemini(Request $request): JsonResponse
    {
        $apiKey = trim($request->input('api_key', ''));
        $model = trim($request->input('model', 'gemini-3.6-flash'));

        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'API Key belum diisi. Silakan masukkan Gemini API Key Anda.',
            ], 422);
        }

        try {
            $response = Http::timeout(8)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                    'contents' => [
                        ['parts' => [['text' => 'Halo! Jawab hanya dalam 1 kata: "Terkoneksi"']]]
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => 15
                    ]
                ]);

            if ($response->successful()) {
                $reply = $response->json('candidates.0.content.parts.0.text') ?? 'Sukses';
                return response()->json([
                    'success' => true,
                    'message' => "Koneksi ke Google Gemini AI ({$model}) BERHASIL! Respon API: \"{$reply}\"",
                ]);
            }

            $errMsg = $response->json('error.message') ?? $response->body();
            return response()->json([
                'success' => false,
                'message' => "Gagal terhubung ke Gemini (Status {$response->status()}): {$errMsg}",
            ], 400);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => "Kesalahan jaringan / timeout: {$e->getMessage()}",
            ], 500);
        }
    }
}

