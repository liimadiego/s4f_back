<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AutorizacaoAPIController;
use App\Models\LogsVeiculos;

class LogVeiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(AutorizacaoAPIController::getAuthorizationHeader()){
            $logs = LogsVeiculos::select('*', 'logs_veiculos.id as id_log')
                ->where('logs_veiculos.id', '>', 0)
                ->join('modelos', 'logs_veiculos.id_modelo', '=', 'modelos.id')
                ->join('locadoras', 'logs_veiculos.id_locadora', '=', 'locadoras.id')
                ->orderBy('id_log', 'DESC')
                ->get();
            print($logs);
        }else{
            echo 'token_invalido';
        }
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
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public static function criarLog($id_modelo, $id_locadora){
        if(AutorizacaoAPIController::getAuthorizationHeader()){

            $log = LogsVeiculos::create([
                'id_modelo' => $id_modelo,
                'id_locadora' => $id_locadora,
                'data_inicio' => date("d/m/Y"),
                'data_fim' => '-'
            ]);

            echo 'success';
            
        }else{
            echo 'token_invalido';
        }
    }

    public static function finalizaLog($id_modelo, $id_locadora){
        if(AutorizacaoAPIController::getAuthorizationHeader()){

            $log = LogsVeiculos::where('id_modelo', '=', $id_modelo)
                ->where('id_locadora', '=', $id_locadora)
                ->where('data_fim', '=', '-')
                ->update([
                    'data_fim' => date("d/m/Y")
                ]);

            echo 'success';
            
        }else{
            echo 'token_invalido';
        }
    }
}
