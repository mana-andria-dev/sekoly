<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('grades', function (Blueprint $table) {
            // Ajouter les colonnes manquantes
            if (!Schema::hasColumn('grades', 'title')) {
                $table->string('title')->nullable()->after('teacher_id');
            }
            
            if (!Schema::hasColumn('grades', 'period')) {
                $table->string('period')->nullable()->after('comment');
            }
            
            if (!Schema::hasColumn('grades', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
            
            // Modifier la colonne max_score pour avoir une valeur par défaut
            $table->float('max_score')->default(20)->change();
        });
    }

    public function down()
    {
        Schema::table('grades', function (Blueprint $table) {
            if (Schema::hasColumn('grades', 'title')) {
                $table->dropColumn('title');
            }
            
            if (Schema::hasColumn('grades', 'period')) {
                $table->dropColumn('period');
            }
            
            if (Schema::hasColumn('grades', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};