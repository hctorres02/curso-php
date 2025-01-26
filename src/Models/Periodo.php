<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Periodo extends Model
{
    use HasFactory;

    protected $fillable = [
        'ano',
        'semestre',
    ];

    public function atividades(): HasManyThrough
    {
        return $this->hasManyThrough(Atividade::class, Disciplina::class);
    }

    public function disciplinas(): HasMany
    {
        return $this->hasMany(Disciplina::class);
    }
}
