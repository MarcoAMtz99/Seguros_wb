<?php

namespace App\Http\Controllers;

use App\CP;
use Illuminate\Http\Request;

class CPController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  \App\CP  $cP
     * @return \Illuminate\Http\Response
     */
    public function get($cp)
    {
        //
        $codigo_postal = CP::where('codigo_postal',$cp)->get();
        // dd( $codigo_postal);
        if(count($codigo_postal) !=0){
            return response()->json(['response'=>$codigo_postal],200);
        }
        else{
            return response()->json(['error'=>'codigo postal no encontrado, verifique y agregue uno valido'],404);
        }
    }
}
