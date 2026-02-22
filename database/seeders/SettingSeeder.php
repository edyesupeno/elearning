<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::set('app_name', 'E-Learning');
        Setting::set('app_description', 'Platform pembelajaran online untuk guru dan murid');
    }
}
