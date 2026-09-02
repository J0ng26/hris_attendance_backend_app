<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserPermissionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return $this->collection->map(function ($userPermission) {
            return [
                'id' => $userPermission->id,
                'permission_key' => $userPermission->permission->permission_key,
            ];
        })->all();
    }
}