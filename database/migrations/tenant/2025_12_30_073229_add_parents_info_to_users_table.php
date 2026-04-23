<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Informations du père
            $table->string('father_name')->nullable()->after('emergency_relation');
            $table->string('father_phone')->nullable()->after('father_name');
            $table->string('father_email')->nullable()->after('father_phone');
            $table->string('father_profession')->nullable()->after('father_email');
            $table->string('father_cin')->nullable()->after('father_profession');
            
            // Informations de la mère
            $table->string('mother_name')->nullable()->after('father_cin');
            $table->string('mother_phone')->nullable()->after('mother_name');
            $table->string('mother_email')->nullable()->after('mother_phone');
            $table->string('mother_profession')->nullable()->after('mother_email');
            $table->string('mother_cin')->nullable()->after('mother_profession');
            
            // Informations du tuteur (si différent)
            $table->string('guardian_name')->nullable()->after('mother_cin');
            $table->string('guardian_phone')->nullable()->after('guardian_name');
            $table->string('guardian_email')->nullable()->after('guardian_phone');
            $table->string('guardian_profession')->nullable()->after('guardian_email');
            $table->string('guardian_cin')->nullable()->after('guardian_profession');
            $table->string('guardian_relation')->nullable()->after('guardian_cin'); // Oncle, tante, grand-parent, etc.
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'father_name', 'father_phone', 'father_email', 'father_profession', 'father_cin',
                'mother_name', 'mother_phone', 'mother_email', 'mother_profession', 'mother_cin',
                'guardian_name', 'guardian_phone', 'guardian_email', 'guardian_profession', 'guardian_cin', 'guardian_relation'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};