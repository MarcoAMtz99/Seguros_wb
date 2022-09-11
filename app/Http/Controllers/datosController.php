<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class recibirController extends Controller
{
    //
    public function index(Request $request){
        $descuento = $request->descuento;
        $uso = $request->uso;
        $fecha = $request->fecha;
        $modelo = $request->modelo;
        $cp = $request->cotizacion;
        $genero = $request->genero;

        return view('index', compact('descuento','uso','fecha','modelo','cp','genero'));
    }
}