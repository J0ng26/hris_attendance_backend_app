<?php

namespace App\Domain\Stabs;

class ProviderStab
{
    public static function getStub(): string
    {
        return <<<'STUB'
        <?php

        namespace {{ namespace }};

        use {{ interface }};
        use {{ repository }};
        use Illuminate\Support\ServiceProvider;

        class {{ class }} extends ServiceProvider
        {

            /**
             * Register services.
             */
            public function register(): void
            {
                $this->app->bind({{ interfaceClass }}::class, {{ repositoryClass }}::class);
            }

            /**
             * Bootstrap services.
             */
            public function boot(): void
            {
                //
            }
        }
        STUB;
    }
}
