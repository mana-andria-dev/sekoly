<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Schema::create('subjects', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('tenant_id');
        //     $table->string('code')->unique(); // Code unique (ex: MATH-001)
        //     $table->string('name'); // Nom complet (ex: Mathématiques)
        //     $table->text('description')->nullable();
        //     $table->enum('level', ['primary', 'secondary', 'high_school', 'university'])->nullable();
        //     $table->integer('hours_per_week')->default(0);
        //     $table->decimal('coefficient', 3, 1)->default(1.0);
        //     $table->boolean('is_active')->default(true);
        //     $table->json('metadata')->nullable(); // Pour extensions futures
        //     $table->timestamps();
            
        //     // Indexes
        //     $table->index('tenant_id');
        //     $table->index('code');
        //     $table->index(['tenant_id', 'is_active']);
            
        //     // Foreign keys
        //     $table->foreign('tenant_id')
        //           ->references('id')
        //           ->on('tenants')
        //           ->onDelete('cascade');
        // });
    }

    public function down()
    {
        // Schema::dropIfExists('subjects');
    }
};