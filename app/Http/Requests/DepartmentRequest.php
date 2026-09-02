<?php

namespace App\Http\Requests;

class DepartmentRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $action = $this->route()?->getActionMethod();

        return match ($action) {
            'add' => $this->storeRules(),
            'edit' => $this->updateRules(),
            'delete' => $this->destroyRules(),
            default => $this->defaultRules(),
        };
    }

    private function defaultRules(): array
    {
        return [];
    }

    private function storeRules(): array
    {
        return [
            'name' => ['required', 'string', 'unique:departments,name'],
        ];
    }

    private function updateRules(): array
    {
        return [
            'id' => ['required', 'string', 'exists:departments,id'],
            'name' => ['required', 'string', 'unique:departments,name'],
        ];
    }

    private function destroyRules(): array
    {
        return [
            'id' => ['required', 'string', 'exists:departments,id'],
        ];
    }
}
