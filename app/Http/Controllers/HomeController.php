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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        // Generate random math captcha for anti-bot protection
        $num1 = rand(3, 9);
        $num2 = rand(1, 8);
        $captchaQuestion = "{$num1} + {$num2}";
        session(['captcha_answer' => $num1 + $num2]);

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
            'laboratories'
        ));
    }

    public function submitContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
            'captcha_input' => 'required|integer',
        ], [
            'captcha_input.required' => 'Kode verifikasi keamanan (anti-bot) wajib diisi.',
            'captcha_input.integer' => 'Kode verifikasi harus berupa angka.',
        ]);

        if (session()->has('captcha_answer') && (int) $request->input('captcha_input') !== (int) session('captcha_answer')) {
            return redirect()->to(url('/#helpdesk'))
                ->withInput()
                ->with('captcha_error', 'Kode verifikasi keamanan (anti-bot) tidak cocok. Silakan coba lagi.');
        }

        // Clear captcha answer after successful verification
        session()->forget('captcha_answer');

        unset($validated['captcha_input']);

        InboxLead::create([
            ...$validated,
            'status' => 'unread',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->to(url('/#helpdesk'))->with('success', 'Tiket bantuan IT Helpdesk berhasil dikirim! Tim operasional kami akan segera menghubungi Anda.');
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

        return view('articles.show', compact('article', 'recentArticles', 'profile', 'settings'));
    }

    public function submitArticleComment(Request $request, Article $article): RedirectResponse
    {
        if (isset($article->allow_comments) && !$article->allow_comments) {
            return back()->with('comment_error', 'Maaf, kolom komentar untuk artikel ini telah ditutup oleh admin.');
        }

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

        return back()->with('comment_success', 'Komentar Anda berhasil dikirim!');
    }
}
