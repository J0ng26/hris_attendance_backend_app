<?php

namespace App\Domain\Generators;

use App\Domain\Stabs\RequestStab;

class RequestGenerator extends StabGenerator
{
    public function generate(array $context): array
    {
        return [
            $this->path("app/Http/Requests/{$context['requestClass']}.php") => $this->render(RequestStab::getStub(), [
                'class' => $context['requestClass'],
                'table' => $context['table'],
                'storeRules' => $this->validationRules($context['fields'], $context['table']),
                'updateRules' => $this->validationRules($context['fields'], $context['table']),
            ]),
        ];
    }
}
