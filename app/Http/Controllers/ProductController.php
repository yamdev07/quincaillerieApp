<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // 🧱 Liste des produits AVEC RECHERCHE
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
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        $products = $query->paginate(10);
        
        // Calcul des statistiques
        $totalStock = $products->sum('stock');
        $totalValue = $products->sum(function($product) {
            return ($product->sale_price ?? 0) * ($product->stock ?? 0);
        });
        
        return view('products.index', compact('products', 'totalStock', 'totalValue'));
    }
    
    /**
     * Méthode pour la recherche seulement (si vous voulez séparer)
     * Dans ce cas, modifiez votre formulaire pour utiliser cette route
     */
    public function search(Request $request)
    {
        // Appelle simplement la méthode index
        return $this->index($request);
    }

    // ... (le reste de vos méthodes reste inchangé)

    // 🆕 Page d'ajout
    public function create()
    {
        $categories = Category::all();
        $suppliers  = Supplier::all();

        return view('products.create', compact('categories', 'suppliers'));
    }

    // 💾 Enregistrement d'un nouveau produit
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'quantity'       => 'required|integer|min:0', // Quantité totale achetée
            'purchase_price' => 'required|numeric|min:0',
            'sale_price'     => 'required|numeric|min:0',
            'description'    => 'nullable|string|max:1000',
            'category_id'    => 'required|exists:categories,id',
            'supplier_id'    => 'required|exists:suppliers,id',
        ]);

        // Créer le produit avec les deux champs synchronisés
        Product::create([
            'name'           => $request->name,
            'quantity'       => $request->quantity, // Quantité totale achetée (historique)
            'stock'          => $request->quantity, // Stock initial = tout ce qui a été acheté
            'purchase_price' => $request->purchase_price,
            'sale_price'     => $request->sale_price,
            'description'    => $request->description,
            'category_id'    => $request->category_id,
            'supplier_id'    => $request->supplier_id,
        ]);

        return redirect()->route('products.index')->with('success', 'Produit ajouté avec succès ✅');
    }

    // 👁️ Détails d'un produit
    public function show(Product $product)
    {
        // Calculer la quantité vendue (optionnel)
        $quantitySold = $product->quantity - $product->stock;
        
        return view('products.show', compact('product', 'quantitySold'));
    }

    // ✏️ Page d'édition
    public function edit(Product $product)
    {
        $categories = Category::all();
        $suppliers  = Supplier::all();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    // ✏️ Mise à jour
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price'     => 'required|numeric|min:0',
            'quantity'       => 'required|integer|min:0', // Nouvelle quantité totale achetée
            'description'    => 'nullable|string|max:1000',
            'category_id'    => 'required|exists:categories,id',
            'supplier_id'    => 'required|exists:suppliers,id',
        ]);
        
        // Calculer la différence d'achat
        $purchaseDifference = $validated['quantity'] - $product->quantity;
        
        // Ajuster le stock disponible en conséquence
        // Si on achète plus (+), on ajoute au stock
        // Si on ajuste la quantité totale (-), on ne touche pas au stock disponible
        $validated['stock'] = max(0, $product->stock + max(0, $purchaseDifference));
        
        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produit mis à jour avec succès.');
    }

    // 🗑️ Suppression d'un produit
    public function destroy(Product $product)
    {
        // Vérifier si le produit a été vendu (stock < quantity)
        if ($product->stock < $product->quantity) {
            return redirect()->route('products.index')
                ->with('warning', 'Impossible de supprimer ce produit car des ventes sont associées.');
        }
        
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produit supprimé avec succès.');
    }

    // 📊 Rapport des produits (optionnel)
    public function productsReport()
    {
        $products = Product::with(['category', 'supplier'])
                          ->orderBy('stock', 'asc') // Trier par stock disponible
                          ->get();
        
        $reportData = [
            'total_products' => $products->count(),
            'total_stock_value' => $products->sum(fn($p) => $p->stock * $p->purchase_price),
            'low_stock' => $products->where('stock', '<', 5)->count(),
            'out_of_stock' => $products->where('stock', '=', 0)->count(),
            'total_purchased' => $products->sum('quantity'), // Quantité totale achetée
            'total_sold' => $products->sum(fn($p) => $p->quantity - $p->stock), // Quantité vendue
        ];

        return view('reports.products', compact('products', 'reportData'));
    }

    // 📦 Gestion manuelle du stock (ajustement)
    public function stockAdjustment(Request $request, Product $product)
    {
        $request->validate([
            'adjustment_type' => 'required|in:add,remove,set',
            'amount' => 'required|integer|min:0',
            'reason' => 'nullable|string|max:500',
        ]);
        
        DB::transaction(function () use ($request, $product) {
            $oldStock = $product->stock;
            
            switch ($request->adjustment_type) {
                case 'add':
                    $newStock = $oldStock + $request->amount;
                    // Ajuster aussi la quantité totale
                    $product->increment('quantity', $request->amount);
                    break;
                    
                case 'remove':
                    if ($oldStock < $request->amount) {
                        throw new \Exception("Stock insuffisant. Disponible: {$oldStock}, À retirer: {$request->amount}");
                    }
                    $newStock = $oldStock - $request->amount;
                    break;
                    
                case 'set':
                    $newStock = $request->amount;
                    // Ajuster la quantité totale si nécessaire
                    if ($request->amount > $product->quantity) {
                        $product->quantity = $request->amount;
                        $product->save();
                    }
                    break;
            }
            
            $product->update(['stock' => $newStock]);
            
            // Historique des ajustements (optionnel)
            // StockAdjustment::create([...]);
        });
        
        return redirect()->route('products.index')
            ->with('success', "Stock ajusté avec succès : {$oldStock} → {$product->stock}");
    }

    // 🔄 Réapprovisionnement
    public function restock(Request $request, Product $product)
    {
        $request->validate([
            'amount' => 'required|integer|min:1',
            'purchase_price' => 'nullable|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);
        
        DB::transaction(function () use ($request, $product) {
            $oldStock = $product->stock;
            $oldQuantity = $product->quantity;
            
            // Ajouter au stock disponible
            $product->increment('stock', $request->amount);
            
            // Ajouter à la quantité totale achetée
            $product->increment('quantity', $request->amount);
            
            // Mettre à jour le prix d'achat si fourni
            if ($request->filled('purchase_price')) {
                $product->update(['purchase_price' => $request->purchase_price]);
            }
            
            // Mettre à jour le fournisseur si fourni
            if ($request->filled('supplier_id')) {
                $product->update(['supplier_id' => $request->supplier_id]);
            }
            
            // Historique de réapprovisionnement (optionnel)
            // RestockHistory::create([...]);
        });
        
        return redirect()->route('products.index')
            ->with('success', "Réapprovisionnement réussi : +{$request->amount} unités");
    }

    // 📈 Statistiques rapides (pour dashboard)
    public function getQuickStats()
    {
        return response()->json([
            'total_products' => Product::count(),
            'total_stock_value' => Product::sum(DB::raw('purchase_price * stock')),
            'low_stock_count' => Product::where('stock', '<', 5)->count(),
            'out_of_stock_count' => Product::where('stock', '=', 0)->count(),
            'total_quantity_purchased' => Product::sum('quantity'),
            'total_quantity_sold' => Product::sum(DB::raw('quantity - stock')),
        ]);
    }
}