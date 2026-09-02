<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NonAdminUserResource extends ResourceCollection
{
    public static $wrap = null;

    public function toArray($request): array
    {
        return $this->collection->map(function ($user) {
            return [
                'id' => $user->id,
                'full_name' => trim(
                    $user->last_name . ', ' .
                    $user->first_name . ' ' .
                    ($user->middle_name ? substr($user->middle_name, 0, 1) . '.' : ' ')
                ),
                'active' => $user->active,
            ];
        })->all();
    }
}