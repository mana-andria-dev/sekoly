<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Schema::create('exams', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('tenant_id');
        //     $table->unsignedBigInteger('class_id');
        //     $table->unsignedBigInteger('subject_id');
        //     $table->unsignedBigInteger('teacher_id');
        //     $table->string('title');
        //     $table->text('description')->nullable();
        //     $table->string('type'); // trimester, semester, final, quiz, test
        //     $table->date('exam_date');
        //     $table->time('start_time');
        //     $table->time('end_time');
        //     $table->integer('duration_minutes');
        //     $table->float('max_score')->default(100);
        //     $table->float('coefficient')->default(1);
        //     $table->string('location')->nullable();
        //     $table->json('topics')->nullable(); // Sujets abordés
        //     $table->string('status')->default('scheduled'); // scheduled, ongoing, completed, cancelled
        //     $table->json('instructions')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();
            
        //     $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        //     $table->foreign('class_id')->references('id')->on('school_classes')->onDelete('cascade');
        //     $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        //     $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            
        //     $table->index(['tenant_id', 'class_id', 'exam_date']);
        // });
        
        // // Table pour les notes d'examens
        // Schema::create('exam_results', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('exam_id');
        //     $table->unsignedBigInteger('student_id');
        //     $table->float('score');
        //     $table->text('feedback')->nullable();
        //     $table->json('details')->nullable(); // Détails par question
        //     $table->timestamp('recorded_at');
        //     $table->unsignedBigInteger('recorded_by')->nullable();
        //     $table->timestamps();
            
        //     $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
        //     $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        //     $table->foreign('recorded_by')->references('id')->on('users')->onDelete('set null');
            
        //     $table->unique(['exam_id', 'student_id']);
        // });
    }

    public function down()
    {
        // Schema::dropIfExists('exam_results');
        // Schema::dropIfExists('exams');
    }
};