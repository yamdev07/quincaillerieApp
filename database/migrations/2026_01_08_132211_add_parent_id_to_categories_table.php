<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentIdToCategoriesTable extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            // Ajouter la clé étrangère pour la relation parent-enfant
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('categories')
                  ->onDelete('set null')
                  ->after('id');
            
            // Supprimer la colonne sub_name si elle existe
            if (Schema::hasColumn('categories', 'sub_name')) {
                $table->dropColumn('sub_name');
            }
            
            // Ajouter d'autres champs optionnels
            $table->string('color')->nullable()->after('description');
            $table->string('icon')->nullable()->after('color');
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'color', 'icon']);
        });
    }
}