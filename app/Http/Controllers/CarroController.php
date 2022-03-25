<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use Illuminate\Http\Request;
use App\Repositories\CarroRepository;

class CarroController extends Controller
{   
    public function __construct(Carro $carro) {
        $this->carro = $carro;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // INSTANCIANDO UM OBJETO DA CLASSE REPOSITORIO PASSANDO carro
        $carroRepository = new CarroRepository($this->carro);

        // Verificando se existe filtro de atributos da relação na requisição
        if ($request->has('atributos_modelo')) {

            // se sim -> define a relação e os atributos que serão trazidos
            $atributos_modelo = 'modelo:id,'.$request->atributos_modelo;
            
            $carroRepository->atributosRegistrosRelacionados($atributos_modelo);

        } else {
            // se não -> define a relação e tras todos os atributos
            $carroRepository->atributosRegistrosRelacionados('modelo');
        }

        // Verificando se existe filtro na requisição
        if ($request->has('filtros')) {
            
            $carroRepository->filtro($request->filtros);

        }

        // Verificando se existe filtro de atributos na requisição
        if($request->has('atributos')) {

            // se sim -> retornar todos os registros com os atributos solicitados
            $carroRepository->atributos('id,'.$request->atributos.',modelo_id');

        }

        return response()->json($carroRepository->getResult(), 200);
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
        $request->validate($this->carro->rules(), $this->carro->feedbacks());
    
        // salvando dados no banco
        $carro = $this->carro->create([
            'modelo_id' => $request->modelo_id,
            'placa'  => $request->placa,
            'disponivel'  => $request->disponivel,
            'km'  => $request->km
        ]);
        
        // retorna a responsa em json
        return response()->json($carro, 201);
    }

    /**
     * METODO RESPONSAVEL POR RECURAR UM REGISTRO EM ESPECIFICO
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // recuperando registro atraves do objeto instanciado no metodo construtor
        $carro = $this->carro->with('modelo')->find($id);

        // verificando se o registro buscado no banco existe
        if($carro === null) {
            // retorna erro em json
            return response()->json(['erro' => 'Registro não existe'], 404);
        } 

        // retorna a responsa em json
        return response()->json($carro, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         // recuperando registro atraves do objeto instanciado no metodo construtor
         $carro = $this->carro->find($id);

         // verificando se o registro buscado no banco existe
         if($carro === null) {
             // retorna erro em json
             return response()->json(['erro' => 'Registro não existe'], 404);
         } 
 
         // verificando se o metodo enviado é do tipo correto para efetuar a alteração e carregando dinamicamente as regras de validação de dados
         if ($request->method() === 'PATCH') {
             
             // definindo array
             $regrasDinamicas = [];
 
             // percorrendo as regras definidas no model carro
             foreach ($carro->rules() as $input => $regra) {
                 
                 // verificando se o valor informado possui regra
                 if (array_key_exists($input, $request->all())) {
                     $regrasDinamicas[$input] = $regra;
                 }
 
             }
 
             // validando dados - com regras dinamicas
             $request->validate($regrasDinamicas, $carro->feedbacks());
 
         } else {
 
             // validando dados 
             $request->validate($carro->rules(), $carro->feedbacks());
 
         }
 
         $carro->fill($request->all());
         $carro->save();
 
         // retorna a responsa em json
         return response()->json($carro, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // recuperando registro atraves do objeto instanciado no metodo construtor
        $carro = $this->carro->find($id);

        // verificando se o registro buscado no banco existe
        if($carro === null) {
            // retorna erro em json
            return response()->json(['erro' => 'Registro não existe'], 404);
        } 
        
        // excluindo registro
        $carro->delete();

        // retorna a responsa em json
        return response()->json(['mensagem' => 'O carro foi removido com sucesso!'], 200);
    }
}
