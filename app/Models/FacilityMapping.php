<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityMapping extends Model
{
    protected $fillable = [
        'base_facility_id',
        'mapped_facility_id',
    ];

    public function facility(){
        return $this->belongsTo(Facility::class, 'mapped_facility_id', 'id');
    }
}
