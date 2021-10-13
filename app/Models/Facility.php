<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Facility
 *
 * @property int $id
 * @property string $facility_code
 * @property string $name
 * @property int $facility_type_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\FacilityType $facilityType
 * @method static \Illuminate\Database\Eloquent\Builder|Facility newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Facility newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Facility query()
 * @method static \Illuminate\Database\Eloquent\Builder|Facility whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Facility whereFacilityCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Facility whereFacilityTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Facility whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Facility whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Facility whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Facility extends Model
{
    protected $table = 'facilities';

    protected $fillable = [
        'facility_code',
        'name',
        'facility_type_id',
    ];

    public function facilityType(){
        return $this->belongsTo(FacilityType::class);
    }
}
