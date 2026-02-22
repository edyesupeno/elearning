<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Role::create(['name' => 'admin']);
        \App\Models\Role::create(['name' => 'guru']);
        \App\Models\Role::create(['name' => 'murid']);

        // Create default admin user
        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@elearning.com',
            'password' => bcrypt('password'),
            'role_id' => 1
        ]);
    }
}
