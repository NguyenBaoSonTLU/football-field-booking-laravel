<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/dang-ky', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/dang-ky', [RegisteredUserController::class, 'store']);

    Route::get('/dang-nhap', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/dang-nhap', [AuthenticatedSessionController::class, 'store']);

    Route::get('/quen-mat-khau', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/quen-mat-khau', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/dat-lai-mat-khau/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/dat-lai-mat-khau', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::post('/dang-xuat', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
