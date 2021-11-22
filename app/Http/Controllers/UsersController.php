<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
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

    public static function obtenerAdministradores(){
        $administradores = User::get()->where("rol","administrador");
        return $administradores;
    }

    public function obtenerNotificaciones(){
        
        $usuario = auth()->user();

        //leo las notificaciones
        $usuario->unreadNotifications->markAsRead();

        $notificacionesActivas = [];

        //muestro solo las que requiera el usuario
        foreach ($usuario->notifications as $notificacion) {
            if($notificacion->notificated == 1){
                array_push($notificacionesActivas,$notificacion);
            }
        }

        return response($notificacionesActivas,200);
    }

    public function eliminarNotificaciones($id){
        $usuario = auth()->user();

        $notificacion = $usuario->notifications()->find($id);
        $notificacion->notificated = 0;

        $notificacion->save();

        return response(["message" => "notificacion eliminada"],200);
    }

    public function obtenerNotificacionesNoLeidas(){
        return response(["cantidad" => count(auth()->user()->unreadNotifications)],200);
    }

}
