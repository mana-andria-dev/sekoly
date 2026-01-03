<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SchoolAccessMail;

class RegisterSchoolController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string',
            'email'       => 'required|email|unique:users',
            'first_name'  => 'required|string',
            'last_name'   => 'required|string',
            'phone'       => 'required|string',
            'address'     => 'required|string',
            'logo'        => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // 🔐 Mot de passe auto
            $password = Str::random(10);


            // 🏫 TENANT = ÉCOLE
            $slug = Str::slug($request->school_name);

            $tenant = Tenant::create([
                'name'    => $request->school_name,
                'slug'    => $slug,
                'email'   => $request->email,
                'address' => $request->address,
                'phone'   => $request->phone,
            ]);

            // 🖼️ Logo
            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('logos', 'public');
                $tenant->update(['logo_path' => $path]);
            }

            // 👤 Admin école
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'name'       => $request->first_name.' '.$request->last_name,
                'email'      => $request->email,
                'password'   => Hash::make($password),
                'tenant_id'  => $tenant->id,
                'role'       => 'admin',
            ]);

            // 📅 Année scolaire automatique
            $year = now()->year;

            SchoolYear::create([
                'tenant_id'  => $tenant->id,
                'name'       => $year . '-' . ($year + 1),
                'start_date' => now()->startOfYear(),
                'end_date'   => now()->addYear()->endOfYear(),
                'is_active'  => true,
            ]);

            // 📧 Email accès
            Mail::to($user->email)->send(
                new SchoolAccessMail($tenant, $user, $password)
            );

            DB::commit();

            return redirect('/')
                ->with('success', '🎉 Votre école a été créée ! Les accès ont été envoyés par email.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect('/')
                ->withErrors([
                    'register' => config('app.debug') ? $e->getMessage() : 'Erreur interne.'
                ]);
        }
    }
}
