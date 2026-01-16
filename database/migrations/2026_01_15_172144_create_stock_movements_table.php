<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['entree', 'sortie']);
            $table->integer('quantity');
            $table->integer('stock_after');
            $table->string('motif')->nullable();
            $table->string('reference_document')->nullable();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->timestamps();
            
            $table->index(['product_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};