<?php

namespace App\Http;

use App\Traits\IsSingleton;
use Respect\Validation\Exceptions\ValidationException;
use Symfony\Component\HttpFoundation\Request as BaseRequest;

class Request
{
    use IsSingleton;

    private BaseRequest $request;

    public array $errors = [];

    public array $validated = [];

    public static function boot(): static
    {
        static::getInstance()->request = BaseRequest::createFromGlobals();

        return static::getInstance();
    }

    public function __get($name): mixed
    {
        return $this->request->{$name};
    }

    public function __call($method, $args): mixed
    {
        return call_user_func_array([$this->request, $method], $args);
    }

    public function getErrors(): array
    {
        return $this->getSession()->getFlashBag()->get('err');
    }

    public function setErrors(array $errors): void
    {
        $this->getSession()->getFlashBag()->set('err', $errors);
    }

    public function validate(array $rules): bool
    {
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
            }
        }

        // Define os erros no objeto, caso existam.
        $this->setErrors($this->errors);

        // Retorna true se não houver erros, indicando que a validação foi bem-sucedida; caso contrário, retorna false.
        return ! $this->errors;
    }
}
