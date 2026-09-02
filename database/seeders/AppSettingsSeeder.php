<?php

namespace Database\Seeders;

use App\Models\AppSettings;
use Illuminate\Database\Seeder;

class AppSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppSettings::create([
            'lock_api' => false,
        ]);
    }
}
