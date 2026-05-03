<?php

namespace Database\Seeders;

use App\Models\BarangaySetting;
use Illuminate\Database\Seeder;

class BarangaySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BarangaySetting::updateOrCreate(
            ['barangay_name' => 'Alfonso XIII'],
            [
                'municipality' => 'Quezon',
                'province' => 'Palawan',
            ]
        );
    }
}
