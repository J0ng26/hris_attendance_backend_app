<?php

namespace App\Domain\Stabs;

class RepositoryStab
{
    public static function getStub(): string
    {
        return <<<'STUB'
        <?php

        namespace {{ namespace }};

        use App\Models\{{ model }};
        use App\Modules\BaseRepository;
        use {{ interfaceNamespace }};

        class {{ class }} extends BaseRepository implements {{ interface }}
        {
            /**
             * Constructor to set the {{ model }} model.
             */
            public function __construct({{ model }} $model)
            {
                parent::__construct($model);
            }
                
            /**
            |--------------------------------------------------------------------------
            | Custom Methods
            |--------------------------------------------------------------------------
            | Add methods that extend the base repository contract.
            | Business-specific queries should live here.
            */
        }
        STUB;
    }
}
