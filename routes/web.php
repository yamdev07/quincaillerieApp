<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;

Route::view('/welcomme', 'welcome');

// Dashboard
Route::get('/', [SaleController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profil utilisateur
Route::view('/profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Auth routes
require __DIR__.'/auth.php';

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    
    // Ventes (CRUD complet)
    Route::prefix('sales')->group(function () {
        Route::get('/', [SaleController::class, 'index'])->name('sales.index');
        Route::get('/create', [SaleController::class, 'create'])->name('sales.create');
        Route::post('/', [SaleController::class, 'store'])->name('sales.store');
        Route::get('/{sale}', [SaleController::class, 'show'])->name('sales.show');
        Route::get('/{sale}/edit', [SaleController::class, 'edit'])->name('sales.edit');
        Route::put('/{sale}', [SaleController::class, 'update'])->name('sales.update');
        Route::delete('/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');
        Route::get('/{sale}/invoice', [SaleController::class, 'invoice'])->name('sales.invoice');
        Route::post('/{sale}/status', [SaleController::class, 'updateStatus'])->name('sales.status');
    });

    // Clients (CRUD complet)
    Route::prefix('clients')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('clients.index');
        Route::get('/create', [ClientController::class, 'create'])->name('clients.create');
        Route::post('/', [ClientController::class, 'store'])->name('clients.store');
        Route::get('/{client}', [ClientController::class, 'show'])->name('clients.show');
        Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
        Route::put('/{client}', [ClientController::class, 'update'])->name('clients.update');
        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
        Route::get('/{client}/sales', [ClientController::class, 'sales'])->name('clients.sales');
        Route::get('/{client}/statistics', [ClientController::class, 'statistics'])->name('clients.statistics');
    });

    // Fournisseurs (CRUD complet)
    Route::prefix('suppliers')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::get('/create', [SupplierController::class, 'create'])->name('suppliers.create');
        Route::post('/', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::get('/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
        Route::get('/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
        Route::put('/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
        Route::get('/{supplier}/products', [SupplierController::class, 'products'])->name('suppliers.products');
        Route::get('/{supplier}/orders', [SupplierController::class, 'orders'])->name('suppliers.orders');
    });

    // Produits (CRUD complet)
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('products.index');
        Route::get('/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/', [ProductController::class, 'store'])->name('products.store');
        Route::get('/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::get('/{product}/stock', [ProductController::class, 'stock'])->name('products.stock');
        Route::post('/{product}/stock', [ProductController::class, 'updateStock'])->name('products.stock.update');
        Route::get('/category/{category}', [ProductController::class, 'byCategory'])->name('products.byCategory');
        Route::get('/search', [ProductController::class, 'search'])->name('products.search');
    });

    // Routes supplémentaires pour les statistiques et rapports
    Route::prefix('reports')->group(function () {
        Route::get('/sales', [SaleController::class, 'salesReport'])->name('reports.sales');
        Route::get('/clients', [ClientController::class, 'clientsReport'])->name('reports.clients');
        Route::get('/products', [ProductController::class, 'productsReport'])->name('reports.products');
        Route::get('/inventory', [ProductController::class, 'inventoryReport'])->name('reports.inventory');
    });

    // API routes pour les données en temps réel
    Route::prefix('api')->group(function () {
        Route::get('/dashboard-stats', [SaleController::class, 'dashboardStats'])->name('api.dashboard.stats');
        Route::get('/recent-sales', [SaleController::class, 'recentSales'])->name('api.recent.sales');
        Route::get('/top-products', [ProductController::class, 'topProducts'])->name('api.top.products');
    });

});

// Routes publiques (si nécessaire)
Route::get('/products/catalog', [ProductController::class, 'catalog'])->name('products.catalog');
Route::get('/products/{product}/details', [ProductController::class, 'publicShow'])->name('products.public.show');

// Fallback route
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

// Gestion des employés (accessible uniquement aux admins)
Route::middleware(['auth', 'admin'])->prefix('users')->group(function () {
    Route::get('/', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('/create', [\App\Http\Controllers\UserController::class, 'create'])->name('users.create');
    Route::post('/', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    Route::get('/{user}/edit', [\App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
    Route::put('/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::delete('/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
});
