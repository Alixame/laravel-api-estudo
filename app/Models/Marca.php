<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'imagem'];

    public function rules(){
        return [
            'nome' => 'required|unique:marcas,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png,jpg,jpeg'
        ];
    }

    public function feedbacks() {
        return [
            'required' => 'O campo :attribute é obrigatorio',
            'nome.unique' => 'Esse nome já existe',
            'nome.min' => 'O campo nome precisa conter no minimo 3 caracters',
            'imagem.file' => 'O campo imagem deve ser um arquivo',
            'imagem.mimes' => 'O campo imagem deve ser do tipo PNG, JPG ou JPEG'
        ];
    }

    public function modelos() {
        return $this->hasMany('App\Models\Modelo');
    }
}
