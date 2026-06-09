<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('tenants', function (Blueprint $table) {
            if (!Schema::hasColumn('tenants', 'status')) {
                $table->enum('status', ['pending', 'active', 'suspended', 'inactive'])
                      ->default('pending')
                      ->after('data');
            }
            
            if (!Schema::hasColumn('tenants', 'activated_at')) {
                $table->timestamp('activated_at')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('tenants', 'suspended_at')) {
                $table->timestamp('suspended_at')->nullable()->after('activated_at');
            }
            
            if (!Schema::hasColumn('tenants', 'activation_notes')) {
                $table->text('activation_notes')->nullable()->after('suspended_at');
            }
        });
    }
    
    public function down()
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['status', 'activated_at', 'suspended_at', 'activation_notes']);
        });
    }

};
