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
        Schema::table('products', function (Blueprint $table) {
            // Ajouter les nouvelles colonnes pour la gestion des cumuls
            $table->boolean('is_cumulated')->default(false)->after('supplier_id');
            $table->boolean('has_been_cumulated')->default(false)->after('is_cumulated');
            $table->foreignId('cumulated_to')->nullable()->after('has_been_cumulated')->constrained('products')->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->after('cumulated_to')->constrained('products')->onDelete('set null');
            $table->string('batch_number')->nullable()->after('parent_id');
            
            // Optionnel: Ajouter un index pour améliorer les performances
            $table->index('is_cumulated');
            $table->index('has_been_cumulated');
            $table->index('cumulated_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées
            $table->dropForeign(['cumulated_to']);
            $table->dropForeign(['parent_id']);
            $table->dropIndex(['is_cumulated']);
            $table->dropIndex(['has_been_cumulated']);
            $table->dropIndex(['cumulated_to']);
            
            $table->dropColumn([
                'is_cumulated',
                'has_been_cumulated',
                'cumulated_to',
                'parent_id',
                'batch_number'
            ]);
        });
    }
};