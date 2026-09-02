<?php

namespace App\Domain\Generators;

use App\Domain\Stabs\InterfaceStab;

class InterfaceGenerator extends StabGenerator
{
    public function generate(array $context): array
    {
        return [
            $this->path("app/Modules/{$context['model']}/Repository/{$context['interfaceClass']}.php") => $this->render(InterfaceStab::getStub(), [
                'namespace' => "App\\Modules\\{$context['model']}\\Repository",
                'class' => $context['interfaceClass'],
            ]),
        ];
    }
}
