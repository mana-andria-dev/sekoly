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
                // Configurer la connexion tenant
                Config::set('database.connections.tenant.database', $databaseName);
                DB::purge('tenant');
                DB::connection('tenant')->reconnect();
                
                // Vérifier la connexion
                $currentDb = DB::connection('tenant')->select("SELECT DATABASE() as db");
                $this->info("Connected to: " . $currentDb[0]->db);
                
                // Fresh migration si demandé
                if ($this->option('fresh')) {
                    $this->dropAllTenantTables();
                }
                
                // Créer la table migrations si elle n'existe pas
                $this->createMigrationsTable();
                
                // Exécuter les migrations
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
    
    private function dropAllTenantTables()
    {
        $this->warn('Dropping all tenant tables...');
        
        try {
            $tables = DB::connection('tenant')->select('SHOW TABLES');
            $database = DB::connection('tenant')->select("SELECT DATABASE() as db")[0]->db;
            $tableKey = "Tables_in_{$database}";
            
            if (!empty($tables)) {
                DB::connection('tenant')->statement('SET FOREIGN_KEY_CHECKS=0');
                
                foreach ($tables as $table) {
                    $tableName = $table->$tableKey;
                    if ($tableName !== 'migrations') {
                        DB::connection('tenant')->statement("DROP TABLE IF EXISTS `{$tableName}`");
                        $this->line("  Dropped: {$tableName}");
                    }
                }
                
                DB::connection('tenant')->table('migrations')->truncate();
                DB::connection('tenant')->statement('SET FOREIGN_KEY_CHECKS=1');
            }
            
            $this->info('✓ All tenant tables dropped');
        } catch (\Exception $e) {
            $this->error("Error dropping tables: " . $e->getMessage());
        }
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
        $loadedClasses = []; // Garder trace des classes déjà chargées
        
        foreach ($migrationFiles as $file) {
            $migrationName = basename($file, '.php');
            
            if (in_array($migrationName, $executedMigrations)) {
                $this->line("  ⚠ Skipping: {$migrationName}");
                continue;
            }
            
            $this->info("  Running: {$migrationName}");
            
            try {
                // Extraire le nom de la classe du fichier
                $content = file_get_contents($file);
                $className = null;
                
                if (preg_match('/class\s+(\w+)\s+extends/', $content, $matches)) {
                    $className = $matches[1];
                }
                
                if (!$className) {
                    $this->warn("  ⚠ Cannot find class name in: {$migrationName}");
                    continue;
                }
                
                // Vérifier si la classe existe déjà (conflit)
                if (!class_exists($className, false)) {
                    require_once $file;
                } else {
                    $this->warn("  ⚠ Class {$className} already exists, using existing class");
                }
                
                // Créer une instance de la migration
                if (class_exists($className)) {
                    $migration = new $className();
                    
                    if (method_exists($migration, 'up')) {
                        // Sauvegarder la connexion par défaut
                        $defaultConnection = DB::getDefaultConnection();
                        DB::setDefaultConnection('tenant');
                        
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
                        $this->warn("  ⚠ No up() method in: {$migrationName}");
                    }
                } else {
                    $this->warn("  ⚠ Class {$className} not found after require");
                }
                
            } catch (\Exception $e) {
                $this->error("  ✗ Error on {$migrationName}: " . $e->getMessage());
                
                if (!$this->confirm('Continue with next migrations?', true)) {
                    throw $e;
                }
            }
        }
        
        if ($executedCount === 0) {
            $this->info("No new migrations to run.");
        } else {
            $this->info("✓ {$executedCount} migration(s) executed");
        }
    }
}