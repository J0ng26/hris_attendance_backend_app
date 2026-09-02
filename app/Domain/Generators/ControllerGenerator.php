<?php

namespace App\Domain\Generators;

use App\Domain\Stabs\ControllerStab;

class ControllerGenerator extends StabGenerator
{
    public function generate(array $context): array
    {
        return [
            $this->path("app/Http/Controllers/{$context['controllerClass']}.php") => $this->render(ControllerStab::getStub(), [
                'request' => "App\\Http\\Requests\\{$context['requestClass']}",
                'service' => "App\\Modules\\{$context['model']}\\Service\\{$context['serviceClass']}",
                'class' => $context['controllerClass'],
                'serviceVar' => $context['serviceVar'],
                'serviceClass' => $context['serviceClass'],
                'requestClass' => $context['requestClass'],
                'model' => $context['model'],
                'createArguments' => $this->controllerCreateArguments($context['fields']),
                'updateArguments' => $this->controllerUpdateArguments($context['fields']),
            ]),
        ];
    }
}
