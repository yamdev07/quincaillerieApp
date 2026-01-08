<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Client;

// Route de test
Route::view('/welcome', 'welcome');

// ======================
// Routes AJAX pour le dashboard
// ======================
Route::middleware(['auth'])->prefix('ajax')->group(function () {
    
    Route::get('/dashboard/chart-data', function (Request $request) {
        try {
            $period = (int) $request->query('period', 7);
            $dates = [];
            $totals = [];
            
            for ($i = $period - 1; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dates[] = $date->format('d/m');
                
                // Vérifier si le modèle Sale existe
                if (class_exists(Sale::class)) {
                    $total = Sale::whereDate('created_at', $date->format('Y-m-d'))->sum('total_price');
                    $totals[] = (int) $total;
                } else {
                    // Données de test si le modèle n'existe pas
                    $totals[] = rand(100000, 500000);
                }
            }
            
            return response()->json([
                'dates' => $dates,
                'totals' => $totals
            ]);
            
        } catch (\Exception $e) {
            // Données de secours en cas d'erreur
            $dates = [];
            $totals = [];
            $period = (int) ($request->query('period') ?? 7);
            
            for ($i = $period - 1; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dates[] = $date->format('d/m');
                $totals[] = rand(100000, 500000);
            }
            
            return response()->json([
                'dates' => $dates,
                'totals' => $totals,
                'error' => $e->getMessage()
            ]);
        }
    })->name('ajax.dashboard.chart');
    
    Route::get('/dashboard/stats', function () {
        try {
            $today = Carbon::today();
            
            // Vérifier si les modèles existent
            $salesToday = class_exists(Sale::class) 
                ? Sale::whereDate('created_at', $today)->count() 
                : rand(5, 50);
                
            $totalRevenue = class_exists(Sale::class)
                ? Sale::whereDate('created_at', $today)->sum('total_price')
                : rand(500000, 2000000);
                
            $lowStockCount = class_exists(Product::class)
                ? Product::where('stock', '<', 10)->count()
                : rand(0, 5);
                
            $activeClients = class_exists(Client::class)
                ? Client::where('created_at', '>=', Carbon::now()->startOfMonth())->count()
                : rand(10, 100);
                
            $averageSale = $salesToday > 0 ? $totalRevenue / $salesToday : 0;
            
            return response()->json([
                'salesToday' => $salesToday,
                'totalRevenue' => $totalRevenue,
                'lowStockCount' => $lowStockCount,
                'activeClients' => $activeClients,
                'averageSale' => round($averageSale, 2)
            ]);
            
        } catch (\Exception $e) {
            // Données de secours
            return response()->json([
                'salesToday' => rand(5, 50),
                'totalRevenue' => rand(500000, 2000000),
                'lowStockCount' => rand(0, 5),
                'activeClients' => rand(10, 100),
                'averageSale' => rand(10000, 50000),
                'error' => $e->getMessage()
            ]);
        }
    })->name('ajax.dashboard.stats');
    
    Route::get('/dashboard/recent-sales', function () {
        try {
            $recentSales = [];
            
            if (class_exists(Sale::class)) {
                $recentSales = Sale::with(['product', 'client'])
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(function ($sale) {
                        return [
                            'product_name' => $sale->product->name ?? 'Produit inconnu',
                            'client_name' => $sale->client->name ?? 'Client inconnu',
                            'total_price' => $sale->total_price,
                            'created_at' => $sale->created_at->toISOString()
                        ];
                    })->toArray();
            }
            
            // Si pas de données ou erreur, retourner des données de test
            if (empty($recentSales)) {
                $productNames = ['Marteau', 'Clou 3"', 'Vis à bois', 'Scie circulaire', 'Perceuse'];
                $clientNames = ['Jean Dupont', 'Marie Martin', 'Pierre Durand', 'Sophie Lambert'];
                
                for ($i = 0; $i < 5; $i++) {
                    $recentSales[] = [
                        'product_name' => $productNames[array_rand($productNames)],
                        'client_name' => $clientNames[array_rand($clientNames)],
                        'total_price' => rand(5000, 50000),
                        'created_at' => Carbon::now()->subHours(rand(1, 24))->toISOString()
                    ];
                }
            }
            
            return response()->json($recentSales);
            
        } catch (\Exception $e) {
            return response()->json([]);
        }
    })->name('ajax.dashboard.recent-sales');
    
    Route::get('/dashboard/low-stock', function () {
        try {
            $lowStockProducts = [];

            if (class_exists(Product::class)) {
                $lowStockProducts = Product::where('stock', '<', 10)
                    ->orderBy('stock', 'asc')
                    ->limit(10)
                    ->get()
                    ->map(function ($product) {
                        return [
                            'name' => $product->name,
                            'stock' => $product->stock,
                            'sale_price' => $product->sale_price ?? 0
                        ];
                    })->toArray();
            }

            // Données de test si pas de produits
            if (empty($lowStockProducts)) {
                $productNames = ['Clou 2"', 'Vis à métal', 'Ampoule LED', 'Interrupteur'];
                for ($i = 0; $i < 3; $i++) {
                    $lowStockProducts[] = [
                        'name' => $productNames[array_rand($productNames)],
                        'stock' => rand(1, 9),
                        'sale_price' => rand(500, 5000)
                    ];
                }
            }

            return response()->json($lowStockProducts);

        } catch (\Exception $e) {
            return response()->json([]);
        }
    })->name('ajax.dashboard.low-stock');

});

// ======================
// IMPORTANT: D'ABORD les routes ADMIN (CRUD complet)
// Doivent être DÉFINIES AVANT les routes publiques
// ======================

// 1. CRUD catégories pour les admins seulement
Route::middleware(['auth', 'adminmiddleware'])->prefix('categories')->group(function () {
    Route::get('/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

// 2. CRUD produits pour les admins seulement
Route::middleware(['auth', 'adminmiddleware'])->prefix('products')->group(function () {
    Route::get('/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/', [ProductController::class, 'store'])->name('products.store');
    Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/{product}/stock', [ProductController::class, 'stock'])->name('products.stock');
    Route::post('/{product}/stock', [ProductController::class, 'updateStock'])->name('products.stock.update');
});

// 3. Admin Dashboard + Gestion utilisateurs
Route::middleware(['auth', 'adminmiddleware'])->prefix('admin')->group(function () {
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

// ======================
// Routes protégées par authentification (pour tous les utilisateurs)
// ======================
Route::middleware(['auth'])->group(function () {

    // ----------------------
    // TABLEAU DE BORD PRINCIPAL
    // ----------------------
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.alt');

    // ----------------------
    // VENTES (avec invoice)
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
        Route::get('/', [ProductController::class, 'index'])->name('products.index');
        Route::get('/category/{category}', [ProductController::class, 'byCategory'])->name('products.byCategory');
        Route::get('/search', [ProductController::class, 'search'])->name('products.search');
        Route::get('/{product}', [ProductController::class, 'show'])->whereNumber('product')->name('products.show');
    });

    // ----------------------
    // CATEGORIES - Lecture seule pour tous les utilisateurs
    // ----------------------
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/{category}', [CategoryController::class, 'show'])->name('categories.show');
    });

    // -----------------------
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
    
    // ----------------------
    // PROFIL UTILISATEUR
    // ----------------------
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
});

// Auth routes
require __DIR__ . '/auth.php';