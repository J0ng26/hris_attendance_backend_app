<?php

namespace App\Domain\Stabs;

class MigrationStab
{
    public static function getStub(): string
    {
        return <<<'STUB'
        <?php

        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;

        return new class extends Migration
        {
            /**
             * Run the migrations.
             */
            public function up(): void
            {
                Schema::create('{{ table }}', function (Blueprint $table) {
                    $table->uuid('id')->primary();
        {{ columns }}            $table->timestamps();
                });
            }

            /**
             * Reverse the migrations.
             */
            public function down(): void
            {
                Schema::dropIfExists('{{ table }}');
            }
        };
        STUB;
    }
}
