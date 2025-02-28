<?php

use App\Http\View;
use Faker\Factory;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

if (! function_exists('attribute')) {
    function attr(array $attributes): string
    {
        return collect($attributes)->reduce(function (string $attributes, mixed $value, string $attr) {
            if ($value !== false) {
                $attributes .= ' '.(is_bool($value) ? $attr : "{$attr}=\"{$value}\"");
            }

            return trim($attributes, ' ');
        }, '');
    }
}

if (! function_exists('createLocalDatabase')) {
    function createLocalDatabase(bool $forceOverwrite = false): void
    {
        $driver = DB::connection()->getDriverName();
        $database = DB::connection()->getDatabaseName();

        if ($driver != 'sqlite') {
            return;
        }

        if (file_exists($database) && ! $forceOverwrite) {
            return;
        }

        // cria banco de dados local
        file_put_contents($database, null);
    }
}

if (! function_exists('faker')) {
    function faker(): Faker\Generator
    {
        return Factory::create(env('APP_LOCALE', 'en'));
    }
}

if (! function_exists('hello_word')) {
    function hello_world(): string
    {
        return 'Hello, world!';
    }
}

if (! function_exists('migrate')) {
    function migrate(string $migration): void
    {
        // executa migration
        (require $migration)->up();

        // registra migration no banco de dados
        DB::table('migrations')->insert([
            'filename' => pathinfo($migration, PATHINFO_FILENAME),
            'executed_at' => Carbon::now(),
        ]);
    }
}

if (! function_exists('refreshDatabase')) {
    function refreshDatabase(): void
    {
        // verifica base de dados local (SQLite)
        createLocalDatabase(true);

        // executa migrations
        foreach (glob(PROJECT_ROOT.'/database/migrations/*.php') as $migration) {
            migrate($migration);
        }
    }
}

if (! function_exists('rollback')) {
    function rollback(string $filename): void
    {
        // remove registro do banco de dados
        DB::table('migrations')->where('filename', $filename)->delete();

        // reverte migration
        (require PROJECT_ROOT."/database/migrations/{$filename}.php")->down();
    }
}

if (! function_exists('redirect')) {
    function redirect(string $url, int $status = Response::HTTP_FOUND): RedirectResponse
    {
        return new RedirectResponse($url, $status);
    }
}

if (! function_exists('response')) {
    function response(string $viewName, array $data = [], int $status = Response::HTTP_OK): Response
    {
        return new Response(View::render($viewName, $data), $status);
    }
}

if (! function_exists('runServer')) {
    function runServer(bool $keepOn = false): string
    {
        $host = env('APP_URL');

        if (! $host) {
            echo "Você precisa definir um valor para APP_URL no seu `.env`\n";
            exit(1);
        }

        $root = PROJECT_ROOT;
        $command = "php -c {$root}/99-custom.ini -S {$host} -t {$root}/public";

        if (! $keepOn) {
            $command = "{$command} > /dev/null 2>&1 &";
        }

        exec($command);
        sleep(2);

        return $command;
    }
}

if (! function_exists('stopServer')) {
    function stopServer(): void
    {
        $root = PROJECT_ROOT;
        $host = env('APP_URL');
        $command = "php -c {$root}/99-custom.ini -S {$host} -t {$root}/public";

        exec("pkill -f '{$command}'");
    }
}

if (! function_exists('today')) {
    function today(): string
    {
        return Carbon::today();
    }
}

if (! function_exists('url')) {
    function url(string $path, array $params = []): string
    {
        $params = collect($params)->reduce(function ($params, $value, $key) use (&$path) {
            $newPath = preg_replace('/\{'.preg_quote($key, '/').'\}/', $value, $path);

            if ($newPath === $path) {
                $params[$key] = $value;
            }

            $path = $newPath;

            return $params;
        }, []);

        return implode('?', array_filter([$path, http_build_query($params)]));
    }
}
