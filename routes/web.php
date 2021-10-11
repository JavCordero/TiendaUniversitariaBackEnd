<?php

use Illuminate\Support\Facades\Route;

// importamos los modelos
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// obtener todos los productos en JSON
// http://127.0.0.1:8000/productos
Route::get('/productos', function () {
    $productos = Producto::get();
    return $productos;
});

// obtener todos los usuarios en JSON
// http://127.0.0.1:8000/usuarios
Route::get('/usuarios', function () {
    $usuarios = User::get();
    return $usuarios;
});

Route::get('/asdf', function () {
    $asdf = User::findOrFail("1");
    return json_decode($asdf->ventas);
});

Route::get('/qwer', function () {
    $qwer = Producto::findOrFail("111111");
    return json_decode($qwer->ventas);
});
