<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Table des notes continues
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('teacher_id');
            $table->string('grade_type'); // homework, test, quiz, participation, project
            $table->unsignedBigInteger('reference_id')->nullable(); // ID du devoir ou examen lié
            $table->string('reference_type')->nullable(); // Type de référence (homework, exam)
            $table->float('score');
            $table->float('max_score');
            $table->float('coefficient')->default(1);
            $table->date('grade_date');
            $table->text('comment')->nullable();
            $table->timestamps();
            
// //             $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
//             $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('school_classes')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            
            $table->index(['student_id', 'subject_id', 'grade_date']);
        });
        
        // Table des bulletins
        Schema::create('report_cards', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('school_year_id');
            $table->string('period'); // trimester1, trimester2, trimester3, semester1, semester2, annual
            $table->json('subject_grades'); // Stocke les notes par matière
            $table->float('average')->nullable();
            $table->float('class_average')->nullable();
            $table->integer('class_rank')->nullable();
            $table->integer('total_students')->nullable();
            $table->text('appreciation')->nullable();
            $table->text('teacher_comments')->nullable();
            $table->text('principal_comments')->nullable();
            $table->json('absences')->nullable(); // Statistiques d'absences
            $table->json('behaviors')->nullable(); // Comportement
            $table->date('issued_date');
            $table->string('status')->default('draft'); // draft, published, archived
            $table->timestamps();
            
// //             $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
//             $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('school_classes')->onDelete('cascade');
            $table->foreign('school_year_id')->references('id')->on('school_years')->onDelete('cascade');
            
            $table->unique(['student_id', 'school_year_id', 'period']);
            $table->index(['class_id', 'period']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('report_cards');
        Schema::dropIfExists('grades');
    }
};