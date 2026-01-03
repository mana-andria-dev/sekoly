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
        Schema::table('tenants', function (Blueprint $table) {
            // rendre slug obligatoire
            $table->string('slug')->nullable(false)->change();

            // supprimer subdomain s'il existe
            if (Schema::hasColumn('tenants', 'subdomain')) {
                $table->dropColumn('subdomain');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('subdomain')->nullable();
        });
    }

};
