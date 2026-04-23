<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSchoolYearIdToSchoolClasses extends Migration
{
    public function up()
    {
        Schema::table('school_classes', function (Blueprint $table) {
            // Vérifiez d'abord si la colonne n'existe pas déjà
            if (!Schema::hasColumn('school_classes', 'school_year_id')) {
                $table->unsignedBigInteger('school_year_id')->nullable()->after('tenant_id');
                $table->foreign('school_year_id')
                      ->references('id')
                      ->on('school_years')
                      ->onDelete('cascade');
            }
            
            // Supprimez la colonne level si elle existe
            if (Schema::hasColumn('school_classes', 'level')) {
                $table->dropColumn('level');
            }
        });
    }

    public function down()
    {
        Schema::table('school_classes', function (Blueprint $table) {
            $table->dropForeign(['school_year_id']);
            $table->dropColumn('school_year_id');
            
            // Si vous voulez restaurer le level
            $table->string('level')->nullable();
        });
    }
}