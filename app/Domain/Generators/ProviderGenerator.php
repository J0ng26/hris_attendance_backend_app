<?php

namespace App\Domain\Generators;

use App\Domain\Stabs\ProviderStab;

class ProviderGenerator extends StabGenerator
{
    public function generate(array $context): array
    {
        return [
            $this->path("app/Providers/ServiceBindingProvider/{$context['providerClass']}.php") => $this->render(ProviderStab::getStub(), [
                'namespace' => 'App\\Providers\\ServiceBindingProvider',
                'interface' => "App\\Modules\\{$context['model']}\\Repository\\{$context['interfaceClass']}",
                'repository' => "App\\Modules\\{$context['model']}\\Repository\\Eloquent\\{$context['repositoryClass']}",
                'class' => $context['providerClass'],
                'interfaceClass' => $context['interfaceClass'],
                'repositoryClass' => $context['repositoryClass'],
            ]),
        ];
    }
}
