<?php
// database/migrations/2026_04_06_xxxxxx_add_custom_columns_to_tenants_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tenants', function (Blueprint $table) {
            // Vérifier si les colonnes existent avant de les ajouter
            if (!Schema::hasColumn('tenants', 'name')) {
                $table->string('name')->nullable()->after('id');
            }
            if (!Schema::hasColumn('tenants', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
            if (!Schema::hasColumn('tenants', 'email')) {
                $table->string('email')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('tenants', 'address')) {
                $table->text('address')->nullable()->after('email');
            }
            if (!Schema::hasColumn('tenants', 'phone')) {
                $table->string('phone')->nullable()->after('address');
            }
            if (!Schema::hasColumn('tenants', 'logo_path')) {
                $table->string('logo_path')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('tenants', 'school_type_id')) {
                $table->unsignedBigInteger('school_type_id')->nullable()->after('logo_path');
            }
        });
    }

    public function down()
    {
        Schema::table('tenants', function (Blueprint $table) {
            $columns = ['name', 'slug', 'email', 'address', 'phone', 'logo_path', 'school_type_id'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('tenants', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};