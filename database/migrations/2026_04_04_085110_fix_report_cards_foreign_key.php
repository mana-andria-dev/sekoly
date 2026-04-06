<?php
// database/migrations/xxxx_xx_xx_xxxxxx_fix_report_cards_foreign_key.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Supprimer l'ancienne contrainte
        Schema::table('report_cards', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
        });
        
        // Ajouter la nouvelle contrainte vers users
        Schema::table('report_cards', function (Blueprint $table) {
            $table->foreign('student_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('report_cards', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->foreign('student_id')
                  ->references('id')
                  ->on('students')
                  ->onDelete('cascade');
        });
    }
};