<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Respect\Validation\Validator;

class Usuario extends Model
{
    use HasFactory;

    public $fillable = [
        'usuario_id',
        'nome',
        'email',
        'senha',
    ];

    public static function rules(): array
    {
        return [
            'usuario_id' => Validator::nullable(Validator::intVal()->callback(self::exists(...))),
            'nome' => Validator::notEmpty()->regex('/^(?!.*\s{2})[\p{L}\s]{3,30}$/u'),
            'email' => Validator::notEmpty()->email(),
            'senha' => Validator::notEmpty()->length(8),
        ];
    }

    public function nome(): Attribute
    {
        return Attribute::make(set: fn (string $nome) => ucwords(strtolower($nome)));
    }

    public function email(): Attribute
    {
        return Attribute::make(set: fn (string $email) => strtolower($email));
    }

    public function senha(): Attribute
    {
        return Attribute::make(set: fn (string $senha) => password_hash($senha, constant(env('PASSWORD_ALGO'))));
    }
}
