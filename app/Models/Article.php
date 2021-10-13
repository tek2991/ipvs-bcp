<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Article
 *
 * @property int $id
 * @property string $article_no
 * @property int $article_type_id
 * @property int $from_facility_id
 * @property int $to_facility_id
 * @property int $article_transaction_type_id
 * @property int $bag_id
 * @property int $set_id
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ArticleTransactionType $articleTransactionType
 * @property-read \App\Models\ArticleType $articleType
 * @property-read \App\Models\Bag $bag
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\Facility $fromFacility
 * @property-read \App\Models\Set $set
 * @property-read \App\Models\Facility $toFacility
 * @property-read \App\Models\User $updator
 * @method static \Illuminate\Database\Eloquent\Builder|Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereArticleNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereArticleTransactionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereArticleTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereBagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereFromFacilityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereSetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereToFacilityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedBy($value)
 * @mixin \Eloquent
 */
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
