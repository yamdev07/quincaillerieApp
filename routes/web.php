<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
    

Route::view('/', 'welcome');

// Dashboard
Route::get('/dashboard', [SaleController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

    // Profil utilisateur
    Route::view('/profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
    
// Auth routes
require __DIR__.'/auth.php';

// Ventes (CRUD)
Route::middleware(['auth'])->group(function () {
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
});
    Route::resource('clients', ClientController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('products', ProductController::class);
    