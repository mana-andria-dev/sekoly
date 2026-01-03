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
        Schema::create('teacher_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->string('contract_number')->unique();
            $table->string('contract_type'); // CDI, CDD, Vacation
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->integer('hours_per_week')->nullable();
            $table->string('document_path')->nullable(); // PDF du contrat
            $table->json('terms')->nullable(); // Conditions spécifiques
            $table->enum('status', ['draft', 'active', 'expired', 'terminated'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_contracts');
    }
};
