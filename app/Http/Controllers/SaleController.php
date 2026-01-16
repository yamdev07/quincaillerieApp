<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Client;
use App\Models\StockMovement; // AJOUTEZ CE USE
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
        \Log::info('=== DÉBUT VENTE ===');
        \Log::info('Données brutes reçues:', $request->all());
        
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);
        
        \Log::info('Données validées:', $validated);
        
        DB::transaction(function () use ($validated) {
            \Log::info('Transaction DB démarrée');
            
            // Créer la vente
            $sale = Sale::create([
                'client_id' => $validated['client_id'],
                'user_id' => Auth::id(),
                'total_price' => 0,
            ]);
            
            \Log::info("Vente créée - ID: {$sale->id}");
            
            // Récupérer le client pour le motif
            $clientName = 'Client';
            if ($validated['client_id']) {
                $client = Client::find($validated['client_id']);
                $clientName = $client ? $client->name : 'Client';
            }
            
            $grandTotal = 0;
            
            foreach ($validated['products'] as $index => $productData) {
                \Log::info("Produit #{$index}:", $productData);
                
                // Récupérer le produit avec verrou
                $product = Product::lockForUpdate()->find($productData['product_id']);
                
                if (!$product) {
                    \Log::error("Produit non trouvé: " . $productData['product_id']);
                    continue;
                }
                
                \Log::info("Produit trouvé: {$product->name} (ID: {$product->id})");
                \Log::info("Stock AVANT vente: {$product->stock}");
                
                $quantityToSell = $productData['quantity'];
                $unitPrice = $productData['unit_price'];
                
                // Vérifier le stock
                if ($product->stock < $quantityToSell) {
                    $message = "Stock insuffisant pour '{$product->name}'. Stock: {$product->stock}, Demandé: {$quantityToSell}";
                    \Log::error($message);
                    throw new \Exception($message);
                }
                
                // Calculer le stock après vente
                $stockAfter = $product->stock - $quantityToSell;
                
                // ============================================
                // ENREGISTRER LE MOUVEMENT DE STOCK - AJOUTÉ
                // ============================================
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'sortie',
                    'quantity' => $quantityToSell,
                    'stock_after' => $stockAfter,
                    'motif' => "Vente #{$sale->id} à {$clientName}",
                    'reference_document' => 'VTE-' . $sale->id,
                    'user_id' => Auth::id(),
                ]);
                
                \Log::info("Mouvement de stock enregistré pour produit ID: {$product->id}");
                
                // Créer l'item de vente
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantityToSell,
                    'unit_price' => $unitPrice,
                    'total_price' => $unitPrice * $quantityToSell,
                ]);
                
                \Log::info("SaleItem créé pour produit ID: {$product->id}");
                
                // DÉDUCTION DU STOCK
                $product->decrement('stock', $quantityToSell);
                
                // Recharger le produit depuis la base
                $product->refresh();
                
                \Log::info("Stock APRÈS vente: {$product->stock}");
                \Log::info("---");
                
                $grandTotal += ($unitPrice * $quantityToSell);
            }
            
            // Mettre à jour le total de la vente
            $sale->update(['total_price' => $grandTotal]);
            \Log::info("Total vente: {$grandTotal} CFA");
        });
        
        \Log::info('=== FIN VENTE ===');
        
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
            // Récupérer le client pour le motif
            $clientName = $sale->client ? $sale->client->name : 'Client';
            
            // RÉTABLIR le STOCK pour chaque produit vendu
            foreach ($sale->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                if ($product) {
                    // Calculer le nouveau stock après annulation
                    $stockAfter = $product->stock + $item->quantity;
                    
                    // ============================================
                    // ENREGISTRER LE MOUVEMENT D'ANNULATION - AJOUTÉ
                    // ============================================
                    StockMovement::create([
                        'product_id' => $product->id,
                        'type' => 'entree', // C'est une entrée car on remet le stock
                        'quantity' => $item->quantity,
                        'stock_after' => $stockAfter,
                        'motif' => "Annulation vente #{$sale->id} à {$clientName}",
                        'reference_document' => 'ANNUL-VTE-' . $sale->id,
                        'user_id' => Auth::id(),
                    ]);
                    
                    // REMETTRE dans le STOCK la quantité qui avait été vendue
                    $product->increment('stock', $item->quantity);
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
    
    // ----------------------
    // Méthode pour mettre à jour une vente (si vous en avez besoin)
    // ----------------------
    public function update(Request $request, $id)
    {
        $sale = Sale::findOrFail($id);
        
        $validated = $request->validate([
            // ... vos règles de validation ...
        ]);
        
        DB::transaction(function () use ($sale, $validated) {
            // Logique pour mettre à jour une vente existante
            // Pensez à enregistrer aussi les mouvements de stock pour les ajustements
            
            // Exemple simple :
            foreach ($sale->items as $oldItem) {
                // Pour chaque item modifié, enregistrez un mouvement d'ajustement
                // ...
            }
            
            // Mettre à jour la vente
            $sale->update($validated);
        });
        
        return redirect()->route('sales.show', $sale->id)
            ->with('success', 'Vente mise à jour avec succès.');
    }
}