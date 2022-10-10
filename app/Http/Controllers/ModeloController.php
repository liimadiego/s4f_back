<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modelos;
use App\Models\Montadoras;
use App\Http\Controllers\AutorizacaoAPIController;

class ModeloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(AutorizacaoAPIController::getAuthorizationHeader()){
            $modelos = Modelos::select('*', 'modelos.id as id_modelo')
                ->where('modelos.id', '>', 0)
                ->join('montadoras', 'modelos.montadora_id', '=', 'montadoras.id')
                ->get();
            print($modelos);
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
        if(AutorizacaoAPIController::getAuthorizationHeader()){
            $montadoras = Montadoras::all();
            print($montadoras);
        }else{
            echo 'token_invalido';
        }
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

            if(!$this->verificaSeJaExiste($request->input('nome_modelo'), $request->input('montadora_id'))){
                $modelo = Modelos::create([
                    'nome_modelo' => $request->input('nome_modelo'),
                    'montadora_id' => $request->input('montadora_id')
                ]);
    
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
            $modelos = Modelos::where('id', '=', $id)->get();
            print($modelos);
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
            if(!$this->verificaSeJaExiste($request->input('nome_modelo'), $request->input('montadora_id'), $id)){
                $modelo = [
                    'nome_modelo' => $request->input('nome_modelo'),
                    'montadora_id' => $request->input('montadora_id')
                ];
    
                Modelos::where('id', $id)
                    ->update($modelo);
    
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
            Modelos::where('id', $id)
                ->forceDelete();
            
            echo 'success';
        }else{
            echo 'Token inválido!';
        }
    }

    public function verificaSeJaExiste($nome_modelo, $montadora_id, $id = 0){
        if($id > 0){
            $modelos = Modelos::where('nome_modelo', '=', $nome_modelo)
                ->where('montadora_id', '=', $montadora_id)
                ->where('id', '<>', $id)
                ->get();
        }else{
            $modelos = Modelos::where('nome_modelo', '=', $nome_modelo)
                ->where('montadora_id', '=', $montadora_id)
                ->get();
        }

        return count($modelos) > 0 ? true : false;
    }
}
