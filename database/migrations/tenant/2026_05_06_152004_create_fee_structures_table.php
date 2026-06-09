<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeeStructuresTable extends Migration
{
    public function up()
    {
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_year_id')->constrained('school_years')->onDelete('cascade');
            $table->foreignId('class_id')->nullable()->constrained('school_classes')->onDelete('cascade');
            $table->string('name'); // Frais d'inscription, Mensualité, etc.
            $table->enum('type', ['registration', 'monthly', 'exam', 'activity', 'other']);
            $table->decimal('amount', 10, 2);
            $table->integer('month')->nullable(); // Pour les mensualités (1-12)
            $table->date('due_date')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_required')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Index pour les recherches
            $table->index(['school_year_id', 'class_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('fee_structures');
    }
}