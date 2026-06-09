<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('student_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->string('document_type');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->integer('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('status')->default('draft');
            $table->json('metadata')->nullable();
            $table->foreignId('generated_by')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes
            $table->index(['student_id', 'document_type']);
            $table->index('status');
            $table->index('generated_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_documents');
    }
}