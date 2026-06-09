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
            $domainName = $request->subdomain . '.site.test';
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
            DB::statement("DROP DATABASE IF EXISTS `{$databaseName}`");
            DB::statement("CREATE DATABASE `{$databaseName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            // 5-9. Exécution des migrations (inchangé)
            Config::set('database.connections.tenant.database', $databaseName);
            DB::purge('tenant');
            DB::reconnect('tenant');
            $originalDefaultConnection = DB::getDefaultConnection();
            DB::setDefaultConnection('tenant');
            
            // Exécuter migrations (même code que tu as)
            // ... (je garde ton code existant) ...
            
            DB::setDefaultConnection($originalDefaultConnection);
            
            // 10. Créer l'année scolaire (inchangé)
            // ...
            
            // ⚠️ ON NE CRÉE PAS L'UTILISATEUR ADMIN PENDANT L'INSCRIPTION !
            // On va le créer plus tard lors de l'activation
            
            // 11. Envoyer l'email de confirmation (SANS identifiants)
            try {
                Mail::to($request->email)->send(new SchoolRegistrationConfirmationMail($tenant, $domainName));
            } catch (\Exception $e) {
                Log::warning('Erreur envoi email confirmation', ['error' => $e->getMessage()]);
            }
            
            Log::info('Demande d\'inscription enregistrée', ['tenant_id' => $tenant->id]);
            
            // Redirection avec message d'attente
            return redirect('/')->with('success', 
                "✅ Votre demande d'inscription pour <strong>{$request->school_name}</strong> a bien été enregistrée !<br><br>
                📧 Un email de confirmation vous a été envoyé à <strong>{$request->email}</strong>.<br><br>
                ⏳ Notre équipe va étudier votre dossier et vous serez notifié par email dès l'activation de votre espace.<br>
                🔔 Cela peut prendre entre 24h et 48h ouvrées.<br><br>
                Merci de votre confiance ! 🚀");
            
        } catch (\Exception $e) {
            Log::error('Erreur création école', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
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