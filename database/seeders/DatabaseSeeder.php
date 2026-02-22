<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan seeder lainnya
        $this->call([
            RoleSeeder::class,
            SettingSeeder::class,
        ]);

        // Ambil role IDs
        $adminRole = Role::where('name', 'admin')->first();
        $teacherRole = Role::where('name', 'teacher')->first();
        $studentRole = Role::where('name', 'student')->first();

        // Buat user admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@elearning.com',
            'password' => Hash::make('admin123'),
            'role_id' => $adminRole->id,
        ]);

        // Buat user guru contoh
        User::create([
            'name' => 'Ahmad Thohir',
            'email' => 'thohir@elearning.com',
            'password' => Hash::make('password'),
            'role_id' => $teacherRole->id,
        ]);

        // Buat user siswa contoh
        User::create([
            'name' => 'Siswa',
            'email' => 'siswa@elearning.com',
            'password' => Hash::make('password'),
            'role_id' => $studentRole->id,
        ]);
    }
}
