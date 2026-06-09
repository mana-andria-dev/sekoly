<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('homeworks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('teacher_id');
            $table->string('title');
            $table->text('description');
            $table->date('due_date');
            $table->time('due_time')->nullable();
            $table->integer('max_score')->default(20);
            $table->string('type')->default('homework'); // homework, project, research
            $table->json('attachments')->nullable();
            $table->text('instructions')->nullable();
            $table->string('status')->default('active'); // active, expired, cancelled
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('school_classes')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            
            $table->index(['tenant_id', 'class_id', 'subject_id', 'due_date']);
        });
        
        // Table pour les soumissions de devoirs
        Schema::create('homework_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('homework_id');
            $table->unsignedBigInteger('student_id');
            $table->text('submission_text')->nullable();
            $table->json('attachments')->nullable();
            $table->float('score')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamp('submitted_at');
            $table->timestamp('graded_at')->nullable();
            $table->string('status')->default('submitted'); // submitted, late, graded, returned
            $table->timestamps();
            
            $table->foreign('homework_id')->references('id')->on('homeworks')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            
            $table->unique(['homework_id', 'student_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('homework_submissions');
        Schema::dropIfExists('homeworks');
    }
};