<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        AdminUser::create([
            'name' => 'Super Admin',
            'email' => 'admin@sekoly.com',
            'password' => Hash::make('admin2026'),
        ]);
    }
}