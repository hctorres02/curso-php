<?php

namespace App\Http;

use App\Http\Request;
use App\Traits\IsSingleton;
use Symfony\Component\HttpFoundation\Response;

/**
 * Classe Router que gerencia o roteamento de requisições HTTP.
 *
 * Métodos mágicos estáticos para registrar rotas HTTP.
 *
 * Esses métodos permitem o registro de rotas para os métodos HTTP (GET, POST, PUT, DELETE),
 * facilitando a definição de ações para as rotas diretamente, sem a necessidade de declarar
 * métodos explícitos na classe. Eles são interceptados pelo método mágico `__callStatic`,
 * que direciona a chamada para o método `register` para efetuar o registro da rota.
 *
 * @method static void get(string $uri, mixed $action, array $middlewares = [], string $name = null) Registra uma rota do tipo GET.
 * @method static void post(string $uri, mixed $action, array $middlewares = [], string $name = null) Registra uma rota do tipo POST.
 * @method static void put(string $uri, mixed $action, array $middlewares = [], string $name = null) Registra uma rota do tipo PUT.
 * @method static void delete(string $uri, mixed $action, array $middlewares = [], string $name = null) Registra uma rota do tipo DELETE.
 */
class Router
{
    use IsSingleton;

    /**
     * Array de rotas registradas.
     *
     * @var array
     */
    private array $routes = [];

    /**
     * Objeto Request que contém a requisição atual.
     *
     * @var Request
     */
    private Request $request;

    /**
     * Método mágico que intercepta chamadas estáticas de métodos não definidos,
     * registrando as rotas no roteador.
     *
     * @param string $name Nome do método chamado.
     * @param array $arguments Argumentos passados para o método.
     *
     * @return void
     */
    public static function __callStatic(string $name, array $arguments): void
    {
        static::getInstance()->register($name, ...$arguments);
    }

    public static function createUrl(string $path, array $params = []): string
    {
        if ($params && array_key_exists(0, $params) && is_array($params[0])) {
            $params = $params[0];
        }

        foreach ($params as $key => $value) {
            if (is_array($value)) {
                continue;
            }

            $newPath = $path;
            $isNumericKey = is_numeric($key);
            $pattern = $isNumericKey ? '/\{(.*?)\}/' : '/\{'.preg_quote($key, '/').'\}/';
            $limit = intval($isNumericKey ?: -1);
            $value = strval($value);
            $path = preg_replace($pattern, $value, $path, $limit);

            if ($path != $newPath) {
                unset($params[$key]);
            }
        }

        if ($params) {
            $path .= '?'.http_build_query($params);
        }

        $protocol = (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = env('APP_URL');
        $path = ltrim($path, '/');

        return rtrim("{$protocol}://{$host}/{$path}", '/');
    }

    public static function createUrlFromName(string $name, array $params = []): string
    {
        return static::createUrl(static::getRouteUri($name), $params);
    }

    public static function createSignedUrl(string $path, array $params = []): string
    {
        $params['expires'] ??= strtotime('+5 minutes');

        unset($params['signature'], $params['senha']);
        ksort($params);

        $params['signature'] = hash_hmac('sha256', http_build_query($params), env('APP_KEY'));

        return static::createUrl($path, $params);
    }

    public static function createSignedUrlFromName(string $name, array $params = []): string
    {
        return static::createSignedUrl(static::getRouteUri($name), $params);
    }

    /**
     * Dispara o roteador, resolvendo a rota correspondente à requisição e retornando a resposta.
     *
     * @param Request $request Objeto Request que representa a requisição HTTP.
     * @param array $middlewares Middlewares globais.
     *
     * @return Response A resposta gerada pela execução dos middlewares e ação da rota.
     */
    public static function dispatch(Request $request, array $middlewares): Response
    {
        $router = static::getInstance();
        $router->request = $request;

        return $router->getResponse($middlewares, $router->resolveRoute(...));
    }

    private static function getRouteUri(string $name): string
    {
        foreach (static::getInstance()->routes as $group) {
            foreach ($group as $uri => $definition) {
                if ($definition['name'] === $name) {
                    return $uri;
                }
            }
        }

        throw new \Exception("A rota '{$name}' não foi definida");
    }

    /**
     * Registra um redirecionamento de uma URL para outra.
     *
     * @param string $from URL de origem.
     * @param string $to URL de destino.
     * @param int $status Código de status HTTP (default: 302).
     * @param string $name
     *
     * @return void
     */
    public static function redirect(string $from, string $to, int $status = Response::HTTP_FOUND, string $name = null): void
    {
        static::get($from, fn () => redirect($to, $status), name: $name);
    }

    /**
     * Resolve a execução dos middlewares fornecidos, executando-os na ordem e retornando a resposta apropriada.
     *
     * Esse método percorre os middlewares passados, resolvendo e executando cada um deles.
     * Caso algum middleware retorne uma resposta diferente de `true`, será retornado um erro 403.
     *
     * @param array $middlewares Lista de middlewares a serem executados.
     * @param mixed $action Ação subsequente a execução dos middlewares.
     * @param array $params Parâmetros adicionais.
     *
     * @return Response
     */
    private function getResponse(array $middlewares, mixed $action, array $params = []): Response
    {
        foreach ($middlewares as $middleware) {
            $middlewareResponse = resolveCallback($middleware, $params);

            if ($middlewareResponse !== true) {
                return $middlewareResponse ?: responseError(Response::HTTP_FORBIDDEN);
            }
        }

        return resolveCallback($action, $params);
    }

    /**
     * Retorna as rotas registradas para o método HTTP especificado.
     *
     * @param string $method O método HTTP (GET, POST, etc.).
     *
     * @return array Lista de rotas registradas para o método.
     */
    private function getRoutes(string $method): array
    {
        return $this->routes[strtoupper($method)] ?? [];
    }

    /**
     * Registra uma nova rota no roteador.
     *
     * @param string $method Método HTTP (GET, POST, etc.).
     * @param string $uri URI da rota.
     * @param mixed $action Será executado quando a rota for acionada.
     * @param array $middlewares Middlewares locais.
     * @param string $name
     *
     * @return void
     */
    private function register(string $method, string $uri, mixed $action, array $middlewares = [], string $name = null): void
    {
        $this->routes[strtoupper($method)][$uri] = compact('action', 'middlewares', 'name');
    }

    /**
     * Resolve a rota correspondente à requisição e retorna a resposta.
     *
     * Verifica se o método da solicitação está entre os métodos permitidos (DELETE, POST, PUT)
     * e se o token CSRF é válido. Em seguida, tenta combinar o caminho da solicitação com as
     * rotas registradas e resolve a ação correspondente.
     * Caso nenhuma rota seja encontrada, será retornado um erro 404.
     * Caso o CSRF falhe, será retornado um erro 403.
     *
     * @return Response A resposta da execução da rota ou erro
     */
    private function resolveRoute(): Response
    {
        foreach ($this->getRoutes($this->request->getMethod()) as $uri => $action) {
            $pattern = '#^'.preg_replace('/\{([\w]+)\}/', '(?P<\1>[^/]+)', $uri).'$#';
            $path = rtrim($this->request->getPathInfo(), '/') ?: '/';

            if (preg_match($pattern, $path, $params)) {
                extract($action);

                return $this->getResponse(
                    $middlewares,
                    $action,
                    array_filter($params, is_string(...), ARRAY_FILTER_USE_KEY)
                );
            }
        }

        return responseError(Response::HTTP_NOT_FOUND);
    }
}
