<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ArticleTransactionType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleTransactionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleTransactionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleTransactionType query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleTransactionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleTransactionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleTransactionType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleTransactionType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ArticleTransactionType extends Model
{
    protected $fillable = [
        'name',
    ];
}
