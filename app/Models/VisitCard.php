<?php

namespace PockDoc\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * PockDoc\Models\VisitCard
 *
 * @property int $visit_id
 * @property int $card_id
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\VisitCard whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\VisitCard whereVisitId($value)
 * @mixin \Eloquent
 */
class VisitCard extends Model
{
    protected $table = 'visit_card';
    public $timestamps = false;

    protected $fillable = [
        'card_id',
        'visit_id',
    ];
}
