<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Ajouter phone si n'existe pas
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            
            // Ensuite ajouter photo après email (ou après phone si phone existe)
            if (!Schema::hasColumn('users', 'photo')) {
                if (Schema::hasColumn('users', 'phone')) {
                    $table->string('photo')->nullable()->after('phone');
                } else {
                    $table->string('photo')->nullable()->after('email');
                }
            }
            
            // Continuer avec les autres champs
            if (!Schema::hasColumn('users', 'emergency_contact')) {
                $table->string('emergency_contact')->nullable()->after('phone');
            }
            
            if (!Schema::hasColumn('users', 'emergency_relation')) {
                $table->string('emergency_relation')->nullable()->after('emergency_contact');
            }
            
            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('emergency_relation');
            }
            
            if (!Schema::hasColumn('users', 'gender')) {
                $table->string('gender')->nullable()->after('date_of_birth');
            }
            
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('gender');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'phone',
                'photo', 
                'emergency_contact', 
                'emergency_relation',
                'date_of_birth',
                'gender',
                'address'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};