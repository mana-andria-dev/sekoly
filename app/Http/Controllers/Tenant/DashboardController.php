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
        $tenant = app('tenant');

        return view('tenant.dashboard', [
            'tenant' => $tenant,
            'usersCount' => User::where('tenant_id', $tenant->id)->count(),
            'activeYear' => SchoolYear::where('tenant_id', $tenant->id)
                ->where('is_active', true)->first()
        ]);
    }
}
