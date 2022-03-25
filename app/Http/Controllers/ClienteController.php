<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Repositories\ClienteRepository;

class ClienteController extends Controller
{   
    public function __construct(Cliente $cliente) {
        $this->cliente = $cliente;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // INSTANCIANDO UM OBJETO DA CLASSE REPOSITORIO PASSANDO cliente
        $clienteRepository = new ClienteRepository($this->cliente);

        // Verificando se existe filtro na requisição
        if ($request->has('filtros')) {
            
            $clienteRepository->filtro($request->filtros);

        }

        // Verificando se existe filtro de atributos na requisição
        if($request->has('atributos')) {

            // se sim -> retornar todos os registros com os atributos solicitados
            $clienteRepository->atributos('id,'.$request->atributos);

        }

        return response()->json($clienteRepository->getResult(), 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validando campos passados
        $request->validate($this->cliente->rules(), $this->cliente->feedbacks());
    
        // salvando dados no banco
        $cliente = $this->cliente->create([
            'nome' => $request->nome,
        ]);
        
        // retorna a responsa em json
        return response()->json($cliente, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       // recuperando registro atraves do objeto instanciado no metodo construtor
       $cliente = $this->cliente->find($id);

       // verificando se o registro buscado no banco existe
       if($cliente === null) {
           // retorna erro em json
           return response()->json(['erro' => 'Registro não existe'], 404);
       } 

       // retorna a responsa em json
       return response()->json($cliente, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // recuperando registro atraves do objeto instanciado no metodo construtor
        $cliente = $this->cliente->find($id);

        // verificando se o registro buscado no banco existe
        if($cliente === null) {
            // retorna erro em json
            return response()->json(['erro' => 'Registro não existe'], 404);
        } 

        // verificando se o metodo enviado é do tipo correto para efetuar a alteração e carregando dinamicamente as regras de validação de dados
        if ($request->method() === 'PATCH') {
            
            // definindo array
            $regrasDinamicas = [];

            // percorrendo as regras definidas no model cliente
            foreach ($cliente->rules() as $input => $regra) {
                
                // verificando se o valor informado possui regra
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }

            }

            // validando dados - com regras dinamicas
            $request->validate($regrasDinamicas, $cliente->feedbacks());

        } else {

            // validando dados 
            $request->validate($cliente->rules(), $cliente->feedbacks());

        }

        $cliente->fill($request->all());
        $cliente->save();

        // retorna a responsa em json
        return response()->json($cliente, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // recuperando registro atraves do objeto instanciado no metodo construtor
        $cliente = $this->cliente->find($id);

        // verificando se o registro buscado no banco existe
        if($cliente === null) {
            // retorna erro em json
            return response()->json(['erro' => 'Registro não existe'], 404);
        } 
        
        // excluindo registro
        $cliente->delete();

        // retorna a responsa em json
        return response()->json(['mensagem' => 'O cliente foi removido com sucesso!'], 200);
    }
}
