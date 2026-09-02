<?php

namespace App\Domain\Generators;

use App\Domain\Stabs\RouteStab;

class RouteGenerator extends StabGenerator
{
    public function generate(array $context): array
    {
        return [
            $this->path("routes/web/{$context['routeFile']}") => $this->render(RouteStab::getStub(), [
                'controller' => "App\\Http\\Controllers\\{$context['controllerClass']}",
                'controllerClass' => $context['controllerClass'],
                'prefix' => $context['prefix'],
            ]),
        ];
    }
}
