<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompanyProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\HeroBannerController;
use App\Http\Controllers\Admin\InboxController;
use App\Http\Controllers\Admin\LabBookingController;
use App\Http\Controllers\Admin\LaboratoryController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LabScheduleController;
use Illuminate\Support\Facades\Route;

// Language Switcher Route
Route::get('/lang/{locale}', [\App\Http\Controllers\LanguageController::class, 'switch'])->name('lang.switch');

// Public Web Profil Landing Page (cms.test)
Route::get('/', [HomeController::class, 'company'])->name('home');
// Route::get('/desa2', [HomeController::class, 'desa2'])->name('desa2');
// Route::get('/desa1', [HomeController::class, 'desa1'])->name('desa1');
// Route::get('/home', [HomeController::class, 'index'])->name('desa2');
Route::get('/contact/captcha-refresh', [HomeController::class, 'refreshContactCaptcha'])->name('contact.captcha-refresh');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');
Route::get('/berita', [HomeController::class, 'allArticles'])->name('articles.index');
Route::get('/berita/{slug}', [HomeController::class, 'showArticle'])->name('articles.show');
Route::get('/articles/captcha-refresh', [HomeController::class, 'refreshCommentCaptcha'])->name('articles.comment.captcha-refresh');
Route::post('/articles/{article}/comment', [HomeController::class, 'submitArticleComment'])->name('articles.comment.submit');

//pemdi
Route::get('/pemdi', function () {
    return view('pemdi');
});
Route::get('/sigap-tppo', function () {
    return view('sigap-tppo');
});

