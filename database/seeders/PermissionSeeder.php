<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Enums\PermissionKey;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $titles = [
            'Department',
            'Permission',
            'User',
            'User Type',
        ];

        foreach ($titles as $title) {
            $this->seedPermissions($title);
        }

        Permission::updateOrCreate(
            ['permission_key' => 'user.activate'],
            ['name' => $this->getDisplayName('User')]
        );
    }

    private function seedPermissions(string $title): void
    {
        $base = $this->getBase($title);
        $name = $this->getDisplayName($title);

        foreach (PermissionKey::values() as $action) {
            Permission::updateOrCreate(
                ['permission_key' => $base . '.' . $action],
                ['name' => $name]
            );
        }
    }

    private function getBase(string $title): string
    {
        return Str::kebab(Str::singular($title));
    }

    private function getDisplayName(string $title): string
    {
        return Str::title(Str::singular(trim($title)));
    }

}
