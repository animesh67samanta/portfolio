<?php

use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Frontend\ContactController as FrontendContactController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::post('/contact', [FrontendContactController::class, 'store'])->name('contacts.store');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::prefix('admin')->name('admin.')->middleware('can:accessAdmin')->group(function (): void {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::resource('banners', BannerController::class)->only(['index', 'store', 'update', 'destroy']);

        Route::resource('abouts', AboutController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('skills', SkillController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('projects', ProjectController::class)->only(['index', 'create', 'edit', 'store', 'update', 'destroy']);
        Route::resource('blogs', BlogController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('testimonials', TestimonialController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::patch('contacts/{contact}/read', [ContactController::class, 'markAsRead'])->name('contacts.read');
        Route::patch('contacts/{contact}/unread', [ContactController::class, 'markAsUnread'])->name('contacts.unread');
        Route::post('contacts/{contact}/reply', [ContactController::class, 'reply'])->name('contacts.reply');
        Route::resource('contacts', ContactController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    });

    Route::redirect('/dashboard', '/admin')->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
