<?php

namespace App\Http\Controllers;

use App\Models\Motorista;
use Illuminate\Http\Request;

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

        $fotoPath = $request->file('foto')->store('motoristas', 'public');

        Motorista::create([
            'nome' => $request->nome,
            'matricula' => $request->matricula,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('motoristas.index')->with('success', 'Motorista cadastrado com sucesso.');
    }

    // Método show para visualizar um motorista específico
    public function show(Motorista $motorista)
    {
        // Retorna a view 'motorista.show' passando o motorista
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
            $fotoPath = $request->file('foto')->store('motoristas', 'public');
            $data['foto'] = $fotoPath;
        }

        $motorista->update($data);

        return redirect()->route('motoristas.index')->with('success', 'Motorista atualizado com sucesso.');
    }

    public function destroy(Motorista $motorista)
    {
        $motorista->delete();
        return redirect()->route('motoristas.index')->with('success', 'Motorista excluído com sucesso.');
    }
}
