# Curso PHP: do XSLX ao CMS
Este projeto tem por finalidade exemplificar a transformação uma planilha em um sistema web.

Além de ensinar sobre a linguagem PHP, será usada abordagens sobre modelagem de dados e elaboração da documentação inicial de software baseado no modelo UML, bem como introdução à segurança digital de dados.

## Roadmap
Inicialmente o projeto será elaborado com "programação natural", onde cada arquivo fará, de forma repetidda e proposital, a mesma coisa que outros em questão de estrutura de código e layout de páginas. Com o desenvolver do projeto, será mostrado como dividir o código para realizar reaproveitamento e mitigar a repetição desnecessária.

Noutro momento, será implementado um sistema de layout dinâmico, chamado tecnicamente de *template engine*. Para essa tarefa, usaremos a biblioteca [Twig](https://twig.symfony.com).

Para elevar o nível da aplicação ao paradigma MVC, bem como aplicação das [PHP PSRs](https://www.php-fig.org/psr), momento que será apresentado o uso do [Composer](https://getcomposer.org), o gerenciador de dependências do PHP, aplicaremos um sistema de rotas utilizando a biblioteca [Plug-Route](https://github.com/erandirjunior/plug-route).

## Ferramentas
### HTML e CSS
Para facilitar o aprendizado da criação da inteface gráfica (UI) e trabalhar mais a parte de criação de HTML, usaremos como framework CSS a biblioteca [Bulma.io](https://bulma.io), por ter uma característica mais verbosa que outras no quesito HTML.

### JavaScript
Para as interações com o navegador, isto é, a manipulação do DOM, usaremos a biblioteca [AlpineJS](https://alpinejs.dev).

### Banco de dados
O banco de dados utilizado será _serverless_, ou seja, não dependerá de servidor dedicado para ser executado, assim, usaremos [SQLite](https://sqlite.org).

## Colaborativo
As aulas serão transmitidas todo domingo (com ressalvas), sempre as 15h, no grupo do [PHP Brasil](https://t.me/phpbrasil) no Telegram, onde os participantes podem tirar dúvidas sobre outros assuntos relacionados ou não às aulas.

Focando no modelo colaborativo, algumas tarefas serão realizadas com apoio da comunidade, garantindo que haja uma participação múltua. Esta abordagem visa que os participantes ganhem confiança para realizar *pull request* (PR) em projetos de terceiros com suas próprias correções.

> Por ser um projeto colaborativo e aberto a todos, **não haverá divulgação de gravação da transmissão!** Os códigos serão divulgados por meio deste repositório. Demais materiais de aula, enviados ao grupo no Telegram supracitado.