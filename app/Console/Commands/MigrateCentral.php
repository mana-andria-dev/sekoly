<?php
// app/Console/Commands/MigrateCentral.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateCentral extends Command
{
    protected $signature = 'central:migrate {--fresh : Drop all tables and re-run migrations} 
                                      {--seed : Run seeders after migrations}
                                      {--fix : Fix migration table without running migrations}';
    protected $description = 'Run migrations for central/main database';

    public function handle()
    {
        $this->info("==========================================");
        $this->info("Migrating central database");
        
        try {
            // Configurer la connexion centrale
            $centralConnection = Config::get('database.default');
            $databaseName = Config::get("database.connections.{$centralConnection}.database");
            $this->info("Database: {$databaseName}");
            
            // Vérifier la connexion
            $currentDb = DB::select("SELECT DATABASE() as db");
            $this->info("Connected to: " . $currentDb[0]->db);
            
            // Option fix : synchroniser la table migrations
            if ($this->option('fix')) {
                $this->fixMigrationsTable();
                return 0;
            }
            
            // Fresh migration si demandé
            if ($this->option('fresh')) {
                if (!$this->confirm('WARNING: This will drop ALL tables. Are you sure?')) {
                    return 0;
                }
                $this->warn('Dropping all tables...');
                $this->dropAllTables();
                $this->info('✓ All tables dropped');
            }
            
            // Créer la table migrations si elle n'existe pas
            $this->createMigrationsTable();
            
            // Exécuter les migrations
            $this->runMigrations();
            
            // Exécuter les seeders si demandé
            if ($this->option('seed')) {
                $this->info("Running seeders...");
                $this->call('db:seed', ['--force' => true]);
                $this->info("✓ Seeders completed");
            }
            
            $this->info("✓ Central database migration completed successfully");
            
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
    
    private function fixMigrationsTable()
    {
        $this->info("Fixing migrations table...");
        
        try {
            // Get all existing tables
            $tables = DB::select('SHOW TABLES');
            $database = Config::get("database.connections." . Config::get('database.default') . ".database");
            $tableKey = "Tables_in_{$database}";
            
            $existingTables = [];
            foreach ($tables as $table) {
                $existingTables[] = $table->$tableKey;
            }
            
            // Get all migration files
            $migrationPath = database_path('migrations');
            if (!is_dir($migrationPath)) {
                $this->error("Migration path not found: {$migrationPath}");
                return;
            }
            
            $migrationFiles = glob($migrationPath . '/*.php');
            
            // Filter out tenant migrations
            $migrationFiles = array_filter($migrationFiles, function($file) {
                return !str_contains($file, '/tenant/') && !str_contains($file, '/tenant\\');
            });
            
            $executedCount = 0;
            $batch = DB::table('migrations')->max('batch');
            if (!$batch) {
                $batch = 1;
            } else {
                $batch++;
            }
            
            foreach ($migrationFiles as $file) {
                $migrationName = basename($file, '.php');
                
                // Check if migration already recorded
                $exists = DB::table('migrations')
                    ->where('migration', $migrationName)
                    ->exists();
                
                if (!$exists) {
                    // Try to extract table name from migration
                    require_once $file;
                    $migrationClass = $this->getMigrationClass($file);
                    
                    if ($migrationClass) {
                        $reflection = new \ReflectionClass($migrationClass);
                        $properties = $reflection->getDefaultProperties();
                        
                        if (isset($properties['table'])) {
                            $tableName = $properties['table'];
                            
                            // Check if table exists in database
                            if (in_array($tableName, $existingTables)) {
                                $this->info("  ✓ Recording existing migration: {$migrationName} (table: {$tableName})");
                                DB::table('migrations')->insert([
                                    'migration' => $migrationName,
                                    'batch' => $batch,
                                ]);
                                $executedCount++;
                            } else {
                                $this->warn("  ⚠ Table {$tableName} not found for migration: {$migrationName}");
                            }
                        } else {
                            $this->warn("  ⚠ No table property found for migration: {$migrationName}");
                        }
                    }
                }
            }
            
            $this->info("✓ Fixed {$executedCount} migration(s)");
            
        } catch (\Exception $e) {
            $this->error("Error fixing migrations: " . $e->getMessage());
        }
    }
    
    private function getMigrationClass($file)
    {
        $content = file_get_contents($file);
        if (preg_match('/class\s+(\w+)\s+extends\s+Migration/', $content, $matches)) {
            $className = $matches[1];
            if (class_exists($className)) {
                return $className;
            }
        }
        return null;
    }
    
    private function dropAllTables()
    {
        // Récupérer toutes les tables
        $tables = DB::select('SHOW TABLES');
        $database = Config::get("database.connections." . Config::get('database.default') . ".database");
        $tableKey = "Tables_in_{$database}";
        
        if (!empty($tables)) {
            // Désactiver les contraintes de clés étrangères
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            foreach ($tables as $table) {
                $tableName = $table->$tableKey;
                if ($tableName !== 'migrations') {
                    DB::statement("DROP TABLE IF EXISTS `{$tableName}`");
                    $this->line("  Dropped: {$tableName}");
                }
            }
            
            // Vider la table migrations
            DB::table('migrations')->truncate();
            
            // Réactiver les contraintes
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
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
            DB::statement($createMigrationsTable);
            $this->info("✓ Migrations table ready");
        } catch (\Exception $e) {
            $this->warn("Migrations table might already exist: " . $e->getMessage());
        }
    }
    
    private function runMigrations()
    {
        // Utiliser le dossier migrations par défaut
        $migrationPath = database_path('migrations');
        
        if (!is_dir($migrationPath)) {
            $this->error("Migration path not found: {$migrationPath}");
            return;
        }
        
        // Récupérer les migrations déjà exécutées
        $executedMigrations = DB::table('migrations')
            ->pluck('migration')
            ->toArray();
        
        // Récupérer tous les fichiers de migration
        $migrationFiles = glob($migrationPath . '/*.php');
        
        // Filtrer pour exclure les migrations tenant
        $migrationFiles = array_filter($migrationFiles, function($file) {
            return !str_contains($file, '/tenant/') && !str_contains($file, '/tenant\\');
        });
        
        sort($migrationFiles);
        
        if (empty($migrationFiles)) {
            $this->warn("No migration files found");
            return;
        }
        
        $batch = DB::table('migrations')->max('batch') + 1;
        if (!$batch) {
            $batch = 1;
        }
        
        $executedCount = 0;
        
        foreach ($migrationFiles as $file) {
            $migrationName = basename($file, '.php');
            
            // Skip si déjà exécuté
            if (in_array($migrationName, $executedMigrations)) {
                $this->line("  ⚠ Skipping: {$migrationName}");
                continue;
            }
            
            $this->info("  Running: {$migrationName}");
            
            try {
                // Inclure le fichier de migration
                require_once $file;
                $migrationClass = $this->getMigrationClass($file);
                
                if ($migrationClass && method_exists($migrationClass, 'up')) {
                    $migration = new $migrationClass();
                    
                    // Exécuter la migration SANS transaction
                    $migration->up();
                    
                    // Enregistrer dans la table migrations
                    DB::table('migrations')->insert([
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
                
                // Demander s'il faut continuer
                if (!$this->confirm('Migration failed. Continue with next migrations?', false)) {
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