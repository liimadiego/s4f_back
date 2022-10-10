<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Montadoras;
use App\Http\Controllers\AutorizacaoAPIController;
use Illuminate\Support\Facades\DB;

class MontadoraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(AutorizacaoAPIController::getAuthorizationHeader()){
            $montadoras = Montadoras::select('*', DB::raw('(SELECT COUNT(*) FROM modelos WHERE modelos.montadora_id = montadoras.id) as cont'))->get();
            print($montadoras);
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
        if(AutorizacaoAPIController::getAuthorizationHeader()){

            if(!$this->verificaSeJaExiste($request->input('nome_montadora'))){
                $locadora = Montadoras::create([
                    'nome_montadora' => $request->input('nome_montadora')
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
            $montadoras = Montadoras::where('id', '=', $id)->get();
            print($montadoras);
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
            if(!$this->verificaSeJaExiste($request->input('nome_montadora'), $id)){
                $montadora = [
                    'nome_montadora' => $request->input('nome_montadora')
                ];
    
                Montadoras::where('id', $id)
                    ->update($montadora);
    
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
            Locadoras::where('id', $id)
                ->forceDelete();
            
            echo 'success';
        }else{
            echo 'Token inválido!';
        }
    }

    public function verificaSeJaExiste($nome_montadora, $id = 0){
        if($id > 0){
            $montadoras = Montadoras::where('nome_montadora', '=', $nome_montadora)
                ->where('id', '<>', $id)
                ->get();
        }else{
            $montadoras = Montadoras::where('nome_montadora', '=', $nome_montadora)
                ->get();
        }
        
        return count($montadoras) > 0 ? true : false;
    }
}
