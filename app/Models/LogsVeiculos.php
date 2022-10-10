<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogsVeiculos extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_modelo',
        'id_locadora',
        'data_inicio',
        'data_fim'
    ];
}
