<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('tenant.users.index', [
            'users' => User::get()
        ]);
    }

    public function create()
    {
        return view('tenant.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            // 'name'  => 'required|string',
            'first_name'  => 'required|string',
            'last_name'  => 'required|string',
            'email' => 'required|email|unique:users',
            'role'  => 'required|in:admin,staff,teacher',
        ]);

        User::create([
            'tenant_id' => app('tenant')->id,
            'name'      => $request->first_name . ' ' . $request->last_name,
            'first_name'      => $request->first_name,
            'last_name'      => $request->last_name,
            'email'     => $request->email,
            'role'      => $request->role,
            'password'  => Hash::make(Str::random(10)),
        ]);

        return redirect('/users')->with('success', 'Utilisateur créé');
    }

    public function edit($tenant, User $user)
    {
        $this->authorizeTenant($user);

        return view('tenant.users.edit', compact('user'));
    }

    public function update($tenant, Request $request, User $user)
    {
        $this->authorizeTenant($user);

        $user->update($request->only('name', 'email', 'role'));

        return redirect('/users')->with('success', 'Utilisateur modifié');
    }

    public function destroy($id)
    {
        // $this->authorizeTenant($user);
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'Utilisateur supprimé');
    }

    private function authorizeTenant(User $user)
    {
        abort_if($user->tenant_id !== app('tenant')->id, 403);
    }
}
