<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PermissionCollection extends ResourceCollection
{
	public static $wrap = null;
	
	/**
	 * Transform the resource collection into an array of unique, title-cased names.
	 *
	 * @param Request $request
	 * @return array
	 */
	public function toArray($request): array
	{
		return $this->collection
			->pluck('name')
			->map(function ($name) {
				return ucwords(strtolower($name));
			})
			->unique()
			->values()
			->map(function ($name) {
				return ['name' => $name];
			})
			->all();
	}
}