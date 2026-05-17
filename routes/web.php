<?php

use Illuminate\Support\Facades\Route;

// +++ ADD THESE IMPORTS +++
use App\Http\Controllers\StudioController;
use App\Http\Controllers\Admin\ImpersonationController;
use App\Http\Controllers\DownloadController;
// +++ END ADDITIONS +++

// +++ ADD THESE +++
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\ComicsController;
use App\Http\Controllers\Admin\CommandController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EconomicsController;
use App\Http\Controllers\Admin\ForgeController;
// +++ END ADDITIONS +++


use App\Http\Controllers\DiscoveryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Admin\AuthController;

use App\Http\Controllers\Admin\ForgeController as AdminForgeController;
use App\Http\Controllers\Admin\ComicsController as AdminComicsController;


// Discovery (public)
Route::get('/discovery', [App\Http\Controllers\DiscoveryController::class, 'index'])->name('discovery');

// Redirect the site root to the discovery controller (avoid rendering the blade directly)
Route::redirect('/', '/discovery')->name('home');

// User account (auth required)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
});

// +++ ONBOARDING (SESSION-DRIVEN DEMO, SECURE WITH AUTH LATER) +++
Route::get('/onboarding/register', [OnboardingController::class, 'createAccountForm'])->name('onboarding.register.form');
Route::post('/onboarding/register', [OnboardingController::class, 'createAccount'])->name('onboarding.register');
Route::get('/onboarding/studio', [OnboardingController::class, 'studioProfileForm'])->name('onboarding.studio.form');
Route::post('/onboarding/studio', [OnboardingController::class, 'studioProfile'])->name('onboarding.studio');
Route::get('/onboarding/pending', [OnboardingController::class, 'pending'])->name('onboarding.pending');
// +++ END ONBOARDING +++

// Admin Auth
Route::prefix('admin')->group(function () {
    Route::get('/login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('admin.login.form');
    Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('admin.login.attempt');
    Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('admin.logout');

    // Protect all admin features behind admin-only middleware
    Route::middleware(App\Http\Middleware\AdminOnly::class)->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/command', [CommandController::class, 'index'])->name('admin.command');
        Route::get('/economics', [EconomicsController::class, 'index'])->name('admin.economics');

        Route::get('/forge', [App\Http\Controllers\Admin\ForgeController::class, 'index'])->name('admin.forge');
        Route::post('/forge', [App\Http\Controllers\Admin\ForgeController::class, 'store'])->name('admin.forge.store');

        Route::get('/comics', [App\Http\Controllers\Admin\ComicsController::class, 'index'])->name('admin.comics');
        Route::post('/comics', [App\Http\Controllers\Admin\ComicsController::class, 'store'])->name('admin.comics.store');
        Route::post('/comics/{project}/publish', [App\Http\Controllers\Admin\ComicsController::class, 'publish'])->name('admin.comics.publish');

        // +++ ADMIN IMPERSONATION ROUTES +++
        Route::get('/impersonate/{author}', [ImpersonationController::class, 'start'])->name('admin.impersonate.start');
        Route::get('/stop-impersonate', [ImpersonationController::class, 'stop'])->name('admin.impersonate.stop');
        // +++ END ADMIN +++

        // +++ ADMIN REVIEW QUEUE (PROTECT WITH AUTH/GATES IN REAL APP) +++
        Route::get('/review', [ReviewController::class, 'index'])->name('admin.review.index');
        Route::post('/review/{author}/approve', [ReviewController::class, 'approve'])->name('admin.review.approve');
        Route::post('/review/{author}/suspend', [ReviewController::class, 'suspend'])->name('admin.review.suspend');
        // +++ END ADMIN +++

        // ... existing admin.review & impersonation routes remain here ...
    });
});

// +++ SIGNED DOWNLOAD ENDPOINT (UNCHANGED) +++
Route::get('/projects/{project}/download/{path}', DownloadController::class)
    ->where('path', '.*')
    ->name('projects.download');

// +++ COMIC VIEWER +++
Route::get('/comics/{project}', [ComicsController::class, 'show'])->name('comics.show');
// +++ END COMIC +++

// Add a simple dashboard redirect to avoid broken "Dashboard" links from default templates
Route::get('/dashboard', function () {
    return redirect('/');
})->name('dashboard');

