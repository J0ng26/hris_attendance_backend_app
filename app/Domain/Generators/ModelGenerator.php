<?php

namespace App\Domain\Generators;

use App\Domain\Stabs\ModelStab;

class ModelGenerator extends StabGenerator
{
    public function generate(array $context): array
    {
        return [
            $this->path("app/Models/{$context['model']}.php") => $this->render(ModelStab::getStub(), [
                'namespace' => 'App\\Models',
                'class' => $context['model'],
                'fillable' => $this->fillable($context['fields']),
            ]),
        ];
    }
}
