<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    /** @var array */
    protected $fillable = [
        'ano',
        'semestre',
    ];
}
