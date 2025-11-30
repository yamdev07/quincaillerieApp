<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

// Route de test
Route::view('/welcome', 'welcome');

// Tableau de bord principal
Route::get('/', [SaleController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profil utilisateur
Route::view('/profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Auth routes
require __DIR__ . '/auth.php';

// ======================
// Routes protégées par authentification
// ======================
Route::middleware(['auth'])->group(function () {

    // ----------------------
    // VENTES
    // ----------------------
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

    // ----------------------
    // CLIENTS
    // ----------------------
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

    // ----------------------
    // FOURNISSEURS
    // ----------------------
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

    // ----------------------
    // PRODUITS - Lecture seule pour tous les utilisateurs
    // ----------------------
    Route::prefix('products')->group(function () {

        // Liste et recherche
        Route::get('/', [ProductController::class, 'index'])->name('products.index');
        Route::get('/category/{category}', [ProductController::class, 'byCategory'])->name('products.byCategory');
        Route::get('/search', [ProductController::class, 'search'])->name('products.search');

        // Affichage d'un produit spécifique (conflit évité avec whereNumber)
        Route::get('/{product}', [ProductController::class, 'show'])
             ->whereNumber('product')
             ->name('products.show');
    });

    // ----------------------
    // RAPPORTS ET STATISTIQUES
    // ----------------------
    Route::prefix('reports')->group(function () {
        Route::get('/sales', [SaleController::class, 'salesReport'])->name('reports.sales');
        Route::get('/clients', [ClientController::class, 'clientsReport'])->name('reports.clients');
        Route::get('/products', [ProductController::class, 'productsReport'])->name('reports.products');
        Route::get('/inventory', [ProductController::class, 'inventoryReport'])->name('reports.inventory');
    });

    // ----------------------
    // API TEMPS RÉEL
    // ----------------------
    Route::prefix('api')->group(function () {
        Route::get('/dashboard-stats', [SaleController::class, 'dashboardStats'])->name('api.dashboard.stats');
        Route::get('/recent-sales', [SaleController::class, 'recentSales'])->name('api.recent.sales');
        Route::get('/top-products', [ProductController::class, 'topProducts'])->name('api.top.products');
    });
});

// ======================
// CRUD produits pour les admins seulement
// ======================
Route::middleware(['auth', 'adminmiddleware'])->prefix('products')->group(function () {
    Route::get('/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/', [ProductController::class, 'store'])->name('products.store');
    Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/{product}/stock', [ProductController::class, 'stock'])->name('products.stock');
    Route::post('/{product}/stock', [ProductController::class, 'updateStock'])->name('products.stock.update');
});

// ======================
// Admin Dashboard + Gestion utilisateurs
// ======================
Route::middleware(['auth', 'adminmiddleware'])->prefix('admin')->group(function () {

    // Dashboard admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Gestion des utilisateurs
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

// Dashboard sécurisé
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');
