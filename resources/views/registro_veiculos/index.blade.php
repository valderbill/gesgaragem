@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/digital.css') }}">
    {{-- Bootstrap para estilo da paginação --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('content')
@php
    use Carbon\Carbon;
@endphp

<div class="container">
    {{-- Botões e Filtro --}}
    <div class="mb-3 d-flex justify-content-between align-items-center gap-2 flex-wrap">
        <a href="{{ route('registro_veiculos.create') }}" class="btn btn-primary">Novo Registro</a>
        <a href="{{ route('registro_veiculos.index', ['filtro' => 'sem_saida']) }}" title="Ocultar registros com saída" class="btn btn-outline-secondary">
            <i class="bi bi-x-circle"></i>
        </a>
    </div>

    {{-- Filtro por placa --}}
    <form method="GET" action="{{ route('registro_veiculos.index') }}" class="mb-4 d-flex">
        <input
            type="text"
            name="placa"
            class="form-control me-2"
            placeholder="Buscar por placa"
            value="{{ request('placa') }}"
        >
        <button type="submit" class="btn btn-outline-primary">Buscar</button>
    </form>

    {{-- Nome do estacionamento selecionado (preenchido via JS) --}}
    <h5 id="nome-estacionamento" class="text-center mb-3 text-muted"></h5>

    {{-- Painel de Contadores Digitais --}}
    <div class="mb-4">
        <div class="row text-center justify-content-center">
            @foreach (['OFICIAL', 'PARTICULAR', 'MOTO'] as $tipo)
                <div class="col-md-3 col-sm-6 mb-3 d-flex justify-content-center">
                    <div class="card-quantidade">
                        <div>
                            <div class="digital-counter" id="total-{{ $tipo }}">0</div>
                            <div>Total {{ ucfirst(strtolower($tipo)) }}</div>
                        </div>
                        <div>
                            <div class="digital-counter" id="disponiveis-{{ $tipo }}">0</div>
                            <div>Disponíveis</div>
                        </div>
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
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>Placa</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Cor</th>
                    <th>Tipo</th>
                    <th>Motorista Entrada</th>
                    <th>Motorista Saída</th>
                    <th>Passageiros</th>
                    <th>Horário Entrada</th>
                    <th>Horário Saída</th>
                    <th>Usuário Entrada</th>
                    <th>Usuário Saída</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($registros as $registro)
                <tr>
                    <td>{{ $registro->placa }}</td>
                    <td>{{ $registro->marca }}</td>
                    <td>{{ $registro->modelo }}</td>
                    <td>{{ $registro->cor }}</td>
                    <td>{{ $registro->tipo }}</td>
                    <td>{{ $registro->nome_motorista_entrada ?? 'N/A' }}</td>
                    <td>
                        @if (!$registro->horario_saida)
                            @if ($registro->tipo === 'OFICIAL')
                                <form id="form-saida-{{ $registro->id }}" action="{{ route('registro_veiculos.registrar_saida', $registro->id) }}" method="POST">
                                    @csrf
                                    <select name="motorista_saida_id" class="form-control form-control-sm" required>
                                        <option value="" disabled selected>Selecione motorista</option>
                                        @isset($motoristas)
                                            @foreach($motoristas as $motorista)
                                                <option value="{{ $motorista->id }}">{{ $motorista->nome }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </form>
                            @else
                                {{ $registro->nome_motorista_entrada ?? 'N/A' }}
                            @endif
                        @else
                            {{ optional($registro->motoristaSaida)->nome ?? $registro->nome_motorista_entrada ?? 'N/A' }}
                        @endif
                    </td>
                    <td>{{ $registro->quantidade_passageiros ?? 0 }}</td>
                    <td>{{ $registro->horario_entrada ? Carbon::parse($registro->horario_entrada)->format('d/m/Y H:i:s') : 'N/A' }}</td>
                    <td>{{ $registro->horario_saida ? Carbon::parse($registro->horario_saida)->format('d/m/Y H:i:s') : 'N/A' }}</td>
                    <td>{{ optional($registro->usuarioEntrada)->nome ?? 'N/A' }}</td>
                    <td>{{ optional($registro->usuarioSaida)->nome ?? 'N/A' }}</td>
                    <td>
                        @if (!$registro->horario_saida)
                            @if ($registro->tipo === 'OFICIAL')
                                <button
                                    type="submit"
                                    form="form-saida-{{ $registro->id }}"
                                    class="btn btn-success btn-sm mt-1"
                                    onclick="return confirm('Confirmar registro de saída deste veículo?')"
                                >
                                    Registrar Saída
                                </button>
                            @else
                                <form action="{{ route('registro_veiculos.registrar_saida', $registro->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="motorista_saida_id" value="{{ $registro->motorista_entrada_id }}">
                                    <button
                                        type="submit"
                                        class="btn btn-success btn-sm mt-1"
                                        onclick="return confirm('Confirmar registro de saída deste veículo?')"
                                    >
                                        Registrar Saída
                                    </button>
                                </form>
                            @endif
                        @else
                            <button class="btn btn-secondary btn-sm mt-1" disabled>Saída Registrada</button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center text-muted py-4">Nenhum registro encontrado.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginação com Bootstrap --}}
    <div class="d-flex justify-content-center">
        {{ $registros->withQueryString()->links('pagination::bootstrap-4') }}
    </div>

    {{-- Canvas para gráfico --}}
    <canvas id="graficoVagas" style="max-width:600px; margin: 20px auto; display:block;"></canvas>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let ctx = document.getElementById('graficoVagas')?.getContext('2d');
    let chart;

    function carregarGrafico(data) {
        const tipos = ['OFICIAL', 'PARTICULAR', 'MOTO'];
        const total = tipos.map(t => (data.total && data.total[t]) ? data.total[t] : 0);
        const ocupadas = tipos.map(t => (data.ocupadas && data.ocupadas[t]) ? data.ocupadas[t] : 0);
        const disponiveis = tipos.map((t, i) => Math.max(total[i] - ocupadas[i], 0));

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
            const total = (data.total && data.total[tipo] != null) ? data.total[tipo] : 0;
            const ocupadas = (data.ocupadas && data.ocupadas[tipo] != null) ? data.ocupadas[tipo] : 0;
            const disponiveis = Math.max(total - ocupadas, 0);

            const elTotal = document.getElementById(`total-${tipo}`);
            const elDisp = document.getElementById(`disponiveis-${tipo}`);

            if (elTotal) elTotal.textContent = total;
            if (elDisp) elDisp.textContent = disponiveis;
        });
    }

    function atualizarDados() {
        fetch("{{ route('painel.dados') }}", {
            method: 'GET',
            credentials: 'same-origin'
        })
        .then(async response => {
            if (!response.ok) {
                const raw = await response.text();
                console.error('Erro painel/dados', response.status, raw);
                return null;
            }
            return response.json();
        })
        .then(data => {
            if (!data) return;

            // Preenche nome do estacionamento (se existir no JSON)
            if (data.estacionamento_nome) {
                const el = document.getElementById('nome-estacionamento');
                if (el) el.textContent = `Estacionamento: ${data.estacionamento_nome}`;
            }

            carregarGrafico(data);
            atualizarContadores(data);
        })
        .catch(err => console.error('Erro ao carregar dados painel:', err));
    }

    document.addEventListener('DOMContentLoaded', () => {
        atualizarDados();
        setInterval(atualizarDados, 10000); // atualiza a cada 10s
    });
</script>
@endsection
