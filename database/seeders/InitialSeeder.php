<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// importamos los modelos
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use App\Models\Entrada;

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
            'email' => "pain@ucn.cl",
            'password' => bcrypt("123123"),
            'rol' => "administrador",
        ]);

        User::create([
            'name' => "Mikasa",
            'email' => "mikasa@ce.ucn.cl",
            'password' => bcrypt("123123"),
            'rol' => "administrador",
        ]);

        User::create([
            'name' => "Reu",
            'email' => "reu@alumnos.ucn.cl",
            'password' => bcrypt("123123"),
            'rol' => "vendedor",
        ]);

        // Crear Productos
        Producto::create([
            'nombre' => "Lapiz",
            'descripcion' => "Este es un lapiz muy bonito",
            'codigo_barra' => "0760970020001",
            'codigo_interno' => "002-0001",
            'categoria' => "002",
            'imagen' => "https://images.emojiterra.com/google/android-10/512px/270f.png",
            'precio' => 700,
            'cantidad' => 100,
            'stock_critico' => 5,
        ]);

        Producto::create([
            'nombre' => "Poleron",
            'descripcion' => "Este poleron esta abrigadito",
            'codigo_barra' => "0801110010001",
            'codigo_interno' => "001-0001",
            'categoria' => "001",
            'imagen' => "https://solosurf.cl/wp-content/uploads/2018/06/poleron-negro.png",
            'precio' => 24000,
            'cantidad' => 50,
            'stock_critico' => 13,
        ]);

        Producto::create([
            'nombre' => "Agenda 2015",
            'descripcion' => "Agenda del año 2015",
            'codigo_barra' => "0651030020002",
            'codigo_interno' => "002-0002",
            'categoria' => "002",
            'imagen' => "https://cloudfront-eu-central-1.images.arcpublishing.com/prisa/AGYRBXKZQH6C4KYQU6IGD2BDIE.jpg",
            'precio' => 2800,
            'cantidad' => 200,
            'stock_critico' => 5,
        ]);

        Producto::create([
            'nombre' => "Goma de borrar profesional",
            'descripcion' => "",
            'codigo_barra' => "0711110020003",
            'codigo_interno' => "002-0003",
            'categoria' => "002",
            'imagen' => "",
            'precio' => 300,
            'cantidad' => 200,
            'stock_critico' => 5,
        ]);

        Producto::create([
            'nombre' => "Camiseta de la UCN",
            'descripcion' => "Bonita camiseta XL",
            'codigo_barra' => "0670970010002",
            'codigo_interno' => "001-0002",
            'categoria' => "001",
            'imagen' => "",
            'precio' => 5000,
            'cantidad' => 20,
            'stock_critico' => 5,
        ]);

        // Crear Ventas
        Venta::create([
            'user_id' => 1,
            'producto_codigo_interno' => "002-0002",
            'cantidad' => 5
        ]);
        Venta::create([
            'user_id' => 1,
            'producto_codigo_interno' => "001-0001",
            'cantidad' => 4
        ]);
        Venta::create([
            'user_id' => 2,
            'producto_codigo_interno' => "002-0001",
            'cantidad' => 2
        ]);

        //Crear Entradas
        Entrada::create([
            'user_id' => 1,
            'producto_codigo_interno' => "002-0002",
            'cantidad' => 5
        ]);
        Entrada::create([
            'user_id' => 1,
            'producto_codigo_interno' => "001-0001",
            'cantidad' => 4
        ]);
        Entrada::create([
            'user_id' => 2,
            'producto_codigo_interno' => "002-0001",
            'cantidad' => 2
        ]);
    }
}
