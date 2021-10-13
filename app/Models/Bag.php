<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo(Facility::class);
    }

    public function toFacility(){
        return $this->belongsTo(Facility::class);
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
}
