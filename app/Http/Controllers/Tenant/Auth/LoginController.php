<?php
// app/Http/Controllers/Tenant/Auth/LoginController.php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Déboguer la connexion
        \Log::info('Tenant login form', [
            'database' => DB::connection()->getDatabaseName(),
            'tenant_id' => tenant() ? tenant()->id : null,
        ]);
        
        // Vérifier si déjà connecté
        if (Auth::guard('tenant')->check()) {
            return redirect()->route('tenant.dashboard');
        }
        
        return view('tenant.auth.login');
    }
    
    public function login(Request $request)
    {
        // Vérifier que le tenant est bien chargé
        $tenant = tenant();
        if (!$tenant) {
            \Log::error('No tenant found during login');
            return back()->withErrors(['email' => 'Configuration error: No tenant found.']);
        }
        
        \Log::info('Attempting tenant login', [
            'tenant_id' => $tenant->id,
            'database' => DB::connection()->getDatabaseName(),
            'email' => $request->email
        ]);
        
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        
        // Configurer explicitement la connexion tenant
        $connection = config('database.default');
        \Log::info('Current connection', ['connection' => $connection]);
        
        // Tenter la connexion avec le guard tenant
        if (Auth::guard('tenant')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::guard('tenant')->user();
            
            \Log::info('Tenant login successful', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'database' => DB::connection()->getDatabaseName()
            ]);
            
            if (!$user->is_active) {
                Auth::guard('tenant')->logout();
                return back()->withErrors([
                    'email' => 'Ce compte est désactivé. Veuillez contacter l\'administrateur.'
                ]);
            }
            
            session([
                'tenant_user_id' => $user->id,
                'tenant_user_role' => $user->role,
                'tenant_user_name' => $user->name,
            ]);
            
            return redirect()->intended(route('tenant.dashboard'));
        }
        
        \Log::warning('Tenant login failed', ['email' => $request->email]);
        
        return back()->withErrors([
            'email' => 'Les identifiants fournis sont incorrects.',
        ])->onlyInput('email');
    }
    
    public function logout(Request $request)
    {
        Auth::guard('tenant')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('tenant.login');
    }
}