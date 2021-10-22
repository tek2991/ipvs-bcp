<?php

namespace App\Rules;

use App\Models\Bag;
use Illuminate\Contracts\Validation\Rule;

class BagReceiveRule implements Rule
{
    private $active_set_id = '';
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($active_set_id)
    {
        $this->active_set_id = $active_set_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $bag_no = trim(strtoupper($value));
        return Bag::where('bag_no', $bag_no)->where('set_id', $this->active_set_id)->exists() ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The bag no has already been taken(case insensitive).';
    }
}
