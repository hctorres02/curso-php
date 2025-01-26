<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    /** @var array */
    protected $fillable = [
        'periodo_id',
        'nome',
        'cor',
    ];
}
