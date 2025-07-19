<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Banned user route
Route::middleware('auth')->group(function () {
    Route::get('/banned', function () {
        if (!auth()->user()->is_banned) {
            return redirect()->route(auth()->user()->role . '.dashboard');
        }
        return view('auth.banned');
    })->name('banned');
});

// Check if user is banned middleware
Route::middleware(['auth', 'check.banned'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // User routes
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
        Route::get('/packages', [UserController::class, 'packages'])->name('packages');
        Route::post('/packages/{package}/purchase', [UserController::class, 'purchasePackage'])->name('package.purchase');
        Route::get('/payment/{userPackage}', [UserController::class, 'showPayment'])->name('payment');
        Route::post('/payment/{userPackage}/submit', [UserController::class, 'submitPayment'])->name('payment.submit');
        Route::get('/videos', [UserController::class, 'videos'])->name('videos');
        Route::get('/videos/{video}/watch', [UserController::class, 'watchVideo'])->name('video.watch');
        Route::post('/videos/{video}/progress', [UserController::class, 'updateWatchProgress'])->name('video.progress');
        Route::get('/rewards', [UserController::class, 'rewards'])->name('rewards');
        Route::get('/withdrawals', [UserController::class, 'withdrawals'])->name('withdrawals');
        Route::post('/withdrawals', [UserController::class, 'requestWithdrawal'])->name('withdrawal.request');
        Route::get('/referrals', [UserController::class, 'referrals'])->name('referrals');
        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
        Route::get('/notifications', [UserController::class, 'notifications'])->name('notifications');
        Route::get('/location-history', [UserController::class, 'locationHistory'])->name('location.history');
    });

    // Manager routes
    Route::middleware(['role:manager'])->prefix('manager')->name('manager.')->group(function () {
        Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('dashboard');

        Route::get('/payments', [ManagerController::class, 'payments'])->name('payments');
        Route::get('/payments/{userPackage}', [ManagerController::class, 'getPaymentDetails'])->name('payments.details');
        Route::post('/payments/{userPackage}/review', [ManagerController::class, 'reviewPayment'])->name('payments.review');

        Route::get('/packages', [ManagerController::class, 'packages'])->name('packages');

        Route::get('/videos', [ManagerController::class, 'videos'])->name('videos');
        Route::post('/videos', [ManagerController::class, 'storeVideo'])->name('video.store');
        Route::put('/videos/{video}', [ManagerController::class, 'updateVideo'])->name('video.update');
        Route::delete('/videos/{video}', [ManagerController::class, 'destroyVideo'])->name('video.destroy');
        Route::get('/videos/{video}/analytics', [ManagerController::class, 'videoAnalytics'])->name('video.analytics');

        Route::get('/users', [ManagerController::class, 'users'])->name('users');
        Route::get('/users/{user}', [ManagerController::class, 'userDetails'])->name('user.details');
        Route::put('/users/{user}/ban', [ManagerController::class, 'banUser'])->name('user.ban');
        Route::put('/users/{user}/unban', [ManagerController::class, 'unbanUser'])->name('user.unban');

        Route::get('/categories', [ManagerController::class, 'categories'])->name('categories');

        Route::get('/withdrawals', [ManagerController::class, 'withdrawals'])->name('withdrawals');
        Route::post('/withdrawals/{withdrawal}/process', [ManagerController::class, 'processWithdrawal'])->name('withdrawal.process');

        Route::get('/analytics', [ManagerController::class, 'analytics'])->name('analytics');
        Route::get('/reports', [ManagerController::class, 'reports'])->name('reports');
        Route::get('/notifications', [ManagerController::class, 'notifications'])->name('notifications');
        Route::post('/notifications/{notification}/read', [ManagerController::class, 'markNotificationAsRead'])->name('notifications.read');
    });

    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/{user}', [AdminController::class, 'userDetails'])->name('user.details');
        Route::put('/users/{user}/ban', [AdminController::class, 'banUser'])->name('user.ban');
        Route::put('/users/{user}/unban', [AdminController::class, 'unbanUser'])->name('user.unban');
        Route::put('/users/{user}/role', [AdminController::class, 'changeUserRole'])->name('user.role');
        Route::post('/manager/create', [AdminController::class, 'createManager'])->name('manager.create');

        Route::get('/ip-tracking', [AdminController::class, 'ipTracking'])->name('ip.tracking');
        Route::get('/location-logs', [AdminController::class, 'locationLogs'])->name('location.logs');

        Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('category.store');
        Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('category.update');
        Route::delete('/categories/{category}', [AdminController::class, 'deleteCategory'])->name('category.destroy');

        Route::get('/packages', [AdminController::class, 'packages'])->name('packages');
        Route::post('/packages', [AdminController::class, 'storePackage'])->name('package.store');
        Route::put('/packages/{package}', [AdminController::class, 'updatePackage'])->name('package.update');
        Route::delete('/packages/{package}', [AdminController::class, 'deletePackage'])->name('package.destroy');

        Route::get('/videos', [AdminController::class, 'videos'])->name('videos');
        Route::post('/videos', [AdminController::class, 'storeVideo'])->name('video.store');
        Route::put('/videos/{video}', [AdminController::class, 'updateVideo'])->name('video.update');
        Route::delete('/videos/{video}', [AdminController::class, 'destroyVideo'])->name('video.destroy');

        Route::get('/withdrawals', [AdminController::class, 'withdrawals'])->name('withdrawals');
        Route::post('/withdrawals/{withdrawal}/process', [AdminController::class, 'processWithdrawal'])->name('withdrawal.process');

        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

        Route::get('/manager-logs', [AdminController::class, 'managerLogs'])->name('manager.logs');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');

        Route::get('/notifications', [AdminController::class, 'notifications'])->name('notifications');
        Route::post('/notifications/send', [AdminController::class, 'sendNotification'])->name('notifications.send');
        Route::delete('/notifications/{notification}', [AdminController::class, 'deleteNotification'])->name('notifications.destroy');


    });
});
Route::get('/transactions', [AdminController::class, 'transactions'])->name('admin.transactions');
Route::get('/referral/settings', [AdminController::class, 'referral'])->name('admin.referral.settings');
Route::get('/analytics', [AdminController::class, 'analytics'])->name('admin.analytics');
Route::get('/system/info', [AdminController::class, 'systeminfo'])->name('admin.system.info');
//Route::get('/users/{id}', [AdminController::class, 'show'])
//    ->name('admin.user-details');
// Fallback route
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->is_banned) {
            return redirect()->route('banned');
        }
        return redirect()->route($user->role . '.dashboard');
    }
    return redirect()->route('login');
});
