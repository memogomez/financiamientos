<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Oficio extends Model
{
    protected $table = 'oficios';

    protected $fillable = [
        'id_solicitud',
        'tipo_oficio',
        'num_oficio',
        'url',
        'estatus',
    ];

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class, 'id_solicitud');
    }
}
