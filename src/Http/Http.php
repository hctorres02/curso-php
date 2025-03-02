<?php

namespace App\Http;

use App\Traits\IsSingleton;
use DOMDocument;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * A classe Http fornece uma interface para realizar requisições HTTP usando o cliente Guzzle.
 * Ela é um Singleton que inicializa o cliente Guzzle e expõe métodos estáticos para realizar
 * requisições HTTP como GET, POST, PUT e DELETE.
 *
 * @method static ResponseInterface delete(string $uri, array $options = []) Realiza uma requisição HTTP DELETE.
 * @method static ResponseInterface get(string $uri, array $options = []) Realiza uma requisição HTTP GET.
 * @method static ResponseInterface post(string $uri, array $options = []) Realiza uma requisição HTTP POST.
 * @method static ResponseInterface put(string $uri, array $options = []) Realiza uma requisição HTTP PUT.
 */
class Http
{
    use IsSingleton;

    private Client $client;

    /**
     * Inicializa o cliente HTTP Guzzle com a configuração fornecida.
     *
     * @param array $config Configurações a serem passadas ao cliente Guzzle.
     *
     * @return void
     */
    public static function boot(array $config): void
    {
        self::getInstance()->client = new Client($config);
    }

    /**
     * Método mágico para chamar métodos do cliente Guzzle de forma estática.
     * Este método permite chamar métodos do Guzzle como delete(), get(), post(), put().
     *
     * @param string $method O método do cliente Guzzle que será chamado.
     * @param array $args Os argumentos a serem passados para o método do cliente Guzzle.
     *
     * @return ResponseInterface A resposta retornada pela requisição HTTP.
     */
    public static function __callStatic($method, $args = []): ResponseInterface
    {
        return call_user_func_array([self::getInstance()->client, $method], $args);
    }

    /**
     * Recupera o CSRF Token de uma página, buscando a tag <input name="_csrf-token">.
     *
     * Realiza uma requisição GET para a URL fornecida, analisa o HTML da resposta
     * e extrai o valor do token CSRF da tag <input name="_csrf-token">.
     *
     * @param string $path O caminho da URL da qual o CSRF token será extraído.
     *
     * @return string|null O CSRF token se encontrado, ou null se não encontrado.
     */
    public static function getCsrfToken(string $path): ?string
    {
        $response = self::get($path);
        $responseContent = $response->getBody()->getContents();
        $dom = new DOMDocument;

        // Suprime erros de HTML mal formado
        libxml_use_internal_errors(true);

        // Carrega o conteúdo HTML da resposta para o DOMDocument
        $dom->loadHTML($responseContent);

        // Limpa os erros de parsing
        libxml_clear_errors();

        foreach ($dom->getElementsByTagName('input') as $input) {
            if ($input->getAttribute('name') === '_csrf_token') {
                return $input->getAttribute('value');
            }
        }

        return null;
    }
}
