<?php

namespace PockDoc\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notification;
use Mpociot\Versionable\VersionableTrait;

/**
 * Class User
 *
 * @method static $this create(array $a)
 * @package PockDoc\Models
 * @property int $id
 * @property string|null $phone_number
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $card_id
 * @property Card $card
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\User whereCardId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\PockDoc\Models\Card[] $cards
 * @property-write mixed $reason
 * @property-read \Illuminate\Database\Eloquent\Collection|\Mpociot\Versionable\Version[] $versions
 * @property-read \Illuminate\Database\Eloquent\Collection|\PockDoc\Models\Address[] $addresses
 * @property-read \Illuminate\Database\Eloquent\Collection|\PockDoc\Models\PaymentCard[] $payment_cards
 * @property string|null $email
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\User whereEmail($value)
 */
class User extends Authenticatable
{
    use Notifiable, VersionableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone_number',
        'card_id',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function cards()
    {
        return $this->hasMany('PockDoc\Models\Card');
    }

    public function card()
    {
        return $this->belongsTo('PockDoc\Models\Card');
    }

    public function getAttributeName()
    {
        return $this->card ? $this->card->first_name : $this->phone_number;
    }

    public function setPhoneNumberAttribute($phone_number)
    {
        $phone_number = preg_replace('/[^0-9]*/', '', $phone_number);
        $this->attributes['phone_number'] = substr($phone_number, strlen($phone_number) - 10, 10);
        return $this->attributes['phone_number'];
    }

    public function addresses() {
        return $this->hasMany('PockDoc\Models\Address');
    }

    public function payment_cards() {
        return $this->hasMany('PockDoc\Models\PaymentCard');
    }

    public function fcm_ids() {
        return $this->hasMany('PockDoc\Models\FCMId');
    }

    public function doctor() {
        return $this->hasOne('PockDoc\Models\Doctor', 'user_id');
    }

    public function notify(Notification $notification) {
        foreach ($this->fcm_ids as $fcm_id) {
            $fcm_id->notify(clone $notification);
        }
    }

}
