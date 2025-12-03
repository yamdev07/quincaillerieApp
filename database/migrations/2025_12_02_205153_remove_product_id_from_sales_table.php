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
        Schema::table('sales', function (Blueprint $table) {
            // Supprimer d'abord la clé étrangère
            $table->dropForeign(['product_id']); 
            // Puis supprimer la colonne
            $table->dropColumn('product_id');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
        });
    }


};
