<?php

namespace PockDoc\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * PockDoc\Models\Medicine
 *
 * @property int $id
 * @property int $illness_id
 * @property string $name
 * @property string|null $description
 * @property string|null $schedule
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Medicine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Medicine whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Medicine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Medicine whereIllnessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Medicine whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Medicine whereSchedule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Medicine whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Medicine extends Model
{

    protected $fillable = [
        'illness_id',
        'name',
        'description',
        'schedule',
        'end_date',
        'period',
    ];

}
