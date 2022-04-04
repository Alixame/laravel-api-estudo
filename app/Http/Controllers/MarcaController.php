<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Marca;
use App\Repositories\MarcaRepository;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    /**
     * METODO CONTRUTOR DA CLASSE
     *
     * @param Marca $marca
     */
    public function __construct(Marca $marca) {
        $this->marca = $marca;
    }

    /**
     * METODO RESPONSAVEL POR RENDERIZAR TODOS OS REGISTROS
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // INSTANCIANDO UM OBJETO DA CLASSE REPOSITORIO PASSANDO MARCA
        $marcaRepository = new MarcaRepository($this->marca);

        // Verificando se existe filtro de atributos da relação na requisição
        if ($request->has('atributos_modelos')) {

            // se sim -> define a relação e os atributos que serão trazidos
            $atributos_modelos = 'modelos:id,marca_id,'.$request->atributos_modelos;

            $marcaRepository->atributosRegistrosRelacionados($atributos_modelos);

        } else {
            // se não -> define a relação e tras todos os atributos
            $marcaRepository->atributosRegistrosRelacionados('modelos');
        }

        // Verificando se existe filtro na requisição
        if ($request->has('filtros')) {

            $marcaRepository->filtro($request->filtros);

        }

        // Verificando se existe filtro de atributos na requisição
        if($request->has('atributos')) {

            // se sim -> retornar todos os registros com os atributos solicitados
            $marcaRepository->atributos($request->atributos);

        }

        return response()->json($marcaRepository->getResultPaginate(3), 200);
    }

    /**
     * METODO RESPONSAVEL POR ADICIONAR UM REGISTRO NO BANCO
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$marca = Marca::create($request->all());

        // validando campos passados
        $request->validate($this->marca->rules(), $this->marca->feedbacks());

        // pegando dados do arquivo
        $imagem = $request->file('imagem');

        // definindo local que será salvo o arquivo
        $img_urn = $imagem->store('img/marca', 'public');

        // salvando dados no banco
        $marca = $this->marca->create(['nome' => $request->get('nome'), 'imagem' => $img_urn]);

        // retorna a responsa em json
        return response()->json($marca, 201);
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
        $marca = $this->marca->with('marcas')->find($id);

        // verificando se o registro buscado no banco existe
        if($marca === null) {
            // retorna erro em json
            return response()->json(['erro' => 'Registro não existe'], 404);
        }

        // retorna a responsa em json
        return response()->json($marca, 200);
    }

    /**
     * METODO RESPONSAVEL POR ALTERAR UM REGISTRO DO BANCO
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // recuperando registro atraves do objeto instanciado no metodo construtor
        $marca = $this->marca->find($id);

        // verificando se o registro buscado no banco existe
        if($marca === null) {
            // retorna erro em json
            return response()->json(['erro' => 'Registro não existe'], 404);
        }

        // verificando se o metodo enviado é do tipo correto para efetuar a alteração e carregando dinamicamente as regras de validação de dados
        if ($request->method() === 'PATCH') {

            // definindo array
            $regrasDinamicas = [];

            // percorrendo as regras definidas no model marca
            foreach ($marca->rules() as $input => $regra) {

                // verificando se o valor informado possui regra
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }

            }

            // validando dados - com regras dinamicas
            $request->validate($regrasDinamicas, $marca->feedbacks());

        } else {

            // validando dados
            $request->validate($marca->rules(), $marca->feedbacks());

        }

        // se for passado um arquivo, vai deletar a antiga para salvar a nova
        if($request->file('imagem')){
            // apagando arquivo relacionado
            Storage::disk('public')->delete($marca->imagem);
        }

        // pegando dados do arquivo
        $imagem = $request->file('imagem');

        // definindo local que será salvo o arquivos
        $img_urn = $imagem->store('img/marca', 'public');

        // alterando dados no banco
        //$marca->update(['nome' => $request->get('nome'), 'imagem' => $img_urn]);

        $marca->fill($request->all());
        $marca->imagem = $img_urn;
        $marca->save();

        // retorna a responsa em json
        return response()->json($marca, 200);

    }

    /**
     * METODO RESPONSAVEL POR REMOVER UM REGISTRO DO BANCO
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // recuperando registro atraves do objeto instanciado no metodo construtor
        $marca = $this->marca->find($id);

        // verificando se o registro buscado no banco existe
        if($marca === null) {
            // retorna erro em json
            return response()->json(['erro' => 'Registro não existe'], 404);
        }

        // excluindo registro
        $marca->delete();

        // apagando arquivo relacionado
        Storage::disk('public')->delete($marca->imagem);

        // retorna a responsa em json
        return response()->json(['mensagem' => 'O marca foi removido com sucesso!'], 200);

    }
}
