<?php

namespace App\Http;

use App\Http\Request;
use App\Traits\IsSingleton;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use ReflectionFunction;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * Objeto Response que será retornado ao cliente.
     *
     * @var Response
     */
    private Response $response;

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
        $router->response = new Response;

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
    public static function redirect(string $from, string $to, int $status = 302): void
    {
        static::get($from, fn () => new RedirectResponse($to, $status));
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
     * Resolve o callback de uma rota com os parâmetros extraídos da URL.
     *
     * @param mixed $action O callback da rota.
     * @param array $params Parâmetros extraídos da URL.
     * @param string $uri Nome da rota que está sendo resolvida.
     *
     * @return mixed O conteúdo gerado pela execução do callback.
     */
    private function resolveCallback(mixed $action, array $params): mixed
    {
        if (is_callable($action)) {
            $reflectionFunction = new ReflectionFunction($action);
            $params = $this->resolveParams($reflectionFunction->getParameters(), $params);

            return $reflectionFunction->invokeArgs($params);
        }

        [$controller, $method] = is_array($action) ? $action : [$action, '__invoke'];
        $reflectionClass = new ReflectionClass($controller);

        if (! $reflectionClass->hasMethod($method)) {
            $this->response->setStatusCode(Response::HTTP_NOT_IMPLEMENTED);

            return View::render('erro_501', [
                'controller' => $controller,
                'method' => $method,
            ]);
        }

        $dependencies = $this->resolveParams($reflectionClass->getConstructor()?->getParameters() ?: []);
        $controllerInstance = $reflectionClass->newInstanceArgs($dependencies);
        $reflectionMethod = $reflectionClass->getMethod($method);
        $params = $this->resolveParams($reflectionMethod->getParameters(), $params);

        return $reflectionMethod->invokeArgs($controllerInstance, $params);
    }

    /**
     * Resolve uma dependência através do nome da classe.
     *
     * @param string $className O nome completo da classe a ser resolvida.
     *
     * @return object A instância da dependência.
     */
    private function resolveDependency(string $className, mixed $value): object
    {
        return match (true) {
            $className === Request::class => $this->request,
            $className === Response::class => $this->response,
            is_subclass_of($className, Model::class) => $className::findOrFail($value),
            default => new $className
        };
    }

    /**
     * Resolve os parâmetros necessários para a execução de um método, incluindo a injeção de dependências.
     *
     * Este método usa reflexão para analisar os parâmetros do construtor ou do método de um controlador e resolve
     * as dependências necessárias através de um container de dependências ou usa valores de parâmetros de rota
     * para preenchê-los quando apropriado.
     *
     * O método pode resolver tanto parâmetros de dependências (como objetos que precisam ser injetados) quanto
     * parâmetros extraídos da URL, como os parâmetros de rota que são passados para o controlador.
     *
     * @param \ReflectionParameter[] $reflectionParams Parâmetros obtidos via reflexão do método ou do construtor.
     * @param array $routeParams Parâmetros extraídos da URL ou parâmetros adicionais que precisam ser passados ao método.
     *
     * @return array Retorna um array de valores resolvidos para os parâmetros do método ou do construtor.
     */
    private function resolveParams(array $reflectionParams, array $routeParams = [])
    {
        return array_reduce($reflectionParams, function ($resolvedParams, $param) use ($routeParams) {
            $paramType = $param->getType();
            $paramName = $paramType->getName();

            if ($paramType && ! $param->isOptional()) {
                $resolvedParams[] = $this->resolveDependency($paramName, array_shift($routeParams));
            } else {
                $resolvedParams[] = array_shift($routeParams);
            }

            return $resolvedParams;
        }, []);
    }

    /**
     * Resolve a rota correspondente à requisição e retorna a resposta.
     *
     * @return Response A resposta gerada pela execução da rota ou uma resposta 404.
     */
    private function resolveRoute(): Response
    {
        foreach ($this->getRoutes($this->request->getMethod()) as $uri => $action) {
            $pattern = '#^'.preg_replace('/\{([\w]+)\}/', '(?P<\1>[^/]+)', $uri).'$#';
            $path = rtrim($this->request->getPathInfo(), '/') ?: '/';

            if (! preg_match($pattern, $path, $params)) {
                continue;
            }

            $params = array_filter($params, is_string(...), ARRAY_FILTER_USE_KEY);
            $content = $this->resolveCallback($action, $params);

            if ($content instanceof RedirectResponse) {
                return $content;
            }

            $this->response->setContent($content);

            return $this->response;
        }

        $this->response->setContent(View::render('erro_404'));
        $this->response->setStatusCode(Response::HTTP_NOT_FOUND);

        return $this->response;
    }
}
