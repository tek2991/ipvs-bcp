<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    protected $fillable = [
        'user_id',
        'set_id',
        'bag_id',
        'type',
        'file_name',
        'file_path',
    ];

    public function set()
    {
        return $this->belongsTo(Set::class);
    }

    public function bag()
    {
        return $this->belongsTo(Bag::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
