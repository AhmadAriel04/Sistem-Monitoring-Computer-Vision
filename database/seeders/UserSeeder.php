<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Membuat akun default untuk masing-masing role, khusus jika belum ada
     * satu pun user di database. Password default WAJIB diganti setelah
     * login pertama kali (lewat menu Profil).
     */
    public function run(): void
    {
        if (User::count() > 0) {
            return;
        }

        User::create([
            'name' => 'Administrator',
            'email' => 'admin@bsi.co.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'BOSM',
            'email' => 'bosm@bsi.co.id',
            'password' => Hash::make('password'),
            'role' => 'bosm',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Manager',
            'email' => 'manager@bsi.co.id',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'email_verified_at' => now(),
        ]);
    }
}
