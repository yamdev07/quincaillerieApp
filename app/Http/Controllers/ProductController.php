<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(10);

        $totalStock = Product::sum('stock');
        $totalValue = Product::sum(DB::raw('price * stock'));

        // Produits en alerte si stock ≤ 5
        $lowStockProducts = Product::where('stock', '<=', 5)->get();

        return view('products.index', compact('products', 'totalStock', 'totalValue', 'lowStockProducts'));
    }

    public function create() { return view('products.create'); }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Produit ajouté avec succès ✅');
    }

    public function show(Product $product) { return view('products.show', compact('product')); }

    public function edit(Product $product) { return view('products.edit', compact('product')); }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
        ]);

        return redirect()->route('products.index')->with('success', 'Produit mis à jour avec succès ✅');
    }
}
