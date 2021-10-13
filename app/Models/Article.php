<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'article_no',
        'article_type_id',
        'from_facility_id',
        'to_facility_id',
        'article_transaction_type_id',
        'bag_id',
        'set_id',
        'created_by',
        'updated_by',
    ];

    public function articleType(){
        return $this->belongsTo(ArticleType::class);
    }

    public function fromFacility(){
        return $this->belongsTo(Facility::class, 'from_facility_id', 'id');
    }

    public function toFacility(){
        return $this->belongsTo(Facility::class, 'to_facility_id', 'id');
    }

    public function articleTransactionType(){
        return $this->belongsTo(ArticleTransactionType::class, 'article_transaction_type_id', 'id');
    }

    public function bag(){
        return $this->belongsTo(Bag::class);
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
