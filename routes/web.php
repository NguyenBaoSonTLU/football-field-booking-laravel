<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FootballFieldController as AdminFootballFieldController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\TimeSlotController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FootballFieldController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/san-bong', [FootballFieldController::class, 'index'])->name('fields.index');
Route::get('/san-bong/{footballField}', [FootballFieldController::class, 'show'])->name('fields.show');
Route::get('/san-bong/{footballField}/lich-trong', [FootballFieldController::class, 'availability'])->name('fields.availability');

require __DIR__.'/auth.php';

Route::middleware(['auth', 'account.active'])->group(function (): void {
    Route::get('/dat-san/{footballField}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/dat-san', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/lich-su-dat-san', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/lich-su-dat-san/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::patch('/lich-su-dat-san/{booking}/huy', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/lich-su-dat-san/{booking}/dat-lai', [BookingController::class, 'rebook'])->name('bookings.rebook');

    Route::get('/tai-khoan', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/tai-khoan', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/tai-khoan/doi-mat-khau', [ProfileController::class, 'password'])->name('profile.password');
    Route::put('/tai-khoan/doi-mat-khau', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::get('/thong-bao', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/thong-bao/{notification}/da-doc', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::patch('/thong-bao/da-doc-tat-ca', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'account.active', 'admin'])
    ->group(function (): void {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('fields', AdminFootballFieldController::class)
            ->parameters(['fields' => 'field']);

        Route::resource('time-slots', TimeSlotController::class)
            ->except('show')
            ->parameters(['time-slots' => 'timeSlot']);

        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
        Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'update'])->name('bookings.update');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::patch('/users/{user}/status', [UserController::class, 'update'])->name('users.update');

        Route::get('/notifications/create', [AdminNotificationController::class, 'create'])->name('notifications.create');
        Route::post('/notifications', [AdminNotificationController::class, 'store'])->name('notifications.store');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });
