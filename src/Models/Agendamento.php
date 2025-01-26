<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    use HasFactory;

    /** @var array */
    protected $fillable = [
        'disciplina_id',
        'atividade_id',
        'data',
        'conteudo',
    ];
}
