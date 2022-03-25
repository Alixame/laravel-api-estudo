<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Modelo;
use App\Repositories\ModeloRepository;
use Illuminate\Http\Request;

class ModeloController extends Controller
{   
    public function __construct(Modelo $modelo) {
        $this->modelo = $modelo;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       // INSTANCIANDO UM OBJETO DA CLASSE REPOSITORIO PASSANDO MARCA
       $modeloRepository = new ModeloRepository($this->modelo);

       // Verificando se existe filtro de atributos da relação na requisição
       if ($request->has('atributos_marca')) {

           // se sim -> define a relação e os atributos que serão trazidos
           $atributos_marca = 'marca:id,'.$request->atributos_marca;
           
           $modeloRepository->atributosRegistrosRelacionados($atributos_marca);

       } else {
           // se não -> define a relação e tras todos os atributos
           $modeloRepository->atributosRegistrosRelacionados('marca');
       }

       // Verificando se existe filtro na requisição
       if ($request->has('filtros')) {
           
           $modeloRepository->filtro($request->filtros);

       }

       // Verificando se existe filtro de atributos na requisição
       if($request->has('atributos')) {

           // se sim -> retornar todos os registros com os atributos solicitados
           $modeloRepository->atributos('id,'.$request->atributos.',marca_id');

       }

       return response()->json($modeloRepository->getResult(), 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$modelo = Modelo::create($request->all());

        $request->validate($this->modelo->rules(), $this->modelo->feedbacks());
        
        // pegando dados do arquivo
        $imagem = $request->file('imagem');

        // definindo local que será salvo o arquivo
        $img_urn = $imagem->store('img/modelos', 'public');

        $modelo = $this->modelo->create([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $img_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares, 
            'air_bag'=> $request->air_bag,
            'abs' => $request->abs
        ]);

        return response()->json($modelo, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);

        if($modelo === null) {
            return response()->json(['erro' => 'Registro não existe'], 404);
        } 

        return response()->json($modelo, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $modelo = $this->modelo->find($id);

        if($modelo === null) {
            return response()->json(['erro' => 'Registro não existe'], 404);
        } 

        if ($request->method() === 'PATCH') {
            
            $regrasDinamicas = [];

            foreach ($modelo->rules() as $input => $regra) {
                
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }

            }

            $request->validate($regrasDinamicas, $modelo->feedbacks());

        } else {

            $request->validate($modelo->rules(), $modelo->feedbacks());

        }

        // se for passado um arquivo, vai deletar a antiga para salvar a nova
        if($request->file('imagem')){
            // apagando arquivo relacionado
            Storage::disk('public')->delete($modelo->imagem);
        }

        // pegando dados do arquivo
        $imagem = $request->file('imagem');

        // definindo local que será salvo o arquivos
        $img_urn = $imagem->store('img/modelos', 'public');

        /*$modelo->update([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $img_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares, 
            'air_bag'=> $request->air_bag,
            'abs' => $request->abs
        ]);*/

        $modelo->fill($request->all());
        $modelo->imagem = $img_urn;
        $modelo->save();

        return response()->json($modelo, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);

        if($modelo === null) {
            return response()->json(['erro' => 'Registro não existe'], 404);
        } 

        $modelo->delete();

        return response()->json(['mensagem' => 'O modelo foi removida com sucesso!'], 200);
    }
}
