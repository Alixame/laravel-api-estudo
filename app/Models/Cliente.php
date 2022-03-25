<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];

    public function rules(){
        return [
            'nome' => 'required|unique:marcas,nome,'.$this->id.'|min:3',
        ];
    }

    public function feedbacks() {
        return [
            'required' => 'O campo :attribute é obrigatorio',
            'nome.min' => 'O campo nome precisa conter no minimo 3 caracters',
        ];
    }

}
