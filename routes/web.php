<?php

use App\Http\Controllers\SocialController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

// 社群登入
Route::group([
    'prefix' => 'auth',
    'as' => 'auth.',
], function () {
    Route::get('/redirect/{social}', [SocialController::class, 'redirect'])->name('redirect');
    Route::get('/{social}/callback', [SocialController::class, 'callback'])->name('callback');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__ . '/settings.php';
