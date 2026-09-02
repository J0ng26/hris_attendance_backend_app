<?php

namespace App\Domain\Stabs;

class RequestStab
{
    public static function getStub(): string
    {
        return <<<'STUB'
<?php

namespace App\Http\Requests;

class {{ class }} extends BaseRequest
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
            'create' => $this->storeRules(),
            'update' => $this->updateRules(),
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
{{ storeRules }}
        ];
    }

    private function updateRules(): array
    {
        return [
            'id' => ['required', 'string', 'exists:{{ table }},id'],
{{ updateRules }}
        ];
    }

    private function destroyRules(): array
    {
        return [
            'id' => ['required', 'string', 'exists:{{ table }},id'],
        ];
    }
}
STUB;
    }
}
