<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\CompanyProfile;
use App\Models\Faq;
use App\Models\HeroBanner;
use App\Models\InboxLead;
use App\Models\Laboratory;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $profile = CompanyProfile::first() ?? new CompanyProfile([
            'company_name' => 'Nusantara Digital Solusindo',
            'tagline' => 'Transformasi Digital & Inovasi Teknologi Terdepan',
        ]);

        $heroBanners = HeroBanner::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $services = Service::with('category')->where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $portfolios = Portfolio::with('category')->where('is_active', true)->orderBy('sort_order', 'asc')->latest('completion_date')->take(6)->get();
        $articles = Article::with('category')->where('status', 'published')->latest('published_at')->take(3)->get();
        $team = TeamMember::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $testimonials = Testimonial::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('home', compact(
            'profile',
            'heroBanners',
            'services',
            'portfolios',
            'articles',
            'team',
            'testimonials',
            'settings'
        ));
    }

    public function desa1(): View
    {
        $profile = CompanyProfile::first() ?? new CompanyProfile([
            'company_name' => 'Desa Sukamaju',
            'tagline' => 'Maju, Sejahtera, Transparan & Berdaya Saing',
        ]);

        $heroBanners = HeroBanner::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $services = Service::with('category')->where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $portfolios = Portfolio::with('category')->where('is_active', true)->orderBy('sort_order', 'asc')->latest('completion_date')->take(6)->get();
        $articles = Article::with('category')->where('status', 'published')->latest('published_at')->take(3)->get();
        $team = TeamMember::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $testimonials = Testimonial::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $faqs = Faq::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('desa1', compact(
            'profile',
            'heroBanners',
            'services',
            'portfolios',
            'articles',
            'team',
            'testimonials',
            'faqs',
            'settings'
        ));
    }

    public function desa2(): View
    {
        $profile = CompanyProfile::first() ?? new CompanyProfile([
            'company_name' => 'Desa Sukamaju',
            'tagline' => 'Maju, Sejahtera, Transparan & Berdaya Saing',
        ]);

        $heroBanners = HeroBanner::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $services = Service::with('category')->where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $portfolios = Portfolio::with('category')->where('is_active', true)->orderBy('sort_order', 'asc')->latest('completion_date')->take(6)->get();
        $articles = Article::with('category')->where('status', 'published')->latest('published_at')->take(3)->get();
        $team = TeamMember::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $testimonials = Testimonial::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $faqs = Faq::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('desa2', compact(
            'profile',
            'heroBanners',
            'services',
            'portfolios',
            'articles',
            'team',
            'testimonials',
            'faqs',
            'settings'
        ));
    }

    public function company(): View
    {
        $profile = CompanyProfile::first() ?? new CompanyProfile([
            'company_name' => 'Unit TIK & Infrastruktur Digital',
            'tagline' => 'Pusat Pengelolaan Sistem Informasi, Jaringan & Cloud Infrastructure',
        ]);

        // Generate dynamic math captcha & signed token for anti-bot protection
        $contactNum1 = rand(3, 9);
        $contactNum2 = rand(2, 8);
        $captchaQuestion = "{$contactNum1} + {$contactNum2}";
        $contactCaptchaToken = hash_hmac('sha256', "contact:{$contactNum1}+{$contactNum2}", config('app.key'));
        session(['captcha_answer' => $contactNum1 + $contactNum2]);

        $heroBanners = HeroBanner::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $services = Service::with('category')->where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $portfolios = Portfolio::with('category')->where('is_active', true)->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();
        $articles = Article::with('category')->where('status', 'published')->latest('published_at')->take(6)->get();
        $team = TeamMember::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $testimonials = Testimonial::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $faqs = Faq::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $settings = SiteSetting::all()->pluck('value', 'key');
        $laboratories = Laboratory::where('is_active', true)->orderBy('name')->get();

        return view('company', compact(
            'profile',
            'heroBanners',
            'services',
            'portfolios',
            'articles',
            'team',
            'testimonials',
            'faqs',
            'settings',
            'captchaQuestion',
            'contactNum1',
            'contactNum2',
            'contactCaptchaToken',
            'laboratories'
        ));
    }

    /**
     * Refresh dynamic math captcha numbers and signed HMAC token asynchronously
     */
    public function refreshContactCaptcha(): JsonResponse
    {
        $num1 = rand(3, 9);
        $num2 = rand(2, 8);
        $token = hash_hmac('sha256', "contact:{$num1}+{$num2}", config('app.key'));
        session(['captcha_answer' => $num1 + $num2]);

        return response()->json([
            'num1' => $num1,
            'num2' => $num2,
            'token' => $token,
        ]);
    }

    public function submitContact(Request $request): RedirectResponse
    {
        $redirectTarget = url('/#helpdesk');
        $failedRateKey = 'contact-failed:' . $request->ip();
        $submitRateKey = 'contact-submit:' . $request->ip();

        // Lapis 3: Rate Limiting / Brute-Force & Spam Shield (maksimal 5x per menit)
        if (RateLimiter::tooManyAttempts($failedRateKey, 5)) {
            $seconds = RateLimiter::availableIn($failedRateKey);

            return redirect()->to($redirectTarget)
                ->withInput($request->except('captcha_input'))
                ->with('captcha_error', "Terlalu banyak percobaan gagal (maksimal 5 kali per menit). Silakan tunggu {$seconds} detik sebelum mencoba lagi.");
        }

        if (RateLimiter::tooManyAttempts($submitRateKey, 5)) {
            $seconds = RateLimiter::availableIn($submitRateKey);

            return redirect()->to($redirectTarget)
                ->withInput($request->except('captcha_input'))
                ->with('captcha_error', "Batas pengiriman tiket tercapai (maksimal 5 kali per menit). Silakan tunggu {$seconds} detik sebelum mencoba lagi.");
        }

        // Lapis 1: Invisible Honeypot Trap (Perangkap Bot Otomatis)
        if ($request->filled('_hp_website_title')) {
            RateLimiter::hit($failedRateKey, 60);

            return redirect()->to($redirectTarget)
                ->withInput($request->except('captcha_input'))
                ->with('captcha_error', 'Permintaan Anda terdeteksi sebagai aktivitas bot otomatis dan diblokir oleh sistem.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
            'captcha_input' => 'required|integer',
        ], [
            'name.required' => 'Nama lengkap / instansi wajib diisi.',
            'email.required' => 'Alamat email resmi wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'message.required' => 'Rincian pesan / kendala teknis wajib diisi.',
            'captcha_input.required' => 'Kode verifikasi keamanan (anti-bot) wajib diisi.',
            'captcha_input.integer' => 'Kode verifikasi harus berupa angka.',
        ]);

        if ($validator->fails()) {
            RateLimiter::hit($failedRateKey, 60);

            return redirect()->to($redirectTarget)
                ->withErrors($validator)
                ->withInput($request->except('captcha_input'));
        }

        // Lapis 2: Dynamic Math Verification (Tantangan Manusia Visual + HMAC Token)
        $captchaInput = (int) $request->input('captcha_input');
        $num1 = (int) $request->input('captcha_num1');
        $num2 = (int) $request->input('captcha_num2');
        $token = (string) $request->input('captcha_token');

        $expectedSum = $num1 + $num2;
        $isValidHmac = $token !== ''
            && $num1 > 0
            && $num2 > 0
            && hash_equals(hash_hmac('sha256', "contact:{$num1}+{$num2}", config('app.key')), $token);
        $isTokenAnswerValid = $isValidHmac && ($captchaInput === $expectedSum);
        $isSessionAnswerValid = session()->has('captcha_answer') && ($captchaInput === (int) session('captcha_answer'));

        if (!$isTokenAnswerValid && !$isSessionAnswerValid) {
            RateLimiter::hit($failedRateKey, 60);

            return redirect()->to($redirectTarget)
                ->withInput($request->except('captcha_input'))
                ->with('captcha_error', 'Jawaban verifikasi matematika (anti-bot) tidak cocok. Silakan hitung ulang atau klik Segarkan Soal.');
        }

        // Clear captcha answer after successful verification & record submit rate window
        session()->forget('captcha_answer');
        RateLimiter::hit($submitRateKey, 60);

        $validated = $validator->validated();
        unset($validated['captcha_input']);

        InboxLead::create([
            ...$validated,
            'status' => 'unread',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->to($redirectTarget)->with('success', 'Tiket bantuan IT Helpdesk berhasil dikirim! Tim operasional kami akan segera menghubungi Anda.');
    }

    public function allArticles(Request $request): View
    {
        $search = $request->query('q');
        $categoryId = $request->query('category');

        $query = Article::with(['category', 'user'])->where('status', 'published');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $articles = $query->latest('published_at')->paginate(9)->withQueryString();
        $categories = Category::where('type', 'article')->get();
        $profile = CompanyProfile::first();
        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('articles.index', compact('articles', 'categories', 'search', 'categoryId', 'profile', 'settings'));
    }

    public function showArticle(string $slug): View
    {
        $article = Article::with([
            'category',
            'user',
            'comments' => function ($q) {
                $q->whereNull('parent_id')->where('is_approved', true)->with(['replies' => function ($rq) {
                    $rq->where('is_approved', true)->orderBy('created_at', 'asc');
                }]);
            }
        ])->where('slug', $slug)->firstOrFail();

        $article->increment('views_count');

        $recentArticles = Article::where('id', '!=', $article->id)
            ->where('status', 'published')
            ->latest()
            ->take(5)
            ->get();

        $profile = CompanyProfile::first();
        $settings = SiteSetting::all()->pluck('value', 'key');

        // Generate Math Captcha Anti-Bot
        $commentCaptchaNum1 = rand(3, 9);
        $commentCaptchaNum2 = rand(2, 8);
        $commentCaptchaToken = hash_hmac('sha256', "comment:{$commentCaptchaNum1}+{$commentCaptchaNum2}", config('app.key'));
        session(['article_comment_captcha' => $commentCaptchaNum1 + $commentCaptchaNum2]);

        return view('articles.show', compact(
            'article',
            'recentArticles',
            'profile',
            'settings',
            'commentCaptchaNum1',
            'commentCaptchaNum2',
            'commentCaptchaToken'
        ));
    }

    /**
     * Refresh math captcha numbers and signed token for article comments
     */
    public function refreshCommentCaptcha(): JsonResponse
    {
        $num1 = rand(3, 9);
        $num2 = rand(2, 8);
        $token = hash_hmac('sha256', "comment:{$num1}+{$num2}", config('app.key'));
        session(['article_comment_captcha' => $num1 + $num2]);

        return response()->json([
            'num1' => $num1,
            'num2' => $num2,
            'token' => $token,
        ]);
    }

    public function submitArticleComment(Request $request, Article $article): RedirectResponse
    {
        $redirectUrl = url()->previous() . '#comments';

        if (isset($article->allow_comments) && !$article->allow_comments) {
            return redirect()->to($redirectUrl)->with('comment_error', 'Maaf, kolom komentar untuk artikel ini telah ditutup oleh admin.');
        }

        // 1. Honeypot check (anti-bot trap)
        if ($request->filled('_hp_website_title')) {
            return redirect()->to($redirectUrl)
                ->withInput()
                ->with('comment_error', 'Permintaan Anda terdeteksi sebagai spam otomatis (bot).');
        }

        // 2. Anti-Bot Math Captcha verification
        $request->validate([
            'captcha_input' => 'required|integer',
        ], [
            'captcha_input.required' => 'Verifikasi keamanan (Anti-Bot) wajib diisi.',
            'captcha_input.integer' => 'Hasil verifikasi anti-bot harus berupa angka.',
        ]);

        $captchaInput = (int) $request->input('captcha_input');
        $num1 = (int) $request->input('captcha_num1');
        $num2 = (int) $request->input('captcha_num2');
        $token = (string) $request->input('captcha_token');

        $expectedSum = $num1 + $num2;
        $isValidToken = hash_equals(hash_hmac('sha256', "comment:{$num1}+{$num2}", config('app.key')), $token);
        $isSessionValid = session()->has('article_comment_captcha') && $captchaInput === (int) session('article_comment_captcha');

        if ((!$isValidToken || $captchaInput !== $expectedSum) && !$isSessionValid) {
            return redirect()->to($redirectUrl)
                ->withInput()
                ->with('comment_error', 'Hasil penjumlahan verifikasi keamanan (Anti-Bot) tidak tepat. Silakan hitung dan coba lagi.');
        }

        session()->forget('article_comment_captcha');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'comment' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:article_comments,id',
        ]);

        \App\Models\ArticleComment::create([
            'article_id' => $article->id,
            'parent_id' => $validated['parent_id'] ?? null,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'comment' => $validated['comment'],
            'is_admin_reply' => false,
            'is_approved' => true,
        ]);

        return redirect()->to($redirectUrl)->with('comment_success', 'Komentar Anda berhasil dikirim!');
    }
}
