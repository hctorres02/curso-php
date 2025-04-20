<?php

namespace App\Controllers;

use App\Enums\Permission;
use App\Http\Request;
use App\Models\Agendamento;
use App\Models\Anexo;
use App\Models\Atividade;
use App\Models\Disciplina;
use App\Models\Usuario;
use App\Notifications\AgendamentoAtualizado;
use App\Notifications\AgendamentoCadastrado;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class AgendamentoController
{
    public function index(Request $request): Response
    {
        $data = Agendamento::toSearch([
            'periodo_id' => $request->get('periodo_id'),
            'atividade_id' => $request->get('atividade_id'),
            'disciplina_id' => $request->get('disciplina_id'),
        ]);

        return response('agendamentos/index.twig', $data);
    }

    public function cadastrar(): Response
    {
        $atividades = Atividade::toOptGroup();
        $disciplinas = Disciplina::toOptGroup();
        $extensoes_permitidas = Anexo::allowedExtensions(true);

        return response('agendamentos/cadastrar.twig', compact('atividades', 'disciplinas', 'extensoes_permitidas'));
    }

    public function salvar(Request $request): RedirectResponse
    {
        if (! $request->validate(Agendamento::rules())) {
            return redirectRoute('cadastrar_agendamento');
        }

        $agendamento = Agendamento::create($request->validated);

        if (hasPermission(Permission::INSERIR_ANEXOS)) {
            $agendamento->inserirAnexos($request->validated['anexos']);
        }

        notifyMany(Usuario::pluck('id')->toArray(), AgendamentoCadastrado::class, [
            'agendamento' => $agendamento->id,
        ]);

        return redirectRoute('agendamentos');
    }

    public function ver(Agendamento $agendamento): Response
    {
        $agendamento->load('anexos');

        return response('agendamentos/ver.twig', compact('agendamento'));
    }

    public function editar(Agendamento $agendamento): Response
    {
        $atividades = Atividade::toOptGroup();
        $disciplinas = Disciplina::toOptGroup();
        $extensoes_permitidas = Anexo::allowedExtensions(true);

        return response('agendamentos/editar.twig', compact('atividades', 'disciplinas', 'agendamento', 'extensoes_permitidas'));
    }

    public function atualizar(Request $request, Agendamento $agendamento): RedirectResponse
    {
        if (! $request->validate(Agendamento::rules())) {
            return redirectRoute('editar_agendamento', $agendamento->id);
        }

        $agendamento->update($request->validated);

        if (hasPermission(Permission::INSERIR_ANEXOS)) {
            $agendamento->inserirAnexos($request->validated['anexos']);
        }

        notifyMany(Usuario::pluck('id')->toArray(), AgendamentoAtualizado::class, [
            'agendamento' => $agendamento->id,
        ]);

        return redirectRoute('agendamentos');
    }

    public function excluir(Agendamento $agendamento): RedirectResponse
    {
        $agendamento->delete();

        return redirectRoute('agendamentos');
    }
}
