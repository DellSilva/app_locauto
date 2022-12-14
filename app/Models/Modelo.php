<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;
    protected $fillable = ['marca_id', 'nome', 'imagem', 'numero_portas', 'lugares', 'air_bag', 'abs'];

    public function rules() {
        return [
            'marca_id' => 'exists:marcas,id',
            'nome' => 'required|unique:modelos,nome|min:3',
            'imagem' => 'required|file|mimes:png,jpg,jpeg',
            'numero_portas' => 'required|integer|digits_between:1,5'
        ];                 
        
    }

    public function marca() {
        //UM modelo PERTENCE a UMA marca
        return $this->belongsTo('App\Models\Marca');
    }

}
