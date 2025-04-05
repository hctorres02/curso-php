<?php

use App\Enums\Permission;
use App\Enums\Role;
use App\Http\Request;
use Spatie\Menu\Link;
use Spatie\Menu\Menu;

$usuario = session()->get('usuario');
$isAuthenticated = boolval($usuario);
$currentUri = Request::getPathInfo();

return array_map(fn (Menu $menu) => ! $menu->count() ? null : $menu
    // Define como ativo o link correspondente à URI atual
    ->setActive($currentUri)

    // Aplica definição de ativo ao link (A) e não ao elemento (LI)
    ->setActiveClassOnLink()

    // Adiciona atributos ao link
    ->each(fn (Link $link) => $link->setAttributes([
        'aria-current' => $link->isActive() ? 'true' : 'false'
    ]))

    // Renderiza o menu como HTML somente se existirem elementos nele
    ->render(), [

    /**
     * Menu principal
     * Fica abaixo do cabeçalho
     */
    'MAIN' => Menu::new()
        ->if($isAuthenticated, fn (Menu $menu) => $menu
            ->wrap('nav', ['class' => 'container'])
            ->link('/agendamentos', 'Agendamentos')
            ->linkIf($usuario->hasPermission(Permission::VER_PERIODOS), route('periodos'), 'Períodos')
            ->linkIf($usuario->hasPermission(Permission::VER_DISCIPLINAS), route('disciplinas'), 'Disciplinas')
            ->linkIf($usuario->hasPermission(Permission::VER_ATIVIDADES), route('atividades'), 'Atividades')
            ->linkIf($usuario->hasPermission(Permission::VER_USUARIOS), route('usuarios'), 'Usuários')
            ->linkIf($usuario->hasRole(Role::ADMINISTRADOR), route('logs'), 'Logs')
        ),

    /**
     * Menu auxiliar
     * Fica ao lado do cabeçalho
     */
    'AUX' => Menu::new()
        ->if($isAuthenticated, fn (Menu $menu) => $menu
            ->submenu(fn (Menu $submenu) => $submenu
                ->wrap('details', ['class' => 'dropdown', 'dir' => 'rtl'])
                ->prepend("<summary>{$usuario->email}</summary>")
                ->link(route('editar_cadastro'), 'Editar perfil')
                ->link(route('logout'), 'Logout')
            )
        )
        ->if(! $isAuthenticated, fn (Menu $menu) => $menu
            ->link(route('login'), 'Login')
            ->link(route('cadastro'), 'Cadastro')
        ),
]);
