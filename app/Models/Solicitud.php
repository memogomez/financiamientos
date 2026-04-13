<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'solicitudes';

    protected $fillable = [
        'id_area',
        'id_usuario',
        'fecha',
        'solicita',
        'dirigido',
        'monto_solicitado',
        'observaciones',
        'comprobacion',
        'estatus',
    ];

    protected $casts = [
        'fecha'            => 'date',
        'monto_solicitado' => 'decimal:2',
        'comprobacion'     => 'boolean',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'id_area');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function oficios(): HasMany
    {
        return $this->hasMany(Oficio::class, 'id_solicitud');
    }
}
