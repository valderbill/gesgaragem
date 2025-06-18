@extends('layouts.app')

@section('content')
<div class="container">
    
    <a href="{{ route('registro_veiculos.create') }}" class="btn btn-primary mb-3">Novo Registro</a>

    {{-- Painel de Contadores Digitais --}}
    <div class="mb-4">
        <div style="display:flex; justify-content: space-around; text-align:center; font-family: monospace;">
            @foreach (['OFICIAL', 'PARTICULAR', 'MOTO'] as $tipo)
            <div style="border:1px solid #ddd; padding:20px; border-radius:8px; width:200px;">
                <div style="font-size: 2.5rem; font-weight: bold;" id="total-{{ $tipo }}">0</div>
                <div>Total {{ ucfirst(strtolower($tipo)) }}</div>
                <div style="font-size: 1.8rem; color: green;" id="disponiveis-{{ $tipo }}">0</div>
                <div>Disponíveis</div>
            </div>
            @endforeach
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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
                    </td>
                    <td>{{ $registro->horario_entrada }}</td>
                    <td>{{ $registro->horario_saida ?? 'N/A' }}</td>
                    <td>{{ $registro->usuarioLogado->nome ?? 'N/A' }}</td>
                    <td>{{ $registro->usuarioSaida->nome ?? 'N/A' }}</td>
                    <td>
                                <button type="submit" class="btn btn-success btn-sm mt-1"
                                    onclick="return confirm('Confirmar registro de saída deste veículo?')">
                                    Registrar Saída
                                </button>
                            </form>
                        @else
                            {{ $registro->motoristaSaida->nome ?? 'N/A' }}
                    </td>
                    <td>{{ $registro->horario_entrada }}</td>
                    <td>{{ $registro->horario_saida }}</td>
                    <td>{{ $registro->usuarioLogado->nome ?? 'N/A' }}</td>
                    <td>{{ $registro->usuarioSaida->nome ?? 'N/A' }}</td>
                    <td>
                        <button class="btn btn-secondary btn-sm" disabled>Saída Registrada</button>
                    </td>
                        @endif
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
