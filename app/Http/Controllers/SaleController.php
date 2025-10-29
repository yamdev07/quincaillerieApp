<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'client_id' => 'nullable|exists:clients,id',
        ]);

        $clientId = $request->client_id;

        DB::transaction(function() use ($request, $clientId) {
            foreach ($request->products as $productData) {
                $product = Product::lockForUpdate()->find($productData['product_id']);
                $quantity = $productData['quantity'];

                if ($product->stock < $quantity) {
                    throw new \Exception("Stock insuffisant pour le produit {$product->name}. Disponible : {$product->stock}");
                }

                $totalPrice = $product->price * $quantity;

                Sale::create([
                    'product_id' => $product->id,
                    'client_id' => $clientId,
                    'quantity' => $quantity,
                    'total_price' => $totalPrice,
                    'user_id' => Auth::id(),
                ]);

                // Décrémenter le stock réel
                $product->decrement('stock', $quantity);
            }
        });

        return redirect()->route('sales.index')->with('success', 'Vente enregistrée avec succès !');
    }

    public function show($saleId)
    {
        $sale = Sale::findOrFail($saleId);
        return view('sales.show', compact('sale'));
    }

    public function dashboard()
    {
        $recentSales = Sale::with(['product','client','user'])->latest()->take(10)->get();
        $salesToday = Sale::whereDate('created_at', today())->count();
        $totalRevenue = Sale::whereDate('created_at', today())->sum('total_price');
        $lowStockProducts = Product::where('stock', '<=', 5)->get();
        $activeClients = Client::count();

        $salesByDay = Sale::selectRaw('DATE(created_at) as date, SUM(total_price) as total')
                            ->where('created_at', '>=', now()->subDays(7))
                            ->groupBy('date')
                            ->orderBy('date')
                            ->get();

        $dates = $salesByDay->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))->toArray();
        $totals = $salesByDay->pluck('total')->toArray();

        return view('dashboard', compact(
            'recentSales',
            'salesToday',
            'totalRevenue',
            'lowStockProducts',
            'activeClients',
            'dates',
            'totals'
        ));
    }
}
