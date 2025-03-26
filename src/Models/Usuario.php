<?php

namespace App\Models;

use App\Enums\Permission;
use App\Enums\Role;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Respect\Validation\Validator;

class Usuario extends Model
{
    use HasFactory;

    protected $casts = [
        'role' => Role::class,
    ];

    public $fillable = [
        'usuario_id',
        'nome',
        'email',
        'senha',
        'role',
        'permissions',
    ];

    public static function rules(): array
    {
        return [
            'usuario_id' => Validator::nullable(Validator::intVal()->callback(self::exists(...))),
            'nome' => Validator::notEmpty()->regex('/^(?!.*\s{2})[\p{L}\s]{3,30}$/u'),
            'email' => Validator::notEmpty()->email(),
            'senha' => Validator::notEmpty()->length(8),
            'role' => Validator::notEmpty()->in(Role::values()),
            'permissions' => Validator::nullable(Validator::arrayType()->each(Validator::in(Permission::values()))),
        ];
    }

    public static function toSearch(array $params): array
    {
        $data = static::query()
            ->when($params['q'], fn ($query, $q) => $query->where(
                fn ($query) => $query
                    ->whereLike('nome', "%{$q}%")
                    ->orWhereLike('email', "%{$q}%")
            ))
            ->whereNotIn('id', [1, session()->get('usuario_id')])
            ->paginate(10)
            ->appends(array_filter($params))
            ->toArray();

        $data['roles'] = Role::toArray();

        return array_merge($params, $data);
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

    public function permissions(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => json_decode($value ?: '[]'),
            set: fn (?array $value) => json_encode($value ?: []),
        );
    }

    public function hasPermission(Permission|string $permission): bool
    {
        return $this->hasRole(Role::ADMINISTRADOR)
            || in_array($permission->value ?? $permission, array_merge($this->permissions, $this->role->permissions()));
    }

    public function hasRole(Role|string ...$roles): bool
    {
        foreach ($roles as $key => $value) {
            if (is_string($value)) {
                $roles[$key] = Role::from($value);
            }
        }

        return in_array($this->role, $roles, true);
    }
}
