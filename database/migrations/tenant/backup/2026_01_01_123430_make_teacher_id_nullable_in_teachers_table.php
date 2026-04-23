<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Rendre teacher_id nullable
            $table->string('teacher_id')->nullable()->change();
            
            // Optionnel : Retirer l'index unique temporairement si ça pose problème
            // $table->dropUnique(['teacher_id']);
        });
    }

    public function down()
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Remettre comme avant
            $table->string('teacher_id')->nullable(false)->change();
            
            // Optionnel : Remettre l'index unique
            // $table->unique(['teacher_id']);
        });
    }
};