<?php

use App\Http\Request;
use Spatie\Menu\Link;
use Spatie\Menu\Menu;

$usuario = session()->get('usuario');
$currentUri = Request::getPathInfo();

return Menu::new()
    ->link('/agendamentos', 'Agendamentos')

    // Se o usuário estiver autenticado, adiciona links específicos
    ->if(! ! $usuario, fn (Menu $menu) => $menu
        ->link('/periodos', 'Períodos')
        ->link('/disciplinas', 'Disciplinas')
        ->link('/atividades', 'Atividades')
        ->submenu(fn (Menu $submenu) => $submenu
            ->wrap('details', ['class' => 'dropdown', 'dir' => 'rtl'])
            ->prepend("<summary>{$usuario->email}</summary>")
            ->link('/usuarios/editar', 'Editar perfil')
            ->link('/logout', 'Logout')
        )
    )

    // Se o usuário NÃO estiver autenticado, exibe apenas opções limitadas
    ->if(! $usuario, fn (Menu $menu) => $menu
        ->link('/login', 'Login')
        ->link('/usuarios/cadastrar', 'Cadastro')
    )

    // Define como ativo o link correspondente à URI atual
    ->setActive($currentUri)

    // Adiciona automaticamente uma classe CSS "active" ao link ativo
    ->setActiveClassOnLink()

    // Adiciona atributos ao link ativo
    ->each(fn (Link $link) => $link->setAttribute('aria-current', $link->isActive() ? 'true' : 'false'))

    // Renderiza o menu como HTML
    ->render();
