<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Supprimer l'ancienne colonne ENUM
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn('level');
        });
        
        // Recréer la colonne en VARCHAR
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('level', 20)->nullable()->after('description');
        });
    }

    public function down()
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn('level');
        });
        
        Schema::table('subjects', function (Blueprint $table) {
            $table->enum('level', ['maternelle', 'primaire', 'college', 'lycee'])->nullable()->after('description');
        });
    }
};