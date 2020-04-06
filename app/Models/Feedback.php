<?php

namespace PockDoc\Models;

use Illuminate\Database\Eloquent\Model;
use Mpociot\Versionable\VersionableTrait;

class Feedback extends Model
{
    use VersionableTrait;

    protected $fillable = [
        'user_id',
        'visit_id',
        'mark',
        'text',
    ];

}
