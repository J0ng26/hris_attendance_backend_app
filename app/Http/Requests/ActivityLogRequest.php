<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Validation\Validator;

class ActivityLogRequest extends BaseRequest
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
            default => [],
        };
    }

    // filter by user_id, action, and date range (start_date and end_date)
    private function allRules(): array
    {
        return [
            'user_id' => 'nullable|string',
            'action' => 'nullable|string',
            'start_date' => 'required|date|before_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:today',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isNotEmpty() || !$this->start_date || !$this->end_date) {
                return;
            }

            $startDate = Carbon::parse($this->start_date);
            $endDate = Carbon::parse($this->end_date);

            if ($startDate->diffInDays($endDate) > 30) {
                $validator->errors()->add('start_date', 'The selected date range must not be greater than 30 days.');
            }
        });
    }
}
