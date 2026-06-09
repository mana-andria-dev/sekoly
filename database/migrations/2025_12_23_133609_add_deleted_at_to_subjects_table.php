<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Schema::table('subjects', function (Blueprint $table) {
        //     // Vérifier si la colonne n'existe pas déjà
        //     if (!Schema::hasColumn('subjects', 'deleted_at')) {
        //         $table->softDeletes(); // Ajoute la colonne deleted_at
        //     }
        // });
    }

    public function down()
    {
        // Schema::table('subjects', function (Blueprint $table) {
        //     $table->dropSoftDeletes(); // Supprime la colonne deleted_at
        // });
    }
};