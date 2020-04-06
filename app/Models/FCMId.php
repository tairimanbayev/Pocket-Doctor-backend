<?php

namespace PockDoc\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * PockDoc\Models\FCMId
 *
 * @property string $id
 * @property int $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\FCMId whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\FCMId whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\FCMId whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\FCMId whereUserId($value)
 * @mixin \Eloquent
 * @property-read \PockDoc\Models\User $user
 */
class FCMId extends Model
{
    use Notifiable;

    protected $primaryKey = null;

    public $incrementing = false;

    protected $table = 'fcm_ids';

    protected $fillable = [
        'id',
        'user_id'
    ];

    public function update(array $attributes = [], array $options = [])
    {
        $this->primaryKey = 'id';
        $res = parent::update($attributes, $options);
        $this->primaryKey = null;
        return $res;
    }

    public function user() {
        return $this->belongsTo('PockDoc\Models\User')
            ->with('card');
    }

    public function routeNotificationForFcm()
    {
        return (string) $this->id;
    }

}
