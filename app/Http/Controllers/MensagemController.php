<?php

namespace App\Http\Controllers;

use App\Models\Mensagem;
use App\Models\MensagemDestinatario;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MensagemController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $enviadas = Mensagem::where('remetente_id', $userId)->get();

        $recebidas = MensagemDestinatario::where('destinatario_id', $userId)
            ->with('mensagem')
            ->get();

        return view('mensagens.index', compact('enviadas', 'recebidas'));
    }

    public function create()
    {
        $usuarios = Usuario::all();
        return view('mensagens.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required',
            'conteudo' => 'required',
            'destinatarios' => 'required|array',
        ]);

        $mensagem = Mensagem::create([
            'remetente_id' => Auth::id(),
            'titulo' => $request->titulo,
            'conteudo' => $request->conteudo,
        ]);

        if (in_array('todos', $request->destinatarios)) {
            $destinatarios = Usuario::where('id', '!=', Auth::id())->pluck('id');
        } else {
            $destinatarios = $request->destinatarios;
        }

        foreach ($destinatarios as $destinatario) {
            MensagemDestinatario::create([
                'mensagem_id' => $mensagem->id,
                'destinatario_id' => $destinatario,
            ]);
        }

        return redirect()->route('mensagens.index');
    }

    public function show($id)
    {
        $mensagem = Mensagem::findOrFail($id);

        $destinatario = MensagemDestinatario::where('mensagem_id', $id)
            ->where('destinatario_id', Auth::id())
            ->first();

        if ($destinatario && !$destinatario->lida) {
            $destinatario->update([
                'lida' => true,
                'data_leitura' => now(),
            ]);
        }

        return view('mensagens.show', compact('mensagem'));
    }

    public function edit($id)
    {
        $mensagem = Mensagem::findOrFail($id);

        if ($mensagem->remetente_id !== Auth::id()) {
            abort(403);
        }

        // Não permitir edição se alguém já leu:
        if ($mensagem->destinatarios()->where('lida', true)->exists()) {
            abort(403, 'A mensagem já foi lida por alguém e não pode mais ser editada.');
        }

        $usuarios = Usuario::all();
        return view('mensagens.edit', compact('mensagem', 'usuarios'));
    }

    public function update(Request $request, $id)
    {
        $mensagem = Mensagem::findOrFail($id);

        if ($mensagem->remetente_id !== Auth::id()) {
            abort(403);
        }

        $mensagem->update([
            'titulo' => $request->titulo,
            'conteudo' => $request->conteudo,
        ]);

        return redirect()->route('mensagens.index');
    }

    public function destroy($id)
    {
        $mensagem = Mensagem::findOrFail($id);

        if ($mensagem->remetente_id !== Auth::id()) {
            abort(403);
        }

        $mensagem->delete();

        return redirect()->route('mensagens.index');
    }
}
