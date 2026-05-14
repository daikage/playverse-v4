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
// +++ END ADDITIONS +++

Route::get('/', function () {
    // return view('welcome');
    // +++ CHANGE TO DISCOVERY LANDING +++
    return view('discovery');
    // +++ END CHANGE +++
});

// +++ ONBOARDING (SESSION-DRIVEN DEMO, SECURE WITH AUTH LATER) +++
Route::get('/onboarding/register', [OnboardingController::class, 'createAccountForm'])->name('onboarding.register.form');
Route::post('/onboarding/register', [OnboardingController::class, 'createAccount'])->name('onboarding.register');
Route::get('/onboarding/studio', [OnboardingController::class, 'studioProfileForm'])->name('onboarding.studio.form');
Route::post('/onboarding/studio', [OnboardingController::class, 'studioProfile'])->name('onboarding.studio');
Route::get('/onboarding/pending', [OnboardingController::class, 'pending'])->name('onboarding.pending');
// +++ END ONBOARDING +++

// +++ ADMIN IMPERSONATION ROUTES +++
Route::prefix('admin')->group(function () {
    Route::get('/impersonate/{author}', [ImpersonationController::class, 'start'])->name('admin.impersonate.start');
    Route::get('/stop-impersonate', [ImpersonationController::class, 'stop'])->name('admin.impersonate.stop');
});
// +++ END ADMIN +++

// +++ ADMIN REVIEW QUEUE (PROTECT WITH AUTH/GATES IN REAL APP) +++
Route::prefix('admin')->group(function () {
    Route::get('/review', [ReviewController::class, 'index'])->name('admin.review.index');
    Route::post('/review/{author}/approve', [ReviewController::class, 'approve'])->name('admin.review.approve');
    Route::post('/review/{author}/suspend', [ReviewController::class, 'suspend'])->name('admin.review.suspend');

    Route::get('/impersonate/{author}', [ImpersonationController::class, 'start'])->name('admin.impersonate.start');
    Route::get('/stop-impersonate', [ImpersonationController::class, 'stop'])->name('admin.impersonate.stop');
});
// +++ END ADMIN +++

// +++ SIGNED DOWNLOAD ENDPOINT (UNCHANGED) +++
Route::get('/projects/{project}/download/{path}', DownloadController::class)
    ->where('path', '.*')
    ->name('projects.download');

// +++ COMIC VIEWER +++
Route::get('/comics/{project}', [ComicsController::class, 'show'])->name('comics.show');
// +++ END COMIC +++

// +++ TENANT ROUTES VIA FIRST SEGMENT (UNCHANGED) +++
Route::middleware(['impersonate', 'tenant'])->group(function () {
    Route::get('/{studio}', [StudioController::class, 'dashboard'])->name('studio.dashboard');
});
// +++ END TENANT +++
