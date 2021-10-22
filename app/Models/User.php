<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;

// importamos Laravel Passport
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    // protected $primaryKey = 'email';
    // protected $keyType = 'string';
    // public $incrementing = false;
    protected $attributes = [
        'estado' => 1,
        'rol' => "vendedor"
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relacion N a M
    public function ventas()
    {
        return $this->belongsToMany(Producto::class, 'ventas', 'user_id', 'producto_codigo_interno');
    }
    public function mermas()
    {
        return $this->belongsToMany(Producto::class, 'mermas', 'user_id', 'producto_codigo_interno');
    }
    public function entradas()
    {
        return $this->belongsToMany(Producto::class, 'entradas', 'user_id', 'producto_codigo_interno');
    }
}
