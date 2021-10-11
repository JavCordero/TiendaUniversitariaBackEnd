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

    // Relacion N a M
    public function ventas()
    {
        return $this->belongsToMany(User::class, 'ventas', 'producto_codigo_interno', 'user_id');
    }
    public function mermas()
    {
        return $this->belongsToMany(User::class, 'mermas', 'producto_codigo_interno', 'user_id');
    }
    public function entradas()
    {
        return $this->belongsToMany(User::class, 'entradas', 'producto_codigo_interno', 'user_id');
    }
}
