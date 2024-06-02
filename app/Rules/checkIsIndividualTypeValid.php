<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class checkIsIndividualTypeValid implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if(count(array_intersect(explode(",",env('INDIVIDUAL_TYPE')), explode(",",$value))) != count(explode(",",$value))){

            $fail('The :attribute Should be Valid.');
        }

    }
}
