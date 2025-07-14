<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

// Página pública
Route::get('/', function () {
    return view('welcome');
});

// Dashboard protegida
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Perfil do usuário autenticado
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// Redirecionamento por perfil
Route::get('/redirect', function () {
    $usuario = Auth::user();
    $perfilNome = optional($usuario->perfil)->nome;

    return match ($perfilNome) {
        'administrador'   => redirect()->route('admin.dashboard'),
        'vigilante'       => redirect()->route('vigilante.dashboard'),
        'recepcionista'   => redirect()->route('recepcionista.dashboard'),
        default           => redirect()->route('home'),
    };
})->middleware(['auth'])->name('perfil.redirect');

// Dashboards por perfil
Route::middleware(['auth'])->group(function () {
    Route::view('/admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
    Route::view('/vigilante/dashboard', 'vigilante.dashboard')->name('vigilante.dashboard');
    Route::view('/recepcionista/dashboard', 'recepcionista.dashboard')->name('recepcionista.dashboard');
    Route::view('/home', 'home')->name('home');
});

// ---------------------
// Buscas e autocomplete
// ---------------------
Route::get('/veiculos/buscar', [VeiculoController::class, 'buscar'])->name('veiculos.buscar');
Route::get('/veiculos/buscar-por-placa/{placa}', [VeiculoController::class, 'buscarPorPlaca'])->name('veiculos.buscarPorPlaca');
Route::get('/veiculos/{id}/buscar', [VeiculoController::class, 'buscarPorId'])->name('veiculos.buscarPorId');

// ✅ Buscar motorista anterior por placa
Route::get('/api/motorista-por-placa/{placa}', [VeiculoController::class, 'motoristaPorPlaca']);

// ✅ Buscar veículo por placa (API para AJAX)
Route::get('/api/veiculo-por-placa/{placa}', [VeiculoController::class, 'buscarPorPlaca']);

// ✅ Rota para buscar acessos liberados (autocomplete nome/matrícula)
Route::get('/acessos/buscar', [AcessoLiberadoController::class, 'buscar'])->name('acessos.buscar');

// ✅ Rota para buscar motoristas de acessos liberados (autocomplete para veículos do tipo PARTICULAR e MOTO)
Route::get('/registro-veiculos/buscar-motoristas-acesso', [RegistroVeiculoController::class, 'buscarMotoristasAcesso'])->name('registro_veiculos.buscar_motoristas_acesso');

// ---------------------------
// Seleção de Estacionamento
// ---------------------------
Route::get('/selecionar-estacionamento', [EstacionamentoController::class, 'selecionar'])->name('selecionar.estacionamento');
Route::post('/definir-estacionamento', [EstacionamentoController::class, 'definir'])->name('definir.estacionamento');

// ---------------------------
// Recursos principais
// ---------------------------
Route::resource('usuarios', UsuarioController::class);
Route::resource('motoristas', MotoristaController::class);
Route::patch('motoristas/{id}/alternar-status', [MotoristaController::class, 'alternarStatus'])->name('motoristas.alternar-status');
Route::resource('acessos_liberados', AcessoLiberadoController::class);
Route::patch('acessos_liberados/{id}/status', [AcessoLiberadoController::class, 'alterarStatus'])->name('acessos_liberados.status');
Route::resource('veiculos', VeiculoController::class);
Route::resource('registro_veiculos', RegistroVeiculoController::class);
Route::resource('estacionamentos', EstacionamentoController::class);

// ✅ Protegendo ocorrências e acompanhamentos com auth
Route::middleware(['auth'])->group(function () {
    Route::resource('ocorrencias', OcorrenciaController::class);
    Route::get('acompanhamentos/{ocorrencia}/create', [AcompanhamentoController::class, 'create'])->name('acompanhamentos.create');
    Route::post('acompanhamentos/{ocorrencia}', [AcompanhamentoController::class, 'store'])->name('acompanhamentos.store');
});

// ---------------------------
// Perfis e Permissões
// ---------------------------
Route::resource('perfis', PerfilController::class)->parameters(['perfis' => 'perfil']);
Route::resource('permissoes', PermissaoController::class)->parameters(['permissoes' => 'permissao']);

// ---------------------------
// Ações extras
// ---------------------------
Route::patch('usuarios/{usuario}/alternar-status', [UsuarioController::class, 'alternarStatus'])->name('usuarios.alternar-status');
Route::post('usuarios/{usuario}/reset-senha', [UsuarioController::class, 'resetSenha'])->name('usuarios.resetSenha');
Route::post('registro_veiculos/{id}/registrar_saida', [RegistroVeiculoController::class, 'registrarSaida'])->name('registro_veiculos.registrar_saida');

// ✅ Limpar registros com saída
Route::post('/registro-veiculos/limpar-com-saida', [RegistroVeiculoController::class, 'limparComSaida'])->name('registro_veiculos.limpar_com_saida');

// ---------------------------
// Painel de dados
// ---------------------------
Route::get('/painel/dados', [PainelController::class, 'dados'])->name('painel.dados');

// ---------------------------
// Rota de teste
// ---------------------------
Route::get('/teste', function () {
    return 'Você está no projeto gesgaragem!';
});

// ✅ Rotas de Mensagens
Route::resource('mensagens', MensagemController::class);

// ✅ Rotas de Relatórios de Veículos
    Route::prefix('relatorios/veiculos')->name('relatorios.veiculos.')->group(function () {
    Route::get('/', [RelatorioVeiculoController::class, 'index'])->name('index');
    Route::get('/exportar', [RelatorioVeiculoController::class, 'exportar'])->name('exportar');
    Route::get('/{relatorio}', [RelatorioVeiculoController::class, 'show'])->name('show');
});

Route::prefix('relatorios/usuarios')->name('relatorios.usuarios.')->group(function () {
    Route::get('/', [RelatorioUsuarioController::class, 'index'])->name('index');
    Route::get('/exportar', [RelatorioUsuarioController::class, 'exportar'])->name('exportar');
});