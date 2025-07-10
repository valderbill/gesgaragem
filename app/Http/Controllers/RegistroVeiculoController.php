<?php

namespace App\Http\Controllers;

use App\Models\{
    RegistroVeiculo,
    Veiculo,
    Motorista,
    AcessoLiberado,
    Usuario,
    Estacionamento
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegistroVeiculoController extends Controller
{
    public function index(Request $request)
    {
        $estacionamentoId = auth()->user()->estacionamento_id ?? session('estacionamento_id');
        $filtro = $request->input('filtro');
        $placa = $request->input('placa');
        $motorista = $request->input('motorista');

        $registros = RegistroVeiculo::with([
            'veiculo',
            'veiculo.acesso',
            'motoristaEntrada',
            'motoristaSaida',
            'usuarioEntrada',
            'usuarioSaida',
            'estacionamento'
        ])
        ->when($estacionamentoId, fn($query) => $query->where('estacionamento_id', $estacionamentoId))
        ->when($filtro === 'sem_saida', fn($query) => $query->whereNull('horario_saida'))
        ->when($placa, fn($query) => $query->whereHas('veiculo', fn($q) =>
            $q->where('placa', 'ilike', '%' . $placa . '%')))
        ->when($motorista, function ($query) use ($motorista) {
            $query->where(function ($q) use ($motorista) {
                $q->orWhereHas('motoristaEntrada', fn($q2) =>
                    $q2->where('nome', 'ilike', '%' . $motorista . '%'));
                $q->orWhereHas('veiculo.acesso', fn($q2) =>
                    $q2->where('nome', 'ilike', '%' . $motorista . '%'));
            });
        })
        ->orderByDesc('horario_entrada')
        ->paginate(10);

        $motoristas = Motorista::all();

        return view('registro_veiculos.index', compact('registros', 'motoristas'));
    }

    public function create()
    {
        $veiculos = Veiculo::with(['motoristaOficial', 'acesso'])->get();
        $estacionamentos = Estacionamento::all();

        return view('registro_veiculos.create', compact('veiculos', 'estacionamentos'));
    }

    public function store(Request $request)
    {
        $rules = [
            'veiculo_id' => 'required|exists:veiculos,id',
            'marca' => 'required|string|max:50',
            'modelo' => 'required|string|max:50',
            'cor' => 'required|string|max:20',
            'tipo' => 'required|in:OFICIAL,PARTICULAR,MOTO',
            'motorista_saida_id' => 'nullable|exists:motoristas_oficiais,id',
            'horario_saida' => 'nullable|date',
            'usuario_saida_id' => 'nullable|exists:usuarios,id',
            'estacionamento_id' => 'required|exists:estacionamentos,id',
            'quantidade_passageiros' => 'required|integer|min:0|max:10',
        ];

        if ($request->tipo === 'OFICIAL') {
            $rules['motorista_entrada_id'] = 'required|exists:motoristas_oficiais,id';
        }

        $request->validate($rules);

        $registroAberto = RegistroVeiculo::where('veiculo_id', $request->veiculo_id)
            ->whereNull('horario_saida')
            ->first();

        if ($registroAberto) {
            return redirect()->back()->withInput()->withErrors([
                'veiculo_id' => 'Este veículo já possui uma entrada em aberto.'
            ]);
        }

        $veiculo = Veiculo::with(['motoristaOficial', 'acesso'])->findOrFail($request->veiculo_id);

        if ($request->tipo === 'OFICIAL') {
            $motoristaEntradaId = $request->motorista_entrada_id;
        } else {
            $motoristaEntradaId = $veiculo->acesso->usuario_id ?? null;
            if (!$motoristaEntradaId) {
                return redirect()->back()->withInput()->withErrors([
                    'motorista_entrada_id' => 'Veículo PARTICULAR/MOTO precisa estar vinculado a um usuário através da tabela de acessos liberados.'
                ]);
            }
        }

        RegistroVeiculo::create([
            'veiculo_id' => $veiculo->id,
            'placa' => $veiculo->placa,
            'marca' => $request->marca,
            'modelo' => $request->modelo,
            'cor' => $request->cor,
            'tipo' => $request->tipo,
            'motorista_entrada_id' => $motoristaEntradaId,
            'motorista_saida_id' => $request->motorista_saida_id,
            'horario_saida' => $request->horario_saida,
            'usuario_saida_id' => $request->usuario_saida_id,
            'estacionamento_id' => $request->estacionamento_id,
            'quantidade_passageiros' => $request->quantidade_passageiros,
            'horario_entrada' => now(),
            'usuario_entrada_id' => Auth::id(),
        ]);

        return redirect()->route('registro_veiculos.index')->with('success', 'Registro criado com sucesso.');
    }

    public function show($id)
    {
        $registro = RegistroVeiculo::with([
            'veiculo',
            'veiculo.acesso',
            'motoristaEntrada',
            'motoristaSaida',
            'usuarioEntrada',
            'usuarioSaida',
            'estacionamento'
        ])->findOrFail($id);

        return view('registro_veiculos.show', compact('registro'));
    }

    public function edit($id)
    {
        $registro = RegistroVeiculo::findOrFail($id);
        $veiculos = Veiculo::with(['motoristaOficial', 'acesso'])->get();
        $estacionamentos = Estacionamento::all();

        return view('registro_veiculos.edit', compact('registro', 'veiculos', 'estacionamentos'));
    }

    public function update(Request $request, $id)
    {
        $registro = RegistroVeiculo::findOrFail($id);

        $rules = [
            'veiculo_id' => 'required|exists:veiculos,id',
            'marca' => 'required|string|max:50',
            'modelo' => 'required|string|max:50',
            'cor' => 'required|string|max:20',
            'tipo' => 'required|in:OFICIAL,PARTICULAR,MOTO',
            'motorista_saida_id' => 'nullable|exists:motoristas_oficiais,id',
            'horario_saida' => 'nullable|date',
            'usuario_saida_id' => 'nullable|exists:usuarios,id',
            'estacionamento_id' => 'required|exists:estacionamentos,id',
            'quantidade_passageiros' => 'required|integer|min:0|max:10',
        ];

        if ($request->tipo === 'OFICIAL') {
            $rules['motorista_entrada_id'] = 'required|exists:motoristas_oficiais,id';
        }

        $request->validate($rules);

        $veiculo = Veiculo::with(['motoristaOficial', 'acesso'])->findOrFail($request->veiculo_id);

        if ($request->tipo === 'OFICIAL') {
            $motoristaEntradaId = $request->motorista_entrada_id;
        } else {
            $motoristaEntradaId = $veiculo->acesso->usuario_id ?? null;
            if (!$motoristaEntradaId) {
                return redirect()->back()->withInput()->withErrors([
                    'motorista_entrada_id' => 'Veículo PARTICULAR/MOTO precisa estar vinculado a um usuário através da tabela de acessos liberados.'
                ]);
            }
            $request->merge(['motorista_entrada_id' => $motoristaEntradaId]);
        }

        $registro->update($request->only([
            'veiculo_id',
            'marca',
            'modelo',
            'cor',
            'tipo',
            'motorista_entrada_id',
            'motorista_saida_id',
            'horario_saida',
            'usuario_saida_id',
            'estacionamento_id',
            'quantidade_passageiros',
        ]));

        return redirect()->route('registro_veiculos.index')->with('success', 'Registro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $registro = RegistroVeiculo::findOrFail($id);
        $registro->delete();

        return redirect()->route('registro_veiculos.index')->with('success', 'Registro removido com sucesso.');
    }

    public function registrarSaida(Request $request, $id)
    {
        $registro = RegistroVeiculo::findOrFail($id);

        if ($registro->horario_saida !== null) {
            return redirect()->route('registro_veiculos.index')
                ->with('error', 'Saída já registrada para este veículo.');
        }

        $request->validate([
            'motorista_saida_id' => 'required|exists:motoristas_oficiais,id',
        ]);

        $registro->update([
            'horario_saida' => now(),
            'usuario_saida_id' => Auth::id(),
            'motorista_saida_id' => $request->motorista_saida_id,
        ]);

        return redirect()->route('registro_veiculos.index')->with('success', 'Saída registrada com sucesso!');
    }

    public function limparComSaida()
    {
        return redirect()->route('registro_veiculos.index', ['filtro' => 'sem_saida']);
    }

    public function buscarMotoristasAcesso(Request $request)
    {
        $veiculoId = $request->input('veiculo_id');
        $tipo = $request->input('tipo');
        $results = [];

        if ($tipo === 'OFICIAL') {
            $motoristas = DB::table('motoristas_oficiais')
                ->where('ativo', true)
                ->limit(10)
                ->get();

            $results = $motoristas->map(fn($m) => [
                'id' => $m->id,
                'nome' => $m->nome,
            ]);
        } else {
            $veiculo = Veiculo::with('acesso')->find($veiculoId);
            if ($veiculo && $veiculo->acesso) {
                $results[] = [
                    'id' => $veiculo->acesso->usuario_id,
                    'nome' => $veiculo->acesso->nome,
                ];
            }
        }

        return response()->json(['results' => $results]);
    }
}
