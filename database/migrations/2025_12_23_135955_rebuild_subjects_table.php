<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Créer une table temporaire
        // Schema::create('subjects_new', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('tenant_id');
        //     $table->string('code')->nullable();
        //     $table->string('name');
        //     $table->text('description')->nullable();
        //     $table->string('level', 20)->nullable(); // Changé de ENUM à string
        //     $table->integer('hours_per_week')->default(0);
        //     $table->decimal('coefficient', 3, 1)->default(1.0);
        //     $table->boolean('is_active')->default(true);
        //     $table->json('metadata')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();
            
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
        
        // // 2. Copier les données de l'ancienne table
        // if (Schema::hasTable('subjects')) {
        //     $subjects = DB::table('subjects')->get();
        //     foreach ($subjects as $subject) {
        //         DB::table('subjects_new')->insert((array) $subject);
        //     }
        // }
        
        // // 3. Supprimer l'ancienne table
        // Schema::dropIfExists('subjects');
        
        // // 4. Renommer la nouvelle table
        // Schema::rename('subjects_new', 'subjects');
    }

    public function down()
    {
        // Créer la table originale avec ENUM
        // Schema::create('subjects_old', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('tenant_id');
        //     $table->string('code')->nullable();
        //     $table->string('name');
        //     $table->text('description')->nullable();
        //     $table->enum('level', ['maternelle', 'primaire', 'college', 'lycee'])->nullable();
        //     $table->integer('hours_per_week')->default(0);
        //     $table->decimal('coefficient', 3, 1)->default(1.0);
        //     $table->boolean('is_active')->default(true);
        //     $table->json('metadata')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();
            
        //     $table->index('tenant_id');
        //     $table->index('code');
        //     $table->index(['tenant_id', 'is_active']);
            
        //     $table->foreign('tenant_id')
        //           ->references('id')
        //           ->on('tenants')
        //           ->onDelete('cascade');
        // });
        
        // // Copier les données
        // if (Schema::hasTable('subjects')) {
        //     $subjects = DB::table('subjects')->get();
        //     foreach ($subjects as $subject) {
        //         DB::table('subjects_old')->insert((array) $subject);
        //     }
        // }
        
        // // Supprimer et renommer
        // Schema::dropIfExists('subjects');
        // Schema::rename('subjects_old', 'subjects');
    }
};