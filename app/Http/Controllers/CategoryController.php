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

    // Affiche toutes les catégories principales avec leurs sous-catégories
    public function index()
    {
        $categories = Category::with(['children' => function($query) {
                $query->withCount('products')
                      ->orderBy('name');
            }])
            ->withCount(['products as total_products'])
            ->withSum('products as total_stock', 'stock')
            ->whereNull('parent_id') // Seulement les catégories principales
            ->orderBy('name')
            ->get();
        
        return view('categories.index', compact('categories'));
    }

    // Affiche une catégorie spécifique avec ses sous-catégories et produits
    public function show($id)
    {
        $category = Category::with(['children' => function($query) {
                $query->withCount('products')
                      ->orderBy('name');
            }])
            ->with(['products' => function($query) {
                $query->orderBy('name')->with('supplier');
            }])
            ->findOrFail($id);
        
        // Toutes les catégories principales pour les menus
        $mainCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $id)
            ->orderBy('name')
            ->get();
        
        // Calculer les statistiques (incluant les sous-catégories)
        $allProducts = $category->getAllProducts();
        $stats = [
            'total_products' => $allProducts->count(),
            'total_subcategories' => $category->children->count(),
            'total_stock' => $allProducts->sum('stock'),
            'total_value' => $allProducts->sum(function($product) {
                return $product->stock * $product->purchase_price;
            }),
            'potential_revenue' => $allProducts->sum(function($product) {
                return $product->stock * $product->sale_price;
            }),
            'low_stock' => $allProducts->where('stock', '<=', 5)->where('stock', '>', 0)->count(),
            'out_of_stock' => $allProducts->where('stock', '=', 0)->count(),
            'in_stock' => $allProducts->where('stock', '>', 5)->count(),
            'direct_products' => $category->products->count(), // Produits directs seulement
        ];
        
        // Produits à faible stock (incluant les sous-catégories)
        $lowStockProducts = $allProducts->where('stock', '<=', 5)
            ->sortBy('stock')
            ->take(10);
        
        return view('categories.show', compact('category', 'mainCategories', 'stats', 'lowStockProducts'));
    }

    // -------------------------
    // ROUTES ADMIN
    // -------------------------

    // Formulaire pour créer une nouvelle catégorie
    public function create()
    {
        $mainCategories = Category::whereNull('parent_id')
            ->orderBy('name')
            ->get();
        return view('categories.create', compact('mainCategories'));
    }

    // Enregistre une nouvelle catégorie
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
        ]);

        Category::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id, // null pour catégorie principale
            'description' => $request->description,
        ]);

        $message = $request->parent_id 
            ? 'Sous-catégorie créée avec succès.'
            : 'Catégorie principale créée avec succès.';

        return redirect()->route('categories.index')->with('success', $message);
    }

    // Formulaire pour éditer une catégorie existante
    public function edit($id)
    {
        $category = Category::with('parent')->findOrFail($id);
        
        // Récupérer toutes les catégories principales (sauf elle-même et ses enfants)
        $mainCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $id)
            ->whereNotIn('id', function($query) use ($id) {
                $query->select('id')
                      ->from('categories')
                      ->where('parent_id', $id);
            })
            ->orderBy('name')
            ->get();
        
        return view('categories.edit', compact('category', 'mainCategories'));
    }

    // Met à jour une catégorie existante
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'parent_id' => 'nullable|exists:categories,id|different:id', // Ne pas s'auto-référencer
            'description' => 'nullable|string|max:1000',
        ]);

        // Empêcher de créer une boucle dans l'arborescence
        if ($request->parent_id) {
            $potentialParent = Category::find($request->parent_id);
            if ($this->isDescendant($potentialParent, $category)) {
                return redirect()->back()
                    ->withErrors(['parent_id' => 'Impossible de sélectionner une sous-catégorie comme parent.'])
                    ->withInput();
            }
        }

        $category->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'description' => $request->description,
        ]);

        return redirect()->route('categories.index')->with('success', 'Catégorie mise à jour avec succès.');
    }

    // Vérifie si une catégorie est descendante d'une autre (pour éviter les boucles)
    private function isDescendant($parent, $child)
    {
        if (!$parent || !$child) return false;
        
        $current = $child->parent;
        while ($current) {
            if ($current->id === $parent->id) {
                return true;
            }
            $current = $current->parent;
        }
        
        return false;
    }

    // Supprime une catégorie
    public function destroy($id)
    {
        $category = Category::withCount(['products', 'children'])->findOrFail($id);
        
        // Vérifier si la catégorie peut être supprimée
        if ($category->products_count > 0) {
            return redirect()->route('categories.index')
                ->with('warning', 
                    "Impossible de supprimer cette catégorie car elle contient {$category->products_count} produit(s)."
                );
        }
        
        // Transférer les sous-catégories au parent ou les rendre principales
        if ($category->children_count > 0) {
            if ($category->parent_id) {
                // Transférer les sous-catégories au parent
                $category->children()->update(['parent_id' => $category->parent_id]);
            } else {
                // Rendre les sous-catégories principales
                $category->children()->update(['parent_id' => null]);
            }
        }
        
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }

    // Supprime une catégorie et transfère ses produits et sous-catégories
    public function destroyWithTransfer(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $request->validate([
            'new_category_id' => 'required|exists:categories,id|different:id',
            'move_subcategories' => 'sometimes|boolean',
        ]);
        
        DB::transaction(function () use ($category, $request) {
            // Transférer les produits vers la nouvelle catégorie
            $category->products()->update(['category_id' => $request->new_category_id]);
            
            // Transférer les sous-catégories si demandé
            if ($request->move_subcategories) {
                $category->children()->update(['parent_id' => $request->new_category_id]);
            }
            
            // Supprimer la catégorie
            $category->delete();
        });
        
        $newCategory = Category::find($request->new_category_id);
        
        return redirect()->route('categories.index')
            ->with('success', "Catégorie supprimée. Tous les produits ont été transférés vers '{$newCategory->name}'.");
    }

    // -------------------------
    // ROUTES PRODUITS
    // -------------------------

    // Ajouter un produit à une catégorie
    public function addProduct(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        
        $product = Product::find($request->product_id);
        $product->category_id = $category->id;
        $product->save();
        
        return redirect()->route('categories.show', $category->id)
            ->with('success', "Le produit '{$product->name}' a été ajouté à la catégorie.");
    }

    // Transférer un produit vers une autre catégorie
    public function transferProduct(Request $request, $id, $productId)
    {
        $category = Category::findOrFail($id);
        $product = Product::findOrFail($productId);
        
        $request->validate([
            'target_category_id' => 'required|exists:categories,id|different:' . $id,
        ]);
        
        $targetCategory = Category::find($request->target_category_id);
        $product->category_id = $targetCategory->id;
        $product->save();
        
        return redirect()->route('categories.show', $category->id)
            ->with('success', "Le produit '{$product->name}' a été transféré vers '{$targetCategory->name}'.");
    }

    // Voir tous les produits d'une catégorie (avec pagination)
    public function products($id)
    {
        $category = Category::with(['products' => function($query) {
            $query->with('supplier')->orderBy('name')->paginate(20);
        }])->findOrFail($id);
        
        $allProducts = $category->getAllProducts();
        
        return view('categories.products', compact('category', 'allProducts'));
    }

    // Statistiques avancées de la catégorie
    public function detailedStats($id)
    {
        $category = Category::with(['products', 'children.products'])->findOrFail($id);
        
        $allProducts = $category->getAllProducts();
        
        $stats = [
            'total_products' => $allProducts->count(),
            'direct_products' => $category->products->count(),
            'subcategory_products' => $allProducts->count() - $category->products->count(),
            'by_subcategory' => $category->children->mapWithKeys(function($child) {
                return [$child->name => $child->getAllProducts()->count()];
            })->toArray(),
            'stock_distribution' => [
                'out_of_stock' => $allProducts->where('stock', 0)->count(),
                'low_stock' => $allProducts->whereBetween('stock', [1, 5])->count(),
                'medium_stock' => $allProducts->whereBetween('stock', [6, 20])->count(),
                'high_stock' => $allProducts->where('stock', '>', 20)->count(),
            ],
            'value_by_subcategory' => $category->children->mapWithKeys(function($child) {
                $products = $child->getAllProducts();
                $value = $products->sum(function($product) {
                    return $product->stock * $product->purchase_price;
                });
                return [$child->name => $value];
            })->toArray(),
        ];
        
        return response()->json($stats);
    }
}