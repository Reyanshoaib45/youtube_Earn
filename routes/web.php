<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Manager\ManagerController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\LandingController;

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// User Routes
Route::middleware(['auth', 'role:user'])->prefix('user')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/videos', [UserController::class, 'videos'])->name('user.videos');
    Route::post('/watch-video', [UserController::class, 'watchVideo'])->name('user.watch-video');
    Route::get('/packages', [UserController::class, 'packages'])->name('user.packages');
    Route::post('/buy-package', [UserController::class, 'buyPackage'])->name('user.buy-package');
    Route::post('/submit-purchase-request', [UserController::class, 'submitPurchaseRequest'])->name('user.submit-purchase-request');
    Route::get('/withdraw', [UserController::class, 'withdraw'])->name('user.withdraw');
    Route::post('/withdraw', [UserController::class, 'requestWithdraw'])->name('user.request-withdraw');
    
    // Referral System Routes
    Route::get('/referrals', [UserController::class, 'referrals'])->name('user.referrals');
});

// Manager Routes
Route::middleware(['auth', 'role:manager'])->prefix('manager')->group(function () {
    Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('manager.dashboard');
    Route::get('/videos', [ManagerController::class, 'videos'])->name('manager.videos');
    Route::post('/videos', [ManagerController::class, 'storeVideo'])->name('manager.store-video');
    Route::put('/videos/{video}', [ManagerController::class, 'updateVideo'])->name('manager.update-video');
    Route::delete('/videos/{video}', [ManagerController::class, 'deleteVideo'])->name('manager.delete-video');
    Route::get('/packages', [ManagerController::class, 'packages'])->name('manager.packages');
    Route::post('/packages', [ManagerController::class, 'storePackage'])->name('manager.store-package');
    Route::put('/packages/{package}', [ManagerController::class, 'updatePackage'])->name('manager.update-package');
    Route::delete('/packages/{package}', [ManagerController::class, 'deletePackage'])->name('manager.delete-package');
    
    // Purchase Request Management
    Route::get('/purchase-requests', [ManagerController::class, 'purchaseRequests'])->name('manager.purchase-requests');
    Route::post('/approve-purchase-request/{purchaseRequest}', [ManagerController::class, 'approvePurchaseRequest'])->name('manager.approve-purchase-request');
    Route::post('/reject-purchase-request/{purchaseRequest}', [ManagerController::class, 'rejectPurchaseRequest'])->name('manager.reject-purchase-request');
    
    Route::get('/withdrawals', [ManagerController::class, 'withdrawals'])->name('manager.withdrawals');
    Route::post('/approve-withdrawal/{withdrawal}', [ManagerController::class, 'approveWithdrawal'])->name('manager.approve-withdrawal');
    Route::post('/ban-user/{user}', [ManagerController::class, 'banUser'])->name('manager.ban-user');

});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/managers', [AdminController::class, 'managers'])->name('admin.managers');
    Route::post('/managers', [AdminController::class, 'createManager'])->name('admin.create-manager');
    Route::put('/managers/{manager}', [AdminController::class, 'updateManager'])->name('admin.update-manager');
    Route::get('/withdrawals', [AdminController::class, 'withdrawals'])->name('admin.withdrawals');
});