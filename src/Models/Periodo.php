<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    use HasFactory;

    /** @var array */
    protected $fillable = [
        'ano',
        'semestre',
    ];
}
