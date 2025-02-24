<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Respect\Validation\Validator;

class Atividade extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'cor',
    ];

    public static function rules(): array
    {
        return [
            'nome' => Validator::notEmpty()->length(2, 20),
            'cor' => Validator::hexRgbColor(),
        ];
    }

    public function agendamentos(): HasMany
    {
        return $this->hasMany(Agendamento::class);
    }

    public static function toOptGroup(): array
    {
        return static::query()
            ->orderBy('nome')
            ->pluck('nome', 'id')
            ->toArray();
    }

    public static function toSearch(array $params): array
    {
        // Inicia a consulta de atividades
        $data = static::query()
            // Aplica o filtro 'nome' caso ele seja fornecido, utilizando o operador 'like' para busca parcial
            ->when($params['nome'], fn ($query, $nome) => $query->where('nome', 'like', "%{$nome}%"))
            // Ordena os resultados pelo nome da atividade
            ->orderBy('nome')
            // Realiza a paginação
            ->paginate(20)
            // Mantém os parâmetros originais na URL para navegação entre páginas
            ->appends($params)
            // Converte os resultados para um array
            ->toArray();

        // Agrupa as atividades pela primeira letra inicial de seu nome
        $data['data'] = collect($data['data'])->groupBy(
            fn ($atividade) => $atividade['nome'][0]
        )->toArray();

        return array_merge($params, $data);
    }
}
