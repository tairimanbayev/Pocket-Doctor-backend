<?php

namespace PockDoc\Models;

use Illuminate\Database\Eloquent\Model;

class VisitQuestion extends Model
{
    protected $table = 'visit_question';
    public $timestamps = false;

    protected $fillable = [
        'visit_id',
        'question_id',
    ];
}
