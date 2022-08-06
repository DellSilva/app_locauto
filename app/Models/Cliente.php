<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'CPF', 'telefone'];

    public function rules() {
        return [
            'nome' => 'required',
            'CPF' => 'required',
            'telefone' => 'required',            
        ];     
    }

    /*public function modelo() {        
        return $this->belongsTo('App\Models\Modelo');
    }*/
}
