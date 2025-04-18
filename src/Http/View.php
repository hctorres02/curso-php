<?php

namespace App\Http;

use App\Traits\IsSingleton;
use Twig\Environment;
use Twig\TwigFunction;
use voku\helper\HtmlMin;

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
        $content = static::getInstance()->twig->render($name, $data);

        if (! env('APP_DEBUG')) {
            $content = (new HtmlMin)->doRemoveOmittedQuotes(false)->minify($content);
        }

        return $content;
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
