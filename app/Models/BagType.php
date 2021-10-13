<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BagType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bag[] $bags
 * @property-read int|null $bags_count
 * @method static \Illuminate\Database\Eloquent\Builder|BagType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BagType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BagType query()
 * @method static \Illuminate\Database\Eloquent\Builder|BagType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BagType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BagType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BagType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BagType extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function bags(){
        return $this->hasMany(Bag::class);
    }
}
