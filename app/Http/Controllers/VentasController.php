<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

use App\Http\Controllers\UsersController;

use App\Notifications\AlertaStockCritico;
use App\Models\Producto;
use App\Models\Venta;
use App\Http\Resources\VentaResource;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

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

                $producto->save();

            }

            else{
                $json = ["nombre" => $producto->nombre, "message" => "No se pudo vender la cantidad de {$cantidad} unidades de {$producto->nombre}"];
                array_push($response,$json);
            }
            
        }

        return response($response,200);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ventas = Venta::all();
        return response(['response' => VentaResource::collection($ventas), 'message' => 'Recuperado exitosamente'], 200);

    }

    public function ventasTop(Request $request)
    {
        $limit= $request->get('limit');
        $ventas = DB::table('ventas')->select(DB::raw('SUM(ventas.cantidad) total, productos.codigo_interno, productos.nombre'))->groupBy('producto_codigo_interno')->join('productos', 'productos.codigo_interno', '=', 'ventas.producto_codigo_interno')->limit($limit)->get();
        return response(['response' => $ventas, 'message' => 'Recuperado exitosamente'], 200);
    }

    public function ventasReportes(Request $request)
    {   
        $fechaActual= Carbon::now();
        $diaActual= $fechaActual->day;
        $mesActual= $fechaActual->month;
        $semanaActual= $fechaActual->week;
        $anioActual= $fechaActual->year;

        $filtro= $request->get('filtro');

        if($filtro=="diario"){
            $grafico= DB::table("ventas")->select(DB::raw('users.name nombre, SUM(ventas.cantidad) cantidad'))->whereDay('fecha', '=' ,$diaActual)->whereMonth('fecha', '=', $mesActual)->whereYear('fecha', '=', $anioActual)->groupBy('user_id')->join('users', 'users.id', '=', 'ventas.user_id')->get();
            
            $tabla= DB::table("ventas")->select(DB::raw('users.name nombre_usuario, productos.nombre nombre_producto, productos.codigo_interno codigo_interno, SUM(ventas.cantidad) cantidad'))->whereDay('fecha', '=' ,$diaActual)->whereMonth('fecha', '=', $mesActual)->whereYear('fecha', '=', $anioActual)->groupBy('ventas.producto_codigo_interno')->join('users', 'users.id', '=', 'ventas.user_id')->join('productos', 'productos.codigo_interno', '=', 'ventas.producto_codigo_interno')->get();
            
            return response(['grafico' => $grafico, 'tabla'=> $tabla,'message' => 'Recuperado exitosamente'], 200);

        }else if($filtro=="semanal"){
            $respuesta= DB::table("ventas")->select(DB::raw('users.name nombre, SUM(ventas.cantidad) cantidad'))->whereDay('fecha', '=' ,$diaActual)->whereWeek('fecha', '=', $semanaActual)->whereYear('fecha', '=', $anioActual)->groupBy('user_id')->join('users', 'users.id', '=', 'ventas.user_id')->get();
            return response(['response' => $respuesta, 'message' => 'Recuperado exitosamente'], 200);

        }else if($filtro=="mensual"){
            $respuesta= DB::table("ventas")->select(DB::raw('users.name nombre, SUM(ventas.cantidad) cantidad'))->whereDay('fecha', '=' ,$diaActual)->whereMonth('fecha', '=', $mesActual)->whereYear('fecha', '=', $anioActual)->groupBy('user_id')->join('users', 'users.id', '=', 'ventas.user_id')->get();
            return response(['response' => $respuesta, 'message' => 'Recuperado exitosamente'], 200);
        }else{

        }
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
