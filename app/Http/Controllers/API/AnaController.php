<?php

namespace App\Http\Controllers\API;

use App\CP;
use App\Cliente;
use App\Http\Controllers\Controller;
use App\Services\Ana\EmitirPolizaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SoapClient;

class AnaController extends Controller
{

    protected $opts,$params,$url,$urlPHP,$cliente;

    public function __construct()
    {
        $this->opts=array(
            'http'=>array(
                'header'=>'Content-Type:application/xml;charset=utf-8',
                'user_agent' => 'PHPSoapClient'
            )
        );
        $this->params = array(
            'encoding'=>'UTF-8',
            'verifypeer'=>false,
            'verifyhost'=>false,
            'soap_version'=>SOAP_1_1,
            'trace'=>1,
            'exceptions'=>1,
            'connection_timeout'=>5000,
            'stream_context'=>stream_context_create($this->opts)
        );
        $this->url="https://server.anaseguros.com.mx/ananetws/service.asmx?wsdl";
        $this->urlPHP ="https://server.anaseguros.com.mx/ananetws/servicetext.asmx?wsdl";
    }

    protected function convertXMLtoArray($xmlelements)
    {
        $array = [];
        foreach ($xmlelements as $value) {
            $object = json_decode(json_encode($value),true);
            array_push($array,['id'=>$object['@attributes']['id'],'descripcion'=>$object[0]]);
        }
        return $array;
    }

    public function imprimirPoliza($poliza,$endoso,$tipo_pago){
        if ($tipo_pago == "Tarjeta") {
            $xmlImpr=<<<XML
<transacciones xmlns="">
    <transaccion version="1" tipotransaccion="I" negocio="1196">
        <poliza id="$poliza" endoso="$endoso" inciso="1" link=""/>
        <error/>
    </transaccion>
</transacciones>
XML;
        }
        else{
            $xmlImpr=<<<XML
<transacciones xmlns="">
    <transaccion version="1" tipotransaccion="I" negocio="1195">
        <poliza id="$poliza" endoso="$endoso" inciso="1" link=""/>
        <error/>
    </transaccion>
</transacciones>
XML;

        }
        try{
            $client = new SoapClient($this->urlPHP,$this->params);
            $resImp =$client->TransaccionText(["XML"=>$xmlImpr,"Tipo"=>"Impresion","Usuario"=>"14275","Clave"=>"kdEDyC9F"]);
            // dd($res);
            $array= json_decode(json_encode(simplexml_load_string($resImp->TransaccionTextResult)),true);
            return $array['transaccion']['poliza']['@attributes'];

        }
        catch(SoapFault $fault){
            dd($fault);            
        }
    }

    public function emitirPoliza(Request $request){
        // dd($request);
        $emitirPolizaService = new EmitirPolizaService($request);
        return $emitirPolizaService->response();
    }

    public function bancos(){
        try{
            $client = new SoapClient($this->url,$this->params);
            $bancosXML = $client->Bancos(["Negocio"=>"1195","Usuario"=>"14275","Clave"=>"kdEDyC9F"]);
            $bancos= $this->convertXMLtoArray(simplexml_load_string($bancosXML->BancosResult)->bancos);
            if (isset($bancos)) {
                // $bancos = $bancosResp['bancos'];
                return response()->json(['bancos'=>$bancos],201);
            }
            else{
                return response()->json(['error'=>"Bancos no encontrados"],404);
            }
        }catch(SoapFault $fault){
            dd($fault);
        }
    }

