<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Schema::create('subject_teacher', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('subject_id');
        //     $table->unsignedBigInteger('teacher_id');
        //     $table->timestamps();
            
        //     // Clés étrangères
        //     $table->foreign('subject_id')
        //           ->references('id')
        //           ->on('subjects')
        //           ->onDelete('cascade');
                  
        //     $table->foreign('teacher_id')
        //           ->references('id')
        //           ->on('users')
        //           ->onDelete('cascade');
            
        //     // Empêcher les doublons
        //     $table->unique(['subject_id', 'teacher_id']);
            
        //     // Indexes
        //     $table->index('subject_id');
        //     $table->index('teacher_id');
        // });
    }

    public function down()
    {
        // Schema::dropIfExists('subject_teacher');
    }
};