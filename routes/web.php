<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AddonController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Client;
use App\Models\Waitlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 |--------------------------------------------------------------------------
 | Public Routes
 |--------------------------------------------------------------------------
 */

Route::get('/', [HomeController::class , 'index'])->name('home');

Route::get('/portfolio', [ProjectController::class , 'index'])->name('portfolio.index');
Route::get('/portfolio/{project:slug}', [ProjectController::class , 'show'])->name('portfolio.show');

Route::get('/shop', [AddonController::class , 'index'])->name('shop.index');
Route::get('/shop/{addon:slug}', [AddonController::class , 'show'])->name('shop.show');

Route::get('/services', [ServiceController::class , 'index'])->name('services.index');

Route::get('/learn', [ArticleController::class , 'index'])->name('learn.index');
Route::get('/learn/{article:slug}', [ArticleController::class , 'show'])->name('learn.show');

Route::get('/about', [AboutController::class , 'index'])->name('about');

Route::get('/contact', [ContactController::class , 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class , 'submit'])->name('contact.submit');

// Waitlist
Route::post('/waitlist', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'course_name' => 'required|string|max:255',
    ]);
    Waitlist::create($request->only('email', 'course_name'));
    return back()->with('success', 'You\'ve been added to the waitlist!');
})->name('waitlist.store');

// Download
Route::get('/download/{token}', [CheckoutController::class , 'download'])->name('download');
Route::get('/download/free/{addon:slug}', [CheckoutController::class , 'freeDownload'])->name('download.free');

/*
 |--------------------------------------------------------------------------
 | Checkout Routes (require auth)
 |--------------------------------------------------------------------------
 */

Route::middleware('auth')->group(function () {
    Route::get('/checkout/{addon:slug}', [CheckoutController::class , 'show'])->name('checkout.show');
    Route::post('/checkout/{addon:slug}', [CheckoutController::class , 'process'])->name('checkout.process');
});

Route::get('/checkout/success', [CheckoutController::class , 'success'])->name('checkout.success');
Route::get('/checkout/cancel', [CheckoutController::class , 'cancel'])->name('checkout.cancel');

/*
 |--------------------------------------------------------------------------
 | Client Routes
 |--------------------------------------------------------------------------
 */

Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [Client\DashboardController::class , 'index'])->name('dashboard');
    Route::post('/regenerate-token/{purchase}', [Client\DashboardController::class , 'regenerateToken'])->name('regenerate-token');
});

/*
 |--------------------------------------------------------------------------
 | Admin Routes
 |--------------------------------------------------------------------------
 */

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class , 'index'])->name('dashboard');

    Route::resource('categories', Admin\AddonCategoryController::class)->except(['show']);
    Route::resource('addons', Admin\AddonController::class)->except(['show']);
    Route::resource('project-categories', Admin\ProjectCategoryController::class)->except(['show']);
    Route::resource('projects', Admin\ProjectController::class)->except(['show']);
    Route::post('/projects/upload-image', [Admin\ProjectController::class, 'uploadImage'])->name('projects.upload-image');
    Route::resource('services', Admin\ServiceController::class)->except(['show']);
    Route::resource('brands', Admin\BrandController::class)->except(['show']);
    Route::resource('articles', Admin\ArticleController::class)->except(['show']);
    Route::post('/articles/upload-image', [Admin\ArticleController::class, 'uploadImage'])->name('articles.upload-image');

    Route::get('/purchases', [Admin\PurchaseController::class , 'index'])->name('purchases.index');
    Route::get('/waitlist', [Admin\WaitlistController::class , 'index'])->name('waitlist.index');

    Route::get('/settings/hero', [Admin\SettingController::class , 'hero'])->name('settings.hero');
    Route::put('/settings/hero', [Admin\SettingController::class , 'updateHero'])->name('settings.hero.update');

    Route::get('/settings/general', [Admin\SettingController::class , 'general'])->name('settings.general');
    Route::put('/settings/general', [Admin\SettingController::class , 'updateGeneral'])->name('settings.general.update');

    Route::get('/settings/social', [Admin\SettingController::class , 'social'])->name('settings.social');
    Route::put('/settings/social', [Admin\SettingController::class , 'updateSocial'])->name('settings.social.update');

    Route::get('/settings/about', [Admin\SettingController::class , 'about'])->name('settings.about');
    Route::put('/settings/about', [Admin\SettingController::class , 'updateAbout'])->name('settings.about.update');

    Route::get('/settings/account', [Admin\SettingController::class , 'account'])->name('settings.account');
    Route::put('/settings/account', [Admin\SettingController::class , 'updateAccount'])->name('settings.account.update');

    Route::get('/contact-messages', [Admin\ContactMessageController::class , 'index'])->name('contact-messages.index');
    Route::get('/contact-messages/{contactMessage}', [Admin\ContactMessageController::class , 'show'])->name('contact-messages.show');
    Route::delete('/contact-messages/{contactMessage}', [Admin\ContactMessageController::class , 'destroy'])->name('contact-messages.destroy');
});

/*
 |--------------------------------------------------------------------------
 | Profile Routes (Breeze)
 |--------------------------------------------------------------------------
 */

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';