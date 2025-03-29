<?php

use App\Enums\Permission;
use App\Enums\Role;
use App\Http\Request;
use App\Http\View;
use App\Models\Usuario;
use Faker\Factory;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;

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

if (! function_exists('flash')) {
    function flash(): FlashBag
    {
        return session()->getFlashBag();
    }
}

if (! function_exists('hasPermission')) {
    function hasPermission(Permission|string $permission, ?Usuario $usuario = null): bool
    {
        if (! $usuario) {
            $usuario = session()->get('usuario');
        }

        return $usuario && $usuario->hasPermission($permission);
    }
}

if (! function_exists('hasRole')) {
    function hasRole(Role|string ...$roles): bool
    {
        $usuario = session()->get('usuario');

        return $usuario && $usuario->hasRole(...$roles);
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

if (! function_exists('responseError')) {
    function responseError(int $status): Response
    {
        $data = match ($status) {
            Response::HTTP_BAD_REQUEST => [
                'header' => '400 Solicitação Inválida',
                'message' => 'O servidor não pode processar a solicitação devido a uma sintaxe inválida'
            ],
            Response::HTTP_UNAUTHORIZED => [
                'header' => '401 Não Autorizado',
                'message' => 'A solicitação requer autenticação do usuário'
            ],
            Response::HTTP_FORBIDDEN => [
                'header' => '403 Proibido',
                'message' => 'O cliente não tem permissão para acessar este recurso'
            ],
            Response::HTTP_NOT_FOUND => [
                'header' => '404 Não Encontrado',
                'message' => 'O servidor não conseguiu encontrar o recurso solicitado'
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR => [
                'header' => '500 Erro Interno do Servidor',
                'message' => 'Ocorreu um erro inesperado no servidor'
            ],
            Response::HTTP_NOT_IMPLEMENTED => [
                'header' => '501 Não Implementado',
                'message' => 'O servidor não reconhece o método da solicitação ou não tem capacidade para atendê-lo'
            ],
            default => [
                'header' => 'Erro Desconhecido',
                'message' => 'Consulte a documentação ou entre em contato com o suporte'
            ]
        };

        return response('erro', $data, $status);
    }
}

if (! function_exists('resolveCallback')) {
    function resolveCallback(array|callable|Permission|string $action, array $params = []): mixed
    {
        if (is_a($action, Permission::class)) {
            return hasPermission($action);
        }

        if (is_callable($action)) {
            $reflectionFunction = new \ReflectionFunction($action);
            $params = resolveParams($reflectionFunction->getParameters(), $params);

            return $reflectionFunction->invokeArgs($params);
        }

        try {
            [$className, $method] = is_array($action) ? $action : [$action, '__invoke'];
            $reflectionClass = new \ReflectionClass($className);
            $dependencies = resolveParams($reflectionClass->getConstructor()?->getParameters() ?: []);
            $controllerInstance = $reflectionClass->newInstanceArgs($dependencies);
            $reflectionMethod = $reflectionClass->getMethod($method);
            $params = resolveParams($reflectionMethod->getParameters(), $params);

            return $reflectionMethod->invokeArgs($controllerInstance, $params);
        } catch (ReflectionException $e) {
            return responseError(Response::HTTP_NOT_IMPLEMENTED);
        }
    }
}

if (! function_exists('resolveParams')) {
    function resolveParams(array $reflectionParams, array $routeParams = []): array
    {
        return array_reduce($reflectionParams, function (array $resolvedParams, ReflectionParameter $param) use ($routeParams) {
            $paramType = $param->getType();
            $paramName = $paramType->getName();
            $routeParam = array_shift($routeParams);
            $resolvedParams[] = empty($paramType) || $paramType->isBuiltin() || $param->isOptional() ? $routeParam : match (true) {
                $paramName === Request::class => Request::getInstance(),
                enum_exists($paramName) => $paramName::tryFrom($routeParam),
                is_subclass_of($paramName, Model::class) => $paramName::findOrFail($routeParam),
                default => new $paramName
            };

            return $resolvedParams;
        }, []);
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

if (! function_exists('session')) {
    function session(): Session
    {
        return Request::getSession();
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
