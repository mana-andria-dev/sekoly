<?php
// app/Console/Commands/MigrateTenants.php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MigrateTenants extends Command
{
    protected $signature = 'tenants:migrate {--fresh : Drop all tables and re-run migrations}';
    protected $description = 'Run migrations for all tenants';

    public function handle()
    {
        $tenants = Tenant::all();
        
        if ($tenants->isEmpty()) {
            $this->warn('No tenants found.');
            return;
        }
        
        foreach ($tenants as $tenant) {
            $this->info("==========================================");
            $this->info("Migrating tenant: {$tenant->name}");
            
            $databaseName = $tenant->database ?? 'tenant_' . $tenant->id;
            $this->info("Database: {$databaseName}");
            
            try {
                // Configurer la connexion tenant (comme dans le contrôleur)
                Config::set('database.connections.tenant.database', $databaseName);
                DB::purge('tenant');
                DB::connection('tenant')->reconnect();
                
                // Vérifier la connexion
                $currentDb = DB::connection('tenant')->select("SELECT DATABASE() as db");
                $this->info("Connected to: " . $currentDb[0]->db);
                
                // Créer la table migrations si elle n'existe pas
                $this->createMigrationsTable();
                
                // Exécuter les migrations (sans Artisan)
                $this->runMigrations();
                
                $this->info("✓ Migration completed for {$tenant->name}");
                
            } catch (\Exception $e) {
                $this->error("Error: " . $e->getMessage());
            }
            
            $this->newLine();
        }
        
        // Restaurer la connexion par défaut
        DB::setDefaultConnection('central');
        $this->info("All tenants migrated successfully!");
    }
    
    private function createMigrationsTable()
    {
        $createMigrationsTable = "
            CREATE TABLE IF NOT EXISTS `migrations` (
                `id` int unsigned NOT NULL AUTO_INCREMENT,
                `migration` varchar(255) NOT NULL,
                `batch` int NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        
        try {
            DB::connection('tenant')->statement($createMigrationsTable);
            $this->info("✓ Migrations table ready");
        } catch (\Exception $e) {
            $this->warn("Migrations table might already exist: " . $e->getMessage());
        }
    }
    
private function runMigrations()
{
    $migrationPath = database_path('migrations/tenant');
    
    if (!is_dir($migrationPath)) {
        $this->error("Migration path not found: {$migrationPath}");
        return;
    }
    
    // Récupérer les migrations déjà exécutées
    $executedMigrations = DB::connection('tenant')
        ->table('migrations')
        ->pluck('migration')
        ->toArray();
    
    $migrationFiles = glob($migrationPath . '/*.php');
    sort($migrationFiles);
    
    if (empty($migrationFiles)) {
        $this->warn("No migration files found");
        return;
    }
    
    $batch = DB::connection('tenant')->table('migrations')->max('batch') + 1;
    if (!$batch) {
        $batch = 1;
    }
    
    $executedCount = 0;
    
    foreach ($migrationFiles as $file) {
        $migrationName = basename($file, '.php');
        
        if (in_array($migrationName, $executedMigrations)) {
            $this->line("  ⚠ Skipping: {$migrationName}");
            continue;
        }
        
        $this->info("  Running: {$migrationName}");
        
        try {
            // CRITIQUE: Forcer la connexion tenant dans l'environnement
            // Sauvegarder la connexion par défaut
            $defaultConnection = DB::getDefaultConnection();
            DB::setDefaultConnection('tenant');
            
            // Inclure le fichier de migration
            $migration = require $file;
            
            if (is_object($migration) && method_exists($migration, 'up')) {
                // Exécuter la migration
                $migration->up();
                
                // Restaurer la connexion par défaut
                DB::setDefaultConnection($defaultConnection);
                
                // Enregistrer dans la table migrations
                DB::connection('tenant')->table('migrations')->insert([
                    'migration' => $migrationName,
                    'batch' => $batch,
                ]);
                
                $this->info("  ✓ Executed: {$migrationName}");
                $executedCount++;
            } else {
                $this->warn("  ⚠ Invalid migration file: {$migrationName}");
            }
            
        } catch (\Exception $e) {
            $this->error("  ✗ Error on {$migrationName}: " . $e->getMessage());
            throw $e;
        }
    }
    
    if ($executedCount === 0) {
        $this->info("No new migrations to run.");
    } else {
        $this->info("✓ {$executedCount} migration(s) executed");
    }
}
}