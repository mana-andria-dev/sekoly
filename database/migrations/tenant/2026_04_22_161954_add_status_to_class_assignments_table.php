<?php
// database/migrations/tenant/2026_04_22_170000_add_status_to_class_assignments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Vérifier si la colonne status existe déjà
        $columns = DB::connection('tenant')->select("SHOW COLUMNS FROM class_assignments");
        $hasStatus = false;
        
        foreach ($columns as $column) {
            if ($column->Field === 'status') {
                $hasStatus = true;
                break;
            }
        }
        
        if (!$hasStatus) {
            Schema::table('class_assignments', function (Blueprint $table) {
                $table->string('status', 50)->default('active')->after('is_active');
            });
        }
    }

    public function down()
    {
        Schema::table('class_assignments', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};