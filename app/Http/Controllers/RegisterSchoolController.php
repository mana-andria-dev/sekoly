<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SchoolAccessMail;
use App\Mail\SchoolRegistrationConfirmationMail;
use Stancl\Tenancy\Database\Models\Domain;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

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
            'plan'        => 'required|in:basic,premium,enterprise',
            'subscription_period' => 'required|in:monthly,quarterly,yearly',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $prices = [
            'basic' => ['monthly' => 99, 'quarterly' => 270, 'yearly' => 990],
            'premium' => ['monthly' => 199, 'quarterly' => 540, 'yearly' => 1990],
            'enterprise' => ['monthly' => 299, 'quarterly' => 810, 'yearly' => 2990]
        ];
        
        $plan = $request->plan;
        $period = $request->subscription_period;
        $amount = $prices[$plan][$period];
        
        $startsAt = Carbon::now();
        $endsAt = match($period) {
            'monthly' => $startsAt->copy()->addMonth(),
            'quarterly' => $startsAt->copy()->addMonths(3),
            'yearly' => $startsAt->copy()->addYear(),
        };

        try {
            Log::info('Début création école', ['email' => $request->email]);
            
            $tenantId = (string) Str::uuid();
            
            // 🟢 CRÉATION AVEC STATUS 'pending' (désactivé)
            $tenant = Tenant::create([
                'id' => $tenantId,
                'name' => $request->school_name,
                'database' => 'tenant_' . $tenantId,
                'slug' => Str::slug($request->school_name),
                'email' => $request->email,
                'address' => $request->address,
                'phone' => $request->phone,
                'status' => 'pending',  // ← IMPORTANT : désactivé par défaut
                'data' => [
                    'plan' => $plan,
                    'subscription_period' => $period,
                    'max_students' => $this->getMaxStudents($plan),
                    'max_teachers' => $this->getMaxTeachers($plan),
                ],
            ]);
            
            Log::info('Tenant créé (status pending)', ['tenant_id' => $tenant->id]);
            
            // 2. Créer le domaine
            $domainName = $request->subdomain . config('app.tenant_base_domain');;
            Domain::create([
                'domain' => $domainName,
                'tenant_id' => $tenant->id,
            ]);
            
            // 3. Créer l'abonnement (mais le désactiver jusqu'à activation ?)
            $subscription = Subscription::create([
                'tenant_id' => $tenant->id,
                'plan' => $plan,
                'amount' => $amount,
                'status' => 'pending',  // ← En attente d'activation
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
            ]);
            
            // 4. Créer la base de données du tenant
            $databaseName = 'tenant_' . $tenant->id;
            DB::statement("CREATE DATABASE IF NOT EXISTS `{$databaseName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

// Debug
            $exists = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$databaseName]);
            Log::info('DB tenant créée', [
                'tenant_id' => $tenantId,
                'database' => $databaseName,
                'exists' => !empty($exists),
            ]);

            // Vérifie l'ordre des migrations AVANT exécution
            $migrationPath = database_path('migrations/tenant');
            $files = glob($migrationPath . '/*.php');
            sort($files);
            $classSubjectFiles = array_filter($files, fn($f) => str_contains($f, 'class_subject'));
            Log::info('Fichiers class_subject trouvés', [
                'files' => array_values($classSubjectFiles),
            ]);            
            
            // 5. Configurer la connexion tenant
            Config::set('database.connections.tenant.database', $databaseName);
            DB::purge('tenant');

            $originalDefaultConnection = DB::getDefaultConnection();
            DB::setDefaultConnection('tenant');

            try {
                // 🔑 Initialise le contexte tenant → déclenche loadMigrationsFrom
                tenancy()->initialize($tenant);

                \Illuminate\Support\Facades\Artisan::call('migrate', [
                    '--database' => 'tenant',
                    '--path'     => 'database/migrations/tenant',
                    '--force'    => true,
                ]);
                
                Log::info('Migrations tenant exécutées', [
                    'tenant_id' => $tenant->id,
                    'output' => \Illuminate\Support\Facades\Artisan::output(),
                ]);
                
                // 7. Seeders
                \Illuminate\Support\Facades\Artisan::call('db:seed', [
                    '--database' => 'tenant',
                    '--force' => true,
                ]);
                
                // 8. Année scolaire
                // DB::connection('tenant')->table('academic_years')->insert([
                //     'name' => date('Y') . '-' . (date('Y') + 1),
                //     'start_date' => date('Y') . '-09-01',
                //     'end_date' => (date('Y') + 1) . '-06-30',
                //     'is_current' => true,
                //     'created_at' => now(),
                //     'updated_at' => now(),
                // ]);
                
                Log::info('Année scolaire créée', ['tenant_id' => $tenant->id]);
                
            } catch (\Exception $e) {
                Log::error('Erreur migrations tenant', [
                    'tenant_id' => $tenant->id,
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            } finally {
                // 🔑 Termine le contexte tenant
                tenancy()->end();
                
                DB::setDefaultConnection($originalDefaultConnection);
                DB::purge('tenant');
            }

            // 🟢 SUCCÈS : Redirection vers l'accueil avec message
            Log::info('École créée avec succès', ['tenant_id' => $tenantId]);

            $successMessage = "
                <p><strong>Votre école a bien été enregistrée !</strong></p>
                <p>Voici vos informations d'accès :</p>
                <ul class='mb-2'>
                    <li><strong>École :</strong> {$request->school_name}</li>
                    <li><strong>URL d'accès :</strong> 
                        <a href='http://{$request->subdomain}.site.test' target='_blank'>
                            http://{$request->subdomain}.site.test
                        </a>
                    </li>
                    <li><strong>Email administrateur :</strong> {$request->email}</li>
                </ul>
                <p class='mb-0 text-muted small'>
                    <i class='bi bi-info-circle'></i> 
                    Votre compte est en attente d'activation par l'administrateur. 
                    Vous recevrez un email dès que votre école sera activée.
                </p>
            ";

            return redirect()->route('home')->with('success', $successMessage);
            
        } catch (\Exception $e) {
            Log::error('Erreur création école', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            // 🧹 Cleanup : supprime DB + enregistrements orphelins
            // if (isset($tenantId)) {
            //     $dbName = 'tenant_' . $tenantId;
            //     try {
            //         DB::statement("DROP DATABASE IF EXISTS `{$dbName}`");
            //         Domain::where('tenant_id', $tenantId)->delete();
            //         Subscription::where('tenant_id', $tenantId)->delete();
            //         Tenant::where('id', $tenantId)->delete();
            //         Log::info('Cleanup effectué', ['tenant_id' => $tenantId]);
            //     } catch (\Exception $cleanupError) {
            //         Log::error('Erreur cleanup', [
            //             'tenant_id' => $tenantId,
            //             'error' => $cleanupError->getMessage(),
            //         ]);
            //     }
            // }            
            
            return redirect()->back()
                ->withErrors(['error' => 'Erreur lors de la création: ' . $e->getMessage()])
                ->withInput();
        }
    }
    
    private function getMaxStudents($plan)
    {
        return match($plan) {
            'basic' => 200,
            'premium' => 500,
            'enterprise' => PHP_INT_MAX,
        };
    }
    
    private function getMaxTeachers($plan)
    {
        return match($plan) {
            'basic' => 20,
            'premium' => 50,
            'enterprise' => PHP_INT_MAX,
        };
    }
}