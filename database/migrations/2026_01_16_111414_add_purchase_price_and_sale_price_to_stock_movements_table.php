<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            // Vérifie d'abord si les colonnes n'existent pas déjà
            if (!Schema::hasColumn('stock_movements', 'purchase_price')) {
                $table->decimal('purchase_price', 10, 2)->nullable()->after('quantity');
            }
            
            if (!Schema::hasColumn('stock_movements', 'sale_price')) {
                $table->decimal('sale_price', 10, 2)->nullable()->after('purchase_price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropColumn(['purchase_price', 'sale_price']);
        });
    }
};