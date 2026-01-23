<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FreelancerController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('projects', ProjectController::class);
Route::resource('clients', ClientController::class);
Route::resource('freelancers', FreelancerController::class);