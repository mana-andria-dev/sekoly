<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToTenantsTable extends Migration
{
    public function up()
    {
        Schema::table('tenants', function (Blueprint $table) {
            // Vérifier si la colonne n'existe pas avant de l'ajouter
            if (!Schema::hasColumn('tenants', 'name')) {
                $table->string('name')->nullable()->after('id');
            }
            
            if (!Schema::hasColumn('tenants', 'database')) {
                $table->string('database')->nullable()->after('name');
            }
            
            if (!Schema::hasColumn('tenants', 'slug')) {
                $table->string('slug')->nullable()->after('database');
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
            
            // Ajouter des index pour les colonnes souvent recherchées
            $table->index('email');
            $table->index('slug');
            $table->index('name');
        });
    }

    public function down()
    {
        Schema::table('tenants', function (Blueprint $table) {
            $columns = ['name', 'database', 'slug', 'email', 'address', 'phone', 'logo_path'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('tenants', $column)) {
                    $table->dropColumn($column);
                }
            }
            
            // Dropper les index
            $table->dropIndex(['email']);
            $table->dropIndex(['slug']);
            $table->dropIndex(['name']);
        });
    }
}