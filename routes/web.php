<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\MotoristaController;
use App\Http\Controllers\AcessoLiberadoController;
use App\Http\Controllers\RegistroVeiculoController;
use App\Http\Controllers\OcorrenciaController;
use App\Http\Controllers\AcompanhamentoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\PermissaoController;
use App\Http\Controllers\PainelController;
use App\Http\Controllers\EstacionamentoController;
use App\Http\Controllers\MensagemController;
use App\Http\Controllers\RelatorioVeiculoController;
use App\Http\Controllers\RelatorioUsuarioController;
use App\Http\Controllers\RelatorioRegistroVeiculoController;
use App\Http\Controllers\RelatorioOcorrenciaController;
use App\Http\Controllers\RelatorioMotoristaController;

// Página pública
Route::get('/', fn() => view('welcome'));

// Autenticação
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard protegida
Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Perfil do usuário
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // ✅ Corrigido: nome da rota agora é 'senha.atualizar'
    Route::get('/profile/senha', [ProfileController::class, 'editSenha'])->name('senha.edit');
    Route::patch('/profile/senha', [ProfileController::class, 'updatePassword'])->name('senha.atualizar');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Redirecionamento baseado no perfil do usuário
Route::get('/redirect', function () {
    $usuario = Auth::user();
    $perfilNome = optional($usuario->perfil)->nome;

    return match ($perfilNome) {
        'administrador'   => redirect()->route('admin.dashboard'),
        'vigilante'       => redirect()->route('vigilante.dashboard'),
        'recepcionista'   => redirect()->route('recepcionista.dashboard'),
        default           => redirect()->route('home'),
    };
})->middleware('auth')->name('perfil.redirect');

// Dashboards específicos
Route::middleware('auth')->group(function () {
    Route::view('/admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
    Route::view('/vigilante/dashboard', 'vigilante.dashboard')->name('vigilante.dashboard');
    Route::view('/recepcionista/dashboard', 'recepcionista.dashboard')->name('recepcionista.dashboard');
    Route::view('/home', 'home')->name('home');
});

// Rotas protegidas
Route::middleware('auth')->group(function () {

    // APIs e buscas
    Route::get('/veiculos/buscar', [VeiculoController::class, 'buscar'])->name('veiculos.buscar');
    Route::get('/veiculos/buscar-por-placa/{placa}', [VeiculoController::class, 'buscarPorPlaca'])->name('veiculos.buscarPorPlaca');
    Route::get('/veiculos/{id}/buscar', [VeiculoController::class, 'buscarPorId'])->name('veiculos.buscarPorId');
    Route::get('/api/motorista-por-placa/{placa}', [VeiculoController::class, 'motoristaPorPlaca']);
    Route::get('/api/veiculo-por-placa/{placa}', [VeiculoController::class, 'buscarPorPlaca']);

    Route::get('/acessos/buscar', [AcessoLiberadoController::class, 'buscar'])->name('acessos.buscar');
    Route::get('/registro-veiculos/buscar-motoristas-acesso', [RegistroVeiculoController::class, 'buscarMotoristasAcesso'])->name('registro_veiculos.buscar_motoristas_acesso');

    // Seleção de estacionamento
    Route::get('/selecionar-estacionamento', [EstacionamentoController::class, 'selecionar'])->name('selecionar.estacionamento');
    Route::post('/definir-estacionamento', [EstacionamentoController::class, 'definir'])->name('definir.estacionamento');

    // Recursos principais
    Route::resource('usuarios', UsuarioController::class);
    Route::post('usuarios/{usuario}/reset-senha', [UsuarioController::class, 'resetSenha'])->name('usuarios.resetSenha');
    Route::patch('usuarios/{usuario}/alternar-status', [UsuarioController::class, 'toggleStatus'])->name('usuarios.alternar-status');

    Route::resource('motoristas', MotoristaController::class);
    Route::patch('motoristas/{id}/alternar-status', [MotoristaController::class, 'alternarStatus'])->name('motoristas.alternar-status');

    Route::resource('acessos_liberados', AcessoLiberadoController::class);
    Route::patch('acessos_liberados/{id}/status', [AcessoLiberadoController::class, 'alterarStatus'])->name('acessos_liberados.status');

    Route::resource('veiculos', VeiculoController::class);

    Route::resource('registro_veiculos', RegistroVeiculoController::class);
    Route::post('registro_veiculos/{id}/registrar_saida', [RegistroVeiculoController::class, 'registrarSaida'])->name('registro_veiculos.registrar_saida');
    Route::post('/registro-veiculos/limpar-com-saida', [RegistroVeiculoController::class, 'limparComSaida'])->name('registro_veiculos.limpar_com_saida');

    Route::resource('estacionamentos', EstacionamentoController::class);

    // Ocorrências e Acompanhamentos
    Route::resource('ocorrencias', OcorrenciaController::class);
    Route::get('acompanhamentos/{ocorrencia}/create', [AcompanhamentoController::class, 'create'])->name('acompanhamentos.create');
    Route::post('acompanhamentos/{ocorrencia}', [AcompanhamentoController::class, 'store'])->name('acompanhamentos.store');

    // Perfis e permissões
    Route::resource('perfis', PerfilController::class)->parameters(['perfis' => 'perfil']);
    Route::resource('permissoes', PermissaoController::class)->parameters(['permissoes' => 'permissao']);

    // Painel
    Route::get('/painel/dados', [PainelController::class, 'dados'])->name('painel.dados');

    // Mensagens
    Route::resource('mensagens', MensagemController::class);

    // Relatórios
    Route::prefix('relatorios')->group(function () {

        Route::prefix('veiculos')->name('relatorios.veiculos.')->group(function () {
            Route::get('/', [RelatorioVeiculoController::class, 'index'])->name('index');
            Route::get('/exportar', [RelatorioVeiculoController::class, 'exportar'])->name('exportar');
            Route::get('/{relatorio}', [RelatorioVeiculoController::class, 'show'])->name('show');
        });

        Route::prefix('usuarios')->name('relatorios.usuarios.')->group(function () {
            Route::get('/', [RelatorioUsuarioController::class, 'index'])->name('index');
            Route::get('/exportar', [RelatorioUsuarioController::class, 'exportar'])->name('exportar');
        });

        Route::prefix('registro-veiculos')->name('relatorios.registros.')->group(function () {
            Route::get('/', [RelatorioRegistroVeiculoController::class, 'index'])->name('index');
            Route::get('/exportar', [RelatorioRegistroVeiculoController::class, 'exportar'])->name('exportar');
        });

        Route::prefix('ocorrencias')->name('relatorios.ocorrencias.')->group(function () {
            Route::get('/', [RelatorioOcorrenciaController::class, 'index'])->name('index');
            Route::get('/exportar', [RelatorioOcorrenciaController::class, 'exportar'])->name('exportar');
            Route::get('/exportar/{id}', [RelatorioOcorrenciaController::class, 'exportarIndividual'])->name('exportar_individual');
            Route::post('/exportar-selecionadas', [RelatorioOcorrenciaController::class, 'exportarSelecionadas'])->name('exportar_selecionadas');
        });

        Route::prefix('motoristas')->name('relatorios.motoristas.')->group(function () {
            Route::get('/', [RelatorioMotoristaController::class, 'index'])->name('index');
            Route::get('/exportar', [RelatorioMotoristaController::class, 'exportar'])->name('exportar');
            Route::post('/exportar-selecionados', [RelatorioMotoristaController::class, 'exportarSelecionados'])->name('exportar_selecionados');
        });
    });
});
