<?php

namespace App\Http\Requests;

class UserTypeRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $action = $this->route()?->getActionMethod();

        return match ($action) {
            'all' => $this->allRules(),
            'create' => $this->createRules(),
            'update' => $this->updateRules(),
            'delete' => $this->deleteRules(),
            default => [],
        };
    }

    private function allRules(): array
    {
        return [
            'department_id' => 'required|uuid|exists:departments,id',
        ];
    }

    private function createRules(): array
    {
        return [
            'name' => 'required|string|unique:user_types,name',
            'level' => 'required|integer',
        ];
    }

    private function updateRules(): array
    {
        return [
            'id' => 'required|uuid|exists:user_types,id',
            'name' => 'required|string|unique:user_types,name',
            'level' => 'required|integer',
        ];
    }

    private function deleteRules(): array
    {
        return [
            'id' => 'required|uuid|exists:user_types,id',
        ];
    }

}
