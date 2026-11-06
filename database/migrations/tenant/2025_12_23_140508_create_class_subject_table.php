<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_subject', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('teacher_id')->nullable();
            
            // Colonnes fusionnées depuis ameliorer_class_subject
            $table->integer('hours_per_week')->default(0);
            $table->decimal('coefficient', 3, 1)->default(1.0);
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            // Clés étrangères
            $table->foreign('class_id')
                  ->references('id')
                  ->on('school_classes')
                  ->onDelete('cascade');
                  
            $table->foreign('subject_id')
                  ->references('id')
                  ->on('subjects')
                  ->onDelete('cascade');
                  
            $table->foreign('teacher_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
            
            // Contraintes
            $table->unique(['class_id', 'subject_id']);
            
            // Index
            $table->index('class_id');
            $table->index('subject_id');
            $table->index('teacher_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_subject');
    }
};