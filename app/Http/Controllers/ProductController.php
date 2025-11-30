<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // 🧱 Liste des produits
    public function index()
    {
        $products = Product::latest()->paginate(10);

        $totalStock = Product::sum('stock');
        // Total value = stock * prix d'achat
        $totalValue = Product::sum(DB::raw('purchase_price * stock'));

        // Produits en alerte si stock ≤ 5
        $lowStockProducts = Product::where('stock', '<=', 5)->get();

        return view('products.index', compact('products', 'totalStock', 'totalValue', 'lowStockProducts'));
    }

    // 🆕 Page d’ajout
    public function create()
    {
        return view('products.create');
    }

    // 💾 Enregistrement d’un nouveau produit
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'stock'          => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price'     => 'required|numeric|min:0',
            'description'    => 'nullable|string|max:1000',
        ]);

        Product::create($request->only(['name', 'stock', 'purchase_price', 'sale_price', 'description']));

        return redirect()->route('products.index')->with('success', 'Produit ajouté avec succès ✅');
    }

    // 👁️ Détails d’un produit
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    // ✏️ Page d’édition
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    // ✏️ Mise à jour
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price'     => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'description'    => 'nullable|string',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produit mis à jour avec succès.');
    }
    // 🗑️ Suppression d’un produit
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produit supprimé avec succès.');
    }
}
