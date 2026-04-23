<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Vérifier si la table report_cards existe
        if (Schema::hasTable('report_cards')) {
            
            // Vérifier si la contrainte existe avant de la supprimer
            $foreignKeys = $this->getForeignKeys('report_cards');
            
            if (in_array('report_cards_student_id_foreign', $foreignKeys)) {
                Schema::table('report_cards', function (Blueprint $table) {
                    $table->dropForeign('report_cards_student_id_foreign');
                });
            }
            
            // Ajouter la nouvelle contrainte vers users
            Schema::table('report_cards', function (Blueprint $table) {
                $table->foreign('student_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('report_cards')) {
            // Vérifier si la contrainte existe avant de la supprimer
            $foreignKeys = $this->getForeignKeys('report_cards');
            
            if (in_array('report_cards_student_id_foreign', $foreignKeys)) {
                Schema::table('report_cards', function (Blueprint $table) {
                    $table->dropForeign('report_cards_student_id_foreign');
                });
            }
            
            // Recréer la contrainte originale
            Schema::table('report_cards', function (Blueprint $table) {
                $table->foreign('student_id')
                      ->references('id')
                      ->on('students')
                      ->onDelete('cascade');
            });
        }
    }
    
    /**
     * Récupérer les clés étrangères d'une table
     */
    private function getForeignKeys($table)
    {
        $database = DB::connection()->getDatabaseName();
        $foreignKeys = [];
        
        $results = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = ? 
            AND TABLE_NAME = ? 
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ", [$database, $table]);
        
        foreach ($results as $result) {
            $foreignKeys[] = $result->CONSTRAINT_NAME;
        }
        
        return $foreignKeys;
    }
};