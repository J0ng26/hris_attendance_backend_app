<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserType::create([
            'id' => '9da413c9-9b18-4a88-8f3b-313a68cc1795',
            'name' => 'Super Administrator',
            'level' => 100
        ]);

        UserType::create([
            'id' => '9da413c9-9dfc-40e0-b392-4f5b7647e82e',
            'name' => 'Administrator',
            'level' => 90
        ]);

        UserType::create([
            'id' => '9da413c9-9fa3-4fd5-a925-4df511b44a1c',
            'name' => 'Director',
            'level' => 80
        ]);

        UserType::create([
            'id' => '9da413c9-9ebf-4df1-b034-6c227b36e022',
            'name' => 'Manager',
            'level' => 70
        ]);

        UserType::create([
            'id' => '9da413c9-a095-4bfc-ba6a-96d3622dac3a',
            'name' => 'Assistant',
            'level' => 60
        ]);

        UserType::create([
            'id' => '9da413c9-9b1e-4c2a-bb6e-5a40c7b25e61',
            'name' => 'Staff',
            'level' => 50
        ]);

        UserType::create([
            'id' => '9da413c9-a21e-4c2a-bb6e-5a40c7b26e71',
            'name' => 'Client',
            'level' => 20
        ]);

        UserType::create([
            'id' => '9da413c9-a15e-40a8-9b4e-fba7a3b938af',
            'name' => 'Visitor',
            'level' => 10
        ]);
    }
}
