<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('teacher_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->date('lesson_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('type')->default('regular'); // regular, revision, practical
            $table->string('status')->default('scheduled'); // scheduled, ongoing, completed, cancelled
            $table->json('resources')->nullable(); // Pour stocker les URLs des ressources
            $table->json('objectives')->nullable(); // Objectifs pédagogiques
            $table->timestamps();
            $table->softDeletes();
            
// //             $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
//             $table->foreign('class_id')->references('id')->on('school_classes')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            
            $table->index(['class_id', 'subject_id', 'lesson_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('lessons');
    }
};