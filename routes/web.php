<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FreelancerController;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Admin\UserController;

// Auth Routes
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('projects', ProjectController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('freelancers', FreelancerController::class);


    Route::resource('invoices', InvoiceController::class);
    Route::post('invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
    Route::post('invoices/{invoice}/payment', [InvoiceController::class, 'addPayment'])->name('invoices.payment');

    // Admin Routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
    });
});

Route::get('lang/{locale}', [App\Http\Controllers\LocalizationController::class, 'setLang'])->name('lang.switch');