<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Colonias;


class ColoniasController extends Controller
{
    //
         public function getColonias($cp){

         	$Colonia_desc = Colonias::where('cp',$cp)->get();
		         if(count($Colonia_desc) !=0){
		            return response()->json(['response'=>$Colonia_desc],200);
		        }
		        else{
		            return response()->json(['error'=>'codigo postal no encontrado, verifique y agregue uno valido'],404);
		        }

         	
    }
}
