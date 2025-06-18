<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    public function index()
    {
        $perfis = Perfil::all();
        return view('perfis.index', compact('perfis'));
    }

    public function create()
    {
        return view('perfis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|unique:perfis,nome|max:50',
        ]);

        Perfil::create($request->only('nome'));

        return redirect()->route('perfis.index')->with('success', 'Perfil criado com sucesso!');
    }

    public function show(Perfil $perfil)
    {
        return view('perfis.show', compact('perfil'));
    }

    public function edit(Perfil $perfil)
    {
        return view('perfis.edit', compact('perfil'));
    }

    public function update(Request $request, Perfil $perfil)
    {
        $request->validate([
            'nome' => 'required|string|unique:perfis,nome,' . $perfil->id . '|max:50',
        ]);

        $perfil->update($request->only('nome'));

        return redirect()->route('perfis.index')->with('success', 'Perfil atualizado com sucesso!');
    }

    public function destroy(Perfil $perfil)
    {
        $perfil->delete();
        return redirect()->route('perfis.index')->with('success', 'Perfil deletado com sucesso!');
    }
}
