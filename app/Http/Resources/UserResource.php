<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request)
    {
        return [
            'id' => $this->id ?? ($this['id'] ?? null),
            'username' => $this->username ?? ($this['username'] ?? null),
            'full_name' => trim(
                ($this->first_name ?? ($this['first_name'] ?? '')) .
                ' ' .
                ($this->middle_name ? substr($this->middle_name, 0, 1) . '. ' : '') .
                ($this->last_name ?? ($this['last_name'] ?? ''))
            ),
            'first_name' => $this->first_name ?? ($this['first_name'] ?? null),
            'middle_name' => $this->middle_name ?? ($this['middle_name'] ?? null),
            'last_name' => $this->last_name ?? ($this['last_name'] ?? null),
            'email' => $this->email ?? ($this['email'] ?? null),
            'email_verified_at' => $this->email_verified_at ?? ($this['email_verified_at'] ?? null),
            'active' => $this->active ?? ($this['active'] ?? null),
            'first_use' => $this->first_use ?? ($this['first_use'] ?? null),
            'user_type_id' => $this->user_type_id ?? ($this['user_type_id'] ?? null),
            'department_id' => $this->department_id ?? ($this['department_id'] ?? null),

            'user_type' => $this->when($this->user_type, function () {
                return [
                    'id' => $this->user_type->id ?? ($this->user_type['id'] ?? null),
                    'name' => $this->user_type->name
                        ?? ($this->user_type['name'] ?? null)
                        ?? ($this->user_type->description ?? ($this->user_type['description'] ?? null)),
                    'level' => $this->user_type->level ?? ($this->user_type['level'] ?? 0),
                ];
            }),

            'department' => $this->when($this->department, function () {
                return [
                    'id' => $this->department->id ?? ($this->department['id'] ?? null),
                    'name' => $this->department->name ?? ($this->department['name'] ?? null),
                ];
            }),

            'permissions' => collect($this->user_type->permissions ?? collect())
                ->pluck('permission_key')
                ->filter()
                ->unique()
                ->values()
                ->all(),
        ];
    }
}
