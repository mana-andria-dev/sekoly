<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teacher_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->index()->onDelete('cascade');
            $table->foreignId('evaluator_id')->nullable()->constrained('users');
            $table->date('evaluation_date');
            $table->enum('evaluation_type', ['annual', 'probation', 'performance', 'student_feedback']);
            $table->decimal('pedagogical_skills', 3, 1)->nullable();
            $table->decimal('subject_knowledge', 3, 1)->nullable();
            $table->decimal('classroom_management', 3, 1)->nullable();
            $table->decimal('communication', 3, 1)->nullable();
            $table->decimal('punctuality', 3, 1)->nullable();
            $table->decimal('overall_rating', 3, 1);
            $table->text('strengths')->nullable();
            $table->text('improvements_needed')->nullable();
            $table->text('recommendations')->nullable();
            $table->string('document_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_evaluations');
    }
};
