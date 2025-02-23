<?php

namespace App\Http;

use App\Traits\IsSingleton;
use Symfony\Component\HttpFoundation\Request as BaseRequest;

class Request
{
    use IsSingleton;

    private BaseRequest $request;

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
}
