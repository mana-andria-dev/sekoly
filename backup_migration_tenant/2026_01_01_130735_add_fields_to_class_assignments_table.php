<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToClassAssignmentsTable extends Migration
{
    public function up()
    {
        Schema::table('class_assignments', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('coefficient');
            $table->date('end_date')->nullable()->after('start_date');
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])
                  ->nullable()
                  ->after('end_date');
            $table->enum('status', ['active', 'ended', 'pending'])
                  ->default('active')
                  ->after('day_of_week');
            $table->json('metadata')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('class_assignments', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date', 'day_of_week', 'status']);
        });
    }
}