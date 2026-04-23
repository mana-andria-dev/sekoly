<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Vérifier si la colonne existe déjà en utilisant Schema
        if (!Schema::hasColumn('student_enrollments', 'student_id')) {
            Schema::table('student_enrollments', function (Blueprint $table) {
                $table->unsignedBigInteger('student_id')->nullable()->after('class_id');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('student_enrollments', 'student_id')) {
            Schema::table('student_enrollments', function (Blueprint $table) {
                $table->dropColumn('student_id');
            });
        }
    }
};