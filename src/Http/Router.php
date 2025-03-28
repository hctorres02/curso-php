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
 * @method static void get(string $uri, mixed $action, array $middlewares = []) Registra uma rota do tipo GET.
 * @method static void post(string $uri, mixed $action, array $middlewares = []) Registra uma rota do tipo POST.
 * @method static void put(string $uri, mixed $action, array $middlewares = []) Registra uma rota do tipo PUT.
 * @method static void delete(string $uri, mixed $action, array $middlewares = []) Registra uma rota do tipo DELETE.
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

    /**
     * Registra um redirecionamento de uma URL para outra.
     *
     * @param string $from URL de origem.
     * @param string $to URL de destino.
     * @param int $status Código de status HTTP (default: 302).
     *
     * @return void
     */
    public static function redirect(string $from, string $to, int $status = Response::HTTP_FOUND): void
    {
        static::get($from, fn () => redirect($to, $status));
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
                return $middlewareResponse ?: response('erro_403', status: Response::HTTP_FORBIDDEN);
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
     *
     * @return void
     */
    private function register(string $method, string $uri, mixed $action, array $middlewares = []): void
    {
        $this->routes[strtoupper($method)][$uri] = compact('action', 'middlewares');
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

        return response('erro_404', status: Response::HTTP_NOT_FOUND);
    }
}
