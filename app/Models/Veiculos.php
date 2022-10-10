<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veiculos extends Model
{
    use HasFactory;

    protected $fillable = [
        'portas',
        'modelo_id',
        'cor',
        'locadora_id',
        'ano_modelo',
        'ano_fabricacao',
        'placa',
        'chassi'
    ];
}
