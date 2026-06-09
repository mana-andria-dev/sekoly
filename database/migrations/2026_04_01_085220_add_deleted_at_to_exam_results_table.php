<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_deleted_at_to_exam_results_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Schema::table('exam_results', function (Blueprint $table) {
        //     $table->softDeletes(); // Ajoute la colonne deleted_at
        // });
    }

    public function down()
    {
        // Schema::table('exam_results', function (Blueprint $table) {
        //     $table->dropSoftDeletes();
        // });
    }
};