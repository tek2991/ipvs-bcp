<?php

namespace App\Rules;

use App\Models\Article;
use App\Models\ArticleTransactionType;
use Illuminate\Contracts\Validation\Rule;

class ArticleClosingRule implements Rule
{
    private $active_set = '';
    private $confirm_force = '';
    private $message = 'Article cannot be closed';
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($active_set, $confirm_force)
    {
        $this->active_set = $active_set;
        $this->confirm_force = $confirm_force;
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

        // if confirm_force is true, then only check if the articles is already closed
        if ($this->confirm_force) {
            // If article does not exist then return true
            $article = Article::where('article_no', $article_no)->where('set_id', $this->active_set->id)->first();
            if (!$article) {
                return true;
            }

            // Get the article statuses
            $article_statuses = ArticleTransactionType::whereIn('name', ['CL', 'CL_SCAN'])->get()->modelKeys();

            // get the article status id
            $article_status_id = $article->article_transaction_type_id;

            // check if article status is CL
            if (in_array($article_status_id, $article_statuses)) {
                $this->message = 'This article is already closed: ' . $article->articleTransactionType->description;
                return false;
            }

            return true;
        }

        // check if article exists
        $article = Article::where('article_no', $article_no)->where('set_id', $this->active_set->id)->first();
        if (!$article) {
            $this->message = 'Article does not exist';
            return false;
        }

        // Get the article statuses
        $article_statuses = ArticleTransactionType::whereIn('name', ['OP'])->get()->modelKeys();

        // get the article status id
        $article_status_id = $article->article_transaction_type_id;

        // check if article status is not OP
        if (!in_array($article_status_id, $article_statuses)) {
            $this->message = 'This article cannot be closed: ' . $article->articleTransactionType->description;
            return false;
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
        return $this->message;
    }
}
