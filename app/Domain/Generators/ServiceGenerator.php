<?php

namespace App\Domain\Generators;

use App\Domain\Stabs\ServiceStab;

class ServiceGenerator extends StabGenerator
{
    public function generate(array $context): array
    {
        return [
            $this->path("app/Modules/{$context['model']}/Service/{$context['serviceClass']}.php") => $this->render(ServiceStab::getStub(), [
                'namespace' => "App\\Modules\\{$context['model']}\\Service",
                'interface' => "App\\Modules\\{$context['model']}\\Repository\\{$context['interfaceClass']}",
                'class' => $context['serviceClass'],
                'interfaceClass' => $context['interfaceClass'],
                'repositoryVar' => $context['repositoryVar'],
                'model' => $context['model'],
                'createParameters' => $this->serviceCreateParameters($context['fields']),
                'updateParameters' => $this->serviceUpdateParameters($context['fields']),
                'createData' => $this->repositoryData($context['fields']),
                'updateData' => $this->repositoryData($context['fields']),
            ]),
        ];
    }
}
