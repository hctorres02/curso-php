<?php

namespace App\Http;

use App\Traits\IsSingleton;
use Twig\Environment;
use Twig\TwigFunction;

class View
{
    use IsSingleton;

    private Environment $twig;

    public static function boot(Environment $twig): void
    {
        static::getInstance()->twig = $twig;
    }

    public static function render(string $name, array $data = []): string
    {
        if (! str_ends_with($name, '.twig')) {
            $name = "{$name}.twig";
        }

        return static::getInstance()->twig->render($name, $data);
    }

    public static function addGlobals(array $data): void
    {
        foreach ($data as $name => $value) {
            if (is_callable($value)) {
                $value = resolveCallback($value);
            }

            static::getInstance()->twig->addGlobal($name, $value);
        }
    }

    public static function addFunction(string $name, callable $callback): void
    {
        static::getInstance()->twig->addFunction(new TwigFunction($name, $callback));
    }
}
