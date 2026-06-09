<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Schema::create('class_assignments', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('tenant_id');
        //     $table->unsignedBigInteger('class_id');
        //     $table->unsignedBigInteger('subject_id');
        //     $table->unsignedBigInteger('teacher_id')->nullable();
        //     $table->integer('hours_per_week')->default(0);
        //     $table->decimal('coefficient', 3, 1)->default(1.0);
        //     $table->boolean('is_active')->default(true);
        //     $table->json('metadata')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();
            
        //     // Indexes
        //     $table->index('tenant_id');
        //     $table->index('class_id');
        //     $table->index('subject_id');
        //     $table->index('teacher_id');
        //     $table->index(['class_id', 'subject_id']);
        //     $table->index(['teacher_id', 'is_active']);
            
        //     // Foreign keys
        //     $table->foreign('tenant_id')
        //           ->references('id')
        //           ->on('tenants')
        //           ->onDelete('cascade');
                  
        //     $table->foreign('class_id')
        //           ->references('id')
        //           ->on('school_classes')
        //           ->onDelete('cascade');
                  
        //     $table->foreign('subject_id')
        //           ->references('id')
        //           ->on('subjects')
        //           ->onDelete('cascade');
                  
        //     $table->foreign('teacher_id')
        //           ->references('id')
        //           ->on('users')
        //           ->onDelete('set null');
            
        //     // Unique constraint
        //     $table->unique(['tenant_id', 'class_id', 'subject_id']);
        // });
    }

    public function down()
    {
        // Schema::dropIfExists('class_assignments');
    }
};