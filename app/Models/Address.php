<?php

namespace PockDoc\Models;

use Illuminate\Database\Eloquent\Model;
use Mpociot\Versionable\VersionableTrait;

/**
 * PockDoc\Models\Address
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $city
 * @property string|null $street
 * @property string|null $house
 * @property string|null $building
 * @property string|null $corpus
 * @property string|null $block
 * @property string|null $floor
 * @property string|null $flat
 * @property string|null $code
 * @property string|null $comment
 * @property float|null $latitude
 * @property float|null $longitude
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Address whereBlock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Address whereBuilding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Address whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Address whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Address whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Address whereCorpus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Address whereFlat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Address whereFloor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Address whereHouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Address whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Address whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Address whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Address whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Address whereUserId($value)
 * @mixin \Eloquent
 * @property-write mixed $reason
 * @property-read \PockDoc\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\Mpociot\Versionable\Version[] $versions
 */
class Address extends Model
{
    use VersionableTrait;

    protected $fillable = [
        'user_id',
        'city',
        'street',
        'house',
        'building',
        'corpus',
        'block',
        'floor',
        'flat',
        'code',
        'comment',
        'latitude',
        'longitude',
    ];

    public function user()
    {
        return $this->belongsTo('PockDoc\Models\User');
    }

}
