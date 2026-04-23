<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            // Vérifier si la colonne deleted_at n'existe pas déjà
            if (!Schema::hasColumn('classrooms', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            // Vérifier si la colonne existe avant de la supprimer
            if (Schema::hasColumn('classrooms', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};