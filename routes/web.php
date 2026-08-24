<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompanyProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\HeroBannerController;
use App\Http\Controllers\Admin\InboxController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Language Switcher Route
Route::get('/lang/{locale}', [\App\Http\Controllers\LanguageController::class, 'switch'])->name('lang.switch');

// Public Web Profil Landing Page (cms.test)
Route::get('/', [HomeController::class, 'company'])->name('home');
// Route::get('/desa2', [HomeController::class, 'desa2'])->name('desa2');
// Route::get('/desa1', [HomeController::class, 'desa1'])->name('desa1');
// Route::get('/home', [HomeController::class, 'index'])->name('desa2');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');
Route::get('/berita', [HomeController::class, 'allArticles'])->name('articles.index');
Route::get('/berita/{slug}', [HomeController::class, 'showArticle'])->name('articles.show');
Route::post('/articles/{article}/comment', [HomeController::class, 'submitArticleComment'])->name('articles.comment.submit');

// Named login route for Laravel's Auth Middleware default
Route::get('/masukaksesdalam', [AuthController::class, 'showLoginForm'])->name('login');

// Admin Portal Routes (cms.test/admin)
Route::prefix('dalam')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/masukaksesdalam', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/login/2fa', [AuthController::class, 'show2faForm'])->name('login.2fa');
    Route::post('/login/2fa', [AuthController::class, 'verify2fa'])->name('login.2fa.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Authenticated Admin Dashboard Routes
    Route::middleware('auth')->group(function () {
        // Routes accessible to both Superadmin and Editor
        Route::middleware('role:superadmin,editor')->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

            // Profile Edit (Every logged-in user can edit their own profile)
            Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
            Route::put('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');

            // 2FA Management Routes
            Route::post('/profile/two-factor/enable', [\App\Http\Controllers\Admin\ProfileController::class, 'enableTwoFactor'])->name('profile.two-factor.enable');
            Route::post('/profile/two-factor/confirm', [\App\Http\Controllers\Admin\ProfileController::class, 'confirmTwoFactor'])->name('profile.two-factor.confirm');
            Route::delete('/profile/two-factor/disable', [\App\Http\Controllers\Admin\ProfileController::class, 'disableTwoFactor'])->name('profile.two-factor.disable');
            Route::post('/profile/two-factor/recovery-codes', [\App\Http\Controllers\Admin\ProfileController::class, 'regenerateRecoveryCodes'])->name('profile.two-factor.recovery-codes');

            // Articles & Comments
            Route::resource('/articles', ArticleController::class);
            Route::post('/articles/{article}/comments/{comment}/reply', [ArticleController::class, 'replyComment'])->name('articles.comments.reply');
            Route::delete('/articles/comments/{comment}', [ArticleController::class, 'deleteComment'])->name('articles.comments.destroy');

            // Dedicated Comments Management
            Route::get('/comments', [\App\Http\Controllers\Admin\CommentController::class, 'index'])->name('comments.index');
            Route::post('/comments/{comment}/reply', [\App\Http\Controllers\Admin\CommentController::class, 'reply'])->name('comments.reply');
            Route::delete('/comments/{comment}', [\App\Http\Controllers\Admin\CommentController::class, 'destroy'])->name('comments.destroy');
        });

        // Routes restricted ONLY to Superadmin
        Route::middleware('role:superadmin')->group(function () {
            // Company Profile
            Route::get('/company-profile', [CompanyProfileController::class, 'edit'])->name('company-profile.edit');
            Route::put('/company-profile', [CompanyProfileController::class, 'update'])->name('company-profile.update');

            // Hero Banners
            Route::resource('/hero-banners', HeroBannerController::class)->except(['show']);

            // Categories
            Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
            Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
            Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

            // Services
            Route::resource('/services', ServiceController::class)->except(['show']);

            // Portfolios
            Route::resource('/portfolios', PortfolioController::class)->except(['show']);

            // Team Members
            Route::resource('/team-members', TeamMemberController::class)->except(['show']);

            // Testimonials
            Route::resource('/testimonials', TestimonialController::class)->except(['show']);

            // FAQs
            Route::resource('/faqs', FaqController::class)->except(['show', 'create', 'edit']);

            // Inbox Leads
            Route::get('/inbox', [InboxController::class, 'index'])->name('inbox.index');
            Route::get('/inbox/export/excel', [InboxController::class, 'exportExcel'])->name('inbox.export.excel');
            Route::get('/inbox/export/pdf', [InboxController::class, 'exportPdf'])->name('inbox.export.pdf');
            Route::get('/inbox/{inboxLead}', [InboxController::class, 'show'])->name('inbox.show');
            Route::post('/inbox/{inboxLead}/reply', [InboxController::class, 'reply'])->name('inbox.reply');
            Route::post('/inbox/{inboxLead}/reply-gmail-web', [InboxController::class, 'replyGmailWeb'])->name('inbox.reply-gmail-web');
            Route::patch('/inbox/{inboxLead}/status', [InboxController::class, 'updateStatus'])->name('inbox.status');
            Route::delete('/inbox/{inboxLead}', [InboxController::class, 'destroy'])->name('inbox.destroy');

            // User Management
            Route::resource('/users', UserController::class)->except(['show']);

            // Settings
            Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
            Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
        });
    });
});

// Fallback 404 Route for unmapped URLs
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
