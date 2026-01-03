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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('teacher_id')->unique(); // Code unique: PROF-2024-001
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender', 1); // M/F
            $table->date('date_of_birth');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('nationality')->nullable();
            $table->string('id_number')->nullable(); // CIN/Passeport
            $table->string('social_security_number')->nullable();
            
            // Informations professionnelles
            $table->string('academic_degree'); // Licence, Master, Doctorat
            $table->string('specialization');
            $table->date('hire_date');
            $table->string('employment_type'); // CDI, CDD, Vacataire, Contractuel
            $table->string('status')->default('active'); // active, inactive, on_leave, retired
            $table->text('qualifications')->nullable(); // Diplômes et certifications
            
            // Informations administratives
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->integer('hours_per_week')->default(20);
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            
            // Contact d'urgence
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relation')->nullable();
            
            $table->text('notes')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
