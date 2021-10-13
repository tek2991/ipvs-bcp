<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
