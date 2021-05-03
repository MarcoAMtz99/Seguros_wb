<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Auto;
use App\Marca;
use App\Submarca;
use App\Version;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    
    /**
     * Guarda un nuevo cliente, un auto, marca y submarca para la
     * Cotizacion que hizo el cliente
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response $cliente
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $rules=[
            'uso_auto'      => 'required',
            'marca_auto'    => 'required|array',
            'submarca_auto' => 'required|array',
            'modelo_auto'   => 'required|numeric',
            'cp'            => 'required',
            'cestado'       => 'required',
            'nombre'        => 'required|string',
            'appaterno'     => 'required|string',
            'apmaterno'     => 'nullable|string',
            'telefono'      => 'required|numeric',
            'email'         => 'required|email',
            'sexo'          => 'required|in:Hombre,Mujer,Otro',
            'f_nac'         => 'required|date'

        ];
        $this->validate($request,$rules);
        // return $request->all();

        $cliente = Cliente::create([
            "uso_auto"         => $request->uso_auto,
            'cp'               => $request->cp,
            'cestado'          => $request->cestado,
            'nombre'           => $request->nombre,
            'appaterno'        => $request->appaterno,
            'apmaterno'        => $request->apmaterno,
            'telefono'         => $request->telefono,
            'email'            => $request->email,
            'sexo'             => $request->sexo,
            'f_nac'            => $request->f_nac,
            'ana'              => $request->ana,
            'gs'               => $request->gs,
            'qualitas'         => $request->qualitas,
            'gnp'              => $request->gnp,
            'ejecutivo'        => $request->ejecutivo,
            'codigo_descuento' => $request->codigo_descuento

        ]);

        $auto = new Auto();
        $cliente->auto()->save($auto);

        $marca = new Marca([
            'id_ana'      => $request->marca_auto['id'],
            'descripcion' => $request->marca_auto['descripcion']
        ]);

        $auto->marca()->save($marca);

        $submarca=new Submarca([
            "id_ana"      => $request->submarca_auto['id'],
            "descripcion" => $request->submarca_auto['descripcion'],
            "id_seg_gs"   => "1",
            "anio"        => $request->modelo_auto,
        ]);
        $auto->submarca()->save($submarca);

        $cliente->cotizacion = $cliente->generarCotizacion();
        $cliente->save();
        $cliente->auto;
        $cliente->auto->marca;
        $cliente->auto->submarca;
        //$cliente->emailCotizacion();
        return response()->json(['cotizacion'=>$cliente],201);
    }

    public function sendEmail(Request $request)
    {
        dd($request);
        $cliente = $request->cliente;
        $cliente = Cliente::where('cotizacion', $cliente['cotizacion'])->first();
        $cotizacion = $request->cotizacion;
        $cliente->emailCotizacion($cotizacion, $request->aseguradora);
        dd($request->aseguradora);
    }

    public function search(Request $request){
        $cliente = Cliente::where('cotizacion',$request->cotizacion)->with(['auto','auto.marca','auto.submarca','auto.version'])->first();
        if($cliente != null){
            // $cliente->;
            return response()->json(['cotizacion'=>$cliente],200);
        }
        else{
            return response()->json(['error'=>'Cotización no encontrada'],404);
        }
    }
//1GNCS13Z6M0246591
}
