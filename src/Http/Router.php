<?php

namespace App\Http;

use App\Http\Request;
use App\Traits\IsSingleton;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use ReflectionFunction;
use ReflectionParameter;
use Symfony\Component\HttpFoundation\Request as BaseRequest;
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
 * @method static void get(string $uri, mixed $action) Registra uma rota do tipo GET.
 * O parâmetro `$uri` define o padrão da URL para a rota (exemplo: '/home').
 * O parâmetro `$action` é a função ou o controlador que será executado quando a rota for acessada.
 *
 * @method static void post(string $uri, mixed $action) Registra uma rota do tipo POST.
 * O parâmetro `$uri` define o padrão da URL para a rota (exemplo: '/submit').
 * O parâmetro `$action` é a função ou o controlador que será executado quando a rota for acionada.
 *
 * @method static void put(string $uri, mixed $action) Registra uma rota do tipo PUT.
 * O parâmetro `$uri` define o padrão da URL para a rota (exemplo: '/update/{id}').
 * O parâmetro `$action` é a função ou o controlador que será executado para processar a requisição PUT.
 *
 * @method static void delete(string $uri, mixed $action) Registra uma rota do tipo DELETE.
 * O parâmetro `$uri` define o padrão da URL para a rota (exemplo: '/delete/{id}').
 * O parâmetro `$action` é a função ou o controlador que será executado quando a rota DELETE for chamada.
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
     *
     * @return Response A resposta gerada pela execução da rota.
     */
    public static function dispatch(Request $request): Response
    {
        $router = static::getInstance();
        $router->request = $request;

        return $router->resolveRoute();
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
     *
     * @return void
     */
    private function register(string $method, string $uri, mixed $action): void
    {
        $this->routes[strtoupper($method)][$uri] = $action;
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
        if (in_array($this->request->getMethod(), [
            BaseRequest::METHOD_DELETE,
            BaseRequest::METHOD_POST,
            BaseRequest::METHOD_PUT,
        ]) && ! $this->request->verifyCsrfToken()) {
            return response('erro_403', status: Response::HTTP_FORBIDDEN);
        }

        foreach ($this->getRoutes($this->request->getMethod()) as $uri => $action) {
            $pattern = '#^'.preg_replace('/\{([\w]+)\}/', '(?P<\1>[^/]+)', $uri).'$#';
            $path = rtrim($this->request->getPathInfo(), '/') ?: '/';

            if (preg_match($pattern, $path, $params)) {
                return resolveCallback(
                    $action,
                    array_filter($params, is_string(...), ARRAY_FILTER_USE_KEY)
                );
            }
        }

        return response('erro_404', status: Response::HTTP_NOT_FOUND);
    }
}
