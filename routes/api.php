<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ChatbotController;
use App\Http\Controllers\Api\CompanyProfileController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\HeroBannerController;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SiteSettingController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\TestimonialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public Headless REST API v1 for Web Profil Frontend
Route::prefix('v1')->group(function () {
    Route::get('/company-profile', [CompanyProfileController::class, 'show']);
    Route::get('/hero-banners', [HeroBannerController::class, 'index']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('/services/{slug}', [ServiceController::class, 'show']);
    Route::get('/portfolios', [PortfolioController::class, 'index']);
    Route::get('/portfolios/{slug}', [PortfolioController::class, 'show']);
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/{slug}', [ArticleController::class, 'show']);
    Route::get('/team-members', [TeamController::class, 'index']);
    Route::get('/testimonials', [TestimonialController::class, 'index']);
    Route::post('/contact', [ContactController::class, 'store']);
    Route::get('/settings', [SiteSettingController::class, 'index']);

    // IT Helpdesk Smart Chatbot API Routes (Rate limited for security)
    Route::prefix('chatbot')->group(function () {
        Route::get('/init', [ChatbotController::class, 'init']);
        Route::post('/message', [ChatbotController::class, 'sendMessage'])->middleware('throttle:30,1');
        Route::post('/ticket', [ChatbotController::class, 'submitTicket'])->middleware('throttle:5,1');
        Route::post('/track-ticket', [ChatbotController::class, 'trackTicket'])->middleware('throttle:30,1');
    });
});
