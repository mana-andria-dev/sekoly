<?php
// app/Http/Controllers/RegisterSchoolController.php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SchoolAccessMail;
use Stancl\Tenancy\Database\Models\Domain;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class RegisterSchoolController extends Controller
{
    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'school_name' => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'phone'       => 'required|string|max:20',
            'address'     => 'required|string',
            'logo'        => 'nullable|image|max:2048',
            'subdomain'   => 'required|string|alpha_dash|max:50|unique:domains,domain',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Log::info('Début création école', ['email' => $request->email]);
            
            $password = Str::random(10);
            
            // 1. Créer le tenant
            $tenantId = (string) Str::uuid();
            
            $tenant = Tenant::create([
                'id' => $tenantId,
                'database' => 'tenant_'. $tenantId,
                'name' => $request->school_name,
                'slug' => Str::slug($request->school_name),
                'email' => $request->email,
                'address' => $request->address,
                'phone' => $request->phone,
                'data' => json_encode([]),
            ]);
            
            // 2. Créer le domaine
            $domainName = $request->subdomain . '.site.test';
            Domain::create([
                'domain' => $domainName,
                'tenant_id' => $tenant->id,
            ]);
            
            // 3. Créer la base de données du tenant
            $databaseName = 'tenant_' . $tenant->id;
            Log::info('Création base de données', ['database' => $databaseName]);
            
            DB::statement("DROP DATABASE IF EXISTS `{$databaseName}`");
            DB::statement("CREATE DATABASE `{$databaseName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            // 4. Configurer et reconnecter la connexion tenant
            Config::set('database.connections.tenant.database', $databaseName);
            DB::purge('tenant');
            DB::reconnect('tenant');
            
            // 5. Sauvegarder la connexion par défaut originale
            $originalDefaultConnection = DB::getDefaultConnection();
            
            // 6. Changer la connexion par défaut pour 'tenant'
            DB::setDefaultConnection('tenant');
            Log::info('Connexion par défaut changée pour tenant', ['default' => DB::getDefaultConnection()]);
            
            // 7. Exécuter les migrations
            Log::info('Exécution des migrations tenant');
            
            $migrationPath = database_path('migrations/tenant');
            if (!is_dir($migrationPath)) {
                mkdir($migrationPath, 0755, true);
            }
            
            $migrationFiles = glob($migrationPath . '/*.php');
            sort($migrationFiles);
            
            if (empty($migrationFiles)) {
                Log::warning('Aucune migration trouvée dans ' . $migrationPath);
            }
            
            // Créer la table migrations
            $createMigrationsTable = "
                CREATE TABLE IF NOT EXISTS `migrations` (
                    `id` int unsigned NOT NULL AUTO_INCREMENT,
                    `migration` varchar(255) NOT NULL,
                    `batch` int NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ";
            
            DB::statement($createMigrationsTable);
            Log::info('Table migrations créée/vérifiée');
            
            // Exécuter les migrations
            $batch = DB::table('migrations')->max('batch') + 1;
            if (!$batch) {
                $batch = 1;
            }
            
            foreach ($migrationFiles as $file) {
                $migrationName = basename($file, '.php');
                
                $exists = DB::table('migrations')
                    ->where('migration', $migrationName)
                    ->exists();
                
                if (!$exists) {
                    Log::info('Exécution migration', ['migration' => $migrationName]);
                    
                    try {
                        $migration = require $file;
                        
                        if (is_object($migration) && method_exists($migration, 'up')) {
                            $migration->up();
                            
                            DB::table('migrations')->insert([
                                'migration' => $migrationName,
                                'batch' => $batch,
                            ]);
                            
                            Log::info('Migration exécutée avec succès', ['migration' => $migrationName]);
                        } else {
                            Log::warning('Fichier de migration invalide', ['file' => $file]);
                        }
                    } catch (\Exception $e) {
                        Log::error('Erreur sur la migration', [
                            'migration' => $migrationName,
                            'error' => $e->getMessage(),
                            'file' => $file
                        ]);
                        throw new \Exception("Erreur sur la migration {$migrationName}: " . $e->getMessage());
                    }
                }
            }
            
            
            // 9. Restaurer la connexion par défaut originale
            DB::setDefaultConnection($originalDefaultConnection);
            Log::info('Connexion par défaut restaurée', ['default' => DB::getDefaultConnection()]);
            
            // 10. Créer l'année scolaire par défaut
            try {
                DB::connection('tenant')->reconnect();
                
                if (Schema::connection('tenant')->hasTable('school_years')) {
                    $year = now()->year;
                    DB::connection('tenant')->table('school_years')->insert([
                        'name' => $year . '-' . ($year + 1),
                        'start_date' => now()->startOfYear(),
                        'end_date' => now()->addYear()->endOfYear(),
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    Log::info('Année scolaire créée avec succès');
                }
            } catch (\Exception $e) {
                Log::warning('Insertion année scolaire ignorée', ['error' => $e->getMessage()]);
            }
            
            // 11. Créer l'utilisateur admin dans la base centrale
            $centralUser = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($password),
                'tenant_id' => $tenant->id,
                'role' => 'admin',
                'is_active' => true,
            ]);

            // Puis créer l'utilisateur tenant avec le MÊME email mais son propre ID
            // On n'utilise pas le même ID pour éviter les conflits
            DB::connection('tenant')->table('users')->insert([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $centralUser->email, // Même email
                'password' => $centralUser->password, // Même mot de passe
                'role' => 'admin',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);            
            
            // Récupérer l'ID généré automatiquement
            $tenantUserId = DB::connection('tenant')->table('users')
                ->where('email', $centralUser->email)
                ->value('id');
            
            Log::info('Utilisateurs créés', [
                'central_user_id' => $centralUser->id,
                'tenant_user_id' => $tenantUserId
            ]);

            
            // 12. Gérer le logo
            if ($request->hasFile('logo')) {
                try {
                    $path = $request->file('logo')->store('logos', 'public');
                    $tenant->update(['logo_path' => $path]);
                } catch (\Exception $e) {
                    Log::warning('Erreur upload logo', ['error' => $e->getMessage()]);
                }
            }
            
            // 13. Envoyer l'email (utiliser l'utilisateur central)
            try {
                Mail::to($centralUser->email)->send(new SchoolAccessMail($tenant, $centralUser, $password, $domainName));
            } catch (\Exception $e) {
                Log::warning('Erreur envoi email', ['error' => $e->getMessage()]);
            }
            
            Log::info('École créée avec succès', ['tenant_id' => $tenant->id]);
            
            return redirect('/')->with('success', "🎉 École créée avec succès !<br>
                Accès : http://{$domainName}<br>
                Email: {$centralUser->email}<br>
                Mot de passe: {$password}<br>
                <strong>⚠️ Important : Veuillez changer votre mot de passe à la première connexion.</strong>");
            
        } catch (\Exception $e) {
            Log::error('Erreur création école', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withErrors(['error' => 'Erreur lors de la création: ' . $e->getMessage()])
                ->withInput();
        }
    }
}