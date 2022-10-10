<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocadoraController;
use App\Http\Controllers\MontadoraController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\LogVeiculoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::get('/locadoras', [LocadoraController::class, 'index']);
Route::post('/registrar_locadora', [LocadoraController::class, 'store']);
Route::get('/dados_editar_locadora/{id}', [LocadoraController::class, 'edit']);
Route::post('/editar_locadora/{id}', [LocadoraController::class, 'update']);
Route::get('/deletar_locadora/{id}', [LocadoraController::class, 'destroy']);
Route::get('/locadoras_x_modelos', [LocadoraController::class, 'locadorasModelos']);

Route::get('/montadoras', [MontadoraController::class, 'index']);
Route::post('/registrar_montadora', [MontadoraController::class, 'store']);
Route::get('/dados_editar_montadora/{id}', [MontadoraController::class, 'edit']);
Route::post('/editar_montadora/{id}', [MontadoraController::class, 'update']);
Route::get('/deletar_montadora/{id}', [MontadoraController::class, 'destroy']);

Route::get('/modelos', [ModeloController::class, 'index']);
Route::post('/registrar_modelo', [ModeloController::class, 'store']);
Route::get('/dados_editar_modelo/{id}', [ModeloController::class, 'edit']);
Route::post('/editar_modelo/{id}', [ModeloController::class, 'update']);
Route::get('/deletar_modelo/{id}', [ModeloController::class, 'destroy']);

Route::get('/veiculos', [VeiculoController::class, 'index']);
Route::post('/veiculos_filtrados', [VeiculoController::class, 'veiculosFiltrados']);
Route::post('/registrar_veiculo', [VeiculoController::class, 'store']);
Route::get('/dados_editar_veiculo/{id}', [VeiculoController::class, 'edit']);
Route::post('/editar_veiculo/{id}', [VeiculoController::class, 'update']);
Route::get('/deletar_veiculo/{id}', [VeiculoController::class, 'destroy']);

Route::get('/logs', [LogVeiculoController::class, 'index']);