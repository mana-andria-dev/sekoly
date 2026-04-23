<?php
// database/migrations/tenant/2026_04_22_100000_add_school_year_id_to_school_classes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Vérification directe avec DB au lieu de Schema::hasColumn
        $columns = DB::connection('tenant')->select("SHOW COLUMNS FROM school_classes");
        $exists = false;
        foreach ($columns as $column) {
            if ($column->Field === 'school_year_id') {
                $exists = true;
                break;
            }
        }
        
        if (!$exists) {
            Schema::table('school_classes', function (Blueprint $table) {
                $table->unsignedBigInteger('school_year_id')->nullable()->after('teacher_id');
            });
        }
    }

    public function down()
    {
        Schema::table('school_classes', function (Blueprint $table) {
            $table->dropColumn('school_year_id');
        });
    }
};