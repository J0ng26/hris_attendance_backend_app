<?php

namespace App\Http\Requests;

class PermissionRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $action = $this->route()?->getActionMethod();

        return match ($action) {
            'create' => $this->createRules(),
            'update' => $this->updateRules(),
            'delete' => $this->deleteRules(),
            'manage' => $this->manageRules(),
            default => [],
        };
    }

    private function createRules(): array
    {
        return [
            'title' => 'required|string|unique:permissions,title',
        ];
    }

    private function updateRules(): array
    {
        return [
            'oldTitle' => 'required|string|exists:permissions,title',
            'newTitle' => 'required|string|unique:permissions,title',
        ];
    }

    private function deleteRules(): array
    {
        return [
            'title' => 'required|string|exists:permissions,title',
        ];
    }

    private function manageRules(): array
    {
        return [
            'user_type_id' => 'required|string||exists:user_types,id',
            'permissions' => 'required|array',
            'permission_ids.*' => 'string|exists:permissions,id'
        ];
    }
}
