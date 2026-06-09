<?php
// database/migrations/2025_12_17_081005_create_school_years_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolYearsTable extends Migration
{
    public function up()
    {
        Schema::create('school_years', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_current')->default(false);
            $table->timestamps();
            
            // Commenter temporairement la contrainte
            // $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            
            // Ajouter un index simple
            $table->index('tenant_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('school_years');
    }
}