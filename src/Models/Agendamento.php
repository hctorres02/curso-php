<?php

namespace App\Models;

use App\Models\Anexo;
use App\Models\Atividade;
use App\Models\Disciplina;
use App\Models\Periodo;
use App\Traits\Auditable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Respect\Validation\Validator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Agendamento extends Model
{
    use Auditable;
    use HasFactory;

    protected $fillable = [
        'atividade_id',
        'disciplina_id',
        'conteudo',
        'data',
    ];

    protected $casts = [
        'data' => 'date',
    ];

    public static function rules(): array
    {
        return [
            'atividade_id' => Validator::intVal()->callback(Atividade::exists(...)),
            'disciplina_id' => Validator::intVal()->callback(Disciplina::exists(...)),
            'conteudo' => Validator::notEmpty()->length(1, 512),
            'data' => Validator::date('Y-m-d'),
        ];
    }

    public function anexos(): HasMany
    {
        return $this->hasMany(Anexo::class);
    }

    public function atividade(): BelongsTo
    {
        return $this->belongsTo(Atividade::class);
    }

    public function disciplina(): BelongsTo
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function periodo(): HasOneThrough
    {
        return $this->hasOneThrough(Periodo::class, Disciplina::class, 'id', 'id', 'disciplina_id', 'periodo_id');
    }

    public function scopePrevistos(Builder $query): void
    {
        $today = today();
        $endOfPeriod = $today->copy()->setMonth($today->month <= 6 ? 6 : 12)->endOfMonth();

        $query->whereBetween('data', [$today, $endOfPeriod]);
    }

    public static function toSearch(array $params): array
    {
        $baseQuery = static::query()->when(
            $params['periodo_id'],
            fn ($query, $periodo_id) => $query->whereHas('periodo', fn ($periodo) => $periodo->where('periodos.id', $periodo_id)),
            fn ($query) => $query->previstos()
        );

        $data['total'] = $baseQuery?->count() ?: 0;

        $data['agendamentos'] = $baseQuery
            ->clone()
            ->with('atividade:id,nome,cor', 'disciplina:id,nome')
            ->when($params['atividade_id'], fn ($query, $id) => $query->where('atividade_id', $id))
            ->when($params['disciplina_id'], fn ($query, $id) => $query->where('disciplina_id', $id))
            ->get(['id', 'atividade_id','disciplina_id','data'])
            ->map(fn (Agendamento $agendamento) => [
                'title' => "{$agendamento->atividade->nome}: {$agendamento->disciplina->nome}",
                'start' => $agendamento->data,
                'color' => $agendamento->atividade->cor,
                'url' => route('ver_agendamento', $agendamento->id),
            ])
            ->toArray();

        $data['periodos'] = Periodo::query()
            ->select('id')
            ->selectRaw("(ano||'.'||semestre) AS nome")
            ->pluck('nome', 'id')
            ->sortDesc();

        $data['atividades'] = $baseQuery
            ->clone()
            ->rightJoin('atividades', 'atividades.id', 'agendamentos.atividade_id')
            ->pluck('atividades.nome', 'atividades.id')
            ->sort();

        $data['disciplinas'] = $baseQuery
            ->clone()
            ->rightJoin('disciplinas', 'disciplinas.id', 'agendamentos.disciplina_id')
            ->pluck('disciplinas.nome', 'disciplinas.id')
            ->sort();

        return array_merge($params, $data);
    }
}
