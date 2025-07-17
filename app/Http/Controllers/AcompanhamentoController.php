<?php

namespace App\Http\Controllers;

use App\Models\Acompanhamento;
use App\Models\Ocorrencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcompanhamentoController extends Controller
{
    /**
     * Exibe o formulário de criação de um novo acompanhamento.
     *
     * @param int $ocorrenciaId
     * @return \Illuminate\View\View
     */
    public function create($ocorrenciaId)
    {
        // Carrega a ocorrência com o relacionamento do usuário (quem registrou)
        $ocorrencia = Ocorrencia::with('usuario')->findOrFail($ocorrenciaId);

        // Envia a variável $ocorrencia para a view acompanhamentos.create
        return view('acompanhamentos.create', compact('ocorrencia'));
    }

    /**
     * Armazena um novo acompanhamento no banco de dados.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $ocorrenciaId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $ocorrenciaId)
    {
        // Validação dos dados enviados pelo formulário
        $request->validate([
            'descricao' => 'required|string|max:1000',
            'horario'   => 'required|date',
        ]);

        // Criação do novo acompanhamento
        Acompanhamento::create([
            'descricao'     => $request->descricao,
            'horario'       => $request->horario,
            'ocorrencia_id' => $ocorrenciaId,
            'usuario_id'    => Auth::id(),
        ]);

        // Redireciona de volta à tela de detalhes da ocorrência com mensagem de sucesso
        return redirect()
            ->route('ocorrencias.show', $ocorrenciaId)
            ->with('success', 'Acompanhamento salvo com sucesso.');
    }
}
