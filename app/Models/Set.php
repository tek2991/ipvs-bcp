<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Set
 *
 * @property int $id
 * @property int $created_by
 * @property int $updated_by
 * @property int $facility_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\Facility $facility
 * @property-read \App\Models\User $updator
 * @method static \Illuminate\Database\Eloquent\Builder|Set newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Set newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Set query()
 * @method static \Illuminate\Database\Eloquent\Builder|Set whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Set whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Set whereFacilityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Set whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Set whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Set whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class Set extends Model
{
    protected $fillable = [
        'created_by',
        'updated_by',
        'facility_id',
        'is_active',
    ];

    public function creator(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updator(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function facility(){
        return $this->belongsTo(Facility::class);
    }

    public function bags(){
        return $this->hasMany(Bag::class);
    }
}
