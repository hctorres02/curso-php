<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atividade extends Model
{
    /** @var array */
    protected $fillable = [
        'nome',
        'cor',
    ];
}
