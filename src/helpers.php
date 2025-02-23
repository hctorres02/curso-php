<?php

use Faker\Factory;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\Carbon;

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
        (require_once $migration)->up();

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
        (require_once PROJECT_ROOT."/database/migrations/{$filename}.php")->down();
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
