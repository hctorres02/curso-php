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

    public function agendamentos()
    {
        return $this->hasManyThrough(Agendamento::class, Disciplina::class);
    }

    public function disciplinas(): HasMany
    {
        return $this->hasMany(Disciplina::class);
    }

    public static function toSearch(array $params): array
    {
        $data = static::query()
            ->clone()
            ->orderByDesc('ano')
            ->orderByDesc('semestre')
            ->withCount('agendamentos', 'disciplinas')
            ->with('disciplinas:id,periodo_id,nome')
            ->when($params['q'], fn ($query, $q) => $query->where(
                fn ($query) => $query
                    ->where('ano', 'like', "%{$q}%")
                    ->orWhere('semestre', 'like', "%{$q}%")
            ))
            ->paginate(12)
            ->appends(array_filter($params))
            ->toArray();

        return array_merge($params, $data);
    }
}
