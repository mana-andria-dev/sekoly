<?php
// database/migrations/xxxx_xx_xx_xxxxxx_change_exam_results_student_id_foreign.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Supprimer l'ancienne contrainte
        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
        });
        
        // Modifier la colonne pour référencer users
        Schema::table('exam_results', function (Blueprint $table) {
            $table->foreign('student_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->foreign('student_id')
                  ->references('id')
                  ->on('students')
                  ->onDelete('cascade');
        });
    }
};