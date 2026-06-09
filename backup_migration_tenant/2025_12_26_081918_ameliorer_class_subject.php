<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('class_subject', function (Blueprint $table) {
            if (!Schema::hasColumn('class_subject', 'hours_per_week')) {
                $table->integer('hours_per_week')->default(0)->after('teacher_id');
                $table->decimal('coefficient', 3, 1)->default(1.0)->after('hours_per_week');
                $table->boolean('is_active')->default(true)->after('coefficient');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
