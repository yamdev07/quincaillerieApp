<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['product', 'client', 'user'])->latest()->paginate(10);
        return view('sales.index', compact('sales'));
    }
        
    public function create()
    {
        $products = Product::all();
        $clients = Client::all();
        return view('sales.create', compact('products', 'clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'client_id' => 'nullable|exists:clients,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        $totalPrice = $product->price * $request->quantity;

        Sale::create([
            'product_id' => $product->id,
            'client_id' => $request->client_id,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
            'user_id' => Auth::id(), // caissier connecté
        ]);

        return redirect()->route('sales.index')->with('success', 'Vente enregistrée avec succès !');
    }
}
