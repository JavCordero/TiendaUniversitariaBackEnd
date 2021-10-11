<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// importamos los modelos
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;

// importamos los facades
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear Usuarios
        User::create([
            'name' => "Pain",
            'rut' => "15323822840",
            'email' => "pain@gmail.com",
            'password' => bcrypt("123123"),
            'nombre' => "Pain",
            'rol' => "administrador",
        ]);

        User::create([
            'name' => "Mikasa",
            'rut' => "194534712379",
            'email' => "mikasa@gmail.com",
            'password' => bcrypt("123123"),
            'nombre' => "Mikasa",
            'rol' => "administrador",
        ]);

        User::create([
            'name' => "Reu",
            'rut' => "123123123",
            'email' => "reu@gmail.com",
            'password' => bcrypt("123123"),
            'nombre' => "Reu",
            'rol' => "vendedor",
        ]);

        // Crear Productos
        Producto::create([
            'nombre' => "Lapiz",
            'descripcion' => "Este es un lapiz muy bonito",
            'codigo_barra' => "111111",
            'codigo_interno' => "111111",
            'imagen' => "https://images.emojiterra.com/google/android-10/512px/270f.png",
            'precio' => 1000,
            'cantidad' => 100,
            'stock_critico' => 5,
        ]);

        Producto::create([
            'nombre' => "Poleron",
            'descripcion' => "Este poleron esta abrigadito",
            'codigo_barra' => "222222",
            'codigo_interno' => "222222",
            'imagen' => "https://solosurf.cl/wp-content/uploads/2018/06/poleron-negro.png",
            'precio' => 4000,
            'cantidad' => 50,
            'stock_critico' => 13,
        ]);

        Producto::create([
            'nombre' => "Agenda",
            'descripcion' => "Agenda del año 2015",
            'codigo_barra' => "333333",
            'codigo_interno' => "333333",
            'imagen' => "https://cloudfront-eu-central-1.images.arcpublishing.com/prisa/AGYRBXKZQH6C4KYQU6IGD2BDIE.jpg",
            'precio' => 800,
            'cantidad' => 200,
            'stock_critico' => 5,
        ]);

        // Crear Ventas
        Venta::create([
            'user_id' => 1,
            'producto_codigo_interno' => "111111",
            'cantidad' => 5
        ]);
        Venta::create([
            'user_id' => 1,
            'producto_codigo_interno' => "222222",
            'cantidad' => 4
        ]);
        Venta::create([
            'user_id' => 2,
            'producto_codigo_interno' => "111111",
            'cantidad' => 2
        ]);
    }
}
