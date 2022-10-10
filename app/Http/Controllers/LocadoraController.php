<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modelos;
use App\Models\Locadoras;
use App\Models\LocadorasEndereco;
use App\Http\Controllers\AutorizacaoAPIController;
use Illuminate\Support\Facades\DB;

class LocadoraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(AutorizacaoAPIController::getAuthorizationHeader()){
            $locadoras = Locadoras::where('locadoras.id', '>', 0)
                ->join('locadoras_enderecos', 'locadoras.id', '=', 'locadoras_enderecos.id_locadora')
                ->get();
            print($locadoras);
        }else{
            echo 'token_invalido';
        }
    }

    public function locadorasFiltradas($search){
        if(AutorizacaoAPIController::getAuthorizationHeader()){
            if($search == 'empty'){
                print(Locadoras::all());
            }else{
                print(Locadoras::where('title', 'like', '%' . $search . '%')
                ->orwhere('author', 'like', '%' . $search . '%')
                ->orwhere('isbn', 'like', '%' . $search . '%')
                ->orwhere('pages', 'like', '%' . $search . '%')
                ->orwhere('edition', 'like', '%' . $search . '%')
                ->orwhere('publishingCompany', 'like', '%' . $search . '%')
                ->get());
            }
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

            if(!$this->verificaSeJaExiste($request->input('cnpj'))){
                $locadora = Locadoras::create([
                    'nome_fantasia' => $request->input('nome_fantasia'),
                    'razao_social' => $request->input('razao_social'),
                    'cnpj' => $request->input('cnpj'),
                    'email' => $request->input('email'),
                    'telefone' => $request->input('telefone')
                ]);
    
                $lastInsertID = $locadora->id;
    
                LocadorasEndereco::create([
                    'id_locadora' => $lastInsertID,
                    'cep' => $request->input('cep'),
                    'logradouro' => $request->input('logradouro'),
                    'numero' => $request->input('numero'),
                    'bairro' => $request->input('bairro'),
                    'cidade' => $request->input('cidade'),
                    'estado' => $request->input('estado')
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
            $locadoras = Locadoras::where('locadoras.id', '=', $id)
                ->join('locadoras_enderecos', 'locadoras.id', '=', 'locadoras_enderecos.id_locadora')
                ->get();
            print($locadoras);
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
            if(!$this->verificaSeJaExiste($request->input('cnpj'), $id)){
                $locadora = [
                    'nome_fantasia' => $request->input('nome_fantasia'),
                    'razao_social' => $request->input('razao_social'),
                    'cnpj' => $request->input('cnpj'),
                    'email' => $request->input('email'),
                    'telefone' => $request->input('telefone')
                ];
    
                $locadoras_endereco = [
                    'cep' => $request->input('cep'),
                    'logradouro' => $request->input('logradouro'),
                    'numero' => $request->input('numero'),
                    'bairro' => $request->input('bairro'),
                    'cidade' => $request->input('cidade'),
                    'estado' => $request->input('estado')
                ];
    
                Locadoras::where('id', $id)
                    ->update($locadora);
                    
                LocadorasEndereco::where('id_locadora', $id)
                    ->update($locadoras_endereco);
    
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
            LocadorasEndereco::where('id_locadora', $id)
                ->forceDelete();
            
            echo 'success';
        }else{
            echo 'Token inválido!';
        }
    }

    public function verificaSeJaExiste($cnpj, $id = 0){
        if($id > 0){
            $locadoras = Locadoras::where('cnpj', '=', $cnpj)
                ->where('id', '<>', $id)
                ->get();
        }else{
            $locadoras = Locadoras::where('cnpj', '=', $cnpj)
                ->get();
        }
        
        return count($locadoras) > 0 ? true : false;
    }

    public function locadorasModelos(){
        if(AutorizacaoAPIController::getAuthorizationHeader()){
            $modelos = Modelos::select('nome_modelo','nome_fantasia', DB::raw('(SELECT COUNT(*) FROM veiculos WHERE veiculos.modelo_id = modelos.id AND veiculos.locadora_id = locadoras.id) as qtd_veiculo_x_modelo'))
                ->join('veiculos', 'veiculos.modelo_id', '=', 'modelos.id')
                ->join('locadoras', 'veiculos.locadora_id', '=', 'locadoras.id')
                ->orderBy('locadoras.nome_fantasia')
                ->groupBy('nome_fantasia', 'nome_modelo', 'qtd_veiculo_x_modelo')
                ->get();

                print($modelos);
        }else{
            echo 'token_invalido';
        }
    }
}
