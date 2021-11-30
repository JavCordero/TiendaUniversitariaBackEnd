<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// importamos los controladores
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\EntradasController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Hay un middleware que verifica el rol del usuario.
Roles:
    - administrador
    - vendedor
Ej:
    ->middleware(['auth:api', 'scope:administrador,vendedor']);
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// ENDPOINTS
// EJ: http://127.0.0.1:8000/api/productos/123

Route::post('/register', [AuthController::class, 'register'])->middleware(['auth:api', 'scope:administrador']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout'])->middleware(['auth:api']);
Route::get('user', [AuthController::class, 'user'])->middleware(['auth:api']);

// Usuarios
Route::get('users/{id}', [UsersController::class, 'show'])->middleware(['auth:api']);

// Route::group([
//     'middleware' => ['auth:api', 'rol']
// ], function () {
//     // Route::get('logout', [AuthController::class, 'logout']);
//     // Route::get('user', [AuthController::class, 'user']);

// });

// Producto
Route::get('/productos', [ProductosController::class, 'index'])->middleware(['auth:api', 'scope:administrador']);
Route::get('/productos/{id}', [ProductosController::class, 'show']);
Route::post('/productos', [ProductosController::class, 'store']);
Route::put('/productos/{id}', [ProductosController::class, 'update']);
Route::delete('/productos/{id}', [ProductosController::class, 'destroy']);
Route::get('/productos/stock-criticos', [ProductosController::class, 'stockCriticos'])->middleware(['auth:api', 'scope:administrador']);

//Ventas
Route::get('/ventas', [VentasController::class, 'index'])->middleware(['auth:api', 'scope:administrador']);
// http://127.0.0.1:8000/api/ventas/reportes?tipo=semana
Route::get('/ventas/reportes{filtro?}', [VentasController::class, 'ventasReportes'])->middleware(['auth:api', 'scope:administrador']);
Route::get('/ventas/top{limit?}', [VentasController::class, 'ventasTop'])->middleware(['auth:api', 'scope:administrador']);
Route::post('/ventas/masiva', [VentasController::class, 'ventasMasiva'])->middleware(['auth:api', 'scope:vendedor']);
Route::get('/correlativo-categorias/{categoria}', [ProductosController::class, 'correlativoCategorias'])->middleware(['auth:api', 'scope:administrador']);

//Entrada
Route::get('/entradas/reportes', [EntradasController::class, 'entradasReportes'])->middleware(['auth:api', 'scope:administrador']);
Route::get('/entradas', [EntradasController::class, 'index'])->middleware(['auth:api', 'scope:administrador']);


// Route::group([
//     'prefix' => 'auth'
// ], function () {
//     Route::post('/register', [AuthController::class, 'register']);
//     Route::post('/login', [AuthController::class, 'login']);

//     Route::group([
//         'middleware' => 'auth:api'
//     ], function () {
//         Route::get('logout', [AuthController::class, 'logout']);
//         Route::get('user', [AuthController::class, 'user']);
//     });
// });
