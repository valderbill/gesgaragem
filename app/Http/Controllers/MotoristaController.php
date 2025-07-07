<?php

namespace App\Http\Controllers;

use App\Models\Motorista;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;

class MotoristaController extends Controller
{
    public function index()
    {
        $motoristas = Motorista::all();
        return view('motorista.index', compact('motoristas'));
    }

    public function create()
    {
        return view('motorista.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'matricula' => 'required|string|max:50',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Salva a imagem na pasta storage/app/public/motoristas
        $fotoPath = $request->file('foto')->store('motoristas', 'public');

        // Cria novo motorista
        Motorista::create([
            'nome' => $request->nome,
            'matricula' => $request->matricula,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('motoristas.index')->with('success', 'Motorista cadastrado com sucesso.');
    }

    public function show(Motorista $motorista)
    {
        return view('motorista.show', compact('motorista'));
    }

    public function edit(Motorista $motorista)
    {
        return view('motorista.edit', compact('motorista'));
    }

    public function update(Request $request, Motorista $motorista)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'matricula' => 'required|string|max:50',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'nome' => $request->nome,
            'matricula' => $request->matricula,
        ];

        if ($request->hasFile('foto')) {
            if ($motorista->foto && Storage::disk('public')->exists($motorista->foto)) {
                Storage::disk('public')->delete($motorista->foto);
            }

            $fotoPath = $request->file('foto')->store('motoristas', 'public');
            $data['foto'] = $fotoPath;
        }

        $motorista->update($data);

        return redirect()->route('motoristas.index')->with('success', 'Motorista atualizado com sucesso.');
    }

    public function destroy(Motorista $motorista)
    {
        try {
            // Guarda o caminho da foto antes de deletar do banco
            $fotoPath = $motorista->foto;

            // Tenta excluir o motorista do banco
            $motorista->delete();

            // Se deletou com sucesso, então apaga a imagem
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }

            return redirect()->route('motoristas.index')->with('success', 'Motorista excluído com sucesso.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23503') { // PostgreSQL: violação de chave estrangeira
                return redirect()->route('motoristas.index')->with('error', 'Motorista não pode ser deletado. Existem registros vinculados a ele.');
            }

            return redirect()->route('motoristas.index')->with('error', 'Erro ao tentar excluir o motorista.');
        }
    }
}
