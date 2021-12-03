<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrada;
use App\Http\Resources\EntradaResource;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class EntradasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entradas = Entrada::all();
        return response(['entradas' => EntradaResource::collection($entradas), 'message' => 'Recuperado exitosamente'], 200);
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

    public function entradasReportes()
    {
        $entradas= DB::table('entradas')->select(DB::raw('productos.codigo_interno codigo_interno, productos.nombre nombre, entradas.cantidad cantidad, entradas.fecha fecha'))->join('productos', 'productos.codigo_interno', '=', 'entradas.producto_codigo_interno')->get();
        return response(['response' => $entradas, 'message' => 'Recuperado exitosamente'], 200);
    }

    public function entradasReportesFiltro(Request $request)
    {   
        $fechaActual= Carbon::now();
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

        if($filtro=="semestral"){

            if($fechaActual < $anioInicio->addMonths(6)){

                $grafico= DB::table("entradas")->select(DB::raw('users.name nombre, SUM(entradas.cantidad) cantidad'))->whereBetween('fecha',[$inicio1erSemestre,$fin1erSemestre])->groupBy('user_id')->join('users', 'users.id', '=', 'entradas.user_id')->get();
        
                $tabla= DB::table("entradas")->select(DB::raw('users.name nombre_usuario, productos.nombre nombre_producto, productos.codigo_interno codigo_interno, SUM(entradas.cantidad) cantidad,productos.precio precio, entradas.fecha fecha'))->whereBetween('fecha',[$inicio1erSemestre,$fin1erSemestre])->groupBy('entradas.fecha')->join('users', 'users.id', '=', 'entradas.user_id')->join('productos', 'productos.codigo_interno', '=', 'entradas.producto_codigo_interno')->get();
            
                return response(['grafico' => $grafico, 'tabla'=> $tabla,'message' => 'Recuperado exitosamente'], 200);

            }else{

            $grafico= DB::table("entradas")->select(DB::raw('users.name nombre, SUM(entradas.cantidad) cantidad'))->whereBetween('fecha',[$inicio2doSemestre,$fin2doSemestre])->groupBy('user_id')->join('users', 'users.id', '=', 'entradas.user_id')->get();

            $tabla= DB::table("entradas")->select(DB::raw('users.name nombre_usuario, productos.nombre nombre_producto, productos.codigo_interno codigo_interno, SUM(entradas.cantidad) cantidad,productos.precio precio, entradas.fecha fecha'))->whereBetween('fecha',[$inicio2doSemestre,$fin2doSemestre])->groupBy('entradas.fecha')->join('users', 'users.id', '=', 'entradas.user_id')->join('productos', 'productos.codigo_interno', '=', 'entradas.producto_codigo_interno')->get();

            return response(['grafico' => $grafico, 'tabla'=> $tabla,'message' => 'Recuperado exitosamente'], 200);

            }

        }else if($filtro=="anual"){

            $grafico= DB::table("entradas")->select(DB::raw('users.name nombre, SUM(entradas.cantidad) cantidad'))->whereYear('fecha', '=' ,$anioActual)->groupBy('user_id')->join('users', 'users.id', '=', 'entradas.user_id')->get();
            
            $tabla= DB::table("entradas")->select(DB::raw('users.name nombre_usuario, productos.nombre nombre_producto, productos.codigo_interno codigo_interno, SUM(entradas.cantidad) cantidad,productos.precio precio, entradas.fecha fecha'))->whereYear('fecha', '=' ,$anioActual)->groupBy('entradas.fecha')->join('users', 'users.id', '=', 'entradas.user_id')->join('productos', 'productos.codigo_interno', '=', 'entradas.producto_codigo_interno')->get();
            
            return response(['grafico' => $grafico, 'tabla' => $tabla,'message' => 'Recuperado exitosamente'], 200);
    
        }else{

        }
    }

}
