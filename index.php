<!DOCTYPE html>
<html lang="pt_br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.unpkg.com/bulma@1.0.2/css/bulma.min.css">
    <link rel="stylesheet" href="https://unpkg.com/font-awesome@4.7.0/css/font-awesome.min.css">
    <title>Calendário acadêmico</title>
</head>

<body>
    <header>
        <div class="hero is-light">
            <div class="hero-body">
                <div class="container">
                    <p class="title">Calendário acadêmico</p>
                    <p class="subtitle">Período: <a href="#">2024.2</a></p>
                </div>
            </div>
            <div class="hero-foot">
                <nav class="tabs is-boxed">
                    <div class="container">
                        <ul>
                            <li class="is-active">
                                <a href="/">
                                    <span class="icon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <span>Página inicial</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <section class="section">
            <div class="container">
                <div class="table-container">
                    <table class="table is-fullwidth">
                        <thead>
                            <tr>
                                <th width="90">Data</th>
                                <th width="120">Disciplina</th>
                                <th width="120">Atividade</th>
                                <th width="auto">Conteúdo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>11/12</td>
                                <td>
                                    <span class="tag is-success is-light">Matemática</span>
                                </td>
                                <td>
                                    <span class="tag is-danger">Prova</span>
                                </td>
                                <td class="content">
                                    <ul>
                                        <li>Números naturais</li>
                                        <li>Números racionais</li>
                                        <li>Números irracionais</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td>12/12</td>
                                <td>
                                    <span class="tag is-link is-light">Português</span>
                                </td>
                                <td>
                                    <span class="tag is-warning">Debate</span>
                                </td>
                                <td class="content">
                                    <a href="#">Capítulos 1, 2 e 3</a>
                                </td>
                            </tr>
                            <tr>
                                <td>18/12</td>
                                <td>
                                    <span class="tag is-link is-light">Português</span>
                                </td>
                                <td>
                                    <span class="tag is-warning">Debate</span>
                                </td>
                                <td class="content">
                                    <a href="#">Capítulos 4 e 5</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</body>

</html>