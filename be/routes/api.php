<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\BatchController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PegawaiController;
use App\Http\Controllers\Api\SiteAnalyticsController;
use App\Http\Controllers\Api\SiteStatsController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\WebsiteIdentityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Aggregator per halaman: satu request untuk semua data yang dibutuhkan FE.
Route::get('/batch/{page}', [BatchController::class, 'show']);

Route::get('/faqs', [FaqController::class, 'index']);
Route::get('/testimonials', [TestimonialController::class, 'index']);
Route::get('/categories', [ArticleController::class, 'category']);
Route::get('/contact/captcha', [ContactController::class, 'captcha'])->middleware('throttle:api-captcha');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:api-contact');
Route::get('/banners', [BannerController::class, 'index']);

// Identitas website publik (nama, kontak, sosmed, SEO, branding) — untuk frontend
Route::get('/website-identity', [WebsiteIdentityController::class, 'index']);

// Statistik publik (agregat member/vendor/kelas) — untuk frontend
Route::get('/site-stats', [SiteStatsController::class, 'index']);
Route::post('/analytics/visits', [SiteAnalyticsController::class, 'storeVisit']);

// Articles
Route::prefix('articles')->group(function () {
    Route::get('/', [ArticleController::class, 'index']);
    Route::get('category/{slug}', [ArticleController::class, 'byCategory']);
    Route::get('{slug}', [ArticleController::class, 'show']);
});

// Staff (legacy alias 'pegawai' kept for backward compatibility)
Route::prefix('pegawai')->group(function () {
    Route::get('/', [PegawaiController::class, 'index']);
    Route::get('{uuid}', [PegawaiController::class, 'show'])->whereUuid('uuid');
});
Route::prefix('staff')->group(function () {
    Route::get('/', [PegawaiController::class, 'index']);
    Route::get('{uuid}', [PegawaiController::class, 'show'])->whereUuid('uuid');
});

// Static pages
Route::get('/pages', [PageController::class, 'index']);
Route::get('/pages/{slug}', [PageController::class, 'show']);

// Events page
Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{slug}', [EventController::class, 'show']);

// vendors
Route::get('/vendors', [
    VendorController::class,
    'index',
]);

Route::get('/instructors', [
    VendorController::class,
    'index',
]);

// packages
Route::get('/packages', [
    PackageController::class,
    'index',
]);

Route::get('/packages/{slug}', [
    PackageController::class,
    'show',
]);
