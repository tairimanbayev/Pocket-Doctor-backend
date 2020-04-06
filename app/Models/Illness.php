<?php

namespace PockDoc\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * PockDoc\Models\Illness
 *
 * @property int $id
 * @property int $card_id
 * @property int $visit_id
 * @property string $title
 * @property string $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Illness whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Illness whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Illness whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Illness whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Illness whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Illness whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Illness whereVisitId($value)
 * @mixin \Eloquent
 */
class Illness extends Model
{

    protected $fillable = [
        'visit_id',
        'card_id',
        'title',
        'description',
        'complaint',
        'appointment',
        'result'
    ];

    public function card()
    {
        return $this->belongsTo('PockDoc\Models\Card');
    }

    public function visit()
    {
        return $this->belongsTo('PockDoc\Models\Visit');
    }

    public function medicines()
    {
        return $this->hasMany('PockDoc\Models\Medicine', 'illness_id', 'id');
    }

}
