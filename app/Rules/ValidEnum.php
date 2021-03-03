<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidEnum implements Rule
{
    private string $enumClassName;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $enumClassName)
    {
        $this->enumClassName = $enumClassName;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return ($this->enumClassName::isValidValue($value));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Enum value is invalid.';
    }
}
