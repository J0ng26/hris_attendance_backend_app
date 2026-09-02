<?php

namespace App\Http\Requests;

class UserRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $action = $this->route()?->getActionMethod();

        return match ($action) {
            'add' => $this->addRules(),
            'edit' => $this->editRules(),
            'updateProfile' => $this->updateProfileRules(),
            'delete' => $this->deleteRules(),
            default => [],
        };
    }

    private function addRules(): array
    {
        return [
            'username' => 'required|string|unique:users',
            'first_name' => 'required|string|max:45',
            'middle_name' => 'nullable|string|max:45',
            'last_name' => 'required|string|max:45',
            'email' => 'required|email|unique:users',
            'active' => 'required|boolean',
            'user_type_id' => 'required|string',
            'department_id' => 'nullable|string',
        ];
    }

    private function editRules(): array
    {
        return [
            'id' => 'required|string',
            'username' => 'required|string',
            'first_name' => 'required|string|max:45',
            'middle_name' => 'nullable|string|max:45',
            'last_name' => 'required|string|max:45',
            'email' => 'required|email',
            'active' => 'required|boolean',
            'user_type_id' => 'required|string',
            'department_id' => 'nullable|string',
        ];
    }

    private function updateProfileRules(): array
    {
        return [
            'first_name' => 'required|string|max:45',
            'middle_name' => 'nullable|string|max:45',
            'last_name' => 'required|string|max:45',
            'email' => 'required|email',
        ];
    }

    private function deleteRules(): array
    {
        return [
            'id' => 'required|string',
        ];
    }
}
