<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BagTransactionType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bag[] $bags
 * @property-read int|null $bags_count
 * @method static \Illuminate\Database\Eloquent\Builder|BagTransactionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BagTransactionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BagTransactionType query()
 * @method static \Illuminate\Database\Eloquent\Builder|BagTransactionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BagTransactionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BagTransactionType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BagTransactionType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BagTransactionType extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function bags(){
        return $this->hasMany(Bag::class);
    }
}
