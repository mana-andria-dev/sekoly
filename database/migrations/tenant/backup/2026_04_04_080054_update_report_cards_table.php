<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('report_cards', function (Blueprint $table) {
            // Renommer average en overall_average si la colonne existe
            if (Schema::hasColumn('report_cards', 'average') && !Schema::hasColumn('report_cards', 'overall_average')) {
                $table->renameColumn('average', 'overall_average');
            }
            
            // Ajouter les colonnes manquantes
            if (!Schema::hasColumn('report_cards', 'overall_average') && !Schema::hasColumn('report_cards', 'average')) {
                $table->float('overall_average')->nullable()->after('subject_grades');
            }
            
            if (!Schema::hasColumn('report_cards', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down()
    {
        Schema::table('report_cards', function (Blueprint $table) {
            if (Schema::hasColumn('report_cards', 'overall_average') && !Schema::hasColumn('report_cards', 'average')) {
                $table->renameColumn('overall_average', 'average');
            }
            
            if (Schema::hasColumn('report_cards', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};