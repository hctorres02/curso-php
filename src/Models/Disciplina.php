<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Disciplina extends Model
{
    use HasFactory;

    protected $fillable = [
        'periodo_id',
        'nome',
        'cor',
    ];

    public function periodo(): BelongsTo
    {
        return $this->belongsTo(Periodo::class);
    }

    public function agendamentos(): HasMany
    {
        return $this->hasMany(Agendamento::class);
    }
}
