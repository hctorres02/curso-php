<?php

namespace App\Http;

use App\Traits\IsSingleton;
use Twig\Environment;

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
}
