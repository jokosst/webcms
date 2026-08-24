<?php

namespace App\Services;

use App\Models\CompanyProfile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GmailApiService
{
    protected string $clientId;
    protected string $clientSecret;
    protected string $refreshToken;
    protected string $fromEmail;
    protected string $fromName;

    public function __construct()
    {
        $this->clientId = (string) (config('services.gmail.client_id') ?: env('GMAIL_CLIENT_ID', ''));
        $this->clientSecret = (string) (config('services.gmail.client_secret') ?: env('GMAIL_CLIENT_SECRET', ''));
        $this->refreshToken = (string) (config('services.gmail.refresh_token') ?: env('GMAIL_REFRESH_TOKEN', ''));
        $this->fromEmail = (string) (config('services.gmail.user_email') ?: (config('mail.from.address') ?: env('GMAIL_USER_EMAIL', 'tik@untan.ac.id')));
        
        $profile = CompanyProfile::first();
        $this->fromName = $profile->company_name ?? config('mail.from.name', 'UPA. TIK UNTAN');
    }

    /**
     * Get fresh OAuth2 Access Token using Refresh Token over HTTPS Port 443
     */
    public function getAccessToken(): string
    {
        if (empty($this->clientId) || empty($this->clientSecret) || empty($this->refreshToken)) {
            throw new \Exception('Kredensial Gmail API (Client ID, Client Secret, Refresh Token) belum dikonfigurasi di file .env.');
        }

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $this->refreshToken,
            'grant_type' => 'refresh_token',
        ]);

        if ($response->failed()) {
            Log::error('Gmail API Token Error: ' . $response->body());
            throw new \Exception('Gagal mendapatkan Access Token Gmail API: ' . ($response->json('error_description') ?? $response->body()));
        }

        return $response->json('access_token');
    }

    /**
     * Send email via Gmail API REST endpoint over HTTPS Port 443
     */
    public function sendEmail(string $toEmail, string $subject, string $htmlContent): bool
    {
        $accessToken = $this->getAccessToken();

        $mime = "From: {$this->fromName} <{$this->fromEmail}>\r\n";
        $mime .= "To: {$toEmail}\r\n";
        $mime .= "Subject: =?utf-8?B?" . base64_encode($subject) . "?=\r\n";
        $mime .= "MIME-Version: 1.0\r\n";
        $mime .= "Content-Type: text/html; charset=utf-8\r\n\r\n";
        $mime .= $htmlContent;

        // Base64URL safe encoding (RFC 4648)
        $rawMessage = rtrim(strtr(base64_encode($mime), '+/', '-_'), '=');

        $response = Http::withToken($accessToken)
            ->post('https://gmail.googleapis.com/gmail/v1/users/me/messages/send', [
                'raw' => $rawMessage,
            ]);

        if ($response->failed()) {
            Log::error('Gmail API Send Error: ' . $response->body());
            throw new \Exception('Gagal mengirim email via Gmail API (HTTPS Port 443): ' . ($response->json('error.message') ?? $response->body()));
        }

        return true;
    }
}
