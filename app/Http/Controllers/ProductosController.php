<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// importamos los Facades
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

// importamos el modelo Producto
use App\Models\Producto;
use App\Notifications\AlertaStockCritico;
use App\Http\Controllers\UsersController;
use App\Models\Entrada;
use App\Models\Venta;


// importamos la capa de transformacion de Producto
// razon: transformar de manera fácil y expresiva sus modelos y colecciones de modelos en JSON.
use App\Http\Resources\ProductoResource;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos = Producto::all();
        return response(['productos' => ProductoResource::collection($productos), 'message' => 'Recuperado exitosamente'], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'nombre' => 'required',
            'descripcion' => 'nullable',
            'codigo_interno' => 'required',
            'codigo_barra' => 'required',
            'categoria' => 'required',
            'imagen' => 'nullable',
            'precio' => 'required|numeric',
            'cantidad' => 'required|numeric',
            'stock_critico' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'message' => 'Error de validacion'], 400); // OJITO: revisar codigo de respuesta http
        } else {
            $producto = Producto::create($data);
            return response(['producto' => new ProductoResource($producto), 'message' => 'Creado exitosamente'], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // devuelve el primer registro encontrado en la db. Si no existe retorna un null.
        $producto = Producto::find($id);
        if ($producto != null) {
            return response(['producto' => new ProductoResource($producto), 'message' => 'Recuperado exitosamente'], 200);
        } else {
            return response(['error' => 'Producto no encontrado'], 400); // OJITO: revisar codigo de respuesta http
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       
        $data = $request->all();

        // Validaciones https://laravel.com/docs/8.x/validation
        $validator = Validator::make($data, [
            'nombre' => 'required',
            'descripcion' => 'nullable',
            'codigo_interno' => 'required',
            'codigo_barra' => 'required',
            'categoria' => 'required',
            'imagen' => 'nullable',
            'precio' => 'required|numeric',
            'cantidad' => 'required|numeric',
            'stock_critico' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Error de validacion'], 400); // OJITO: revisar codigo de respuesta http
        }

        $producto = Producto::find($request->id);

        if ($producto != null) {
             //Obtener Administradores
            $administradores = UsersController::obtenerAdministradores();
            
            $producto->update($request->all());

            if($producto->cantidad <= $producto->stock_critico){
                Notification::send($administradores, new AlertaStockCritico($producto));
            }
            
            $entrada = Entrada::create([
                'user_id' => auth()->user()->id,
                'producto_codigo_interno' => $producto->codigo_interno,
                'cantidad' => $producto->cantidad,
                'fecha' => DB::raw('CURRENT_TIMESTAMP'),
            ]);
            
            return response(['producto' => new ProductoResource($producto), 'message' => 'Actualizado exitosamente'], 201);
        } else {
            return response(['error' => 'Producto no encontrado'], 400); // OJITO: revisar codigo de respuesta http
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // OJITO: en modo producion cambiar esta funcion.
    public function destroy($id)
    {
        $producto = Producto::find($id);

        if ($producto != null) {
            $producto->delete();
            return response(['message' => "Eliminado exitosamente"], 200);
        } else {
            return response(['error' => 'Producto no encontrado'], 400); // OJITO: revisar codigo de respuesta http
        }
    }

    public function correlativoCategorias($categoria){
        $codigoProducto= Producto::where('categoria', $categoria)->max('codigo_interno');
        if($codigoProducto == null){
            return response([0], 200);
        }else{
            $ultimaCategoria= explode('-', $codigoProducto)[1];
            return response([$ultimaCategoria], 200);
        }
    }

    public function stockCriticos(){
        $productos= Producto::all();
        
    }

    public function identificacionProductos()
    {
        $productos = Producto::all();
        $arreglo= [];
        
        foreach($productos as $producto){
            array_push($arreglo, $producto['codigo_interno']);
        }
        return response(['productos' => ($arreglo), 'message' => 'Recuperado exitosamente'], 200);
       
    }
}
