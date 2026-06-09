<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->enum('plan', ['basic', 'premium', 'enterprise'])->default('basic');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['active', 'cancelled', 'expired'])->default('active');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->timestamps();
            
            $table->foreign('tenant_id')
                  ->references('id')
                  ->on('tenants')
                  ->onDelete('cascade');
                  
            $table->index(['status', 'ends_at']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}