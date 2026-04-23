<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolYear;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Utiliser directement tenant() au lieu de app('tenant')
        $tenant = tenant();
        
        // Vérifier que le tenant existe
        if (!$tenant) {
            abort(403, 'Tenant non trouvé');
        }

        return view('tenant.dashboard', [
            'tenant' => $tenant,
            'usersCount' => User::count(),
            'activeYear' => SchoolYear::where('is_active', true)->first()
        ]);
    }
}