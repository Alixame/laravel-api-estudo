<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository {

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function atributosRegistrosRelacionados($atributos) {
        $this->model = $this->model->with($atributos);
        //a query está sendo montada
    }

    public function filtro($filtros) {

        $filtros = explode(';', $filtros);
        
        foreach($filtros as $key => $condicao) {

            $c = explode(':', $condicao);
            $this->model = $this->model->where($c[0], $c[1], $c[2]);
            //a query está sendo montada
        }
    }

    public function atributos($atributos) {
        $this->model = $this->model->selectRaw($atributos);
    }

    public function getResult() {
        return $this->model->get();
    }

} 