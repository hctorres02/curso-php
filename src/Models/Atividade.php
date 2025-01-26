<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Atividade extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'cor',
    ];

    public function agendamentos(): HasMany
    {
        return $this->hasMany(Agendamento::class);
    }
}
