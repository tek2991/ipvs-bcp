<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ArticleNumberRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $serial = substr($value, 2, 8);
        $checksum = substr($value, 10, 1);
        $serial_arr = str_split($serial);
        $weights = [8, 6, 4, 2, 3, 5, 9, 7];
        $sum = 0;
        foreach ($weights as $index => $weight) {
            $product = $serial_arr[$index] * $weight;
            $sum += $product;
        }
        $remainder = 11 - ($sum % 11);
        $calculated_checksum = $remainder;
        if($remainder == 10){
            $calculated_checksum = 0;
        }elseif($remainder == 11){
            $calculated_checksum = 5;
        }
        return $checksum == $calculated_checksum;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Not a valid article number!';
    }
}
