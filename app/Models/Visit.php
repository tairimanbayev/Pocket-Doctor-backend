<?php

namespace PockDoc\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * PockDoc\Models\Visit
 *
 * @property int $id
 * @property string $role
 * @property int $address_id
 * @property int $payment_card_id
 * @property string $visit_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Visit whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Visit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Visit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Visit wherePaymentCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Visit whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Visit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Visit whereVisitAt($value)
 * @mixin \Eloquent
 * @property int|null $doctor_id
 * @property-read \PockDoc\Models\Address $address
 * @property-read \Illuminate\Database\Eloquent\Collection|\PockDoc\Models\Card[] $cards
 * @property-read \PockDoc\Models\Doctor|null $doctor
 * @property-read \PockDoc\Models\PaymentCard $payment_card
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Visit whereDoctorId($value)
 */
class Visit extends Model
{
    use Notifiable;

    protected $fillable = [
        'role',
        'price',
        'address_id',
        'payment_card_id',
        'doctor_id',
        'visit_at',
        'finished_at',
        'comment',
        'accepted_at',
    ];

    protected $dates = [
        'visit_at',
        'finished_at'
    ];

    public function address()
    {
        return $this->belongsTo('PockDoc\Models\Address');
    }

    public function payment_card()
    {
        return $this->belongsTo('PockDoc\Models\PaymentCard');
    }

    public function doctor()
    {
        return $this->belongsTo('PockDoc\Models\Doctor');
    }

    public function cards()
    {
        return $this->belongsToMany('PockDoc\Models\Card', 'visit_card')->withTrashed();
    }

    public function questions()
    {
        return $this->belongsToMany('PockDoc\Models\Question', 'visit_question');
    }

    public function illnesses()
    {
        return $this->hasMany('PockDoc\Models\Illness');
    }

    public function routeNotificationForFcm()
    {
        return $this->role;
    }

    public function priceRole()
    {
        return $this->belongsTo('PockDoc\Models\Price', 'role', 'role');
    }

}