    public function categorias()
    {
        try{
            $client = new SoapClient($this->url,$this->params);
            $categoriasXML = $client->Categoria(["Negocio"=>"1195","Usuario"=>"14275","Clave"=>"kdEDyC9F"]);
            dd(simplexml_load_string($categoriasXML->CategoriaResult));
            // if (isset($categoriasResp['categorias'])) {
            //     $categorias = $categoriasResp['categorias'];
            //     return response()->json(['categorias'=>$categorias],201);
            // }
            // else{
            //     return response()->json(['error'=>"Categorias no encontradas"],404);
            // }
        }catch(SoapFault $fault){
            dd($fault);
        }
    }
    public function codigoPostales($estado,$ciudad)
    {
        try{
            $client = new SoapClient($this->url,$this->params);
            $codigoPostalXML = $client->CodigoPostal(["Estado"=>$estado,"Ciudad"=>$ciudad,"Negocio"=>"1195","Usuario"=>"14275","Clave"=>"kdEDyC9F"]);
            // dd(simplexml_load_string($codigoPostalXML->CodigoPostalResult)->cp);
            $codigoPostal= $this->convertXMLtoArray(simplexml_load_string($codigoPostalXML->CodigoPostalResult)->cp);
            if (isset($codigoPostal)) {
                return response()->json(['Codigo Postal'=>$codigoPostal],201);
            }
            else{
                return response()->json(['error'=>"Codigo Postales no encontrados"],404);
            }
        }catch(SoapFault $fault){
            dd($fault);
        }
    }
    public function colonias($estado,$ciudad,$cp)
    {
        try{
            $client = new SoapClient($this->url,$this->params);
            $coloniaXML = $client->Colonia(["Estado"=>$estado,"Ciudad"=>$ciudad,"CP"=>$cp,"Negocio"=>"1195","Usuario"=>"14275","Clave"=>"kdEDyC9F"]);
            // dd(simplexml_load_string($coloniaXML->ColoniaResult)->colonia);
            $colonias=$this->convertXMLtoArray(simplexml_load_string($coloniaXML->ColoniaResult)->colonia);
            if (isset($colonias)) {
                return response()->json(['colonias'=>$colonias],201);
            }
            else{
                return response()->json(['error'=>"Colonias no encontradas"],404);
            }
        }catch(SoapFault $fault){
            dd($fault);
        }
    }
    public function colores()
    {
        try{
            $client = new SoapClient($this->url,$this->params);
            $colorXML = $client->Color(["Negocio"=>"1195","Usuario"=>"14275","Clave"=>"kdEDyC9F"]);
            // dd(simplexml_load_string($colorXML->ColorResult)->color);
            $colores=$this->convertXMLtoArray(simplexml_load_string($colorXML->ColorResult)->color);
            if (isset($colores)) {
                return response()->json(['colores'=>$colores],201);
            }
            else{
                return response()->json(['error'=>"Colores no encontrados"],404);
            }
        }catch(SoapFault $fault){
            dd($fault);
        }
    }
    public function delMuns($estado)
    {
        try{
            $client = new SoapClient($this->url,$this->params);
            $municipioXML = $client->DelMun(["Estado"=>$estado,"Negocio"=>"1195","Usuario"=>"14275","Clave"=>"kdEDyC9F"]);
            // dd(simplexml_load_string($municipioXML->DelMunResult)->delmun);
            $municipios=$this->convertXMLtoArray(simplexml_load_string($municipioXML->DelMunResult)->delmun);
            if (isset($municipios)) {
                return response()->json(['municipios'=>$municipios],201);
            }
            else{
                return response()->json(['error'=>"Municipios no encontrados"],404);
            }
        }catch(SoapFault $fault){
            dd($fault);
        }
    }
    public function estados()
    {
        try{
            $client = new SoapClient($this->url,$this->params);
            $estadosXML = $client->EDOS(["Negocio"=>"1195","Usuario"=>"14275","Clave"=>"kdEDyC9F"]);
            $estados=$this->convertXMLtoArray(simplexml_load_string($estadosXML->EDOSResult)->estado);
            if (isset($estados)) {
                return response()->json(['estados'=>$estados],201);
            }
            else{
                return response()->json(['error'=>"Estados no encontrados"],404);
            }
        }catch(SoapFault $fault){
            dd($fault);
        }
    }
    public function formaPagos()
    {
        try{
            $client = new SoapClient($this->url,$this->params);
            $formaPagoXML = $client->FormaPago(["Negocio"=>"1195","Usuario"=>"14275","Clave"=>"kdEDyC9F"]);
            $formapagos=$this->convertXMLtoArray(simplexml_load_string($formaPagoXML->FormaPagoResult)->formapago);
            if (isset($formapagos)) {
                return response()->json(['formapagos'=>$formapagos],201);
            }
            else{
                return response()->json(['error'=>"Forma de pagos no encontrados"],404);
            }
        }catch(SoapFault $fault){
            dd($fault);
        }
    }
    public function giros()
    {
        try{
            $client = new SoapClient($this->url,$this->params);
            $giroXML = $client->GiroEmpresa(["Negocio"=>"1195","Usuario"=>"14275","Clave"=>"kdEDyC9F"]);
            $giros = $this->convertXMLtoArray(simplexml_load_string($giroXML->GiroEmpresaResult)->giroempresa);
            if (isset($giros)) {
                return response()->json(['giros'=>$giros],201);
            }
            else{
                return response()->json(['error'=>"Giros no encontrados"],404);
            }
        }catch(SoapFault $fault){
            dd($fault);
        }
    }
    public function identificaciones()
    {
        try{
            $client = new SoapClient($this->url,$this->params);
            $identificacionesXML = $client->Identificacion(["Negocio"=>"1195","Usuario"=>"14275","Clave"=>"kdEDyC9F"]);
            $identificaciones=$this->convertXMLtoArray(simplexml_load_string($identificacionesXML->IdentificacionResult)->identificiacion);
            if (isset($identificaciones)) {
                return response()->json(['identificaciones'=>$identificaciones],201);
            }
            else{
                return response()->json(['error'=>"Identificaciones no encontradas"],404);
            }
        }catch(SoapFault $fault){
            dd($fault);
        }
    }
    public function marcas($modelo)
    {
        try{
            $client = new SoapClient($this->url,$this->params);
            $marcasXML = $client->Marca(["Negocio"=>"1195","Modelo"=>$modelo,"Categoria"=>100,"Usuario"=>"14275","Clave"=>"kdEDyC9F"]);
            $marcas=$this->convertXMLtoArray(simplexml_load_string($marcasXML->MarcaResult)->marca);
            if (isset($marcas)) {
                return response()->json(['marcas'=>$marcas],201);
            }
            else{
                return response()->json(['error'=>"Marcas no encontradas"],404);
            }
        }catch(SoapFault $fault){
            dd($fault);
        }
    }
    public function modelos()
    {
        try{
            $client = new SoapClient($this->url,$this->params);
            $modelosXML = $client->Modelo(["Negocio"=>"1195","Usuario"=>"14275","Clave"=>"kdEDyC9F"]);
            $modelosResp = json_decode(json_encode(simplexml_load_string($modelosXML->ModeloResult)),true);
            // dd($modelosXML);
            if (isset($modelosResp['modelo'])) {
                $modelos = $modelosResp['modelo'];
                return response()->json(['modelos'=>$modelos],201);
            }
            else{
                return response()->json(['error'=>"Modelos no encontrados"],404);
            }
        }catch(SoapFault $fault){
            dd($fault);
        }
    }
   
