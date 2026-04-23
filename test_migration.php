<?php
// test_migration.php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Tenant;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

$tenants = Tenant::all();

foreach ($tenants as $tenant) {
    echo "\nProcessing: " . $tenant->name . "\n";
    
    $databaseName = $tenant->database ?? 'tenant_' . $tenant->id;
    echo "Database: " . $databaseName . "\n";
    
    // Configurer la connexion
    Config::set('database.connections.tenant.database', $databaseName);
    DB::purge('tenant');
    
    try {
        // Tester la connexion
        $result = DB::connection('tenant')->select("SELECT DATABASE() as db");
        echo "Connected to: " . $result[0]->db . "\n";
        
        // Ajouter la colonne directement
        try {
            DB::connection('tenant')->statement("ALTER TABLE school_classes ADD COLUMN school_year_id BIGINT UNSIGNED NULL AFTER teacher_id");
            echo "✓ Column added successfully\n";
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), "Duplicate column") !== false) {
                echo "✓ Column already exists\n";
            } else {
                echo "Error adding column: " . $e->getMessage() . "\n";
            }
        }
        
    } catch (\Exception $e) {
        echo "Connection error: " . $e->getMessage() . "\n";
    }
}

echo "\nDone!\n";