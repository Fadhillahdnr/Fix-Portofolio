<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortfolioController::class, 'home'])->name('home');
Route::get('/tentang', [PortfolioController::class, 'about'])->name('about');
Route::get('/proyek', [PortfolioController::class, 'projects'])->name('projects.index');
Route::get('/proyek/{project}', [PortfolioController::class, 'show'])->name('projects.show');
Route::get('/lab', [PortfolioController::class, 'lab'])->name('lab');
Route::get('/layanan', [PortfolioController::class, 'services'])->name('services');
Route::get('/kontak', [PortfolioController::class, 'contact'])->name('contact');
Route::post('/kontak', [ContactController::class, 'store'])->middleware('throttle:5,10')->name('contact.store');
Route::view('/privacy', 'pages.privacy')->name('privacy');
Route::get('/sitemap.xml', fn () => response()->view('sitemap', [], 200, ['Content-Type' => 'application/xml']))->name('sitemap');

Route::prefix(config('portfolio.admin_path'))->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/', [AuthController::class, 'create'])->name('admin.login');
        Route::post('/', [AuthController::class, 'store'])->middleware('throttle:5,1')->name('admin.login.store');
    });
    Route::middleware(['auth', 'admin'])->as('admin.')->group(function () {
        Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::resource('projects', ProjectController::class)->except('show');
        Route::post('/projects/{project}/media', [MediaController::class, 'store'])->name('projects.media.store');
        Route::delete('/projects/{project}/media/{medium}', [MediaController::class, 'destroy'])->name('projects.media.destroy');
        Route::get('/content', [ContentController::class, 'index'])->name('content');
        Route::put('/content/profile', [ContentController::class, 'profile'])->name('content.profile');
        foreach (['category', 'skill', 'service', 'experience', 'social', 'setting'] as $type) {
            Route::post('/content/'.$type, [ContentController::class, $type])->name('content.'.$type);
        }
        Route::patch('/messages/{message}', [ContentController::class, 'message'])->name('messages.update');
        Route::delete('/content/{type}/{id}', [ContentController::class, 'destroy'])->name('content.destroy');
    });
});
