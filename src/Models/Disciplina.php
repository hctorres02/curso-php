<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Respect\Validation\Validator;

class Disciplina extends Model
{
    use HasFactory;

    protected $fillable = [
        'periodo_id',
        'nome',
        'cor',
    ];

    public static function rules(): array
    {
        return [
            'periodo_id' => Validator::intVal()->callback(Periodo::exists(...)),
            'nome' => Validator::notEmpty()->max(20),
            'cor' => Validator::hexRgbColor(),
        ];
    }

    public function periodo(): BelongsTo
    {
        return $this->belongsTo(Periodo::class);
    }

    public function agendamentos(): HasMany
    {
        return $this->hasMany(Agendamento::class);
    }

    public static function toOptGroup(): array
    {
        return static::query()
            ->with('periodo:id,ano,semestre')
            ->orderBy('nome')
            ->get(['id', 'periodo_id', 'nome'])
            ->groupBy(fn ($disciplina) => "{$disciplina->periodo->ano}.{$disciplina->periodo->semestre}")
            ->map(fn ($disciplina) => $disciplina->pluck('nome', 'id'))
            ->sortKeysDesc()
            ->toArray();
    }

    public static function toSearch(array $params): array
    {
        // Inicia a consulta das disciplinas, com carregamento do relacionamento 'periodo'
        $data = static::query()
            // Realiza o eager load da relação 'periodo' trazendo apenas os campos necessários
            ->with([
                'periodo:id,ano,semestre'
            ])
            // Aplica o filtro 'nome' caso ele seja fornecido, utilizando o operador 'like' para busca parcial
            ->when($params['nome'], fn ($query, $nome) => $query->where('nome', 'like', "%{$nome}%"))
            // Aplica o filtro 'periodo_id' caso ele seja fornecido
            ->when($params['periodo_id'], fn ($query, $id) => $query->where('periodo_id', $id))
            // Ordena os resultados pelo nome da disciplina
            ->orderBy('nome')
            // Realiza a paginação
            ->paginate(20)
            // Mantém os parâmetros originais na URL para navegação entre páginas
            ->appends($params)
            // Converte os resultados para um array
            ->toArray();

        // Agrupa as disciplinas pela primeira letra inicial de seu nome
        $data['data'] = collect($data['data'])->groupBy(
            fn ($disciplina) => $disciplina['nome'][0]
        )->toArray();

        // Realiza a consulta dos períodos, retornando um array chave-valor com o nome concatenado de ano e semestre
        $data['periodos'] = Periodo::query()
            // Seleciona apenas o campo 'id'
            ->select('id')
            // Cria o campo 'nome' concatenando 'ano' e 'semestre' no formato 'ano.semestre'
            ->selectRaw("(ano||'.'||semestre) AS nome")
            // Retorna um array chave-valor onde a chave é o 'id' e o valor é o 'nome' do período
            ->pluck('nome', 'id')
            // Ordena os períodos de forma decrescente
            ->sortDesc();

        return array_merge($params, $data);
    }
}
