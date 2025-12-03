<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    // -------------------------
    // ROUTES PUBLIQUES
    // -------------------------

    // Affiche toutes les catégories (lecture seule)
    public function index()
    {
        $categories = Category::withCount(['products as active_products' => function($query) {
            $query->where('stock', '>', 0);
        }])
        ->withSum('products as total_stock', 'stock')
        ->withSum('products as total_value', DB::raw('products.stock * products.purchase_price'))
        ->orderBy('name')
        ->get();
        
        return view('categories.index', compact('categories'));
    }

    // Affiche une catégorie spécifique (lecture seule)
    public function show($id)
    {
        // Charger la catégorie avec ses produits et statistiques
        $category = Category::with(['products' => function($query) {
            $query->orderBy('name')->with('supplier');
        }])->findOrFail($id);
        
        // Toutes les catégories pour le menu/sidebar
        $categories = Category::orderBy('name')->get();
        
        // Calculer les statistiques détaillées
        $stats = [
            'total_products' => $category->products->count(),
            'total_stock' => $category->products->sum('stock'),
            'total_value' => $category->products->sum(function($product) {
                return $product->stock * $product->purchase_price;
            }),
            'potential_revenue' => $category->products->sum(function($product) {
                return $product->stock * $product->sale_price;
            }),
            'low_stock' => $category->products->where('stock', '<=', 5)->where('stock', '>', 0)->count(),
            'out_of_stock' => $category->products->where('stock', '=', 0)->count(),
            'in_stock' => $category->products->where('stock', '>', 5)->count(),
        ];
        
        // Produits à faible stock dans cette catégorie
        $lowStockProducts = $category->products()
            ->where('stock', '<=', 5)
            ->where('stock', '>', 0)
            ->orderBy('stock')
            ->limit(10)
            ->get();
        
        return view('categories.show', compact('category', 'categories', 'stats', 'lowStockProducts'));
    }

    // -------------------------
    // ROUTES ADMIN
    // -------------------------

    // Formulaire pour créer une nouvelle catégorie
    public function create()
    {
        $categories = Category::orderBy('name')->get(); // Pour d'éventuels menus
        return view('categories.create', compact('categories'));
    }

    // Enregistre une nouvelle catégorie
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:7|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description ?? null,
            'color' => $request->color ?? '#6b7280', // Couleur par défaut gris
            'icon' => $request->icon ?? 'bi-folder',
        ]);

        return redirect()->route('categories.index')->with('success', 'Catégorie créée avec succès.');
    }

    // Formulaire pour éditer une catégorie existante
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::where('id', '!=', $id)->orderBy('name')->get(); // Toutes sauf celle en cours
        return view('categories.edit', compact('category', 'categories'));
    }

    // Met à jour une catégorie existante
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:7|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description ?? null,
            'color' => $request->color ?? $category->color,
            'icon' => $request->icon ?? $category->icon,
        ]);

        return redirect()->route('categories.index')->with('success', 'Catégorie mise à jour avec succès.');
    }

    // Supprime une catégorie (avec vérification)
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Vérifier si la catégorie contient des produits
        $productCount = $category->products()->count();
        
        if ($productCount > 0) {
            return redirect()->route('categories.index')
                ->with('warning', "Impossible de supprimer cette catégorie car elle contient {$productCount} produit(s).");
        }
        
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Catégorie supprimée avec succès.');
    }

    // -------------------------
    // MÉTHODES AJAX/API
    // -------------------------

    // API: Liste des catégories pour select
    public function apiIndex()
    {
        $categories = Category::select('id', 'name')
            ->orderBy('name')
            ->get();
            
        return response()->json($categories);
    }

    // API: Statistiques des catégories
    public function stats()
    {
        $stats = Category::withCount('products')
            ->withSum('products as total_stock', 'stock')
            ->withSum('products as total_value', DB::raw('products.stock * products.purchase_price'))
            ->orderBy('products_count', 'desc')
            ->limit(10)
            ->get();
            
        return response()->json($stats);
    }

    // Transfert des produits vers une autre catégorie avant suppression
    public function transferProducts(Request $request, $id)
    {
        $request->validate([
            'new_category_id' => 'required|exists:categories,id',
        ]);
        
        $category = Category::findOrFail($id);
        $newCategory = Category::findOrFail($request->new_category_id);
        
        DB::transaction(function () use ($category, $newCategory) {
            // Transférer tous les produits
            $category->products()->update(['category_id' => $newCategory->id]);
            
            // Supprimer la catégorie
            $category->delete();
        });
        
        return redirect()->route('categories.index')
            ->with('success', "Tous les produits ont été transférés vers '{$newCategory->name}' et la catégorie a été supprimée.");
    }

    // Fusionner deux catégories
    public function merge(Request $request)
    {
        $request->validate([
            'source_category_id' => 'required|exists:categories,id',
            'target_category_id' => 'required|exists:categories,id|different:source_category_id',
        ]);
        
        $sourceCategory = Category::findOrFail($request->source_category_id);
        $targetCategory = Category::findOrFail($request->target_category_id);
        
        DB::transaction(function () use ($sourceCategory, $targetCategory) {
            // Transférer tous les produits
            $sourceCategory->products()->update(['category_id' => $targetCategory->id]);
            
            // Optionnel: fusionner la description
            if (empty($targetCategory->description) && !empty($sourceCategory->description)) {
                $targetCategory->update([
                    'description' => $sourceCategory->description
                ]);
            }
            
            // Supprimer la catégorie source
            $sourceCategory->delete();
        });
        
        return redirect()->route('categories.index')
            ->with('success', "Catégories fusionnées avec succès. Tous les produits ont été déplacés vers '{$targetCategory->name}'.");
    }

    // Réassigner les produits sans catégorie
    public function reassignOrphanProducts(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
        ]);
        
        $category = Category::findOrFail($request->category_id);
        $orphanCount = Product::whereNull('category_id')->count();
        
        if ($orphanCount === 0) {
            return redirect()->route('categories.index')
                ->with('info', 'Aucun produit sans catégorie trouvé.');
        }
        
        Product::whereNull('category_id')->update(['category_id' => $category->id]);
        
        return redirect()->route('categories.index')
            ->with('success', "{$orphanCount} produit(s) sans catégorie ont été assignés à '{$category->name}'.");
    }
}