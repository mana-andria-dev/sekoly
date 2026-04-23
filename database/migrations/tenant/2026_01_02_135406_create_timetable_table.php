<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // D'abord créer la table classrooms si elle n'existe pas
        if (!Schema::hasTable('classrooms')) {
            Schema::create('classrooms', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique();
                $table->integer('capacity')->default(30);
                $table->string('type')->default('classroom');
                $table->text('equipment')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Table timetables
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('class_id')->constrained('school_classes')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('school_years')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->string('type')->default('weekly');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
            
            $table->index(['class_id']);
            $table->index(['is_active']);
        });

        // Table timetable_slots - CORRIGÉE
        Schema::create('timetable_slots', function (Blueprint $table) {
            $table->id();
            
            // Définir timetable_id comme colonne (non commentée)
            $table->foreignId('timetable_id')->constrained('timetables')->onDelete('cascade');
            $table->tinyInteger('day_of_week')->comment('1=Lundi, 2=Mardi, etc.');
            $table->time('start_time');
            $table->time('end_time');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->onDelete('set null');
            $table->foreignId('assignment_id')->nullable()->constrained('class_assignments')->onDelete('set null');
            $table->string('color', 7)->default('#3B82F6');
            $table->text('notes')->nullable();
            $table->boolean('recurring')->default(true);
            $table->integer('sequence_order')->default(0);
            $table->timestamps();

            // Indexes (après la définition des colonnes)
            $table->index(['timetable_id', 'day_of_week']);
            $table->index(['teacher_id']);
            $table->index(['subject_id']);
            $table->index(['classroom_id']);
            
            // Contrainte d'unicité
            $table->unique(['timetable_id', 'day_of_week', 'start_time', 'teacher_id'], 'unique_teacher_slot');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timetable_slots');
        Schema::dropIfExists('timetables');
        Schema::dropIfExists('classrooms');
    }
};