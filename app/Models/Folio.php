<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folio extends Model
{
    use HasFactory;

    protected $table = 'folios';

    protected $primaryKey = 'id_folio';

    protected $fillable = [
        'id_solicitante',
        'id_delito',
        'ticket',
        'acronimo',
        'hora',
        'dia_mes',
        'anio',
        'folio',
        'razon',
        'numero_registro',
        'detenido',
        'id_user',
    ];

    public function solicitante() {
        return $this->belongsTo(Solicitante::class, 'id_solicitante', 'id_solicitante');
    }

    public function delito() {
        return $this->belongsTo(Delito::class, 'id_delito', 'id_delito');
    }

    public function user() {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
