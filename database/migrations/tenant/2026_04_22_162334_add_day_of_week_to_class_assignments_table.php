<?php
// database/migrations/tenant/2026_04_22_180000_add_day_of_week_to_class_assignments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $columns = DB::connection('tenant')->select("SHOW COLUMNS FROM class_assignments");
        $hasDayOfWeek = false;
        
        foreach ($columns as $column) {
            if ($column->Field === 'day_of_week') {
                $hasDayOfWeek = true;
                break;
            }
        }
        
        if (!$hasDayOfWeek) {
            Schema::table('class_assignments', function (Blueprint $table) {
                $table->string('day_of_week', 20)->nullable()->after('coefficient');
            });
        }
    }

    public function down()
    {
        Schema::table('class_assignments', function (Blueprint $table) {
            $table->dropColumn('day_of_week');
        });
    }
};