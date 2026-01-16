<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // 🧱 Liste des produits AVEC RECHERCHE ET REGROUPEMENT PAR LOT
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter');
        $sortBy = $request->input('sort_by', 'created_at');
        
        $query = Product::query();
        
        // Recherche
        if ($search) {
            $searchTerm = $search;
            
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('id', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('sale_price', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('purchase_price', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('stock', 'LIKE', "%{$searchTerm}%");
            });
        }
        
        // Filtres
        if ($filter) {
            switch ($filter) {
                case 'low_stock':
                    $query->where('stock', '<=', 10);
                    break;
                case 'out_of_stock':
                    $query->where('stock', '=', 0);
                    break;
                case 'available':
                    $query->where('stock', '>', 0);
                    break;
                case 'multiple_batches':
                    $query->whereHas('stockMovements', function($q) {
                        $q->where('type', 'entree')
                          ->select(DB::raw('COUNT(DISTINCT purchase_price) as batch_count'))
                          ->groupBy('product_id')
                          ->having('batch_count', '>', 1);
                    });
                    break;
            }
        }
        
        // Tri
        switch ($sortBy) {
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'stock':
                $query->orderBy('stock', 'asc');
                break;
            case 'sale_price':
                $query->orderBy('sale_price', 'asc');
                break;
            case 'profit_margin':
                $query->orderByRaw('((sale_price - purchase_price) / purchase_price * 100) DESC');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        $products = $query->paginate(10);
        
        // ============ CALCUL DES TOTAUX PAR LOT ============
        foreach ($products as $product) {
            $stockTotals = $product->getStockTotals();
            $product->stock_summary = $stockTotals;
            $product->has_multiple_batches = $product->hasMultipleBatches();
        }
        
        // ============ CALCUL DES STATISTIQUES ============
        
        // STATISTIQUES GLOBALES
        $totalProductsGlobal = Product::count();
        $totalStockGlobal = Product::sum('stock');
        $totalValueGlobal = Product::sum(DB::raw('sale_price * stock'));
        
        // STATISTIQUES AVEC MULTIPLES LOTS
        $productsWithMultipleBatches = Product::withMultipleBatches()->count();
        
        // STATISTIQUES FILTRÉES
        $totalStockFiltered = $products->sum('stock');
        $totalValueFiltered = $products->sum(function($product) {
            return ($product->sale_price ?? 0) * ($product->stock ?? 0);
        });
        
        return view('products.index', compact(
            'products',
            // Statistiques globales
            'totalProductsGlobal',
            'totalStockGlobal', 
            'totalValueGlobal',
            'productsWithMultipleBatches',
            // Statistiques filtrées
            'totalStockFiltered',
            'totalValueFiltered'
        ));
    }
    
    /**
     * Méthode pour la recherche seulement
     */
    public function search(Request $request)
    {
        return $this->index($request);
    }

    // 🆕 Page d'ajout
    public function create()
    {
        $categories = Category::all();
        $suppliers  = Supplier::all();

        return view('products.create', compact('categories', 'suppliers'));
    }

    // 💾 Enregistrement d'un nouveau produit AVEC GESTION DES DOUBLONS
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'stock'          => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price'     => 'required|numeric|min:0',
            'description'    => 'nullable|string|max:1000',
            'category_id'    => 'required|exists:categories,id',
            'supplier_id'    => 'required|exists:suppliers,id',
        ]);

        // Vérifier si un produit similaire existe déjà
        // On peut vérifier par nom + catégorie + fournisseur
        $existingProduct = Product::where('name', $request->name)
            ->where('category_id', $request->category_id)
            ->where('supplier_id', $request->supplier_id)
            ->first();

        DB::beginTransaction();
        try {
            if ($existingProduct) {
                // ✅ PRODUIT EXISTANT : On crée une NOUVELLE LIGNE avec le cumul
                $oldStock = $existingProduct->stock;
                $newStock = $oldStock + $request->stock;
                
                // Créer un nouveau produit comme "ligne cumulée"
                $cumulatedProduct = Product::create([
                    'name'           => $request->name,
                    'stock'          => $newStock, // Stock cumulé
                    'quantity'       => $newStock,
                    'purchase_price' => ($existingProduct->purchase_price + $request->purchase_price) / 2, // Prix moyen
                    'sale_price'     => ($existingProduct->sale_price + $request->sale_price) / 2, // Prix moyen
                    'description'    => $request->description ?? $existingProduct->description,
                    'category_id'    => $request->category_id,
                    'supplier_id'    => $request->supplier_id,
                    'parent_id'      => $existingProduct->id, // Lien vers le produit original
                    'is_cumulated'   => true, // Marquer comme ligne cumulée
                    'cumulated_from' => $existingProduct->id,
                    'batch_number'   => 'CUMUL-' . time() . '-' . Str::random(4),
                ]);
                
                // Enregistrer le mouvement pour le produit original
                $this->addStockMovementWithPrice(
                    $existingProduct,
                    'sortie',
                    $oldStock,
                    $existingProduct->purchase_price,
                    $existingProduct->sale_price,
                    'Transfert vers ligne cumulée',
                    'CUMUL-' . $cumulatedProduct->id
                );
                
                // Enregistrer le mouvement initial pour le nouveau produit cumulé
                $this->addStockMovementWithPrice(
                    $cumulatedProduct,
                    'entree',
                    $request->stock,
                    $request->purchase_price,
                    $request->sale_price,
                    'Stock initial (cumul)',
                    'INITIAL-CUMUL-' . $cumulatedProduct->id
                );
                
                // Si l'ancien produit avait du stock, l'ajouter au mouvement
                if ($oldStock > 0) {
                    $this->addStockMovementWithPrice(
                        $cumulatedProduct,
                        'entree',
                        $oldStock,
                        $existingProduct->purchase_price,
                        $existingProduct->sale_price,
                        'Ajout du stock existant',
                        'FROM-' . $existingProduct->id
                    );
                }
                
                // Mettre à jour l'ancien produit pour indiquer qu'il a été cumulé
                $existingProduct->update([
                    'has_been_cumulated' => true,
                    'cumulated_to' => $cumulatedProduct->id,
                    'stock' => 0, // Le stock a été transféré
                ]);
                
                $product = $cumulatedProduct;
                
                DB::commit();
                
                return redirect()->route('products.index')
                    ->with('success', 'Produit existant détecté. Une nouvelle ligne cumulée a été créée avec le stock total ✅');
                    
            } else {
                // ✅ NOUVEAU PRODUIT : Création normale
                $product = Product::create([
                    'name'           => $request->name,
                    'stock'          => $request->stock,
                    'quantity'       => $request->stock,
                    'purchase_price' => $request->purchase_price,
                    'sale_price'     => $request->sale_price,
                    'description'    => $request->description,
                    'category_id'    => $request->category_id,
                    'supplier_id'    => $request->supplier_id,
                    'is_cumulated'   => false,
                ]);

                // Enregistrer le mouvement initial avec prix
                if ($request->stock > 0) {
                    $this->addStockMovementWithPrice(
                        $product,
                        'entree',
                        $request->stock,
                        $request->purchase_price,
                        $request->sale_price,
                        'Stock initial',
                        'INITIAL-' . $product->id
                    );
                }
                
                DB::commit();
                
                return redirect()->route('products.index')
                    ->with('success', 'Nouveau produit ajouté avec succès ✅');
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'ajout du produit: ' . $e->getMessage())
                ->withInput();
        }
    }

    // 👁️ Détails d'un produit AVEC STOCKS GROUPÉS
    public function show(Product $product)
    {
        // Vérifier si ce produit a été cumulé vers un autre
        if ($product->cumulated_to) {
            $cumulatedProduct = Product::find($product->cumulated_to);
            if ($cumulatedProduct) {
                return redirect()->route('products.show', $cumulatedProduct)
                    ->with('info', 'Ce produit a été cumulé avec un autre. Affichage du produit cumulé.');
            }
        }
        
        // Calculer la quantité vendue
        $quantitySold = $product->quantity - $product->stock;
        
        // Récupérer les stocks groupés
        $stockTotals = $product->getStockTotals();
        $stockSummary = $product->getStockSummary();
        $stockByPrice = $product->getStockValueByPurchasePrice();
        $largestBatch = $product->getLargestBatch();
        $latestBatch = $product->getLatestBatch();
        $stockConsistency = $product->checkStockConsistency();
        
        // Récupérer les produits originaux si c'est un produit cumulé
        $originalProducts = [];
        if ($product->is_cumulated) {
            $originalProducts = Product::where('cumulated_to', $product->id)
                ->orWhere('parent_id', $product->id)
                ->get();
        }
        
        return view('products.show', compact(
            'product', 
            'quantitySold',
            'stockTotals',
            'stockSummary',
            'stockByPrice',
            'largestBatch',
            'latestBatch',
            'stockConsistency',
            'originalProducts'
        ));
    }

    // ✏️ Page d'édition
    public function edit(Product $product)
    {
        // Vérifier si le produit a été cumulé
        if ($product->has_been_cumulated && $product->cumulated_to) {
            return redirect()->route('products.show', $product)
                ->with('warning', 'Ce produit a été cumulé et ne peut plus être modifié directement.');
        }
        
        $categories = Category::all();
        $suppliers  = Supplier::all();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    // ✏️ Mise à jour (SIMPLIFIÉE)
    public function update(Request $request, Product $product)
    {
        // Vérifier si le produit a été cumulé
        if ($product->has_been_cumulated && $product->cumulated_to) {
            return redirect()->route('products.show', $product)
                ->with('warning', 'Ce produit a été cumulé et ne peut plus être modifié.');
        }
        
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price'     => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'description'    => 'nullable|string|max:1000',
            'category_id'    => 'required|exists:categories,id',
            'supplier_id'    => 'required|exists:suppliers,id',
        ]);
        
        $oldStock = $product->stock;
        
        // Synchroniser quantity avec stock
        $validated['quantity'] = $validated['stock'];
        
        // Si le stock a changé, enregistrer un mouvement d'ajustement
        if ($oldStock != $validated['stock']) {
            $difference = $validated['stock'] - $oldStock;
            $type = $difference > 0 ? 'entree' : 'sortie';
            
            $this->addStockMovementWithPrice(
                $product,
                $type,
                abs($difference),
                $validated['purchase_price'],
                $validated['sale_price'],
                'Ajustement via édition',
                'EDIT-' . $product->id
            );
        } else {
            // Même si le stock n'a pas changé, mettre à jour les prix dans le produit
            $product->update($validated);
        }
        
        return redirect()->route('products.index')->with('success', 'Produit mis à jour avec succès.');
    }

    // 🗑️ Suppression d'un produit
    public function destroy(Product $product)
    {
        // Vérifier si le produit a été cumulé
        if ($product->has_been_cumulated && $product->cumulated_to) {
            return redirect()->route('products.index')
                ->with('warning', 'Ce produit a été cumulé et ne peut pas être supprimé.');
        }
        
        // Vérifier si c'est un produit cumulé qui a des produits originaux
        if ($product->is_cumulated) {
            $originalCount = Product::where('cumulated_to', $product->id)
                ->orWhere('parent_id', $product->id)
                ->count();
            
            if ($originalCount > 0) {
                return redirect()->route('products.index')
                    ->with('warning', 'Ce produit cumulé contient d\'autres produits et ne peut pas être supprimé.');
            }
        }
        
        if ($product->stock < $product->quantity) {
            return redirect()->route('products.index')
                ->with('warning', 'Impossible de supprimer ce produit car des ventes sont associées.');
        }
        
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produit supprimé avec succès.');
    }

    // 📊 Rapport des produits AVEC STOCKS GROUPÉS
    public function productsReport()
    {
        // Exclure les produits qui ont été cumulés
        $products = Product::where('has_been_cumulated', false)
            ->with(['category', 'supplier'])
            ->orderBy('stock', 'asc')
            ->get();
        
        // Ajouter les informations de stocks groupés pour chaque produit
        foreach ($products as $product) {
            $product->stock_totals = $product->getStockTotals();
        }
        
        // Statistiques GLOBALES pour le rapport
        $reportData = [
            'total_products' => $products->count(),
            'total_stock_value' => $products->sum(fn($p) => $p->stock * $p->purchase_price),
            'total_sale_value' => $products->sum(fn($p) => $p->stock * $p->sale_price),
            'low_stock' => $products->where('stock', '<', 10)->count(),
            'out_of_stock' => $products->where('stock', '=', 0)->count(),
            'total_purchased' => $products->sum('stock'),
            'products_multiple_batches' => $products->filter(fn($p) => $p->hasMultipleBatches())->count(),
            'total_batches' => $products->sum(fn($p) => $p->getStockTotals()['number_of_batches']),
            'cumulated_products' => Product::where('is_cumulated', true)->count(),
        ];

        return view('reports.products', compact('products', 'reportData'));
    }

    // 📈 Statistiques rapides AVEC INFOS BATCHES
    public function getQuickStats()
    {
        $products = Product::where('has_been_cumulated', false)->get();
        $productsWithMultipleBatches = $products->filter(fn($p) => $p->hasMultipleBatches())->count();
        $totalBatches = $products->sum(fn($p) => $p->getStockTotals()['number_of_batches']);
        $cumulatedProductsCount = Product::where('is_cumulated', true)->count();
        
        return response()->json([
            'total_products' => $products->count(),
            'total_stock_value' => $products->sum(DB::raw('purchase_price * stock')),
            'total_sale_value' => $products->sum(DB::raw('sale_price * stock')),
            'low_stock_count' => $products->where('stock', '<', 10)->count(),
            'out_of_stock_count' => $products->where('stock', '=', 0)->count(),
            'total_stock' => $products->sum('stock'),
            'products_multiple_batches' => $productsWithMultipleBatches,
            'total_batches' => $totalBatches,
            'cumulated_products' => $cumulatedProductsCount,
        ]);
    }

    // ============================================
    // HISTORIQUE DES STOCKS - MISE À JOUR
    // ============================================

    /**
     * Afficher l'historique d'un produit
     */
    public function history(Product $product, Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'type' => 'nullable|in:entree,sortie',
            'per_page' => 'nullable|integer|min:5|max:100'
        ]);
        
        $query = $product->stockMovements()
            ->with('user:id,name')
            ->orderBy('created_at', 'desc');
        
        // Appliquer les filtres
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        // Pagination
        $perPage = $request->get('per_page', 20);
        $movements = $query->paginate($perPage);
        
        // Calculer les totaux
        $totals = $product->stockMovements()
            ->selectRaw('type, SUM(quantity) as total_quantity, COUNT(*) as count')
            ->when($request->filled('start_date'), function($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->start_date);
            })
            ->when($request->filled('end_date'), function($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->end_date);
            })
            ->groupBy('type')
            ->get()
            ->keyBy('type');
        
        // Récupérer les stocks groupés pour affichage
        $stockTotals = $product->getStockTotals();
        
        return view('products.history', compact('product', 'movements', 'totals', 'stockTotals'));
    }
    
    /**
     * Historique global (tous les produits)
     */
    public function globalHistory(Request $request)
    {
        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'type' => 'nullable|in:entree,sortie',
            'search' => 'nullable|string'
        ]);
        
        $query = StockMovement::with(['product:id,name', 'user:id,name'])
            ->orderBy('created_at', 'desc');
        
        // Filtres
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        if ($request->filled('search')) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%");
            });
        }
        
        $perPage = $request->get('per_page', 50);
        $movements = $query->paginate($perPage);
        
        // Statistiques
        $stats = StockMovement::selectRaw('
            COUNT(*) as total_movements,
            SUM(CASE WHEN type = "entree" THEN quantity ELSE 0 END) as total_entrees,
            SUM(CASE WHEN type = "sortie" THEN quantity ELSE 0 END) as total_sorties,
            AVG(purchase_price) as avg_purchase_price,
            AVG(sale_price) as avg_sale_price
        ')->when($request->filled('start_date'), function($q) use ($request) {
            $q->whereDate('created_at', '>=', $request->start_date);
        })
        ->when($request->filled('end_date'), function($q) use ($request) {
            $q->whereDate('created_at', '<=', $request->end_date);
        })->first();
        
        $products = Product::select('id', 'name')->get();
        
        return view('products.global-history', compact('movements', 'stats', 'products'));
    }
    
    /**
     * Rapport détaillé des stocks groupés par produit
     */
    public function groupedStocksReport(Request $request)
    {
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'sort_by' => 'nullable|in:name,total_value,batches_count'
        ]);
        
        // Exclure les produits cumulés et ceux qui ont été cumulés
        $query = Product::where('has_been_cumulated', false);
        
        // Filtres
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        
        // Appliquer le tri
        switch ($request->get('sort_by', 'name')) {
            case 'total_value':
                $query->orderByRaw('(sale_price * stock) DESC');
                break;
            case 'batches_count':
                // Tri complexe - on triera après
                break;
            default:
                $query->orderBy('name', 'asc');
                break;
        }
        
        $products = $query->get();
        
        // Préparer les données pour chaque produit
        $productsData = [];
        $totalGlobalValue = 0;
        $totalBatches = 0;
        
        foreach ($products as $product) {
            $stockTotals = $product->getStockTotals();
            $summary = $product->getStockSummary();
            
            $productsData[] = [
                'product' => $product,
                'summary' => $summary,
                'totals' => $stockTotals,
                'grouped_stocks' => $stockTotals['grouped_stocks'],
            ];
            
            $totalGlobalValue += $summary['total_value'];
            $totalBatches += $summary['batches_count'];
        }
        
        // Trier par nombre de batches si demandé
        if ($request->get('sort_by') == 'batches_count') {
            usort($productsData, function($a, $b) {
                return $b['summary']['batches_count'] <=> $a['summary']['batches_count'];
            });
        }
        
        // Récupérer aussi les produits cumulés séparément
        $cumulatedProducts = Product::where('is_cumulated', true)
            ->with(['stockMovements'])
            ->get();
        
        // Statistiques
        $reportStats = [
            'total_products' => count($productsData),
            'total_cumulated_products' => $cumulatedProducts->count(),
            'total_value' => $totalGlobalValue,
            'total_batches' => $totalBatches,
            'products_with_multiple_batches' => collect($productsData)->filter(fn($p) => $p['summary']['has_multiple_batches'])->count(),
            'average_batches_per_product' => $totalBatches > 0 ? round($totalBatches / count($productsData), 1) : 0,
        ];
        
        $categories = Category::all();
        $suppliers = Supplier::all();
        
        return view('reports.grouped-stocks', compact(
            'productsData', 
            'cumulatedProducts',
            'reportStats', 
            'categories', 
            'suppliers'
        ));
    }
    
    /**
     * Méthode privée pour ajouter un mouvement de stock AVEC PRIX
     */
    private function addStockMovementWithPrice(Product $product, $type, $quantity, $purchase_price, $sale_price, $motif = null, $reference = null)
    {
        // Vérifier le stock pour les sorties
        if ($type === 'sortie' && $product->stock < $quantity) {
            throw new \Exception("Stock insuffisant. Stock actuel: {$product->stock}");
        }
        
        // Calculer le nouveau stock
        $newStock = $type === 'entree' 
            ? $product->stock + $quantity 
            : $product->stock - $quantity;
        
        // Créer le mouvement avec les prix
        $movement = StockMovement::create([
            'product_id' => $product->id,
            'type' => $type,
            'quantity' => $quantity,
            'purchase_price' => $purchase_price,
            'sale_price' => $sale_price,
            'stock_after' => $newStock,
            'motif' => $motif,
            'reference_document' => $reference,
            'user_id' => auth()->id()
        ]);
        
        // Mettre à jour le stock du produit
        $product->update(['stock' => $newStock]);
        
        return $movement;
    }
    
    /**
     * Méthode privée pour ajouter un mouvement de stock (version héritée)
     */
    private function addStockMovement(Product $product, $type, $quantity, $motif = null, $reference = null)
    {
        return $this->addStockMovementWithPrice(
            $product,
            $type,
            $quantity,
            $product->purchase_price,
            $product->sale_price,
            $motif,
            $reference
        );
    }
    
    /**
     * Gestion manuelle du stock (ajustement)
     */
    public function stockAdjustment(Request $request, Product $product)
    {
        // Vérifier si le produit a été cumulé
        if ($product->has_been_cumulated && $product->cumulated_to) {
            return redirect()->route('products.show', $product)
                ->with('warning', 'Ce produit a été cumulé et ne peut plus être modifié.');
        }
        
        $request->validate([
            'adjustment_type' => 'required|in:add,remove,set',
            'amount' => 'required|integer|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'reason' => 'nullable|string|max:500',
            'reference_document' => 'nullable|string|max:100'
        ]);
        
        try {
            DB::transaction(function () use ($request, $product) {
                $oldStock = $product->stock;
                $quantity = $request->amount;
                $type = 'entree';
                $motif = '';
                
                // Utiliser les prix fournis ou ceux du produit
                $purchase_price = $request->filled('purchase_price') 
                    ? $request->purchase_price 
                    : $product->purchase_price;
                    
                $sale_price = $request->filled('sale_price') 
                    ? $request->sale_price 
                    : $product->sale_price;
                
                switch ($request->adjustment_type) {
                    case 'add':
                        $type = 'entree';
                        $motif = 'Ajustement positif: ' . ($request->reason ?? '');
                        break;
                        
                    case 'remove':
                        if ($oldStock < $quantity) {
                            throw new \Exception("Stock insuffisant. Disponible: {$oldStock}, À retirer: {$quantity}");
                        }
                        $type = 'sortie';
                        $motif = 'Ajustement négatif: ' . ($request->reason ?? '');
                        break;
                        
                    case 'set':
                        $difference = $quantity - $oldStock;
                        if ($difference > 0) {
                            $type = 'entree';
                            $motif = 'Ajustement (définition stock): ' . ($request->reason ?? '');
                            $quantity = $difference;
                        } elseif ($difference < 0) {
                            $type = 'sortie';
                            $motif = 'Ajustement (définition stock): ' . ($request->reason ?? '');
                            $quantity = abs($difference);
                        } else {
                            // Pas de changement
                            return;
                        }
                        break;
                }
                
                // Ajouter le mouvement avec prix
                $this->addStockMovementWithPrice(
                    $product,
                    $type,
                    $quantity,
                    $purchase_price,
                    $sale_price,
                    $motif,
                    $request->reference_document
                );
                
                // Mettre à jour les prix du produit si fournis
                if ($request->filled('purchase_price')) {
                    $product->update(['purchase_price' => $purchase_price]);
                }
                if ($request->filled('sale_price')) {
                    $product->update(['sale_price' => $sale_price]);
                }
            });
            
            return redirect()->route('products.index')
                ->with('success', "Stock ajusté avec succès : {$product->refresh()->stock}");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Réapprovisionnement AVEC GESTION DES PRIX
     */
    public function restock(Request $request, Product $product)
    {
        // Vérifier si le produit a été cumulé
        if ($product->has_been_cumulated && $product->cumulated_to) {
            return redirect()->route('products.show', $product)
                ->with('warning', 'Ce produit a été cumulé et ne peut plus être réapprovisionné.');
        }
        
        $request->validate([
            'amount' => 'required|integer|min:1',
            'purchase_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'motif' => 'nullable|string|max:500',
            'reference_document' => 'nullable|string|max:100'
        ]);
        
        DB::transaction(function () use ($request, $product) {
            $oldStock = $product->stock;
            
            // Utiliser les prix fournis ou ceux du produit
            $purchase_price = $request->filled('purchase_price') 
                ? $request->purchase_price 
                : $product->purchase_price;
                
            $sale_price = $request->filled('sale_price') 
                ? $request->sale_price 
                : $product->sale_price;
            
            // Ajouter le mouvement d'entrée avec prix
            $this->addStockMovementWithPrice(
                $product,
                'entree',
                $request->amount,
                $purchase_price,
                $sale_price,
                $request->motif ?? 'Réapprovisionnement',
                $request->reference_document
            );
            
            // Mettre à jour la quantité totale
            $product->increment('quantity', $request->amount);
            
            // Mettre à jour les prix si fournis
            if ($request->filled('purchase_price')) {
                $product->update(['purchase_price' => $purchase_price]);
            }
            if ($request->filled('sale_price')) {
                $product->update(['sale_price' => $sale_price]);
            }
            
            // Mettre à jour le fournisseur si fourni
            if ($request->filled('supplier_id')) {
                $product->update(['supplier_id' => $request->supplier_id]);
            }
        });
        
        return redirect()->route('products.index')
            ->with('success', "Réapprovisionnement réussi : +{$request->amount} unités");
    }
    
    /**
     * Vente rapide
     */
    public function quickSale(Request $request, Product $product)
    {
        // Vérifier si le produit a été cumulé
        if ($product->has_been_cumulated && $product->cumulated_to) {
            $cumulatedProduct = Product::find($product->cumulated_to);
            if ($cumulatedProduct) {
                return redirect()->route('products.show', $cumulatedProduct)
                    ->with('warning', 'Ce produit a été cumulé. Veuillez effectuer la vente sur le produit cumulé.');
            }
        }
        
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'client_name' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:100'
        ]);
        
        DB::transaction(function () use ($request, $product) {
            $oldStock = $product->stock;
            
            // Ajouter le mouvement de sortie
            $this->addStockMovement(
                $product,
                'sortie',
                $request->quantity,
                'Vente à ' . ($request->client_name ?? 'Client'),
                $request->reference
            );
        });
        
        return redirect()->route('products.history', $product)
            ->with('success', "Vente enregistrée : -{$request->quantity} unités");
    }
    
    /**
     * NOUVELLE : Fonction pour défaire un cumul
     */
    public function uncumulateProduct(Product $product)
    {
        // Vérifier si c'est un produit cumulé
        if (!$product->is_cumulated) {
            return redirect()->back()
                ->with('error', 'Ce produit n\'est pas un produit cumulé.');
        }
        
        DB::beginTransaction();
        try {
            // Trouver les produits originaux
            $originalProducts = Product::where('cumulated_to', $product->id)
                ->orWhere('parent_id', $product->id)
                ->get();
            
            if ($originalProducts->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'Aucun produit original trouvé pour ce cumul.');
            }
            
            // Restaurer chaque produit original
            foreach ($originalProducts as $original) {
                // Calculer combien de stock restituer (proportionnel)
                // Pour simplifier, on restitue le stock d'origine
                $originalStock = $original->getOriginal('stock') ?? 0;
                
                // Mettre à jour le produit original
                $original->update([
                    'stock' => $originalStock,
                    'has_been_cumulated' => false,
                    'cumulated_to' => null,
                ]);
                
                // Créer un mouvement de stock pour la restitution
                $this->addStockMovementWithPrice(
                    $original,
                    'entree',
                    $originalStock,
                    $original->purchase_price,
                    $original->sale_price,
                    'Restauration après dé-cumul',
                    'UNCUMUL-' . $product->id
                );
            }
            
            // Supprimer le produit cumulé
            $product->delete();
            
            DB::commit();
            
            return redirect()->route('products.index')
                ->with('success', 'Cumul défait avec succès. Les produits originaux ont été restaurés.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors du dé-cumul: ' . $e->getMessage());
        }
    }
    
    /**
     * NOUVELLE : Fusionner manuellement des produits
     */
    public function mergeProducts(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array|min:2',
            'product_ids.*' => 'exists:products,id',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);
        
        DB::beginTransaction();
        try {
            $products = Product::whereIn('id', $request->product_ids)
                ->where('has_been_cumulated', false)
                ->get();
            
            if ($products->count() < 2) {
                return redirect()->back()
                    ->with('error', 'Sélectionnez au moins 2 produits non-cumulés à fusionner.');
            }
            
            // Calculer les totaux
            $totalStock = $products->sum('stock');
            $avgPurchasePrice = $products->avg('purchase_price');
            $avgSalePrice = $products->avg('sale_price');
            $totalQuantity = $products->sum('quantity');
            
            // Créer le produit cumulé
            $cumulatedProduct = Product::create([
                'name' => $request->name,
                'stock' => $totalStock,
                'quantity' => $totalQuantity,
                'purchase_price' => $avgPurchasePrice,
                'sale_price' => $avgSalePrice,
                'description' => 'Produit fusionné de ' . $products->count() . ' produits',
                'category_id' => $request->category_id,
                'supplier_id' => $request->supplier_id,
                'is_cumulated' => true,
                'batch_number' => 'MERGE-' . time() . '-' . Str::random(4),
            ]);
            
            // Traiter chaque produit
            foreach ($products as $product) {
                // Transférer le stock
                if ($product->stock > 0) {
                    $this->addStockMovementWithPrice(
                        $product,
                        'sortie',
                        $product->stock,
                        $product->purchase_price,
                        $product->sale_price,
                        'Transfert vers produit fusionné',
                        'MERGE-' . $cumulatedProduct->id
                    );
                    
                    $this->addStockMovementWithPrice(
                        $cumulatedProduct,
                        'entree',
                        $product->stock,
                        $product->purchase_price,
                        $product->sale_price,
                        'Ajout depuis ' . $product->name,
                        'FROM-' . $product->id
                    );
                }
                
                // Marquer comme cumulé
                $product->update([
                    'has_been_cumulated' => true,
                    'cumulated_to' => $cumulatedProduct->id,
                    'stock' => 0,
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('products.show', $cumulatedProduct)
                ->with('success', $products->count() . ' produits fusionnés avec succès dans un nouveau produit cumulé.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de la fusion: ' . $e->getMessage())
                ->withInput();
        }
    }
}