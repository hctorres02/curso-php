<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Anexo extends Model
{
    protected $fillable = [
        'nome_original',
        'caminho',
        'tipo',
        'extensao',
        'tamanho',
    ];

    public function agendamento(): BelongsTo
    {
        return $this->belongsTo(Agendamento::class);
    }

    public static function toSearch(array $params): array
    {
        $baseQuery = static::query()
            ->join('agendamentos', 'agendamentos.id', '=', 'anexos.agendamento_id')
            ->join('disciplinas', 'disciplinas.id', '=', 'agendamentos.disciplina_id')
            ->join('periodos', 'periodos.id', '=', 'disciplinas.periodo_id')
            ->orderByDesc('periodos.ano')
            ->orderByDesc('periodos.semestre')
            ->select('anexos.*');

        $data = $baseQuery
            ->clone()
            ->with('agendamento.disciplina.periodo')
            ->when($params['periodo_id'], fn ($query, $periodo_id) => $query->where('periodos.id', $periodo_id))
            ->when($params['disciplina_id'], fn ($query, $disciplina_id) => $query->where('disciplinas.id', $disciplina_id))
            ->paginate(20)
            ->appends($params)
            ->toArray();

        $data['data'] = array_map(function (array $anexo) {
            if (! file_exists(static::uploadsDir($anexo['caminho']))) {
                $anexo['caminho'] = null;
            }

            return $anexo;
        }, $data['data']);

        $data['periodos'] = $baseQuery
            ->clone()
            ->select('periodos.id')
            ->selectRaw("periodos.ano || '.' || periodos.semestre AS nome")
            ->distinct()
            ->pluck('nome', 'periodos.id')
            ->sortDesc();

        $data['disciplinas'] = $baseQuery
            ->clone()
            ->select('disciplinas.id', 'disciplinas.nome')
            ->distinct()
            ->pluck('disciplinas.nome', 'disciplinas.id')
            ->sort();

        return array_merge($params, $data);
    }

    public static function allowedExtensions(bool $asString = false): array|string
    {
        $extensions = collect(static::allowedMimes())->flatten()->unique()->map(fn ($ext) => ".{$ext}");

        if ($asString) {
            return $extensions->implode(',');
        }

        return $extensions->toArray();
    }

    public static function allowedMimes(): array|string
    {
        $config = require PROJECT_ROOT.'/config/uploads.php';

        return $config['mimes'];
    }

    public static function uploadsDir(string $appends = ''): string
    {
        $config = require PROJECT_ROOT.'/config/uploads.php';
        $directory = $config['directory'];

        if ($appends) {
            $directory .= "/{$appends}";
        }

        return $directory;
    }
}
