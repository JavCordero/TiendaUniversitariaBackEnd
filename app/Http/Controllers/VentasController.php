<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\UsersController;

use App\Notifications\AlertaStockCritico;
use App\Models\Producto;
use App\Models\Venta;
use App\Http\Resources\VentaResource;

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
                    $producto->fecha_notificacion = Carbon::now();
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
        $ventas = Venta::all();
        return response(['response' => VentaResource::collection($ventas), 'message' => 'Recuperado exitosamente'], 200);

    }

    public function ventasTop(Request $request)
    {
        $limit= $request->get('limit');
        $ventas = DB::table('ventas')->select(DB::raw('SUM(ventas.cantidad) total, productos.codigo_interno, productos.nombre'))->groupBy('producto_codigo_interno')->join('productos', 'productos.codigo_interno', '=', 'ventas.producto_codigo_interno')->orderBy('total','desc')->limit($limit)->get();
        return response(['top' => $ventas, 'message' => 'Recuperado exitosamente'], 200);
    }

    public function ventasBottom(Request $request)
    {
        $limit= $request->get('limit');
        $ventas = DB::table('ventas')->select(DB::raw('SUM(ventas.cantidad) total, productos.codigo_interno, productos.nombre'))->groupBy('producto_codigo_interno')->join('productos', 'productos.codigo_interno', '=', 'ventas.producto_codigo_interno')->orderBy('total','asc')->limit($limit)->get();
        return response(['bottom' => $ventas, 'message' => 'Recuperado exitosamente'], 200);
    }

    public function ventasReportes(Request $request)
    {   
        $fechaActual= Carbon::now();
        $diaActual= $fechaActual->day;
        $mesActual= $fechaActual->month;
        $semanaActual= $fechaActual->week;
        $anioActual= $fechaActual->year;
        $anioInicio = $fechaActual->copy()->startOfYear();
        $anioFin = $fechaActual->copy()->endOfYear();
        $inicio1erSemestre = $fechaActual->copy()->startOfYear();//2021-01-01 00:00:00.000000
        $fin1erSemestre = $fechaActual->copy()->startOfYear()->addMonths(6)->subSeconds(1);//2021-06-30 23:59:59.000000
        $inicio2doSemestre = $fechaActual->copy()->startOfYear()->addMonths(6); //2021-07-01 00:00:00.000000
        $fin2doSemestre = $fechaActual->copy()->endOfYear();//2021-12-31 23:59:59.999999
        // Se puede utilizar este otro método para obtener el inicio y fin de los semestres
        // $fechaPrueba = Carbon::parse("1800-01-01 00:00:00.000000");
        // $fechaPrueba->year= Carbon::now()->year;
        
        $filtro= $request->get('filtro');

        if($filtro=="diario"){
            $grafico= DB::table("ventas")->select(DB::raw('users.name nombre, SUM(ventas.cantidad) cantidad'))->whereDay('fecha', '=' ,$diaActual)->whereMonth('fecha', '=', $mesActual)->whereYear('fecha', '=', $anioActual)->groupBy('user_id')->join('users', 'users.id', '=', 'ventas.user_id')->get();
            
            $tabla= DB::table("ventas")->select(DB::raw('users.name nombre_usuario, productos.nombre nombre_producto, productos.codigo_interno codigo_interno, ventas.cantidad cantidad,productos.precio precio, ventas.fecha fecha'))->whereDay('fecha', '=' ,$diaActual)->whereMonth('fecha', '=', $mesActual)->whereYear('fecha', '=', $anioActual)->join('users', 'users.id', '=', 'ventas.user_id')->join('productos', 'productos.codigo_interno', '=', 'ventas.producto_codigo_interno')->get();
            
            return response(['grafico' => $grafico, 'tabla'=> $tabla,'message' => 'Recuperado exitosamente'], 200);

        }else if($filtro=="semanal"){
            $grafico= DB::table("ventas")->select(DB::raw('users.name nombre, SUM(ventas.cantidad) cantidad'))->whereBetween('fecha',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->groupBy('user_id')->join('users', 'users.id', '=', 'ventas.user_id')->get();
            
            $tabla= DB::table("ventas")->select(DB::raw('users.name nombre_usuario, productos.nombre nombre_producto, productos.codigo_interno codigo_interno, ventas.cantidad cantidad,productos.precio precio, ventas.fecha fecha'))->whereBetween('fecha',[Carbon::now()->startOfWeek(), Carbon::now()->endOfweek()])->join('users', 'users.id', '=', 'ventas.user_id')->join('productos', 'productos.codigo_interno', '=', 'ventas.producto_codigo_interno')->get();
            
            return response(['grafico' => $grafico, 'tabla'=> $tabla,'message' => 'Recuperado exitosamente'], 200);

        }else if($filtro=="mensual"){

            $grafico= DB::table("ventas")->select(DB::raw('users.name nombre, SUM(ventas.cantidad) cantidad'))->whereMonth('fecha', '=' ,$mesActual)->whereYear('fecha', '=', $anioActual)->groupBy('user_id')->join('users', 'users.id', '=', 'ventas.user_id')->get();
            
            $tabla= DB::table("ventas")->select(DB::raw('users.name nombre_usuario, productos.nombre nombre_producto, productos.codigo_interno codigo_interno, ventas.cantidad cantidad,productos.precio precio, ventas.fecha fecha'))->whereMonth('fecha', '=' ,$mesActual)->whereYear('fecha', '=', $anioActual)->join('users', 'users.id', '=', 'ventas.user_id')->join('productos', 'productos.codigo_interno', '=', 'ventas.producto_codigo_interno')->get();
            
            return response(['grafico' => $grafico, 'tabla'=> $tabla,'message' => 'Recuperado exitosamente'], 200);

        }else if($filtro=="semestral"){

            if($fechaActual < $anioInicio->addMonths(6)){

                $grafico= DB::table("ventas")->select(DB::raw('users.name nombre, SUM(ventas.cantidad) cantidad'))->whereBetween('fecha',[$inicio1erSemestre,$fin1erSemestre])->groupBy('user_id')->join('users', 'users.id', '=', 'ventas.user_id')->get();
        
                $tabla= DB::table("ventas")->select(DB::raw('users.name nombre_usuario, productos.nombre nombre_producto, productos.codigo_interno codigo_interno, SUM(ventas.cantidad) cantidad,productos.precio precio, ventas.fecha fecha'))->whereBetween('fecha',[$inicio1erSemestre,$fin1erSemestre])->groupBy('ventas.fecha')->join('users', 'users.id', '=', 'ventas.user_id')->join('productos', 'productos.codigo_interno', '=', 'ventas.producto_codigo_interno')->get();
            
                return response(['grafico' => $grafico, 'tabla'=> $tabla,'message' => 'Recuperado exitosamente'], 200);

            }else{

                $grafico= DB::table("ventas")->select(DB::raw('users.name nombre, SUM(ventas.cantidad) cantidad'))->whereBetween('fecha',[$inicio2doSemestre,$fin2doSemestre])->groupBy('user_id')->join('users', 'users.id', '=', 'ventas.user_id')->get();

                $tabla= DB::table("ventas")->select(DB::raw('users.name nombre_usuario, productos.nombre nombre_producto, productos.codigo_interno codigo_interno, SUM(ventas.cantidad) cantidad,productos.precio precio, ventas.fecha fecha'))->whereBetween('fecha',[$inicio2doSemestre,$fin2doSemestre])->groupBy('ventas.fecha')->join('users', 'users.id', '=', 'ventas.user_id')->join('productos', 'productos.codigo_interno', '=', 'ventas.producto_codigo_interno')->get();

                return response(['grafico' => $grafico, 'tabla'=> $tabla,'message' => 'Recuperado exitosamente'], 200);
            }
        

        }else if($filtro=="anual"){

            $grafico= DB::table("ventas")->select(DB::raw('users.name nombre, SUM(ventas.cantidad) cantidad'))->whereYear('fecha', '=' ,$anioActual)->groupBy('user_id')->join('users', 'users.id', '=', 'ventas.user_id')->get();
            
            $tabla= DB::table("ventas")->select(DB::raw('users.name nombre_usuario, productos.nombre nombre_producto, productos.codigo_interno codigo_interno, SUM(ventas.cantidad) cantidad,productos.precio precio, ventas.fecha fecha'))->whereYear('fecha', '=' ,$anioActual)->groupBy('ventas.fecha')->join('users', 'users.id', '=', 'ventas.user_id')->join('productos', 'productos.codigo_interno', '=', 'ventas.producto_codigo_interno')->get();
            
            return response(['grafico' => $grafico, 'tabla'=> $tabla,'message' => 'Recuperado exitosamente'], 200);
    
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
