<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ArticleType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleType query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ArticleType extends Model
{
    protected $fillable = [
        'name',
    ];
}
