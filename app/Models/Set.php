<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
    protected $fillable = [
        'created_by',
        'updated_by',
        'facility_id',
    ];

    public function creator(){
        return $this->belongsTo(User::class);
    }

    public function updator(){
        return $this->belongsTo(User::class);
    }

    public function facility(){
        return $this->belongsTo(Facility::class);
    }
}
