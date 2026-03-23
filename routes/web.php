<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\UserRoleController;
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
    Route::get('/callback/{social}', [SocialController::class, 'callback'])->name('callback');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::group([
        'prefix' => 'rbac',
        'as' => 'rbac.',
    ], function () {
        Route::inertia('/', 'rbac/Index')->name('index');
        Route::get('roles', [RoleController::class, 'index'])->name('roles');
        Route::get('roles/{roleId}', [RoleController::class, 'show'])->name('roles.show')->where('roleId', '[0-9]+');
        Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
        Route::get('permissions', [PermissionController::class, 'index'])->name('permissions');
        Route::get('user-roles', [UserRoleController::class, 'index'])->name('user-roles');
    });
});

require __DIR__ . '/settings.php';
