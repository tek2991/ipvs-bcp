<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bag
 *
 * @property int $id
 * @property string $bag_no
 * @property int $bag_type_id
 * @property int $from_facility_id
 * @property int $to_facility_id
 * @property int $bag_transaction_type_id
 * @property int $set_id
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BagTransactionType $bagTransactionType
 * @property-read \App\Models\BagType $bagType
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\Facility $fromFacility
 * @property-read \App\Models\Set $set
 * @property-read \App\Models\Facility $toFacility
 * @property-read \App\Models\User $updator
 * @method static \Illuminate\Database\Eloquent\Builder|Bag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bag query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bag whereBagNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bag whereBagTransactionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bag whereBagTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bag whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bag whereFromFacilityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bag whereSetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bag whereToFacilityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bag whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bag whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class Bag extends Model
{
    protected $fillable = [
        'bag_no',
        'bag_type_id',
        'from_facility_id',
        'to_facility_id',
        'bag_transaction_type_id',
        'set_id',
        'created_by',
        'updated_by',
    ];

    public function bagType(){
        return $this->belongsTo(BagType::class);
    }

    public function bagTransactionType(){
        return $this->belongsTo(BagTransactionType::class);
    }

    public function fromFacility(){
        return $this->belongsTo(Facility::class, 'from_facility_id', 'id');
    }

    public function toFacility(){
        return $this->belongsTo(Facility::class, 'to_facility_id', 'id');
    }

    public function set(){
        return $this->belongsTo(Set::class);
    }

    public function creator(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updator(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function articles(){
        return $this->hasMany(Article::class);
    }

    public function exports(){
        return $this->hasMany(Export::class);
    }
}
