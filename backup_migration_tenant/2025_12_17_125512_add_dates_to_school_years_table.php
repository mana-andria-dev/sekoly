<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('school_years', function (Blueprint $table) {
            $table->date('start_date')->after('name');
            $table->date('end_date')->after('start_date');
        });
    }

    public function down(): void
    {
        Schema::table('school_years', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date', 'is_active']);
        });
    }
};
