<?php

namespace App\Http\Controllers;

use App\Models\RegistroVeiculo;
use App\Models\Veiculo;
use App\Models\Motorista;
use App\Models\Usuario;
use App\Models\Estacionamento;
use App\Models\AcessoLiberado;
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

        $registros = RegistroVeiculo::with([
            'veiculo',
            'motoristaEntrada',
            'motoristaSaida',
            'usuarioEntrada',
            'usuarioSaida',
            'estacionamento'
        ])
        ->when($estacionamentoId, fn($query) => $query->where('estacionamento_id', $estacionamentoId))
        ->when($filtro === 'sem_saida', fn($query) => $query->whereNull('horario_saida'))
        ->when($placa, function ($query) use ($placa) {
            $query->whereHas('veiculo', function ($q) use ($placa) {
                $q->where('placa', 'like', '%' . $placa . '%');
            });
        })
        ->orderByDesc('horario_entrada')
        ->paginate(10);

        $motoristas = Motorista::all();

        return view('registro_veiculos.index', compact('registros', 'motoristas'));
    }

    public function create()
    {
        $veiculos = Veiculo::all();
        $usuarios = Usuario::where('ativo', true)->get();
        $estacionamentos = Estacionamento::all();

        return view('registro_veiculos.create', compact('veiculos', 'usuarios', 'estacionamentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'veiculo_id' => 'required|exists:veiculos,id',
            'marca' => 'required|string|max:50',
            'modelo' => 'required|string|max:50',
            'cor' => 'required|string|max:20',
            'tipo' => 'required|in:OFICIAL,PARTICULAR,MOTO',
            'motorista_entrada_id' => 'nullable|exists:motoristas_oficiais,id',
            'motorista_saida_id' => 'nullable|exists:motoristas_oficiais,id',
            'horario_saida' => 'nullable|date',
            'usuario_saida_id' => 'nullable|exists:usuarios,id',
            'estacionamento_id' => 'required|exists:estacionamentos,id',
            'quantidade_passageiros' => 'required|integer|min:0|max:10',
        ]);

        $registroAberto = RegistroVeiculo::where('veiculo_id', $request->veiculo_id)
            ->whereNull('horario_saida')
            ->first();

        if ($registroAberto) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['veiculo_id' => 'Este veículo já possui uma entrada aberta. Registre a saída antes de criar uma nova.']);
        }

        $veiculo = Veiculo::findOrFail($request->veiculo_id);

        if ($request->tipo === 'OFICIAL') {
            $motoristaEntradaId = $request->motorista_entrada_id;
        } else {
            // Para PARTICULAR ou MOTO, pega o motorista cadastrado no veículo
            $motoristaEntradaId = $veiculo->motorista_id ?? null;
        }

        if (!$motoristaEntradaId) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['motorista_entrada_id' => 'É necessário selecionar ou ter um motorista cadastrado para este tipo de veículo.']);
        }

        RegistroVeiculo::create([
            'veiculo_id' => $request->veiculo_id,
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
        $veiculos = Veiculo::all();
        $usuarios = Usuario::where('ativo', true)->get();
        $estacionamentos = Estacionamento::all();

        return view('registro_veiculos.edit', compact('registro', 'veiculos', 'usuarios', 'estacionamentos'));
    }

    public function update(Request $request, $id)
    {
        $registro = RegistroVeiculo::findOrFail($id);

        $request->validate([
            'veiculo_id' => 'required|exists:veiculos,id',
            'marca' => 'required|string|max:50',
            'modelo' => 'required|string|max:50',
            'cor' => 'required|string|max:20',
            'tipo' => 'required|in:OFICIAL,PARTICULAR,MOTO',
            'motorista_entrada_id' => 'required|exists:motoristas_oficiais,id',
            'motorista_saida_id' => 'nullable|exists:motoristas_oficiais,id',
            'horario_saida' => 'nullable|date',
            'usuario_saida_id' => 'nullable|exists:usuarios,id',
            'estacionamento_id' => 'required|exists:estacionamentos,id',
            'quantidade_passageiros' => 'required|integer|min:0|max:10',
        ]);

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
        $term = $request->input('term');
        $tipo = $request->input('tipo');

        if ($tipo === 'OFICIAL') {
            // Busca motoristas oficiais ativos
            $query = DB::table('motoristas_oficiais')
                ->where('ativo', true)
                ->where('nome', 'ILIKE', '%' . $term . '%')
                ->limit(10)
                ->get();

            $results = $query->map(function ($m) {
                return [
                    'id' => $m->id,
                    'nome' => $m->nome,
                ];
            });

            return response()->json(['results' => $results]);
        }

        // Para PARTICULAR/MOTO não retorna nada (não há select)
        return response()->json(['results' => []]);
    }
}