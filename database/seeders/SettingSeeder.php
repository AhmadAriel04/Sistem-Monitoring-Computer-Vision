<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        // Sistem Survey (Kiosk) membaca baris ini lewat helper setting()
        // untuk menampilkan logo, nama cabang, dan alamat di halaman utama.
        Setting::firstOrCreate(
            ['id' => 1],
            [
                'branch_name' => 'BSI KCP Balaraja 3',
                'address' => 'Jl. Raya Balaraja, Tangerang, Banten',
                'logo' => 'logo/logo-bsi.jpeg',
            ]
        );
    }
}
