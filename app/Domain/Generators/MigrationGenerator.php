<?php

namespace App\Domain\Generators;

use App\Domain\Stabs\MigrationStab;

class MigrationGenerator extends StabGenerator
{
    public function generate(array $context): array
    {
        $timestamp = date('Y_m_d_His');

        return [
            $this->path("database/migrations/{$timestamp}_create_{$context['table']}_table.php") => $this->render(MigrationStab::getStub(), [
                'table' => $context['table'],
                'columns' => $this->migrationColumns($context['fields']),
            ]),
        ];
    }
}
