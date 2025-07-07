<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use App\Models\AcessoLiberado;
use App\Models\RegistroVeiculo;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VeiculoController extends Controller
{
    public function index()
    {
        $veiculos = Veiculo::with('acessoLiberado')->get();
        return view('veiculos.index', compact('veiculos'));
    }

    public function create()
    {
        $acessos = AcessoLiberado::all();
        $usuarios = Usuario::where('ativo', true)->get();
        return view('veiculos.create', compact('acessos', 'usuarios'));
    }

    // Busca para Select2 - CORRIGIDA
    public function buscar(Request $request)
    {
        $termo = strtoupper($request->input('term'));

        $veiculos = Veiculo::where('placa', 'LIKE', "%{$termo}%")
            ->limit(10)
            ->get();

        $resultados = $veiculos->map(function ($veiculo) {
            return [
                'id' => $veiculo->id,
                'text' => $veiculo->placa,
                'placa' => $veiculo->placa,
                'marca' => $veiculo->marca,
                'modelo' => $veiculo->modelo,
                'cor' => $veiculo->cor,
                'tipo' => $veiculo->tipo,
                'motorista_entrada_id' => $veiculo->motorista_entrada_id,
                'motorista_nome' => optional($veiculo->motoristaEntrada)->nome,
            ];
        });

        // O Select2 espera um array com a chave 'results'
        return response()->json(['results' => $resultados]);
    }

    public function store(Request $request)
    {
        $request->merge([
            'placa' => strtoupper($request->placa),
            'modelo' => strtoupper($request->modelo),
            'cor' => strtoupper($request->cor),
            'tipo' => strtoupper($request->tipo),
            'marca' => strtoupper($request->marca),
        ]);

        $request->validate([
            'placa' => [
                'required',
                'string',
                'max:7',
                'regex:/^[A-Z]{3}[0-9]{4}$|^[A-Z]{3}[0-9][A-Z0-9][0-9]{2}$/',
            ],
            'modelo' => 'required|string|max:100',
            'cor' => 'required|string|max:50',
            'tipo' => 'required|in:OFICIAL,PARTICULAR,MOTO',
            'marca' => 'required|string|max:50',
            'acesso_id' => 'nullable|exists:acessos_liberados,id',
        ], [
            'placa.regex' => 'Formato inválido para placa. Use ABC1234 (antigo) ou ABC1D23 (Mercosul).',
        ]);

        if (Veiculo::where('placa', $request->placa)->exists()) {
            return back()
                ->withErrors(['placa' => 'Veículo já cadastrado com essa placa.'])
                ->withInput();
        }

        $data = $request->only([
            'placa',
            'modelo',
            'cor',
            'tipo',
            'marca',
            'acesso_id',
        ]);

        Veiculo::create($data);

        return redirect()->route('veiculos.index')->with('success', 'Veículo cadastrado com sucesso.');
    }

    public function show(Veiculo $veiculo)
    {
        return view('veiculos.show', compact('veiculo'));
    }

    public function edit(Veiculo $veiculo)
    {
        $acessos = AcessoLiberado::all();
        $usuarios = Usuario::where('ativo', true)->get();

        return view('veiculos.edit', compact('veiculo', 'acessos', 'usuarios'));
    }

    public function update(Request $request, Veiculo $veiculo)
    {
        $request->merge([
            'placa' => strtoupper($request->placa),
            'modelo' => strtoupper($request->modelo),
            'cor' => strtoupper($request->cor),
            'tipo' => strtoupper($request->tipo),
            'marca' => strtoupper($request->marca),
        ]);

        $request->validate([
            'placa' => [
                'required',
                'string',
                'max:7',
                'regex:/^[A-Z]{3}[0-9]{4}$|^[A-Z]{3}[0-9][A-Z0-9][0-9]{2}$/',
            ],
            'modelo' => 'required|string|max:100',
            'cor' => 'required|string|max:50',
            'tipo' => 'required|in:OFICIAL,PARTICULAR,MOTO',
            'marca' => 'required|string|max:50',
            'acesso_id' => 'nullable|exists:acessos_liberados,id',
        ], [
            'placa.regex' => 'Formato inválido para placa. Use ABC1234 (antigo) ou ABC1D23 (Mercosul).',
        ]);

        $data = $request->only([
            'placa',
            'modelo',
            'cor',
            'tipo',
            'marca',
            'acesso_id',
        ]);

        $veiculo->update($data);

        return redirect()->route('veiculos.index')->with('success', 'Veículo atualizado com sucesso.');
    }

    public function destroy(Veiculo $veiculo)
    {
        $temRegistros = RegistroVeiculo::where('placa', $veiculo->placa)->exists();

        if ($temRegistros) {
            return redirect()->route('veiculos.index')
                ->with('error', 'O veículo não pode ser excluído porque está vinculado a registros de entrada/saída.');
        }

        $veiculo->delete();

        return redirect()->route('veiculos.index')
            ->with('success', 'Veículo excluído com sucesso.');
    }

    public function buscarPorPlaca($placa)
    {
        $placa = strtoupper($placa);

        $veiculo = Veiculo::where('placa', $placa)->first();

        if ($veiculo) {
            return response()->json([
                'success' => true,
                'data' => [
                    'modelo' => $veiculo->modelo,
                    'cor' => $veiculo->cor,
                    'tipo' => $veiculo->tipo,
                    'marca' => $veiculo->marca,
                    'acesso_id' => $veiculo->acesso_id,
                ],
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Veículo não encontrado.']);
    }

    public function motoristaPorPlaca($placa)
    {
        $placa = strtoupper($placa);

        $registro = RegistroVeiculo::where('placa', $placa)
            ->whereNotNull('motorista_entrada_id')
            ->latest('id')
            ->with('motoristaEntrada')
            ->first();

        if ($registro && $registro->motoristaEntrada) {
            return response()->json([
                'id' => $registro->motoristaEntrada->id,
                'nome' => $registro->motoristaEntrada->nome,
                'matricula' => $registro->motoristaEntrada->matricula,
            ]);
        }

        return response()->json(null, 404);
    }

    public function buscarPorId($id)
    {
        $veiculo = Veiculo::with('acessoLiberado.motorista')->findOrFail($id);

        return response()->json([
            'placa' => $veiculo->placa,
            'marca' => $veiculo->marca,
            'modelo' => $veiculo->modelo,
            'cor' => $veiculo->cor,
            'tipo' => $veiculo->tipo,
            'motorista_id' => optional($veiculo->acessoLiberado)->motorista_id
        ]);
    }
}