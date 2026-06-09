<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            
            // Dans une base tenant, on n'a pas besoin de tenant_id
            // car chaque base est dédiée à un seul tenant
            // Si vous voulez le garder pour d'éventuelles fusions, le mettre sans clé étrangère
            // $table->unsignedBigInteger('tenant_id')->nullable();
            
            $table->string('code')->unique(); // Code unique (ex: MATH-001)
            $table->string('name'); // Nom complet (ex: Mathématiques)
            $table->text('description')->nullable();
            $table->enum('level', ['primary', 'secondary', 'high_school', 'university'])->nullable();
            $table->integer('hours_per_week')->default(0);
            $table->decimal('coefficient', 3, 1)->default(1.0);
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable(); // Pour extensions futures
            $table->timestamps();
            $table->softDeletes(); // Ajout du soft delete
            
            // Indexes (sans tenant_id)
            $table->index('code');
            $table->index('is_active');
            $table->index('level');
            
            // Pas de foreign key vers tenants car elle n'existe pas dans la base tenant
        });
    }

    public function down()
    {
        Schema::dropIfExists('subjects');
    }
};