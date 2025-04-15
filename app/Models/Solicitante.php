<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitante extends Model
{
    use HasFactory;

    protected $table = 'solicitantes';

    protected $primaryKey = 'id_solicitante';

    protected $fillable = [
        'nombre_solicitante',
        'plaza',
        'gafete',
        'agencia_mp',
        'turno',
    ];
}
