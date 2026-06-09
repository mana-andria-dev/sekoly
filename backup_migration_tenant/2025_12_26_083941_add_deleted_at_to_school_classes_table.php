<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('school_classes', function (Blueprint $table) {
            if (!Schema::hasColumn('school_classes', 'deleted_at')) {
                $table->softDeletes(); // Ajoute la colonne deleted_at
            }
        });
    }

    public function down()
    {
        Schema::table('school_classes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};