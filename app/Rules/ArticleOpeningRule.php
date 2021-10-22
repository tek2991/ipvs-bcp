<?php

namespace App\Rules;

use App\Models\Article;
use Illuminate\Contracts\Validation\Rule;

class ArticleOpeningRule implements Rule
{
    private $bag_no = '';
    private $current_facility = '';
    private $active_set = '';
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($bag_no, $current_facility, $active_set)
    {
        $this->bag_no = $bag_no;
        $this->current_facility = $current_facility;
        $this->active_set = $active_set;
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
        $article_no = trim(strtoupper($value));
        return Article::where('article_no', $article_no)->where('set_id', $this->active_set->id)->exists() ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The article no has already been taken(case insensitive).';
    }
}
