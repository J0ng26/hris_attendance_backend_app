<?php

namespace App\Domain\Generators;

use App\Domain\Stabs\RepositoryStab;

class RepositoryGenerator extends StabGenerator
{
    public function generate(array $context): array
    {
        return [
            $this->path("app/Modules/{$context['model']}/Repository/Eloquent/{$context['repositoryClass']}.php") => $this->render(RepositoryStab::getStub(), [
                'namespace' => "App\\Modules\\{$context['model']}\\Repository\\Eloquent",
                'model' => $context['model'],
                'class' => $context['repositoryClass'],
                'interface' => $context['interfaceClass'],
                'interfaceNamespace' => "App\\Modules\\{$context['model']}\\Repository\\{$context['interfaceClass']}",
            ]),
        ];
    }
}
