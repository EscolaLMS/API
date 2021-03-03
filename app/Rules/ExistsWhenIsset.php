<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ExistsWhenIsset implements Rule
{
    private string $table;
    private string $key;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $table, string $key = 'id')
    {
        $this->table = $table;
        $this->key = $key;
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
        if ($value) {
            return DB::table($this->table)->where([$this->key => $value])->exists();
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Value is invalid.';
    }
}
