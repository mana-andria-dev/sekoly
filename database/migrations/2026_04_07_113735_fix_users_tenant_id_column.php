<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Schema::table('users', function (Blueprint $table) {
        //     // Modifier la colonne tenant_id pour accepter les UUIDs
        //     $table->string('tenant_id', 36)->nullable()->change();
        // });
    }

    public function down()
    {
        // Schema::table('users', function (Blueprint $table) {
        //     $table->unsignedBigInteger('tenant_id')->nullable()->change();
        // });
    }
};