<?php

namespace App\Rules;

use App\Models\Article;
use App\Models\ArticleTransactionType;
use Illuminate\Contracts\Validation\Rule;

class ArticleClosingRule implements Rule
{
    private $active_set = '';
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($active_set)
    {
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
        $article_statuses = ArticleTransactionType::whereIn('name', ['OP'])->get()->modelKeys();
        return Article::where('article_no', $article_no)->where('set_id', $this->active_set->id)->whereIn('article_transaction_type_id', $article_statuses)->exists() ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Article cannot be closed';
    }
}
