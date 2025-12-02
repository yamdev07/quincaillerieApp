<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SaleController extends Controller
{
    // ----------------------
    // Liste des ventes
    // ----------------------
    public function index()
    {
        $sales = Sale::with(['items.product', 'client', 'user'])
                     ->latest()
                     ->paginate(10);

        return view('sales.index', compact('sales'));
    }

    // ----------------------
    // Formulaire de création
    // ----------------------
    public function create()
    {
        $products = Product::all();
        $clients = Client::all();

        return view('sales.create', compact('products', 'clients'));
    }

    // ----------------------
    // Enregistrer une vente
    // ----------------------
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $clientId = $request->client_id;

        DB::transaction(function () use ($request, $clientId) {
            // Créer la vente
            $sale = Sale::create([
                'client_id' => $clientId,
                'user_id' => Auth::id(),
                'total_price' => 0, // sera mis à jour après
            ]);

            $grandTotal = 0;

            foreach ($request->products as $productData) {
                $product = Product::lockForUpdate()->find($productData['product_id']);
                $quantity = $productData['quantity'];

                if ($product->stock < $quantity) {
                    throw new \Exception("Stock insuffisant pour le produit {$product->name}");
                }

                $totalPrice = $product->sale_price * $quantity;

                // Créer un item de vente
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->sale_price,
                    'total_price' => $totalPrice,
                ]);

                // Décrémenter le stock
                $product->decrement('stock', $quantity);

                $grandTotal += $totalPrice;
            }

            // Mettre à jour le total de la vente
            $sale->update(['total_price' => $grandTotal]);
        });

        return redirect()->route('sales.index')->with('success', 'Vente enregistrée avec succès !');
    }

    // ----------------------
    // Afficher une vente
    // ----------------------
    public function show($id)
    {
        $sale = Sale::with(['items.product', 'client', 'user'])->findOrFail($id);
        return view('sales.show', compact('sale'));
    }

    // ----------------------
    // Dashboard principal
    // ----------------------
    public function dashboard()
    {
        $recentSales = Sale::with(['items.product','client','user'])
                           ->latest()
                           ->take(10)
                           ->get();

        $salesToday = Sale::whereDate('created_at', today())->count();
        $totalRevenue = Sale::whereDate('created_at', today())->sum('total_price');
        $lowStockProducts = Product::where('stock', '<=', 5)->get();
        $activeClients = Client::count();

        $salesByDay = Sale::selectRaw('DATE(created_at) as date, SUM(total_price) as total')
                           ->where('created_at', '>=', now()->subDays(7))
                           ->groupBy('date')
                           ->orderBy('date')
                           ->get();

        $dates = $salesByDay->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d/m'))->toArray();
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
    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);

        DB::transaction(function () use ($sale) {
            // Rétablir le stock des produits vendus
            foreach ($sale->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                $product->increment('stock', $item->quantity);
            }

            // Supprimer les items de vente
            $sale->items()->delete();

            // Supprimer la vente
            $sale->delete();
        });

        return redirect()->route('sales.index')->with('success', 'Vente supprimée avec succès !');
    }
}
