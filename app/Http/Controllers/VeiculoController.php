<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AutorizacaoAPIController;
use App\Models\Veiculos;
use App\Models\Modelos;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\LogVeiculoController;

class VeiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(AutorizacaoAPIController::getAuthorizationHeader()){
            $veiculos = Veiculos::select('*', 'veiculos.id as id_veiculo', 'veiculos.created_at as veiculo_criado_em')
                ->where('veiculos.id', '>', 0)
                ->join('modelos', 'veiculos.modelo_id', '=', 'modelos.id')
                ->join('locadoras', 'veiculos.locadora_id', '=', 'locadoras.id')
                ->orderBy('veiculo_criado_em', 'DESC')
                ->get();
            print($veiculos);
        }else{
            echo 'token_invalido';
        }
    }

    public function veiculosFiltrados(Request $request){
        $filtros = $request->all();
        $locadora = $filtros['locadora'];
        $modelo_id = $filtros['modelo_id'];
        $data_inicio = $filtros['data_inicio'];
        $data_fim = $filtros['data_fim'];

        $veiculos = Veiculos::select('*', 'veiculos.id as id_veiculo', 'veiculos.created_at as veiculo_criado_em')
            ->where('veiculos.id', '>', 0)
            ->join('modelos', 'veiculos.modelo_id', '=', 'modelos.id')
            ->join('locadoras', 'veiculos.locadora_id', '=', 'locadoras.id');

        if($locadora != '' && $locadora != NULL){
            $veiculos->where('locadoras.nome_fantasia', 'LIKE', "%$locadora%");
        }
        if($modelo_id != '' && $modelo_id != NULL && $modelo_id != 0){
            $veiculos->where('veiculos.modelo_id', '=', $modelo_id);
        }
        if($data_inicio != '' && $data_inicio != NULL && $data_inicio != 0){
            $data_i = explode('/',$data_inicio);
            $date_i=date_create($data_i[2].'-'.$data_i[1].'-'.$data_i[0]);
            $data_inicio_formatada = date_format($date_i,"Y-m-d");
            $veiculos->where('veiculos.created_at', '>=', $data_inicio_formatada);
        }
        if($data_fim != '' && $data_fim != NULL && $data_fim != 0){
            $data_f = explode('/',$data_fim);
            $date_f=date_create($data_f[2].'-'.$data_f[1].'-'.$data_f[0]);
            $data_fim_formatada = date_format($date_f,"Y-m-d");
            $veiculos->where('veiculos.created_at', '<=', $data_fim_formatada);
        }
        print($veiculos->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(AutorizacaoAPIController::getAuthorizationHeader()){

            if(!$this->verificaSeJaExiste($request->input('placa'), $request->input('chassi'))){
                $veiculo = Veiculos::create([
                    'portas' => $request->input('portas'),
                    'modelo_id' => $request->input('modelo_id'),
                    'locadora_id' => $request->input('locadora_id'),
                    'cor' => $request->input('cor'),
                    'ano_modelo' => $request->input('ano_modelo'),
                    'ano_fabricacao' => $request->input('ano_fabricacao'),
                    'placa' => $request->input('placa'),
                    'chassi' => $request->input('chassi')
                ]);

                LogVeiculoController::criarLog($request->input('modelo_id'), $request->input('locadora_id'));
    
                echo 'success';
            }else{
                echo 'ja_existe';
            }
            
        }else{
            echo 'token_invalido';
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(AutorizacaoAPIController::getAuthorizationHeader()){
            $veiculo = Veiculos::where('id', '=', $id)->get();
            print($veiculo);
        }else{
            echo 'token_invalido';
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(AutorizacaoAPIController::getAuthorizationHeader()){
            if(!$this->verificaSeJaExiste($request->input('placa'), $request->input('chassi'), $id)){
                $veiculo = [
                    'portas' => $request->input('portas'),
                    'modelo_id' => $request->input('modelo_id'),
                    'locadora_id' => $request->input('locadora_id'),
                    'cor' => $request->input('cor'),
                    'ano_modelo' => $request->input('ano_modelo'),
                    'ano_fabricacao' => $request->input('ano_fabricacao'),
                    'placa' => $request->input('placa'),
                    'chassi' => $request->input('chassi')
                ];


                $locadora_ant = $this->verificaSeMudouDeLocadora($id, $request->input('locadora_id'));

                if($locadora_ant > 0){
                    LogVeiculoController::finalizaLog($request->input('modelo_id'), $locadora_ant);
                    LogVeiculoController::criarLog($request->input('modelo_id'), $request->input('locadora_id'));
                }
    
                Veiculos::where('id', $id)
                    ->update($veiculo);
    
                echo 'success';
            }else{
                echo 'ja_existe';
            }
        }else{
            echo 'Token inválido!';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(AutorizacaoAPIController::getAuthorizationHeader()){
            Veiculos::where('id', $id)
                ->forceDelete();
            
            echo 'success';
        }else{
            echo 'Token inválido!';
        }
    }

    public function verificaSeJaExiste($placa, $chassi, $id = 0){
        if($id > 0){
            $veiculos = Veiculos::where('id', '<>', $id)
                ->where(function ($query) use ($placa, $chassi) {
                    $query->where('placa', '=', $placa)
                        ->orwhere('chassi', '=', $chassi);
                })
                ->get();

                
                
        }else{
            $veiculos = Veiculos::where('placa', '=', $placa)
                ->orwhere('chassi', '=', $chassi)
                ->get();
        }

        return count($veiculos) > 0 ? true : false;
    }

    public function verificaSeMudouDeLocadora($id, $id_locadora){
        $id_locadora_anterior = Veiculos::select('locadora_id')
            ->where('id', '=', $id)->get();

        if($id_locadora_anterior[0]->locadora_id && $id_locadora_anterior[0]->locadora_id != $id_locadora){
            return $id_locadora_anterior[0]->locadora_id;
        }
        
        return 0;
    }
}
