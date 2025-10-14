<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        // On récupère les produits paginés
        $products = Product::latest()->paginate(10);

        // Calcul global des totaux sur tous les produits
        $totalStock = Product::sum('stock');
        $totalValue = Product::sum(DB::raw('price * stock'));

        // Envoi à la vue
        return view('products.index', compact('products', 'totalStock', 'totalValue'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')
                         ->with('success', 'Produit ajouté avec succès ✅');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        // Mise à jour explicite
        $product->name = $request->name;
        $product->price = $request->price;
        $product->stock = $request->stock; // ← ici le stock est bien mis à jour
        $product->description = $request->description;
        $product->save();

        return redirect()->route('products.index')
                        ->with('success', 'Produit mis à jour avec succès ✅');
    }

        public function sell(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $quantityRequested = $request->quantity;

        // Vérifie le stock disponible
        if ($product->stock >= $quantityRequested) {
            // Décrémente le stock
            $product->stock -= $quantityRequested;
            $product->save();

            return redirect()->back()->with('success', 'Vente enregistrée ✅ Stock mis à jour.');
        } else {
            return redirect()->back()->with('error', 'Stock insuffisant ❌ Quantité disponible : ' . $product->stock);
        }
    }


}
