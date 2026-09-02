<?php

namespace App\Domain\Stabs;

class ModelStab
{
    public static function getStub(): string
    {
        return <<<'STUB'
        <?php

        namespace {{ namespace }};

        use App\Traits\LogActivities;
        use Illuminate\Database\Eloquent\Concerns\HasUuids;
        use Illuminate\Database\Eloquent\Model;

        class {{ class }} extends Model
        {
            use HasUuids, LogActivities;

            /**
             * The attributes that are mass assignable.
             *
             * @var array<int, string>
             */
            protected $guarded = [];

            /**
             * The attributes that should be hidden for serialization.
             *
             * @var array<int, string>
             */
            protected $fillable = [
        {{ fillable }}
            ];

            /**
             * The attributes that should be cast.
             *
             * @return array<string, string>
             */
            protected function casts(): array
            {
                return [
                    //
                ];
            }

            /**
            |--------------------------------------------------------------------------
            | Eloquent Relationships
            |--------------------------------------------------------------------------
            | Define all relationships for this model here
            */
        }
        STUB;
    }
}
