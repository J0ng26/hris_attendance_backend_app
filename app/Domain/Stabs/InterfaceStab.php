<?php

namespace App\Domain\Stabs;

class InterfaceStab
{
    public static function getStub(): string
    {
        return <<<'STUB'
        <?php

        namespace {{ namespace }};

        use App\Modules\EloquentRepositoryInterface;

        interface {{ class }} extends EloquentRepositoryInterface
        {
            /**
            |--------------------------------------------------------------------------
            | Custom Methods
            |--------------------------------------------------------------------------
            | Add methods that extend the EloquentRepositoryInterface signature.
            | Contract methods should be defined here.
            */
        }
        STUB;
    }
}
