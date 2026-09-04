<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserTypeSeeder::class,
            PermissionSeeder::class,
        ]);

        $this->call([
            AppSettingsSeeder::class
        ]);

        User::create([
            'username' => config('app.user_name'),
            'first_name' => config('app.user_first_name'),
            'middle_name' => config('app.user_middle_name'),
            'last_name' => config('app.user_last_name'),
            'email' => config('app.user_email'),
            'password' => bcrypt(config('app.user_password')),
            'active' => true,
            'user_type_id' => config('app.user_type_id'),
            'first_use' => false,
        ]);

        if (!User::where('username', 'johndoe')->exists()) {
            User::create([
                'username' => 'johndoe',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@company.com',
                'password' => bcrypt('password123'),
                'active' => true,
                'user_type_id' => config('app.user_type_id'),
                'first_use' => false
            ]);
        }
    }
}
