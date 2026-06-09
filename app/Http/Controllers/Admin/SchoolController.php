<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Subscription;
use App\Mail\SchoolAccessMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = Tenant::with('subscriptions')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.schools.index', compact('schools'));
    }
    
    public function show($id)
    {
        $school = Tenant::with('subscriptions', 'domains')->findOrFail($id);
        return view('admin.schools.show', compact('school'));
    }
    
    public function create()
    {
        return view('admin.schools.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);
        
        $slug = Str::slug($validated['name']);
        $databaseName = 'tenant_' . Str::uuid();
        
        $tenant = Tenant::create([
            'id' => (string) Str::uuid(),
            'name' => $validated['name'],
            'database' => $databaseName,
            'slug' => $slug,
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'status' => 'pending',
            'data' => [],
        ]);
        
        // Créer la base de données du tenant
        $tenant->create();
        
        return redirect()->route('admin.schools.index')
            ->with('success', 'École créée avec succès');
    }
    
    public function edit($id)
    {
        $school = Tenant::findOrFail($id);
        return view('admin.schools.edit', compact('school'));
    }
    
    public function update(Request $request, $id)
    {
        $school = Tenant::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);
        
        $school->update($validated);
        
        return redirect()->route('admin.schools.index')
            ->with('success', 'École mise à jour avec succès');
    }
    
    public function destroy($id)
    {
        $school = Tenant::findOrFail($id);
        
        // Supprimer la base de données du tenant
        $school->delete();
        
        return redirect()->route('admin.schools.index')
            ->with('success', 'École supprimée avec succès');
    }
    
    /**
     * Activer une école et envoyer les accès par email
     */
    public function activate($id)
    {
        $tenant = Tenant::with('domains')->findOrFail($id);
        
        if ($tenant->status === 'active') {
            return redirect()->back()->with('error', 'Cette école est déjà activée.');
        }
        
        try {
            DB::beginTransaction();
            
            // Vérifier si un utilisateur existe déjà
            $existingUser = User::where('email', $tenant->email)->first();
            
            if (!$existingUser) {
                // Générer un mot de passe temporaire
                $password = Str::random(10);
                
                // Créer l'utilisateur admin dans la base centrale
                $centralUser = User::create([
                    'name' => $tenant->name . ' Admin',
                    'email' => $tenant->email,
                    'password' => Hash::make($password),
                    'email_verified_at' => now(),
                ]);
                
                // Créer l'utilisateur dans la base du tenant
                $this->createTenantUser($tenant, $centralUser);
            } else {
                // Si l'utilisateur existe déjà, générer un nouveau mot de passe
                $password = Str::random(10);
                $existingUser->password = Hash::make($password);
                $existingUser->save();
                $centralUser = $existingUser;
                
                // Mettre à jour le mot de passe dans la base tenant
                $this->updateTenantUserPassword($tenant, $centralUser);
            }
            
            // Mettre à jour le statut du tenant
            $tenant->update([
                'status' => 'active',
                'activated_at' => now(),
            ]);
            
            // Activer l'abonnement s'il existe
            $subscription = Subscription::where('tenant_id', $tenant->id)->first();
            if ($subscription && $subscription->status === 'pending') {
                $subscription->update(['status' => 'active']);
            }
            
            // Récupérer le domaine
            $domain = $tenant->domains()->first();
            $domainName = $domain ? $domain->domain : $tenant->slug . '.site.test';
            
            DB::commit();
            
            // Envoyer l'email d'accès
            Mail::to($centralUser->email)->send(new SchoolAccessMail($tenant, $centralUser, $password, $domainName, $subscription));
            
            Log::info('École activée avec succès', ['tenant_id' => $tenant->id, 'email' => $tenant->email]);
            
            return redirect()->route('admin.schools.index')
                ->with('success', "✅ École <strong>{$tenant->name}</strong> activée avec succès !<br>Un email contenant les identifiants a été envoyé à <strong>{$tenant->email}</strong>.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur activation école', [
                'tenant_id' => $tenant->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Erreur lors de l\'activation: ' . $e->getMessage());
        }
    }
    
    /**
     * Désactiver une école
     */
    public function deactivate($id)
    {
        $tenant = Tenant::findOrFail($id);
        
        if ($tenant->status === 'pending') {
            return redirect()->back()->with('error', 'Cette école n\'est pas encore activée.');
        }
        
        try {
            $tenant->update([
                'status' => 'suspended',
                'suspended_at' => now(),
            ]);
            
            // Désactiver l'abonnement
            $subscription = Subscription::where('tenant_id', $tenant->id)->first();
            if ($subscription) {
                $subscription->update(['status' => 'suspended']);
            }
            
            Log::info('École désactivée', ['tenant_id' => $tenant->id]);
            
            return redirect()->route('admin.schools.index')
                ->with('warning', "⚠️ École <strong>{$tenant->name}</strong> a été désactivée.");
                
        } catch (\Exception $e) {
            Log::error('Erreur désactivation école', [
                'tenant_id' => $tenant->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Erreur lors de la désactivation: ' . $e->getMessage());
        }
    }
    
    /**
     * Renvoyer les accès par email
     */
    public function resendAccess($id)
    {
        $tenant = Tenant::with('domains')->findOrFail($id);
        
        if ($tenant->status !== 'active') {
            return redirect()->back()->with('error', 'Cette école n\'est pas active. Veuillez l\'activer d\'abord.');
        }
        
        try {
            // Récupérer l'utilisateur
            $user = User::where('email', $tenant->email)->first();
            
            if (!$user) {
                return redirect()->back()->with('error', 'Aucun utilisateur trouvé pour cette école.');
            }
            
            // Générer un nouveau mot de passe
            $newPassword = Str::random(10);
            $user->password = Hash::make($newPassword);
            $user->save();
            
            // Mettre à jour dans la base tenant
            $this->updateTenantUserPassword($tenant, $user);
            
            // Récupérer l'abonnement
            $subscription = Subscription::where('tenant_id', $tenant->id)->first();
            $domain = $tenant->domains()->first();
            $domainName = $domain ? $domain->domain : $tenant->slug . '.site.test';
            
            // Envoyer l'email avec les nouveaux accès
            Mail::to($user->email)->send(new SchoolAccessMail($tenant, $user, $newPassword, $domainName, $subscription));
            
            return redirect()->back()->with('success', "📧 Un nouvel email avec les accès a été envoyé à <strong>{$tenant->email}</strong>.");
            
        } catch (\Exception $e) {
            Log::error('Erreur renvoi accès', [
                'tenant_id' => $tenant->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Erreur lors de l\'envoi: ' . $e->getMessage());
        }
    }
    
    /**
     * Créer l'utilisateur dans la base du tenant
     */
    private function createTenantUser($tenant, $centralUser)
    {
        try {
            // Sauvegarder la connexion par défaut originale
            $originalDefaultConnection = DB::getDefaultConnection();
            
            // Se connecter à la base du tenant
            Config::set('database.connections.tenant.database', $tenant->database);
            DB::purge('tenant');
            DB::reconnect('tenant');
            DB::setDefaultConnection('tenant');
            
            // Vérifier si la table users existe
            if (Schema::connection('tenant')->hasTable('users')) {
                // Vérifier si l'utilisateur existe déjà
                $exists = DB::connection('tenant')->table('users')
                    ->where('email', $centralUser->email)
                    ->exists();
                
                if (!$exists) {
                    DB::connection('tenant')->table('users')->insert([
                        'first_name' => $tenant->name,
                        'last_name' => 'Administrateur',
                        'name' => $tenant->name . ' Admin',
                        'email' => $centralUser->email,
                        'password' => $centralUser->password,
                        'role' => 'admin',
                        'is_active' => true,
                        'email_verified_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    Log::info('Utilisateur créé dans la base tenant', [
                        'tenant_id' => $tenant->id,
                        'email' => $centralUser->email
                    ]);
                }
            } else {
                Log::warning('Table users non trouvée dans la base tenant', [
                    'tenant_id' => $tenant->id,
                    'database' => $tenant->database
                ]);
            }
            
            // Restaurer la connexion par défaut originale
            DB::setDefaultConnection($originalDefaultConnection);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de l\'utilisateur tenant', [
                'tenant_id' => $tenant->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
    
    /**
     * Mettre à jour le mot de passe de l'utilisateur dans la base du tenant
     */
    private function updateTenantUserPassword($tenant, $user)
    {
        try {
            // Sauvegarder la connexion par défaut originale
            $originalDefaultConnection = DB::getDefaultConnection();
            
            // Se connecter à la base du tenant
            Config::set('database.connections.tenant.database', $tenant->database);
            DB::purge('tenant');
            DB::reconnect('tenant');
            DB::setDefaultConnection('tenant');
            
            // Vérifier si la table users existe
            if (Schema::connection('tenant')->hasTable('users')) {
                DB::connection('tenant')->table('users')
                    ->where('email', $user->email)
                    ->update(['password' => $user->password]);
                    
                Log::info('Mot de passe utilisateur mis à jour dans la base tenant', [
                    'tenant_id' => $tenant->id,
                    'email' => $user->email
                ]);
            }
            
            // Restaurer la connexion par défaut originale
            DB::setDefaultConnection($originalDefaultConnection);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du mot de passe tenant', [
                'tenant_id' => $tenant->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}