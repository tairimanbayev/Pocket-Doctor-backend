<?php

namespace PockDoc\Models;

use Illuminate\Database\Eloquent\Model;
use Mpociot\Versionable\VersionableTrait;

/**
 * PockDoc\Models\Clinic
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-write mixed $reason
 * @property-read \Illuminate\Database\Eloquent\Collection|\Mpociot\Versionable\Version[] $versions
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Clinic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Clinic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Clinic whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Clinic whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Clinic extends Model
{
    use VersionableTrait;

    protected $fillable = [
        'name'
    ];

}
