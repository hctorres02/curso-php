<?php

namespace App\Http;

use App\Traits\IsSingleton;
use Respect\Validation\Exceptions\ValidationException;
use Symfony\Component\HttpFoundation\Request as BaseRequest;
use Symfony\Component\HttpFoundation\Session\Session;

class Request
{
    use IsSingleton;

    private BaseRequest $request;

    public array $errors = [];

    public array $protected = [
    ];

    public array $old = [];

    public array $validated = [];

    public static function boot(): static
    {
        static::getInstance()->request = BaseRequest::createFromGlobals();

        return static::getInstance();
    }

    public static function __callStatic($method, $args): mixed
    {
        return call_user_func_array([static::getInstance()->request, $method], $args);
    }

    public function __get($name): mixed
    {
        return $this->request->{$name};
    }

    public function __call($method, $args): mixed
    {
        return call_user_func_array([$this->request, $method], $args);
    }

    public function attemptedUri(string $default): string
    {
        return $this->getSession()->get('attempted_uri', $default);
    }

    public function validate(array $rules, array $only = []): bool
    {
        if ($only) {
            $rules = array_intersect_key($rules, array_flip($only));
        }

        foreach ($rules as $field => $rule) {
            try {
                // Obtém o valor do campo da requisição usando o método get.
                $value = $this->get($field);

                // Executa a validação do valor do campo com a regra fornecida.
                $rule->setName('')->check($value);

                // Se a validação for bem-sucedida, armazena o valor validado no array de valores validados.
                $this->validated[$field] = $value;
            } catch (ValidationException $e) {
                // Se ocorrer um erro durante a validação, armazena a mensagem de erro no array de erros.
                $this->errors[$field] = $e->getMessage();
            } finally {
                $this->old[$field] = ! in_array($field, $this->protected) ? $value : null;
            }
        }

        if ($this->errors) {
            flash()->setAll([
                'err' => $this->errors,
                'old' => $this->old,
            ]);

            return false;
        }

        return true;
    }

    /**
     * Gera um token CSRF único e o armazena na sessão, caso ainda não exista.
     *
     * Este método verifica se já existe um token CSRF na sessão. Caso contrário, ele gera
     * um novo token aleatório de 32 bytes e o armazena. O token gerado é retornado.
     *
     * @return string O token CSRF gerado ou recuperado da sessão.
     */
    public function generateCsrfToken(): string
    {
        /** @var Session */
        $session = $this->getSession();

        if (! $session->get('csrf_token')) {
            $session->set('csrf_token', bin2hex(random_bytes(32)));
        }

        return $session->get('csrf_token');
    }

    /**
     * Verifica se o token CSRF presente na requisição é válido.
     *
     * Este método tenta obter o token CSRF da requisição, seja do cabeçalho 'X-CSRF-TOKEN'
     * ou do corpo da requisição (campo '_csrf_token'). Em seguida, ele compara esse token
     * com o token armazenado na sessão. A verificação é feita utilizando a função hash_equals
     * para prevenir ataques de timing.
     *
     * @return bool Retorna verdadeiro se o token CSRF da requisição for igual ao token da sessão, caso contrário, falso.
     */
    public function verifyCsrfToken(): bool
    {
        // Tenta obter o token CSRF do cabeçalho ou do corpo da requisição
        $csrfToken = $this->headers->get('X-CSRF-TOKEN', $this->get('_csrf_token', ''));

        // Obtém o token da sessão
        $sessionToken = $this->getSession()->get('csrf_token', '');

        // Verifica se os tokens são iguais
        return hash_equals($sessionToken, $csrfToken);
    }
}
