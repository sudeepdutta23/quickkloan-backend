<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class checkIsNumeric implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {


        $numbers = explode(',', $value);

        foreach ($numbers as $number) {
            if (!is_numeric($number)) {
                $fail('The :attribute Should be Valid.');
            }
        }

    }
}
