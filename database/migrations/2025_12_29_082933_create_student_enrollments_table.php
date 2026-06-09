<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Schema::create('student_enrollments', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
        //     $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
        //     $table->foreignId('class_id')->constrained('school_classes')->onDelete('cascade');
        //     $table->foreignId('school_year_id')->constrained()->onDelete('cascade');
        //     $table->date('enrollment_date');
        //     $table->string('status')->default('active');
        //     $table->string('roll_number')->nullable();
        //     $table->string('section')->nullable();
        //     $table->text('remarks')->nullable();
        //     $table->json('metadata')->nullable();
        //     $table->softDeletes();
        //     $table->timestamps();

        //     // Index pour les performances
        //     $table->index(['tenant_id', 'student_id']);
        //     $table->index(['tenant_id', 'class_id']);
        //     $table->unique(['tenant_id', 'school_year_id', 'student_id'], 'unique_enrollment');
        // });
    }

    public function down()
    {
        // Schema::dropIfExists('student_enrollments');
    }
};