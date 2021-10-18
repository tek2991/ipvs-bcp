<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = [
        'name'
    ];

    public function facilities(){
        return $this->hasMany(Facility::class);
    }
}
