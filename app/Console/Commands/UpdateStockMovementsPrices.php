<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class UpdateStockMovementsPrices extends Command
{
    protected $signature = 'stock:update-prices';
    protected $description = 'Met à jour les prix dans les mouvements de stock existants';

    public function handle()
    {
        $this->info('Mise à jour des prix dans les mouvements de stock...');
        
        // Compter les mouvements sans prix
        $count = StockMovement::whereNull('purchase_price')->count();
        $this->info("$count mouvements à mettre à jour.");
        
        if ($count > 0) {
            DB::transaction(function () {
                // Pour chaque mouvement, récupérer les prix du produit
                StockMovement::whereNull('purchase_price')->chunk(100, function ($movements) {
                    foreach ($movements as $movement) {
                        $product = Product::find($movement->product_id);
                        
                        if ($product) {
                            $movement->update([
                                'purchase_price' => $product->purchase_price,
                                'sale_price' => $product->sale_price
                            ]);
                        }
                    }
                });
            });
            
            $this->info('Mise à jour terminée avec succès!');
        } else {
            $this->info('Tous les mouvements ont déjà des prix.');
        }
        
        return 0;
    }
}