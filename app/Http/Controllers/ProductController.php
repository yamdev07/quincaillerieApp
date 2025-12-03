<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // 🧱 Liste des produits
    public function index()
    {
        $products = Product::with(['category', 'supplier'])->latest()->paginate(10);

        // ICI: Utilisez quantity au lieu de stock
        $totalStock = Product::sum('quantity');
        $totalValue = Product::sum(DB::raw('purchase_price * quantity'));
        $lowStockProducts = Product::where('quantity', '<=', 10)->get();

        return view('products.index', compact('products', 'totalStock', 'totalValue', 'lowStockProducts'));
    }

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
            'quantity'       => 'required|integer|min:0', // ICI: quantity au lieu de stock
            'purchase_price' => 'required|numeric|min:0',
            'sale_price'     => 'required|numeric|min:0',
            'description'    => 'nullable|string|max:1000',
            'category_id'    => 'required|exists:categories,id',
            'supplier_id'    => 'required|exists:suppliers,id',
        ]);

        Product::create($request->only([
            'name', 'quantity', 'purchase_price', 'sale_price', 'description', 'category_id', 'supplier_id'
        ]));

        return redirect()->route('products.index')->with('success', 'Produit ajouté avec succès ✅');
    }

    // 👁️ Détails d'un produit
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
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
        // DEBUG
        \Log::info('=== DÉBUT MISE À JOUR PRODUIT ===');
        \Log::info('Données reçues:', $request->all());
        
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price'     => 'required|numeric|min:0',
            'quantity'       => 'required|integer|min:0', // ICI: quantity au lieu de stock
            'description'    => 'nullable|string|max:1000',
            'category_id'    => 'required|exists:categories,id',
            'supplier_id'    => 'required|exists:suppliers,id',
        ]);
        
        \Log::info('Données validées:', $validated);
        
        // Vérifiez avant mise à jour
        \Log::info('Avant mise à jour - sale_price:', [
            'ancien' => $product->sale_price,
            'nouveau' => $validated['sale_price']
        ]);
        
        // Mettez à jour le produit
        $updated = $product->update($validated);
        
        \Log::info('Mise à jour réussie?', ['success' => $updated]);
        
        // Rechargez depuis la base de données
        $product->refresh();
        
        \Log::info('Après mise à jour - sale_price:', ['actuel' => $product->sale_price]);
        \Log::info('=== FIN MISE À JOUR PRODUIT ===');

        return redirect()->route('products.index')->with('success', 'Produit mis à jour avec succès.');
    }

    // 🗑️ Suppression d'un produit
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produit supprimé avec succès.');
    }
    
}