// Public Lab Schedule & Booking Routes
Route::get('/jadwal-lab', [LabScheduleController::class, 'index'])->name('lab-schedule.index');
Route::get('/jadwal-lab/captcha-refresh', [LabScheduleController::class, 'refreshCaptcha'])->name('lab-schedule.captcha-refresh');
Route::post('/jadwal-lab/ajukan', [LabScheduleController::class, 'store'])->name('lab-schedule.store');
Route::post('/jadwal-lab/cek-status', [LabScheduleController::class, 'checkStatus'])->name('lab-schedule.check-status');

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
        // Routes accessible to all authenticated roles (Superadmin, Editor, Helpdesk)
        Route::middleware('role:superadmin,editor,helpdesk')->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

            // Profile Edit (Every logged-in user can edit their own profile)
            Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
            Route::put('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');

            // 2FA Management Routes
            Route::post('/profile/two-factor/enable', [\App\Http\Controllers\Admin\ProfileController::class, 'enableTwoFactor'])->name('profile.two-factor.enable');
            Route::post('/profile/two-factor/confirm', [\App\Http\Controllers\Admin\ProfileController::class, 'confirmTwoFactor'])->name('profile.two-factor.confirm');
            Route::delete('/profile/two-factor/disable', [\App\Http\Controllers\Admin\ProfileController::class, 'disableTwoFactor'])->name('profile.two-factor.disable');
            Route::post('/profile/two-factor/recovery-codes', [\App\Http\Controllers\Admin\ProfileController::class, 'regenerateRecoveryCodes'])->name('profile.two-factor.recovery-codes');
        });

        // Routes accessible to Superadmin & Editor (Berita, Artikel & Komentar)
        Route::middleware('role:superadmin,editor')->group(function () {
            // Articles & Comments
            Route::resource('/articles', ArticleController::class);
            Route::post('/articles/{article}/comments/{comment}/reply', [ArticleController::class, 'replyComment'])->name('articles.comments.reply');
            Route::delete('/articles/comments/{comment}', [ArticleController::class, 'deleteComment'])->name('articles.comments.destroy');

            // Dedicated Comments Management
            Route::get('/comments', [\App\Http\Controllers\Admin\CommentController::class, 'index'])->name('comments.index');
            Route::post('/comments/{comment}/reply', [\App\Http\Controllers\Admin\CommentController::class, 'reply'])->name('comments.reply');
            Route::delete('/comments/{comment}', [\App\Http\Controllers\Admin\CommentController::class, 'destroy'])->name('comments.destroy');
        });

        // Routes accessible to Superadmin & Helpdesk (Interaksi: Inbox Pesan & FAQ)
        Route::middleware('role:superadmin,helpdesk')->group(function () {
            // FAQs
            Route::get('/faqs/export/json', [FaqController::class, 'exportJson'])->name('faqs.export.json');
            Route::get('/faqs/export/csv', [FaqController::class, 'exportCsv'])->name('faqs.export.csv');
            Route::get('/faqs/template/{format}', [FaqController::class, 'downloadTemplate'])->name('faqs.template');
            Route::post('/faqs/import', [FaqController::class, 'import'])->name('faqs.import');
            Route::patch('/faqs/{faq}/toggle-active', [FaqController::class, 'toggleActive'])->name('faqs.toggle-active');
            Route::resource('/faqs', FaqController::class)->except(['show', 'create', 'edit']);

            // Chatbot Logs & Analytics
            Route::get('/chatbot-logs', [\App\Http\Controllers\Admin\ChatbotLogController::class, 'index'])->name('chatbot-logs.index');
            Route::get('/chatbot-logs/export/csv', [\App\Http\Controllers\Admin\ChatbotLogController::class, 'exportCsv'])->name('chatbot-logs.export.csv');
            Route::get('/chatbot-logs/{chatbotLog}', [\App\Http\Controllers\Admin\ChatbotLogController::class, 'show'])->name('chatbot-logs.show');
            Route::delete('/chatbot-logs/{chatbotLog}', [\App\Http\Controllers\Admin\ChatbotLogController::class, 'destroy'])->name('chatbot-logs.destroy');
            Route::post('/chatbot-logs/clear', [\App\Http\Controllers\Admin\ChatbotLogController::class, 'clear'])->name('chatbot-logs.clear');

            // Inbox Leads
            Route::get('/inbox', [InboxController::class, 'index'])->name('inbox.index');
            Route::get('/inbox/export/excel', [InboxController::class, 'exportExcel'])->name('inbox.export.excel');
            Route::get('/inbox/export/pdf', [InboxController::class, 'exportPdf'])->name('inbox.export.pdf');
            Route::post('/inbox/upload-image', [InboxController::class, 'uploadImage'])->name('inbox.upload-image');
            Route::get('/inbox/{inboxLead}', [InboxController::class, 'show'])->name('inbox.show');
            Route::post('/inbox/{inboxLead}/ai-draft', [InboxController::class, 'aiDraft'])->name('inbox.ai-draft');
            Route::post('/inbox/{inboxLead}/reply', [InboxController::class, 'reply'])->name('inbox.reply');
            Route::post('/inbox/{inboxLead}/reply-gmail-web', [InboxController::class, 'replyGmailWeb'])->name('inbox.reply-gmail-web');
            Route::patch('/inbox/{inboxLead}/status', [InboxController::class, 'updateStatus'])->name('inbox.status');
            Route::delete('/inbox/{inboxLead}', [InboxController::class, 'destroy'])->name('inbox.destroy');

            // Fasilitas & Laboratorium
            Route::resource('/laboratories', LaboratoryController::class);
            Route::get('/lab-bookings', [LabBookingController::class, 'index'])->name('lab-bookings.index');
            Route::get('/lab-bookings/create', [LabBookingController::class, 'create'])->name('lab-bookings.create');
            Route::post('/lab-bookings', [LabBookingController::class, 'store'])->name('lab-bookings.store');
            Route::post('/lab-bookings/toggle-service', [LabBookingController::class, 'toggleService'])->name('lab-bookings.toggle-service');
            Route::get('/lab-bookings/{booking}', [LabBookingController::class, 'show'])->name('lab-bookings.show');
            Route::post('/lab-bookings/{booking}/approve', [LabBookingController::class, 'approve'])->name('lab-bookings.approve');
            Route::post('/lab-bookings/{booking}/reject', [LabBookingController::class, 'reject'])->name('lab-bookings.reject');
            Route::post('/lab-bookings/{booking}/complete', [LabBookingController::class, 'complete'])->name('lab-bookings.complete');
            Route::delete('/lab-bookings/{booking}', [LabBookingController::class, 'destroy'])->name('lab-bookings.destroy');
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

            // User Management
            Route::resource('/users', UserController::class)->except(['show']);

            // Settings
            Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
            Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
            Route::post('/settings/test-gemini', [SettingController::class, 'testGemini'])->name('settings.test-gemini');
        });
    });
});

// Fallback 404 Route for unmapped URLs
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
