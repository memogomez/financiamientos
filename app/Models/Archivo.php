<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    use HasFactory;

    protected $table = 'archivos';
    protected $fillable = ['nombre_archivo', 'ruta_archivo', 'fecha_subida', 'fecha_archivo'];
    public $timestamps = false;
}
