<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::insert([
            [
                'name' => 'Pimpinan',
                'icon' => 'bi-person-badge'
            ],
            [
                'name' => 'Teller',
                'icon' => 'bi-cash-stack'
            ],
            [
                'name' => 'Customer Service',
                'icon' => 'bi-headset'
            ],
            [
                'name' => 'Security',
                'icon' => 'bi-shield-check'
            ],
            [
                'name' => 'Fasilitas',
                'icon' => 'bi-building'
            ]
        ]);
    }
}