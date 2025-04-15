<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delito extends Model
{
    use HasFactory;

    protected $table = 'delitos';

    protected $primaryKey = 'id_delito';

    protected $fillable = [
        'delito'
    ];

    protected $attributes = [
        'estatus' => 1
    ];
}