    public function nacionalidades()
    {
        try{
            $client = new SoapClient($this->url,$this->params);
            $nacionalidadesXML = $client->Nacionalidad(["Negocio"=>"1195","Usuario"=>"14275","Clave"=>"kdEDyC9F"]);
            $xml = simplexml_load_string($nacionalidadesXML->NacionalidadResult)->nacionalidad;
            $nacionalidades =  $this->convertXMLtoArray($xml);
            if (isset($nacionalidades)) {
                return response()->json(['nacionalidades'=>$nacionalidades],201);
            }
            else{
                return response()->json(['error'=>"Nacionalidades no encontradas"],404);
            }
        }catch(SoapFault $fault){
            dd($fault);
        }
    }
    public function ocupaciones()
    {
        try{
            $client = new SoapClient($this->url,$this->params);
            $ocupacionesXML = $client->Ocupacion(["Negocio"=>"1195","Usuario"=>"14275","Clave"=>"kdEDyC9F"]);
            $xml=simplexml_load_string($ocupacionesXML->OcupacionResult)->ocupacion;
            $ocupaciones = $this->convertXMLtoArray($xml);
            // dd($ocupacionesResp);
            if (isset($ocupaciones)) {
                return response()->json(['ocupaciones'=>$ocupaciones],201);
            }
            else{
                return response()->json(['error'=>"Ocupaciones no encontradas"],404);
            }
        }catch(SoapFault $fault){
            dd($fault);
        }
    }
    public function subMarcas($marca,$modelo)
    {
        try{
            $client = new SoapClient($this->url,$this->params);
            $submarcasXML = $client->SubMarca(["Negocio"=>"1195","Marca"=>$marca,"Modelo"=>$modelo,"Categoria"=>100,"Usuario"=>"14275","Clave"=>"kdEDyC9F"]);
            // dd(simplexml_load_string($submarcasXML->SubMarcaResult)->submarca);
            $submarcas=[];
            foreach (simplexml_load_string($submarcasXML->SubMarcaResult)->submarca as $value) {
                $object=json_decode(json_encode($value),true);
                array_push($submarcas,['id'=>$object['@attributes']['clave'],'descripcion'=>$object[0]]);
            }
            if (isset($submarcas)) {
                return response()->json(['submarcas'=>$submarcas],201);
            }
            else{
                return response()->json(['error'=>"Sub-Marcas no encontradas"],404);
            }
        }catch(SoapFault $fault){
            dd($fault);
        }
    }
    public function searchMarca($marca,$modelo)
    {
        $marcajson = $this->marcas($modelo);
        $marcas = json_decode(json_encode($marcajson))->original->marcas;
        foreach ($marcas as $value) {
            if ($value->descripcion == $marca) {
                $marcaANA = $value;
            }
        }
        if ($marcaANA) {
            return $marcaANA;
        }
        else{
            return null;
        }
    }
    public function searchSubmarca($submarca,$marca_id,$modelo){
        $submarcasJSON=$this->subMarcas($marca_id, $modelo);
        $submarcas = json_decode(json_encode($submarcasJSON))->original->submarcas;
        $porc_piv=0;
        foreach ($submarcas as $value) {
            similar_text($submarca,$value->descripcion,$porcentaje);
            if($porcentaje>=$porc_piv){
                $porc_piv= $porcentaje;
                $submarcaANA=$value;
            }
        }
        if ($submarcaANA) {
            return $submarcaANA;
        } else {
            return null;
        }
    }
    public function searchVehiculo($descripcion,$marca_id,$submarca_id,$modelo)
    {
        // dd($descripcion." ".$marca_id." ".$submarca_id." ".$modelo);
        $descripcionesJSON=$this->vehiculo($marca_id, $submarca_id, $modelo);
        $descripciones = json_decode(json_encode($descripcionesJSON))->original->vehiculos;
        // $porcentajes =[];
        $porc_piv=0;
        foreach ($descripciones as $value) {
            similar_text($descripcion,$value->descripcion,$porcentaje);
            if($porcentaje>=$porc_piv){
                $porc_piv= $porcentaje;
                $descripcionANA=$value;
            }
            // dd($porcentaje);
            // array_push($porcentajes,['objeto'=>$value,'porcentaje'=>$porcentaje]);

        }
        // dd($porcentajes);
        // var_dump($descripciones);
        if($descripcionANA){
            return $descripcionANA;
        }
        else{
            return null;
        }

    }
    public function cotizacion(Request $request)
    {
        // dd($request->all());
        $cliente = Cliente::where('cotizacion',$request->cotizacion)->first();
        if($cliente == null){
            return response()->json(['error'=>"datos no encontrado"],404);

        }
        else{
            $marca = $cliente->auto->marca->id_ana;
            $submarca= $cliente->auto->submarca->id_ana;
            $modelo = $cliente->auto->submarca->anio;
            $descripcion= $request->descripcion;
            $poliza = $request->poliza;
            
            $planes=['1','3','4'];
            // dd($clave_amis);
            $pagosJSON=$this->formaPagos();
            $pagos = json_decode(json_encode($pagosJSON))->original->formapagos;
            $estadoANA=$cliente->cestado."001";
            $poblacion = CP::where('cestado',$cliente->cestado)->first()->estado;
            $fecha = Carbon::now();
            $fecha_hoy=$fecha->format('d/m/Y');
            // dd($fecha_hoy);
            $fecha_t = Carbon::parse($fecha);
            $fecha_t = $fecha_t->addYears(1)->format('d/m/Y');
            // dd($fecha_t);
            // dd($poblacion);
            $respuestas=[];
            foreach ($pagos as $pago) {
                switch ($poliza) {
                    case "Amplia":
                        // code...
                        $xml = <<<XML
<transacciones xmlns="">
    <transaccion version="1" tipotransaccion="$pago->id" cotizacion="" negocio="1195" tiponegocio="">
        <vehiculo id="1" amis="$descripcion" modelo="$modelo" descripcion="" uso="1" servicio="1" plan="1" motor="" serie="" repuve="" placas="" conductor="" conductorliciencia="" conductorfecnac="" conductorocupacion="" estado="$estadoANA" poblacion="$poblacion" color="01" dispositivo="" fecdispositivo="" tipocarga="" tipocargadescripcion="">
            <cobertura id="02" desc="" sa="" tipo="3" ded="5" pma=""/>
            <cobertura id="04" desc="" sa="" tipo="3" ded="10" pma=""/>
            <cobertura id="06" desc="" sa="200000" tipo="" ded="" pma=""/>
            <cobertura id="07" desc="" sa="" tipo="" ded="" pma=""/>
            <cobertura id="09" desc="" sa="Auto Sustituto" tipo="" ded="" pma=""/>
            <cobertura id="10" desc="" sa="" tipo="B" ded="" pma=""/>
            <cobertura id="13" desc="" sa="2" tipo="" ded="" pma=""/>
            <cobertura id="25" desc="" sa="1000000" tipo="" ded="" pma=""/>
            <cobertura id="26" desc="" sa="1000000" tipo="" ded="" pma=""/>
            <cobertura id="27" desc="" sa="" tipo="" ded="" pma=""/>
            <cobertura id="34" desc="" sa="2000000" tipo="" ded="" pma=""/>
            <cobertura id="35" desc="" sa="" tipo="" ded="" pma=""/>
            <cobertura id="40" desc="" sa="" tipo="" ded="50" pma=""/>
        </vehiculo>
        <asegurado id="" nombre="" paterno="" materno="" calle="" numerointerior="" numeroexterior="" colonia="" poblacion="" estado="$estadoANA" cp="" pais="" tipopersona=""/>
        <poliza id="" tipo="A" endoso="" fecemision="" feciniciovig="$fecha_hoy" fecterminovig="$fecha_t" moneda="0" bonificacion="0" formapago="$pago->id" agente="14275" tarifacuotas="1804" tarifavalores="1804" tarifaderechos="1804" beneficiario="" politicacancelacion="1"/>
        <prima primaneta="" derecho="" recargo="" impuesto="" primatotal="" comision=""/>
        <recibo id="" feciniciovig="" fecterminovig="" primaneta="" derecho="" recargo="" impuesto="" primatotal="" comision="" cadenaoriginal="" sellodigital="" fecemision="" serie="" folio="" horaemision="" numeroaprobacion="" anoaprobacion="" numseriecertificado=""/>
        <error/>
    </transaccion>
</transacciones>
XML;
                            break;
                        
                        case 'Limitada':
                            $xml = <<<XML
<transacciones xmlns="">
    <transaccion version="1" tipotransaccion="$pago->id" cotizacion="" negocio="1195" tiponegocio="">
        <vehiculo id="1" amis="$descripcion" modelo="$modelo" descripcion="" uso="1" servicio="1" plan="3" motor="" serie="" repuve="" placas="" conductor="" conductorliciencia="" conductorfecnac="" conductorocupacion="" estado="$estadoANA" poblacion="$poblacion" color="01" dispositivo="" fecdispositivo="" tipocarga="" tipocargadescripcion="">
            <cobertura id="04" desc="" sa="" tipo="3" ded="10" pma=""/>
      <cobertura id="06" desc="" sa="200000" tipo="" ded="" pma=""/>
            <cobertura id="07" desc="" sa="" tipo="" ded="" pma=""/>
            <cobertura id="10" desc="" sa="" tipo="B" ded="" pma=""/>
            <cobertura id="13" desc="" sa="2" tipo="" ded="" pma=""/>
            <cobertura id="25" desc="" sa="1000000" tipo="" ded="" pma=""/>
            <cobertura id="26" desc="" sa="1000000" tipo="" ded="" pma=""/>
            <cobertura id="34" desc="" sa="2000000" tipo="" ded="" pma=""/>
        </vehiculo>
        <asegurado id="" nombre="" paterno="" materno="" calle="" numerointerior="" numeroexterior="" colonia="" poblacion="" estado="$estadoANA" cp="" pais="" tipopersona=""/>
        <poliza id="" tipo="A" endoso="" fecemision="" feciniciovig="$fecha_hoy" fecterminovig="$fecha_t" moneda="0" bonificacion="0" formapago="$pago->id" agente="14275" tarifacuotas="1804" tarifavalores="1804" tarifaderechos="1804" beneficiario="" politicacancelacion="1"/>
        <prima primaneta="" derecho="" recargo="" impuesto="" primatotal="" comision=""/>
        <recibo id="" feciniciovig="" fecterminovig="" primaneta="" derecho="" recargo="" impuesto="" primatotal="" comision="" cadenaoriginal="" sellodigital="" fecemision="" serie="" folio="" horaemision="" numeroaprobacion="" anoaprobacion="" numseriecertificado=""/>
        <error/>
    </transaccion>
</transacciones>
XML;
                            break;
                        case "RC":
                            $xml= <<<XML
<transacciones xmlns="">
    <transaccion version="1" tipotransaccion="$pago->id" cotizacion="" negocio="1195" tiponegocio="">
        <vehiculo id="1" amis="$descripcion" modelo="$modelo" descripcion="" uso="1" servicio="1" plan="4" motor="" serie="" repuve="" placas="" conductor="" conductorliciencia="" conductorfecnac="" conductorocupacion="" estado="$estadoANA" poblacion="$poblacion" color="01" dispositivo="" fecdispositivo="" tipocarga="" tipocargadescripcion="">
      <cobertura id="06" desc="" sa="200000" tipo="" ded="" pma=""/>
            <cobertura id="07" desc="" sa="" tipo="" ded="" pma=""/>
            <cobertura id="10" desc="" sa="" tipo="B" ded="" pma=""/>
            <cobertura id="13" desc="" sa="2" tipo="" ded="" pma=""/>
            <cobertura id="25" desc="" sa="1000000" tipo="" ded="" pma=""/>
            <cobertura id="26" desc="" sa="1000000" tipo="" ded="" pma=""/>
            <cobertura id="34" desc="" sa="2000000" tipo="" ded="" pma=""/>
        </vehiculo>
        <asegurado id="" nombre="" paterno="" materno="" calle="" numerointerior="" numeroexterior="" colonia="" poblacion="" estado="01001" cp="" pais="" tipopersona=""/>
        <poliza id="" tipo="A" endoso="" fecemision="" feciniciovig="$fecha_hoy" fecterminovig="$fecha_t" moneda="0" bonificacion="0" formapago="$pago->id" agente="14275" tarifacuotas="1804" tarifavalores="1804" tarifaderechos="1804" beneficiario="" politicacancelacion="1"/>
        <prima primaneta="" derecho="" recargo="" impuesto="" primatotal="" comision=""/>
        <recibo id="" feciniciovig="" fecterminovig="" primaneta="" derecho="" recargo="" impuesto="" primatotal="" comision="" cadenaoriginal="" sellodigital="" fecemision="" serie="" folio="" horaemision="" numeroaprobacion="" anoaprobacion="" numseriecertificado=""/>
        <error/>
    </transaccion>
</transacciones>

XML;
                            break;
                        default:
                            return response()->json(['error'=>"Tipo de poliza incorrecta"],501);
                            break;
                    }
                try{
                    $client = new SoapClient($this->urlPHP,$this->params);
                    // $transaccionXML = ;
                    // dd($xml);
                    $respText=$client->TransaccionText(["XML"=>$xml,"Tipo"=>"Cotizacion","Usuario"=>"14275","Clave"=>"kdEDyC9F"]);
                    // TODO
                    $arrayResp = json_decode(json_encode(simplexml_load_string($respText->TransaccionTextResult)),true);
                        // dd($arrayResp);
                    $coberturas=[];
                    foreach ($arrayResp['transaccion']['vehiculo']['cobertura'] as $cobertura) {
                        // dd($cobertura);
                        array_push($coberturas,$cobertura['@attributes']);
                    }
                    $recibos=[];
                    foreach ($arrayResp['transaccion']['recibo'] as $recibo) {
                        if ($pago->descripcion == "CONTADO") {
                            array_push($recibos,$recibo);
                        } else {
                            // dd($recibo);
                            if(isset($recibo["@attributes"])){
                              array_push($recibos,$recibo["@attributes"]);
                            } else{
                                
                                  array_push($recibos,$recibo);
                            }
                            // array_push($recibos,$recibo["@attributes"]);
                        }
                    }
                    array_push($respuestas,
                        [
                            $pago->descripcion=>[
                                "vehiculo"=>$arrayResp['transaccion']['vehiculo']['@attributes'],
                                "coberturas"=>$coberturas,
                                "prima"=>$arrayResp['transaccion']['prima']['@attributes'],
                                "recibos"=>$recibos
                            ]
                        ]
                    );
                }catch(SoapFault $fault){
                    dd($fault);
                }
            }
            dd($respuestas);
            return response()->json(['ANASeguros'=>$respuestas],201);
        }
       
    }
    public function vehiculo($marca,$submarca,$modelo)
    {
        try{
            $client = new SoapClient($this->url,$this->params);
            $vehiculosXML = $client->Vehiculo(["Negocio"=>"1195","Marca"=>$marca,"Submarca"=>$submarca,"Modelo"=>$modelo,"Usuario"=>"14275","Clave"=>"kdEDyC9F"]);
            // dd(simplexml_load_string($vehiculosXML->VehiculoResult)->vehiculo);
            $vehiculos=[];
            foreach (simplexml_load_string($vehiculosXML->VehiculoResult)->vehiculo as $value) {
                $object = json_decode(json_encode($value),true);
                array_push($vehiculos,['clave'=>$object['@attributes']['clave'],'descripcion'=>$object[0]]);
            }
            if (isset($vehiculos)) {
                return response()->json(['vehiculos'=>$vehiculos],201);
            }
            else{
                return response()->json(['error'=>"Descripciones no encontradas"],404);
            }
        }catch(SoapFault $fault){
            dd($fault);
        }
    }
    public function catalogoVehi($modeloMin,$modeloMax)
    {
        try{
            $client = new SoapClient($this->url,$this->params);
            $catalogosXML = $client->CatVeh(["Negocio"=>"1195","ModeloMin"=>$modeloMin,"ModeloMax"=>$modeloMax,"Categ"=>100,"Usuario"=>"14275","Clave"=>"kdEDyC9F"]);
            // dd(simplexml_load_string($catalogosXML->CatVehResult)->vehiculo);
            $catalogos=[];
            foreach (simplexml_load_string($catalogosXML->CatVehResult)->vehiculo as $value) {
                $object=json_decode(json_encode($value),true);
                array_push($catalogos,['categoria'=>$object['@attributes']['categoria'],'modelo'=>$object['@attributes']['modelo'],'clave_armadora'=>$object['@attributes']['cvearmadora'],'armadora'=>$object['@attributes']['armadora'],'cvesubmarca'=>$object['@attributes']['cvesubmarca'],'submarca'=>$object['@attributes']['submarca'],'clave_amis'=>$object['@attributes']['cveamis'],'descripcion'=>$object[0]]);
            }
            if (isset($catalogos)) {
                return response()->json(['catalogos'=>$catalogos],201);
            }
            else{
                return response()->json(['error'=>"Descripciones no encontradas"],404);
            }
        }catch(SoapFault $fault){
            dd($fault);
        }
    }
}
