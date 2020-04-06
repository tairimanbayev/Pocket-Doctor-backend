<?php

namespace PockDoc\Models;

use Illuminate\Database\Eloquent\Model;
use Mpociot\Versionable\VersionableTrait;

/**
 * PockDoc\Models\Faq
 *
 * @property int $id
 * @property string $question
 * @property string $answer
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Faq whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Faq whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Faq whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Faq whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\PockDoc\Models\Faq whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Faq extends Model
{
    use VersionableTrait;

    protected $fillable = [
        'question',
        'answer',
    ];

}
