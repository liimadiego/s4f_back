<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocadorasEndereco extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_locadora',
        'cep',
        'logradouro',
        'numero',
        'bairro',
        'cidade',
        'estado'
    ];
}
