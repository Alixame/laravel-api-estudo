<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $fillable = ['marca_id', 'nome', 'imagem', 'numero_portas', 'lugares', 'air_bag', 'abs'];

    public function rules(){
        return [
            'marca_id' => 'exists:marcas,id',
            'nome' => 'required|unique:modelos,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png,jpg,jpeg',
            'numero_portas' => 'required|integer|digits_between:1,5',
            'lugares' => 'required|integer|digits_between:1,8', 
            'air_bag'=> 'required|boolean',
            'abs' => 'required|boolean' // true ou false = 0 ou 1 = "1" ou "0"
        ];
    }

    public function feedbacks() {
        return [
            'required' => 'O campo :attribute é obrigatorio',
            'marca_id.exists' => 'O campo não é valido',
            'nome.unique' => 'Esse nome já existe',
            'nome.min' => 'O campo nome precisa conter no minimo 3 caracters',
            'imagem.file' => 'O campo imagem deve ser um arquivo',
            'imagem.mimes' => 'O campo imagem deve ser do tipo PNG, JPG ou JPEG',
            'numero_portas.integer' => 'O campo deve ser do tipo numerico inteiro',
            'numero_portas.digits_between' => 'O valor precisa estar entre 1 e 5',
            'lugares.integer' => 'O campo deve ser do tipo numerico inteiro',
            'lugares.digits_between' => 'O valor deve estar entre 1 e 5',
            'air_bag.boolean' => 'O valor informado é invalido',
            'abs.boolean' => 'O valor informado é invalido'
        ];
    }

    public function marca() {
        return $this->belongsTo('App\Models\Marca');
    }

}
