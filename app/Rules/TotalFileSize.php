<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TotalFileSize implements ValidationRule
{
    protected $maxSize = 50000000;
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $totalSize = 0;

        foreach ($value as $file) {
            $totalSize += $file->getSize();
        }

        if($totalSize > $this->maxSize){
            $fail('The total size of all files must not exceed ' . $this->maxSize / (1024 * 1024) . ' MB.');
        }
    }
}
