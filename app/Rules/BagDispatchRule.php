<?php

namespace App\Rules;

use App\Models\Bag;
use App\Models\BagTransactionType;
use Illuminate\Contracts\Validation\Rule;

class BagDispatchRule implements Rule
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
        $bag_exists = Bag::where('bag_no', $bag_no)->where('set_id', $this->active_set_id)->exists();

        // OK if bag does not exist in the set
        if (!$bag_exists) {
            return true;
        }

        // Define allowed bag transaction types
        $allowed_bag_transaction_names = ['RD'];
        $allowed_bag_transaction_ids = BagTransactionType::whereIn('name', $allowed_bag_transaction_names)->pluck('id')->toArray();

        // Check if bag exists in the set and has an allowed transaction type
        $bag_transaction_type_id = Bag::where('bag_no', $bag_no)->where('set_id', $this->active_set_id)->value('bag_transaction_type_id');
        return in_array($bag_transaction_type_id, $allowed_bag_transaction_ids);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This bag cannot be dispatched, please check bag status.';
    }
}
