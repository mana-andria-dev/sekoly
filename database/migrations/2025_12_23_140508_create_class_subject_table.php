<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Schema::create('class_subject', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('class_id');
        //     $table->unsignedBigInteger('subject_id');
        //     $table->unsignedBigInteger('teacher_id')->nullable(); // Professeur principal pour cette matière dans cette classe
        //     $table->timestamps();
            
        //     // Clés étrangères
        //     $table->foreign('class_id')
        //           ->references('id')
        //           ->on('school_classes')
        //           ->onDelete('cascade');
                  
        //     $table->foreign('subject_id')
        //           ->references('id')
        //           ->on('subjects')
        //           ->onDelete('cascade');
                  
        //     $table->foreign('teacher_id')
        //           ->references('id')
        //           ->on('users')
        //           ->onDelete('set null');
            
        //     // Empêcher les doublons
        //     $table->unique(['class_id', 'subject_id']);
            
        //     // Indexes
        //     $table->index('class_id');
        //     $table->index('subject_id');
        //     $table->index('teacher_id');
        // });
    }

    public function down()
    {
        // Schema::dropIfExists('class_subject');
    }
};