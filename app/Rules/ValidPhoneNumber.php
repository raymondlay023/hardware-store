<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Indonesian phone number formats:
        // 08xx-xxxx-xxxx (local format)
        // +62-8xx-xxxx-xxxx (international format)
        // 62-8xx-xxxx-xxxx (alternative international)
        
        $patterns = [
            '/^08\d{8,11}$/',           // 08xxxxxxxxxx (10-13 digits)
            '/^\+628\d{8,11}$/',        // +628xxxxxxxxxx
            '/^628\d{8,11}$/',          // 628xxxxxxxxxx
            '/^08\d{2,4}-\d{3,4}-\d{3,4}$/',  // 08xx-xxx-xxxx (with dashes)
        ];

        $isValid = false;
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                $isValid = true;
                break;
            }
        }

        if (!$isValid) {
            $fail('The :attribute must be a valid Indonesian phone number (e.g., 08123456789 or +628123456789).');
        }
    }
}
