<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\UsersController;

use App\Notifications\AlertaStockCritico;
use App\Models\Producto;
use App\Models\Venta;

class VentasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ventasMasiva(Request $request)
    {
        //Obtener Administradores
        $administradores = UsersController::obtenerAdministradores();

        //Recibe el JSON
        $productos = $request->all();

        //respuesta
        $response = [];

        //Se hace reduccion de stock
        foreach($productos as $productosJson){

            $codigoProducto = $productosJson["codigo_interno"];
            $cantidad = $productosJson["cantidad"];

            $cantidadPreVenta = $cantidad;

            $producto = Producto::find($codigoProducto);

            $cantidadPostVenta = $producto->cantidad-$cantidad;

            //Notifica a los admins a traves de correo y base de datos
            if($cantidadPostVenta >= 0){

                $producto->cantidad = $cantidadPostVenta;
                $json = ["nombre" => $producto->nombre, "message" => "Venta exitosa"];

                array_push($response,$json);

                if($cantidadPostVenta <= $producto->stock_critico){ //falta el && notificated == 0
                    Notification::send($administradores, new AlertaStockCritico($producto));
                }

                $venta = Venta::create([
                    'user_id' => auth()->user()->id,
                    'producto_codigo_interno' => $codigoProducto,
                    'cantidad' => $cantidadPreVenta,
                    'fecha' => DB::raw('CURRENT_TIMESTAMP'),
                ]);

                $producto->save();

            }

            else{
                $json = ["nombre" => $producto->nombre, "message" => "No se pudo vender la cantidad de {$cantidad} unidades de {$producto->nombre}"];
                array_push($response,$json);
            }
            
        }

        return response($response,200);
        // return response(["message" => "hola uwu"],200);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
