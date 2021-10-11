<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';
    protected $primaryKey = 'codigo_interno';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $attributes = [
        'estado' => 1,
    ];

    protected $fillable = [
        'nombre',
        'descripcion',
        'codigo_barra',
        'codigo_interno',
        'imagen',
        'precio',
        'cantidad',
        'stock_critico'
    ];
}
