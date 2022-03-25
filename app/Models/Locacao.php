<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locacao extends Model
{
    use HasFactory;

    protected $table = 'locacoes';

    protected $fillable = ['cliente_id', 'carro_id', 'data_inicio_periodo', 'data_final_previsto_periodo', 'data_final_realizado_periodo', 'valor_diaria', 'km_inicial', 'km_final'];

    public function rules(){
        return [
            'cliente_id'  => 'required',
            'carro_id'  => 'required',
            'data_inicio_periodo'  =>  'required',
            'data_final_previsto_periodo'  => 'required',
            'data_final_realizado_periodo'  => 'required',
            'valor_diaria'  => 'required',
            'km_inicial'  => 'required',
            'km_final'  => 'required',
        ];
    }

    public function feedbacks() {
        return [
            'required' => 'O campo :attribute é obrigatorio',
        ];
    }





}
