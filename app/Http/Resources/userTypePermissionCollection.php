<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserTypePermissionCollection extends ResourceCollection
{
    public static $wrap = null;
    
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request)
	{
		return $this->collection->map(function ($type) {
			$permissions = collect($type->permissions ?? [])
				->map(function ($p) {
					return [
						'id' => $p->id ?? ($p['id'] ?? null),
						'permission_key' => $p->permission_key ?? ($p['permission_key'] ?? null),
					];
				})
				->values()
				->all();

			return [
				'id' => $type->id ?? ($type['id'] ?? null),
				'name' => $type->name ?? ($type['name'] ?? null),
				'level' => $type->level ?? ($type['level'] ?? 0),
				'permissions' => $permissions,
			];
		})->values()->all();
	}
}
