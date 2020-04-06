<?php

namespace PockDoc\Models;

use Illuminate\Database\Eloquent\Model;
use Mpociot\Versionable\VersionableTrait;

/**
 * PockDoc\Models\Doctor
 *
 * @property int $id
 * @property int $user_id
 * @property int $clinic_id
 * @property string $role
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Doctor whereClinicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Doctor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Doctor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Doctor whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Doctor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Doctor whereUserId($value)
 * @mixin \Eloquent
 * @property-read \PockDoc\Models\Clinic $clinic
 * @property-write mixed $reason
 * @property-read \PockDoc\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\Mpociot\Versionable\Version[] $versions
 */
class Doctor extends Model
{
    use VersionableTrait;

    protected $fillable = [
        'user_id',
        'clinic_id',
        'role',
        'description',
        'mark',
    ];

    public function user()
    {
        return $this->belongsTo('PockDoc\Models\User');
    }

    public function clinic()
    {
        return $this->belongsTo('\PockDoc\Models\Clinic');
    }

}