// +++ TENANT ROUTES VIA FIRST SEGMENT (UNCHANGED) +++
Route::middleware(['impersonate', 'tenant'])->group(function () {
    Route::get('/{studio}', [StudioController::class, 'dashboard'])->name('studio.dashboard');

    // +++ NEW: Studio feature placeholders +++
    Route::get('/{studio}/forge', [StudioController::class, 'forge'])->name('studio.forge');
    Route::get('/{studio}/comics', [StudioController::class, 'comics'])->name('studio.comics');
    Route::get('/{studio}/analytics', [StudioController::class, 'analytics'])->name('studio.analytics');
    // +++ END NEW +++
});
// +++ END TENANT +++

// +++ NEW: Discovery + Profile + Settings + Admin Auth routes +++
Route::get('/discovery', [DiscoveryController::class, 'index'])->name('discovery');
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/settings', [SettingsController::class, 'show'])->name('settings.show');
Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

// Admin login (no middleware)
Route::get('/admin/login', [AuthController::class, 'showLogin'])
    ->name('admin.login')
    ->withoutMiddleware('admin.auth');

Route::post('/admin/login', [AuthController::class, 'login'])
    ->name('admin.login.submit')
    ->withoutMiddleware('admin.auth');

Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Admin routes protected by admin.auth middleware
Route::prefix('admin')->middleware('admin.auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/command', [CommandController::class, 'index'])->name('admin.command');
    Route::get('/economics', [EconomicsController::class, 'index'])->name('admin.economics');

    Route::get('/forge', [AdminForgeController::class, 'index'])->name('admin.forge');
    Route::post('/forge', [AdminForgeController::class, 'store'])->name('admin.forge.store');

    Route::get('/comics', [AdminComicsController::class, 'index'])->name('admin.comics');
    Route::post('/comics', [AdminComicsController::class, 'store'])->name('admin.comics.store');
    Route::post('/comics/{project}/publish', [AdminComicsController::class, 'publish'])->name('admin.comics.publish');

    // ... existing admin.review & impersonation routes ...
});
// +++ END NEW +++

// Tenant-scoped studio routes
Route::middleware(['impersonate', 'tenant'])->group(function () {
    Route::get('/{studio}', [StudioController::class, 'dashboard'])->name('studio.dashboard');

    Route::get('/{studio}/forge', [StudioController::class, 'forge'])->name('studio.forge');
    Route::post('/{studio}/forge', [StudioController::class, 'forgeStore'])->name('studio.forge.store');

    // Comics: screen + upload + publish toggle
    Route::get('/{studio}/comics', [StudioController::class, 'comics'])->name('studio.comics');
    Route::post('/{studio}/comics', [StudioController::class, 'comicsStore'])->name('studio.comics.store');
    Route::post('/{studio}/comics/{project}/publish', [StudioController::class, 'comicsPublish'])->name('studio.comics.publish');

    Route::get('/{studio}/analytics', [StudioController::class, 'analytics'])->name('studio.analytics');
});

// Admin routes
Route::prefix('admin')->middleware(\App\Http\Middleware\AdminAuthenticate::class)->group(function () {
    // Discovery page connected to backend
    Route::get('/discovery', [App\Http\Controllers\DiscoveryController::class, 'index'])->name('discovery');

    // Settings and Profile routes (for topbar buttons)
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'account'])->name('settings.account');
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');

    // Admin authentication routes
    // Route::get('/login', [App\Http\Controllers\Admin\AuthController::class, 'loginForm'])->name('admin.login.form');
    // Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('admin.login');

    // Add a logout route
    Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('admin.logout');

   
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/command', [CommandController::class, 'index'])->name('admin.command');
    Route::get('/economics', [EconomicsController::class, 'index'])->name('admin.economics');

    Route::get('/forge', [\App\Http\Controllers\Admin\ForgeController::class, 'index'])->name('admin.forge');
    Route::post('/forge', [\App\Http\Controllers\Admin\ForgeController::class, 'store'])->name('admin.forge.store');

    // Admin comics: reuse view, allow upload + publish (requires impersonation)
    Route::get('/comics', [\App\Http\Controllers\Admin\ComicsController::class, 'index'])->name('admin.comics');
    Route::post('/comics', [\App\Http\Controllers\Admin\ComicsController::class, 'store'])->name('admin.comics.store');
    Route::post('/comics/{project}/publish', [\App\Http\Controllers\Admin\ComicsController::class, 'publish'])->name('admin.comics.publish');
});