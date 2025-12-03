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
                     ->withSum('items', 'quantity') // Quantité totale vendue
                     ->latest()
                     ->paginate(10);

        return view('sales.index', compact('sales'));
    }

    // ----------------------
    // Formulaire de création
    // ----------------------
    public function create()
    {
        // Seuls les produits avec du STOCK disponible
        $products = Product::where('stock', '>', 0)
                          ->orderBy('name')
                          ->get();
        
        $clients = Client::all();

        return view('sales.create', compact('products', 'clients'));
    }

    // ----------------------
    // Enregistrer une vente
    // ----------------------
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1', // Quantité que le client veut acheter
        ]);

        DB::transaction(function () use ($validated) {
            // Créer la vente
            $sale = Sale::create([
                'client_id' => $validated['client_id'],
                'user_id' => Auth::id(),
                'total_price' => 0, // sera mis à jour après
            ]);

            $grandTotal = 0;

            foreach ($validated['products'] as $productData) {
                $product = Product::lockForUpdate()->find($productData['product_id']);
                $quantityToSell = $productData['quantity']; // Quantité demandée par le client

                // Vérifier si le STOCK disponible est suffisant
                if ($product->stock < $quantityToSell) {
                    throw new \Exception(
                        "Stock insuffisant pour '{$product->name}'. " .
                        "Stock disponible: {$product->stock}, " .
                        "Quantité demandée: {$quantityToSell}"
                    );
                }

                $totalPrice = $product->sale_price * $quantityToSell;

                // Créer un item de vente (enregistre la quantité vendue)
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantityToSell, // Quantité réellement vendue
                    'unit_price' => $product->sale_price,
                    'total_price' => $totalPrice,
                ]);

                // RETIRER du STOCK la quantité vendue
                $product->decrement('stock', $quantityToSell);

                // OPTIONNEL: Augmenter le total vendu si vous avez ce champ
                // $product->increment('quantity_sold', $quantityToSell);

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
        
        // Calculer la quantité totale pour la vue
        $totalQuantity = $sale->items->sum('quantity');
        
        return view('sales.show', compact('sale', 'totalQuantity'));
    }

    // ----------------------
    // Supprimer une vente (annulation)
    // ----------------------
    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        
        // Vérifier si l'utilisateur est admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('sales.index')
                ->with('error', 'Vous n\'avez pas les permissions pour supprimer une vente.');
        }

        DB::transaction(function () use ($sale) {
            // RÉTABLIR le STOCK pour chaque produit vendu
            foreach ($sale->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                if ($product) {
                    // REMETTRE dans le STOCK la quantité qui avait été vendue
                    $product->increment('stock', $item->quantity);
                    
                    // OPTIONNEL: Diminuer le total vendu
                    // $product->decrement('quantity_sold', $item->quantity);
                }
            }

            // Supprimer les items de vente
            $sale->items()->delete();

            // Supprimer la vente
            $sale->delete();
        });

        return redirect()->route('sales.index')->with('success', 'Vente annulée et stock rétabli avec succès !');
    }

    // ----------------------
    // Générer une facture
    // ----------------------
    public function invoice($id)
    {
        $sale = Sale::with(['items.product', 'client', 'user'])->findOrFail($id);
        $totalQuantity = $sale->items->sum('quantity');
        
        return view('sales.invoice', compact('sale', 'totalQuantity'));
    }

    // ----------------------
    // API pour les statistiques (optionnel)
    // ----------------------
    public function getStats()
    {
        $stats = [
            'total_sales' => Sale::count(),
            'total_revenue' => Sale::sum('total_price'),
            'total_quantity_sold' => SaleItem::sum('quantity'),
            'average_sale' => Sale::avg('total_price'),
            'unique_clients' => Sale::distinct('client_id')->count('client_id'),
            'active_cashiers' => Sale::distinct('user_id')->count('user_id'),
        ];
        
        return response()->json($stats);
    }

    // ----------------------
    // Ventes par période (optionnel)
    // ----------------------
    public function salesByPeriod(Request $request)
    {
        $period = $request->get('period', 'today');
        
        $query = Sale::query();
        
        switch ($period) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
        }
        
        $sales = $query->with(['items.product', 'client'])
                       ->latest()
                       ->paginate(10);
        
        return view('sales.index', compact('sales', 'period'));
    }
}