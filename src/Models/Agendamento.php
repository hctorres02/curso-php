<?php

namespace App\Models;

use App\Models\Atividade;
use App\Models\Disciplina;
use App\Models\Periodo;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agendamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'atividade_id',
        'disciplina_id',
        'conteudo',
        'data',
    ];

    public function atividade(): BelongsTo
    {
        return $this->belongsTo(Atividade::class);
    }

    public function disciplina(): BelongsTo
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function scopePrevistos(Builder $query): void
    {
        $query->whereDate('data', '>=', today());
    }

    public static function toSearch(array $params): array
    {
        // Cria a consulta base para agendamentos, aplicando o filtro 'previstos'
        $baseQuery = Periodo::find($params['periodo_id'])?->agendamentos() ?: static::query()->previstos();

        // Realiza a consulta de agendamentos com filtros e ordenação, além de paginar os resultados
        $data = $baseQuery
            // Clona a consulta base para evitar modificações diretas
            ->clone()
            // Ordena os agendamentos pela data de forma crescente
            ->oldest('data')
            // Inclui as relações com 'atividade' e 'disciplina', trazendo apenas 'id' e 'nome'
            ->with('atividade:id,nome', 'disciplina:id,nome')
            // Aplica filtro condicional para 'atividade_id', se o parâmetro for fornecido
            ->when($params['atividade_id'], fn ($query, $id) => $query->where('atividade_id', $id))
            // Aplica filtro condicional para 'disciplina_id', se o parâmetro for fornecido
            ->when($params['disciplina_id'], fn ($query, $id) => $query->where('disciplina_id', $id))
            // Pagina os resultados, limitando a 5 agendamentos por página
            ->paginate(5)
            // Mantém os parâmetros originais na URL para navegação entre páginas
            ->appends(array_filter($params))
            // Converte o resultado da consulta para um array
            ->toArray();

        // Consulta períodos
        $data['periodos'] = Periodo::query()
            // Seleciona coluna ID
            ->select('id')
            // Concatena ano e semestre como coluna nome
            ->selectRaw("(ano||'.'||semestre) AS nome")
            // Extrai nome e IDs dos períodos, retornando array associativo
            ->pluck('nome', 'id')
            // Ordena os resultados pela chave (ID do período)
            ->sortDesc();

        // Consulta os nomes das atividades associadas aos agendamentos
        $data['atividades'] = $baseQuery
            // Clona a consulta base novamente
            ->clone()
            // Faz um right join com a tabela 'atividades' para obter os nomes das atividades
            ->rightJoin('atividades', 'atividades.id', 'agendamentos.atividade_id')
            // Extrai os nomes e IDs das atividades, retornando um array associativo
            ->pluck('atividades.nome', 'atividades.id')
            // Ordena os resultados pela chave (ID da atividade)
            ->sort();

        // Consulta os nomes das disciplinas associadas aos agendamentos
        $data['disciplinas'] = $baseQuery
            // Clona a consulta base novamente
            ->clone()
            // Faz um right join com a tabela 'disciplinas' para obter os nomes das disciplinas
            ->when(
                // Quando não for definido o período...
                ! $params['periodo_id'],
                // ...Une a tabela disciplinas à consulta
                fn ($query) => $query->rightJoin('disciplinas', 'disciplinas.id', 'agendamentos.disciplina_id')
            )
            // Extrai os nomes e IDs das disciplinas, retornando um array associativo
            ->pluck('disciplinas.nome', 'disciplinas.id')
            // Ordena os resultados pela chave (ID da disciplina)
            ->sort();

        // Combina os parâmetros originais com os dados obtidos (agendamentos, atividades, disciplinas)
        return array_merge($params, $data);
    }
}
