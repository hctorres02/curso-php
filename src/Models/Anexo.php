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
