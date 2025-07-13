<?php

namespace App\Http\Controllers;

use App\Models\Motorista;
use App\Models\Veiculo;
use App\Models\AcessoLiberado;
use App\Models\RegistroVeiculo;
use Illuminate\Http\Request;

class VeiculoController extends Controller
{
    public function index()
    {
        $veiculos = Veiculo::with(['acesso', 'motoristaOficial'])->get();
        return view('veiculos.index', compact('veiculos'));
    }

    public function create()
    {
        $acessos = AcessoLiberado::where('status', 1)->get();
        $motoristasOficiais = Motorista::all();
        return view('veiculos.create', compact('acessos', 'motoristasOficiais'));
    }

    public function buscar(Request $request)
    {
        $termo = $request->input('term');

        $veiculos = Veiculo::with(['motoristaOficial', 'acesso'])
            ->where(function ($query) use ($termo) {
                $query->where('placa', 'ILIKE', "%{$termo}%")
                    ->orWhereHas('motoristaOficial', function ($q) use ($termo) {
                        $q->where('nome', 'ILIKE', "%{$termo}%");
                    })
                    ->orWhereHas('acesso', function ($q) use ($termo) {
                        $q->where('nome', 'ILIKE', "%{$termo}%");
                    });
            })
            ->limit(10)
            ->get();

        $resultados = $veiculos->map(function ($veiculo) {
            $motorista_id = null;
            $motorista_nome = null;

            if ($veiculo->tipo === 'OFICIAL') {
                $motorista_id = $veiculo->motorista_id;
                $motorista_nome = optional($veiculo->motoristaOficial)->nome;
            } else {
                $motorista_id = optional($veiculo->acesso)->usuario_id;
                $motorista_nome = optional($veiculo->acesso)->nome;
            }

            return [
                'id' => $veiculo->id,
                'text' => $veiculo->placa,
                'placa' => $veiculo->placa,
                'marca' => $veiculo->marca,
                'modelo' => $veiculo->modelo,
                'cor' => $veiculo->cor,
                'tipo' => $veiculo->tipo,
                'motorista_id' => $motorista_id,
                'motorista_nome' => $motorista_nome,
            ];
        });

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
            'motorista_id' => 'nullable|exists:motoristas,id',
        ], [
            'placa.regex' => 'Formato inválido para placa. Use ABC1234 (antigo) ou ABC1D23 (Mercosul).',
        ]);

        if (Veiculo::where('placa', $request->placa)->exists()) {
            return back()->withErrors(['placa' => 'Veículo já cadastrado com essa placa.'])->withInput();
        }

        Veiculo::create($request->only([
            'placa',
            'modelo',
            'cor',
            'tipo',
            'marca',
            'acesso_id',
            'motorista_id',
        ]));

        return redirect()->route('veiculos.index')->with('success', 'Veículo cadastrado com sucesso.');
    }

    public function show(Veiculo $veiculo)
    {
        return view('veiculos.show', compact('veiculo'));
    }

    public function edit(Veiculo $veiculo)
    {
        $acessos = AcessoLiberado::where('status', 1)->get();
        $motoristasOficiais = Motorista::all();
        return view('veiculos.edit', compact('veiculo', 'acessos', 'motoristasOficiais'));
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
            'motorista_id' => 'nullable|exists:motoristas,id',
        ], [
            'placa.regex' => 'Formato inválido para placa. Use ABC1234 (antigo) ou ABC1D23 (Mercosul).',
        ]);

        $veiculo->update($request->only([
            'placa',
            'modelo',
            'cor',
            'tipo',
            'marca',
            'acesso_id',
            'motorista_id',
        ]));

        return redirect()->route('veiculos.index')->with('success', 'Veículo atualizado com sucesso.');
    }

    public function destroy(Veiculo $veiculo)
    {
        if (RegistroVeiculo::where('placa', $veiculo->placa)->exists()) {
            return redirect()->route('veiculos.index')->with('error', 'O veículo não pode ser excluído porque está vinculado a registros de entrada/saída.');
        }

        $veiculo->delete();

        return redirect()->route('veiculos.index')->with('success', 'Veículo excluído com sucesso.');
    }

    public function buscarPorPlaca($placa)
    {
        $placa = strtoupper($placa);

        $veiculo = Veiculo::with(['motoristaOficial'])->where('placa', $placa)->first();

        if ($veiculo) {
            return response()->json([
                'modelo' => $veiculo->modelo,
                'cor' => $veiculo->cor,
                'tipo' => $veiculo->tipo,
                'marca' => $veiculo->marca,
                'acesso_id' => $veiculo->acesso_id,
                'motorista_nome' => optional($veiculo->motoristaOficial)->nome,
                'matricula' => optional($veiculo->motoristaOficial)->matricula,
            ]);
        }

        return response()->json(null, 404);
    }

    public function motoristaPorPlaca($placa)
    {
        $placa = strtoupper($placa);

        $registro = RegistroVeiculo::where('placa', $placa)
            ->whereNotNull('motorista_entrada_id')
            ->latest('id')
            ->with('motoristaOficialEntrada')
            ->first();

        if ($registro && $registro->motoristaOficialEntrada) {
            return response()->json([
                'id' => $registro->motoristaOficialEntrada->id,
                'nome' => $registro->motoristaOficialEntrada->nome,
                'matricula' => $registro->motoristaOficialEntrada->matricula,
            ]);
        }

        return response()->json(null, 404);
    }

    public function buscarPorId($id)
    {
        $veiculo = Veiculo::with(['motoristaOficial'])->findOrFail($id);

        return response()->json([
            'placa' => $veiculo->placa,
            'marca' => $veiculo->marca,
            'modelo' => $veiculo->modelo,
            'cor' => $veiculo->cor,
            'tipo' => $veiculo->tipo,
            'motorista_id' => $veiculo->motorista_id,
            'motorista_nome' => optional($veiculo->motoristaOficial)->nome,
        ]);
    }
}
