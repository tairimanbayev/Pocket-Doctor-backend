<?php

namespace PockDoc\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mpociot\Versionable\VersionableTrait;

/**
 * PockDoc\Models\Card
 *
 * @method static $this create(array $a)
 * @property int $id
 * @property int $user_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $middle_name
 * @property string|null $birthday
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property User $user
 * @property Illness[] $illnesses
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Card whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Card whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Card whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Card whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Card whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Card whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Card whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Card whereUserId($value)
 * @mixin \Eloquent
 * @property-write mixed $reason
 * @property-read \Illuminate\Database\Eloquent\Collection|\Mpociot\Versionable\Version[] $versions
 * @property int|null $gender
 * @property float|null $height
 * @property float|null $weight
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Card whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Card whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Card whereWeight($value)
 */
class Card extends Model
{
    use VersionableTrait, SoftDeletes;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'middle_name',
        'birthday',
        'gender',
        'height',
        'weight',
        'hronics',
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function user()
    {
        return $this->belongsTo('\PockDoc\Models\User');
    }

    public function getBirthdayAttribute($value) {
        if($value && !($value instanceof Carbon)) {
            $value = Carbon::createFromFormat('Y-m-d', $value);
        }
        return $value ? $value->format('d.m.Y') : null;
    }

    public function setBirthdayAttribute($value) {
        $this->attributes['birthday'] = Carbon::createFromFormat('d.m.Y', $value);
    }

    public function illnesses()
    {
        return $this->hasMany('PockDoc\Models\Illness');
    }

}
