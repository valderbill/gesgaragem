@extends('layouts.app')

@section('styles')
    {{-- Estilos adicionais desta view (se necessário futuramente) --}}
@endsection

@section('content')
@php
    use Carbon\Carbon;
@endphp

<div class="container">
    {{-- Botões de Ações --}}
    <div class="mb-3 d-flex justify-content-between">
        <a href="{{ route('registro_veiculos.create') }}" class="btn btn-primary">Novo Registro</a>

        <form action="{{ route('registro_veiculos.limpar_com_saida') }}" method="POST" onsubmit="return confirm('Deseja realmente excluir todos os registros que já possuem saída registrada?')">
            @csrf
            <button type="submit" class="btn btn-danger">Limpar Registros com Saída</button>
        </form>
    </div>

    {{-- Painel de Contadores Digitais --}}
    <div class="mb-4">
        <div class="row text-center justify-content-center">
            @foreach (['OFICIAL', 'PARTICULAR', 'MOTO'] as $tipo)
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="border rounded p-3 shadow-sm">
                        <div class="digital-counter" id="total-{{ $tipo }}">0</div>
                        <div class="mt-2">Total {{ ucfirst(strtolower($tipo)) }}</div>
                        <div class="digital-counter mt-3" id="disponiveis-{{ $tipo }}">0</div>
                        <div>Disponíveis</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Mensagem de Sucesso --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tabela de Registros --}}
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Placa</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Cor</th>
                <th>Tipo</th>
                <th>Motorista Entrada</th>
                <th>Motorista Saída</th>
                <th>Horário Entrada</th>
                <th>Horário Saída</th>
                <th>Usuário Entrada</th>
                <th>Usuário Saída</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($registros as $registro)
            <tr>
                <td>{{ $registro->placa }}</td>
                <td>{{ $registro->marca }}</td>
                <td>{{ $registro->modelo }}</td>
                <td>{{ $registro->cor }}</td>
                <td>{{ $registro->tipo }}</td>
                <td>{{ $registro->motoristaEntrada->nome ?? 'N/A' }}</td>

                <td>
                    @if (!$registro->horario_saida)
                        <form action="{{ route('registro_veiculos.registrar_saida', $registro->id) }}" method="POST">
                            @csrf
                            <select name="motorista_saida_id" class="form-control form-control-sm" required>
                                <option value="" disabled selected>Selecione motorista</option>
                                @foreach($motoristas as $motorista)
                                    <option value="{{ $motorista->id }}">{{ $motorista->nome }}</option>
                                @endforeach
                            </select>
                    @else
                        {{ $registro->motoristaSaida->nome ?? 'N/A' }}
                    @endif
                </td>

                <td>
                    {{ $registro->horario_entrada 
                        ? Carbon::parse($registro->horario_entrada)->format('d/m/Y H:i:s') 
                        : 'N/A' }}
                </td>
                <td>
                    {{ $registro->horario_saida 
                        ? Carbon::parse($registro->horario_saida)->format('d/m/Y H:i:s') 
                        : 'N/A' }}
                </td>
                <td>{{ $registro->usuarioLogado->nome ?? 'N/A' }}</td>
                <td>{{ $registro->usuarioSaida->nome ?? 'N/A' }}</td>

                <td>
                    @if (!$registro->horario_saida)
                            <button type="submit" class="btn btn-success btn-sm mt-1"
                                    onclick="return confirm('Confirmar registro de saída deste veículo?')">
                                Registrar Saída
                            </button>
                        </form>
                    @else
                        <button class="btn btn-secondary btn-sm" disabled>Saída Registrada</button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $registros->links() }}

    {{-- Canvas para gráfico --}}
    <canvas id="graficoVagas" style="max-width:600px; margin: 20px auto; display: block;"></canvas>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let ctx = document.getElementById('graficoVagas')?.getContext('2d');
    let chart;

    function carregarGrafico(data) {
        const tipos = ['OFICIAL', 'PARTICULAR', 'MOTO'];
        const total = tipos.map(t => data.total[t] || 0);
        const ocupadas = tipos.map(t => data.ocupadas[t] || 0);
        const disponiveis = tipos.map((t, i) => total[i] - ocupadas[i]);

        const config = {
            type: 'bar',
            data: {
                labels: tipos,
                datasets: [
                    {
                        label: 'Total de Vagas',
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        data: total
                    },
                    {
                        label: 'Ocupadas',
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        data: ocupadas
                    },
                    {
                        label: 'Disponíveis',
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        data: disponiveis
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        };

        if (chart) {
            chart.data = config.data;
            chart.update();
        } else if (ctx) {
            chart = new Chart(ctx, config);
        }
    }

    function atualizarContadores(data) {
        ['OFICIAL', 'PARTICULAR', 'MOTO'].forEach(tipo => {
            const total = data.total[tipo] ?? 0;
            const ocupadas = data.ocupadas[tipo] ?? 0;
            const disponiveis = total - ocupadas;

            document.getElementById(`total-${tipo}`).textContent = total;
            document.getElementById(`disponiveis-${tipo}`).textContent = disponiveis;
        });
    }

    function atualizarDados() {
        fetch("{{ url('/painel/dados') }}", {
            method: 'GET',
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            carregarGrafico(data);
            atualizarContadores(data);
        })
        .catch(err => console.error('Erro ao carregar dados:', err));
    }

    document.addEventListener('DOMContentLoaded', () => {
        atualizarDados();
        setInterval(atualizarDados, 10000);
    });
</script>
@endsection
