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
        'pincode',
        'district_id',
        'reporting_circle_id',
        'facility_type_id',
        'is_active',
    ];

    public function facilityType(){
        return $this->belongsTo(FacilityType::class);
    }

    public function district(){
        return $this->belongsTo(District::class);
    }

    public function reportingCircle(){
        return $this->belongsTo(ReportingCircle::class);
    }

    public function sets(){
        return $this->hasMany(Set::class);
    }

    public function mappedFacilities(){
        return $this->hasMany(FacilityMapping::class, 'base_facility_id');
    }
}
