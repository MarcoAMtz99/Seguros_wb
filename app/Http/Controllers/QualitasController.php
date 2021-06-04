<?php

namespace App\Http\Controllers;
use App\Cliente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SimpleXMLElement;
use SoapClient;
use Mail;
use App\Mail\EmisionPoliza;
class QualitasController extends Controller
{
    //

    protected $opts,$params,$urlTarifa,$urlCotiza,$urlCotizaImpresion,$clientTarifa,$clientCotiza,$clientCotizaImpresion;

 	public function __construct(){
		$this->opts = array(
		  'ssl' => array('ciphers'=>'RC4-SHA', 'verify_peer'=>false, 'verify_peer_name'=>false),
		  'http'=> array('header'=>array("Content-Type:application/xml;charset=utf-8"))
		);
		$this->params = array ('encoding' => 'UTF-8', 'verifypeer' => false, 'verifyhost' => false, 'soap_version' => SOAP_1_1, 'trace' => 1, 'exceptions' => 1, "connection_timeout" => 180, 'stream_context' => stream_context_create($this->opts) );

		  // DATOS QUALITAS
		$this->urlTarifa = "http://qbcenter.qualitas.com.mx/wsTarifa/wsTarifa.asmx?wsdl";
		$this->urlCotiza = "http://sio.qualitas.com.mx/WsEmision/WsEmision.asmx?wsdl";
		$this->urlCotizaImpresion= "http://qbcenter.qualitas.com.mx/QBCImpresion/Service.asmx?wsdl";
		$this->clientTarifa = new SoapClient($this->urlTarifa,$this->params);
		$this->clientCotiza = new SoapClient($this->urlCotiza,$this->params);
		$this->clientCotizaImpresion= new SoapClient($this->urlCotizaImpresion,$this->params);
 	}

 	public function getDigVer($camis)
 	{

 		$sum_impar = 0;

        $sum_par=0;
       if(strlen($camis) <5){
            $camis = str_pad($camis,5,'0',STR_PAD_LEFT);
        }
        for($i=0;$i<strlen($camis);$i++){
            if (($i+1)%2 != 0) {
              // var_dump($camis[$i]);
                // var_dump($i);
                $sum_impar += $camis[$i];
            }
            else{
                $sum_par +=$camis[$i];
            }
        }
        // var_dump($sum_impar);
        $sum_impar = $sum_impar*3;
        $sum_impar = (string)$sum_impar; 
        // // $sum_par = 0;
        // for ($i = 0; $i < strlen($camis); $i++) {
        //   $sum_par += $sum_impar[$i];
        // }
        // var_dump($sum_impar);
        // var_dump($sum_par);
        $et4 = $sum_impar+$sum_par;
        // var_dump($et4);
        $et5 = $et4%10;
        // dd($et5);
        if($et5 != 0){
          $digito = 10-$et5;
        }
        else{
            $digito = '0';
        }
        return (int)$digito;

        
 		
 	}

 	public function getMarcas()
	{
	  
	  try {
		
		
		// dd($this->clientTarifa->__getTypes());
		$lista_marcas = $this->clientTarifa->listaMarcas(['cUsuario'=>"linea","cTarifa"=>"linea"]);
		// dd($this->clientTarifa->__getLastRequest());
		$xml = simplexml_load_string($lista_marcas->listaMarcasResult->any);
		$response = json_decode(json_encode($xml), true);
		  $marcas = $response["datos"]['Elemento'];
		  // dd($marcas);
		  return response()->json(["marcas"=>$marcas],201);
	  } catch (SoapFault $fault) {
		
		dd($fault);
	  }
	}
	public function getModelos($uso,$marca,$submarca,$modelo)
	{
	  
 		if($submarca == 'SERIE 208'){
			$submarca ='208';
 		}
 		if($submarca == 'SERIE 2008'){
			$submarca ='2008';
 		}
 		if($submarca == 'SERIE 3008'){
			$submarca ='3008';
 		}
 		if($submarca == 'SERIE 308'){
			$submarca ='308';
 		}
 		if($submarca == 'SERIE 301'){
			$submarca ='301';
 		}
 		if($submarca == 'SERIE 207'){
			$submarca ='207';
 		}
 		if($submarca == 'SERIE 508'){
			$submarca ='508';
 		}
 		if($submarca == 'SERIE 5'){
			$submarca ='535IA';
 		}
 		if($submarca == 'SERIE 3'){
			$submarca ='335IA';
 		}
 		
 		if($marca == 'BUIK'){
			$marca ='BUICK';
 		}
 		if($submarca == 'MINIVAN '){
			$submarca ='1000';
 		}
 		if($marca == 'GMC'){
			$marca ='GENERAL MOTORS';
 		}
 		
	  try {
		$result = $this->clientTarifa->listaTarifas(['cUsuario'=>"linea",'cTarifa'=>"linea",'cMarca'=>$marca,'cTipo'=>$submarca,'cModelo'=>$modelo]);
		// dd($result);
		$xml = simplexml_load_string($result->listaTarifasResult->any);
		$results = json_decode(json_encode($xml), true);
		$descripciones= [];
		// dd($results);
		if(empty($results['datos'])){

			$result=null;

			 $aux = array( 
                           
                            "CAMIS"=> "",
							"cCategoria"=> "",
							"cMarca"=> "",
							"cMarcaLarga"=> "",
							"cModelo"=> "",
							"cNvaAMIS"=> "",
							"cOcupantes"=> "",
							"cTarifa"=> "",
							"cTipo"=> "",
							"cTransmision"=> "",
							"cVersion"=> "MODELO NO DISPONIBLE",
							"nV1"=> "",
							"nV2"=> ""
                        );
            if (count($descripciones) == 0) {
               array_push($descripciones, $aux);
            }



			return response()->json(['descripciones'=>$descripciones],201);
		}
		elseif(count($results["datos"]) == 0 ){
		  array_push($descripciones, $results["datos"]['Elemento']);

		}
		else{
			
			if ($results['retorno']['descripcion'] == "1") {
				$results["datos"]['Elemento'] = [ $results["datos"]['Elemento'] ];
			}
			foreach ($results["datos"]['Elemento'] as $elemento) {
				if($uso == "Servicio Particular"){
					// dd('entra servicio particular');
					$version = explode(' ',$elemento['cVersion']);
					if(!in_array('SERVPUB',$version)){
						array_push($descripciones,$elemento);
					}
				}
				if($uso == "Servicio Público"){
					// dd('entra servicio publico');
					$version = explode(' ',$elemento['cVersion']);
					if(in_array('SERVPUB',$version)){
						array_push($descripciones,$elemento);
					}
				}

			}
		}
		return response()->json(['descripciones'=>$descripciones],201);
		
	  } catch (SoapFault $fault) {
	  dd($fault);         
	  }
	}

	public function getCobertura(Request $request)
	{
	  // dd($request->all());
	  $cliente = Cliente::where('cotizacion',$request->cotizacion)->first();
	  if($cliente == null){
		return response()->json(['error'=>"datos no encontrado"],404);

	  }
	  else{
	  	$marca = $cliente->auto->marca->descripcion;
		$submarca= $cliente->auto->submarca->descripcion;
		$modelo = $cliente->auto->submarca->anio;
		// $descripcion= $cliente->auto->version->descripcion;
		// dd($cliente);
		
		$camis = $request->camis;
		$modelo = $cliente->auto->submarca->anio;
		$version = $cliente->auto->version;
		$dig =(int)$this->getDigVer($camis);
		// dd($camis." ".$cliente->auto->version->dig);
		$fecha = Carbon::now()->toDateString();
		$fecha_t = Carbon::parse($fecha);
		$fecha_t = $fecha_t->addYears(1)->toDateString();
		switch ($request->poliza) {
			case "Amplia":
				// code...
				$xml=<<<XML
  <Movimientos>
	<Movimiento TipoMovimiento="2" NoPoliza="" NoCotizacion="" NoEndoso="" TipoEndoso="" NoOTra="" NoNegocio="05545">
	  <DatosAsegurado NoAsegurado="">
		<Nombre/>
		<Direccion/>
		<Colonia/>
		<Poblacion/>
		<Estado>$cliente->cestado</Estado>
		<CodigoPostal>$cliente->cp</CodigoPostal>
		<NoEmpleado/>
		<Agrupador/>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>1</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>2</TipoRegla>
		  <ValorRegla>2</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>3</TipoRegla>
		  <ValorRegla>MEXICO</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>4</TipoRegla>
		  <ValorRegla>$cliente->nombre</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>5</TipoRegla>
		  <ValorRegla>$cliente->appaterno</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>6</TipoRegla>
		  <ValorRegla>$cliente->apmaterno</ValorRegla>
		</ConsideracionesAdicionalesDA>
	  </DatosAsegurado>
	  <DatosVehiculo NoInciso="1">
		<ClaveAmis>$camis</ClaveAmis>
		<Modelo>$modelo</Modelo>
		<DescripcionVehiculo></DescripcionVehiculo>
		<Uso>$cliente->uso</Uso>
		<Servicio>$cliente->servicio</Servicio>
		<Paquete>1</Paquete>
		<Motor/>
		<Serie/>
		<Coberturas NoCobertura="1">
		  <SumaAsegurada>0</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>5</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="3">
		  <SumaAsegurada>0</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>10</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="4">
		  <SumaAsegurada>2000000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="5">
		  <SumaAsegurada>250000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="7">
		  <SumaAsegurada/>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="6">
		  <SumaAsegurada>100000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="13">
			<SumaAsegurada>0</SumaAsegurada>
			<TipoSuma>0</TipoSuma>
			<Deducible>1500</Deducible>
			<Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="14">
		  <SumaAsegurada>90</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="47">
		  <SumaAsegurada>1000000</SumaAsegurada>
		  <TipoSuma>14</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
	  </DatosVehiculo>
	  <DatosGenerales>
		<FechaEmision>$fecha</FechaEmision>
		<FechaInicio>$fecha</FechaInicio>
		<FechaTermino>$fecha_t</FechaTermino>
		<Moneda>0</Moneda>
		<Agente>74285</Agente>
		<FormaPago>C</FormaPago>
		<TarifaValores>LINEA</TarifaValores>
		<TarifaCuotas>LINEA</TarifaCuotas>
		<TarifaDerechos>LINEA</TarifaDerechos>
		<Plazo/>
		<Agencia/>
		<Contrato/>
		<PorcentajeDescuento>20</PorcentajeDescuento>
		<ConsideracionesAdicionalesDG NoConsideracion="1">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>$dig</ValorRegla>
		</ConsideracionesAdicionalesDG>
		<ConsideracionesAdicionalesDG NoConsideracion="4">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>0</ValorRegla>
		</ConsideracionesAdicionalesDG>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		<TipoRegla>41</TipoRegla>
		<ValorRegla>1</ValorRegla>
		</ConsideracionesAdicionalesDA>
	  </DatosGenerales>
	  <Primas>
		<PrimaNeta/>
		<Derecho>520</Derecho>
		<Recargo/>
		<Impuesto/>
		<PrimaTotal/>
		<Comision/>
	  </Primas>
	  <CodigoError/>
	</Movimiento>
  </Movimientos>
XML;
//XML QUE VA DESTINADO A OBTENER LA COTIZACION EN FORMA DE PAGO SEMESTRAL
$xmlS=<<<XML
  <Movimientos>
	<Movimiento TipoMovimiento="2" NoPoliza="" NoCotizacion="" NoEndoso="" TipoEndoso="" NoOTra="" NoNegocio="05545">
	  <DatosAsegurado NoAsegurado="">
		<Nombre/>
		<Direccion/>
		<Colonia/>
		<Poblacion/>
		<Estado>$cliente->cestado</Estado>
		<CodigoPostal>$cliente->cp</CodigoPostal>
		<NoEmpleado/>
		<Agrupador/>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>1</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>2</TipoRegla>
		  <ValorRegla>2</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>3</TipoRegla>
		  <ValorRegla>MEXICO</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>4</TipoRegla>
		  <ValorRegla>$cliente->nombre</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>5</TipoRegla>
		  <ValorRegla>$cliente->appaterno</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>6</TipoRegla>
		  <ValorRegla>$cliente->apmaterno</ValorRegla>
		</ConsideracionesAdicionalesDA>
	  </DatosAsegurado>
	  <DatosVehiculo NoInciso="1">
		<ClaveAmis>$camis</ClaveAmis>
		<Modelo>$modelo</Modelo>
		<DescripcionVehiculo></DescripcionVehiculo>
		<Uso>$cliente->uso</Uso>
		<Servicio>$cliente->servicio</Servicio>
		<Paquete>1</Paquete>
		<Motor/>
		<Serie/>
		<Coberturas NoCobertura="1">
		  <SumaAsegurada>0</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>5</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="3">
		  <SumaAsegurada>0</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>10</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="4">
		  <SumaAsegurada>2000000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="5">
		  <SumaAsegurada>250000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="7">
		  <SumaAsegurada/>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="6">
		  <SumaAsegurada>100000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="13">
			<SumaAsegurada>0</SumaAsegurada>
			<TipoSuma>0</TipoSuma>
			<Deducible>1500</Deducible>
			<Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="14">
		  <SumaAsegurada>90</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="47">
		  <SumaAsegurada>1000000</SumaAsegurada>
		  <TipoSuma>14</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
	  </DatosVehiculo>
	  <DatosGenerales>
		<FechaEmision>$fecha</FechaEmision>
		<FechaInicio>$fecha</FechaInicio>
		<FechaTermino>$fecha_t</FechaTermino>
		<Moneda>0</Moneda>
		<Agente>74285</Agente>
		<FormaPago>S</FormaPago>
		<TarifaValores>LINEA</TarifaValores>
		<TarifaCuotas>LINEA</TarifaCuotas>
		<TarifaDerechos>LINEA</TarifaDerechos>
		<Plazo/>
		<Agencia/>
		<Contrato/>
		<PorcentajeDescuento>20</PorcentajeDescuento>
		<ConsideracionesAdicionalesDG NoConsideracion="1">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>$dig</ValorRegla>
		</ConsideracionesAdicionalesDG>
		<ConsideracionesAdicionalesDG NoConsideracion="4">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>0</ValorRegla>
		</ConsideracionesAdicionalesDG>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		<TipoRegla>41</TipoRegla>
		<ValorRegla>1</ValorRegla>
		</ConsideracionesAdicionalesDA>
	  </DatosGenerales>
	  <Primas>
		<PrimaNeta/>
		<Derecho>520</Derecho>
		<Recargo/>
		<Impuesto/>
		<PrimaTotal/>
		<Comision/>
	  </Primas>
	  <CodigoError/>
	</Movimiento>
  </Movimientos>
XML;
//XML DESTINADO A OBTENER LA COTZIACION CON FORMA DE PAGO MENSUAL
$xmlM=<<<XML
  <Movimientos>
	<Movimiento TipoMovimiento="2" NoPoliza="" NoCotizacion="" NoEndoso="" TipoEndoso="" NoOTra="" NoNegocio="05545">
	  <DatosAsegurado NoAsegurado="">
		<Nombre/>
		<Direccion/>
		<Colonia/>
		<Poblacion/>
		<Estado>$cliente->cestado</Estado>
		<CodigoPostal>$cliente->cp</CodigoPostal>
		<NoEmpleado/>
		<Agrupador/>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>1</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>2</TipoRegla>
		  <ValorRegla>2</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>3</TipoRegla>
		  <ValorRegla>MEXICO</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>4</TipoRegla>
		  <ValorRegla>$cliente->nombre</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>5</TipoRegla>
		  <ValorRegla>$cliente->appaterno</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>6</TipoRegla>
		  <ValorRegla>$cliente->apmaterno</ValorRegla>
		</ConsideracionesAdicionalesDA>
	  </DatosAsegurado>
	  <DatosVehiculo NoInciso="1">
		<ClaveAmis>$camis</ClaveAmis>
		<Modelo>$modelo</Modelo>
		<DescripcionVehiculo></DescripcionVehiculo>
		<Uso>$cliente->uso</Uso>
		<Servicio>$cliente->servicio</Servicio>
		<Paquete>1</Paquete>
		<Motor/>
		<Serie/>
		<Coberturas NoCobertura="1">
		  <SumaAsegurada>0</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>5</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="3">
		  <SumaAsegurada>0</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>10</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="4">
		  <SumaAsegurada>2000000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="5">
		  <SumaAsegurada>250000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="7">
		  <SumaAsegurada/>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="6">
		  <SumaAsegurada>100000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="13">
			<SumaAsegurada>0</SumaAsegurada>
			<TipoSuma>0</TipoSuma>
			<Deducible>1500</Deducible>
			<Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="14">
		  <SumaAsegurada>90</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="47">
		  <SumaAsegurada>1000000</SumaAsegurada>
		  <TipoSuma>14</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
	  </DatosVehiculo>
	  <DatosGenerales>
		<FechaEmision>$fecha</FechaEmision>
		<FechaInicio>$fecha</FechaInicio>
		<FechaTermino>$fecha_t</FechaTermino>
		<Moneda>0</Moneda>
		<Agente>74285</Agente>
		<FormaPago>M</FormaPago>
		<TarifaValores>LINEA</TarifaValores>
		<TarifaCuotas>LINEA</TarifaCuotas>
		<TarifaDerechos>LINEA</TarifaDerechos>
		<Plazo/>
		<Agencia/>
		<Contrato/>
		<PorcentajeDescuento>20</PorcentajeDescuento>
		<ConsideracionesAdicionalesDG NoConsideracion="1">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>$dig</ValorRegla>
		</ConsideracionesAdicionalesDG>
		<ConsideracionesAdicionalesDG NoConsideracion="4">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>0</ValorRegla>
		</ConsideracionesAdicionalesDG>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		<TipoRegla>41</TipoRegla>
		<ValorRegla>1</ValorRegla>
		</ConsideracionesAdicionalesDA>
	  </DatosGenerales>
	  <Primas>
		<PrimaNeta/>
		<Derecho>520</Derecho>
		<Recargo/>
		<Impuesto/>
		<PrimaTotal/>
		<Comision/>
	  </Primas>
	  <CodigoError/>
	</Movimiento>
  </Movimientos>
XML;
//TRIMESTRAL
$xmlT=<<<XML
  <Movimientos>
	<Movimiento TipoMovimiento="2" NoPoliza="" NoCotizacion="" NoEndoso="" TipoEndoso="" NoOTra="" NoNegocio="05545">
	  <DatosAsegurado NoAsegurado="">
		<Nombre/>
		<Direccion/>
		<Colonia/>
		<Poblacion/>
		<Estado>$cliente->cestado</Estado>
		<CodigoPostal>$cliente->cp</CodigoPostal>
		<NoEmpleado/>
		<Agrupador/>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>1</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>2</TipoRegla>
		  <ValorRegla>2</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>3</TipoRegla>
		  <ValorRegla>MEXICO</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>4</TipoRegla>
		  <ValorRegla>$cliente->nombre</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>5</TipoRegla>
		  <ValorRegla>$cliente->appaterno</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>6</TipoRegla>
		  <ValorRegla>$cliente->apmaterno</ValorRegla>
		</ConsideracionesAdicionalesDA>
	  </DatosAsegurado>
	  <DatosVehiculo NoInciso="1">
		<ClaveAmis>$camis</ClaveAmis>
		<Modelo>$modelo</Modelo>
		<DescripcionVehiculo></DescripcionVehiculo>
		<Uso>$cliente->uso</Uso>
		<Servicio>$cliente->servicio</Servicio>
		<Paquete>1</Paquete>
		<Motor/>
		<Serie/>
		<Coberturas NoCobertura="1">
		  <SumaAsegurada>0</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>5</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="3">
		  <SumaAsegurada>0</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>10</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="4">
		  <SumaAsegurada>2000000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="5">
		  <SumaAsegurada>250000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="7">
		  <SumaAsegurada/>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="6">
		  <SumaAsegurada>100000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="13">
			<SumaAsegurada>0</SumaAsegurada>
			<TipoSuma>0</TipoSuma>
			<Deducible>1500</Deducible>
			<Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="14">
		  <SumaAsegurada>90</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="47">
		  <SumaAsegurada>1000000</SumaAsegurada>
		  <TipoSuma>14</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
	  </DatosVehiculo>
	  <DatosGenerales>
		<FechaEmision>$fecha</FechaEmision>
		<FechaInicio>$fecha</FechaInicio>
		<FechaTermino>$fecha_t</FechaTermino>
		<Moneda>0</Moneda>
		<Agente>74285</Agente>
		<FormaPago>T</FormaPago>
		<TarifaValores>LINEA</TarifaValores>
		<TarifaCuotas>LINEA</TarifaCuotas>
		<TarifaDerechos>LINEA</TarifaDerechos>
		<Plazo/>
		<Agencia/>
		<Contrato/>
		<PorcentajeDescuento>20</PorcentajeDescuento>
		<ConsideracionesAdicionalesDG NoConsideracion="1">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>$dig</ValorRegla>
		</ConsideracionesAdicionalesDG>
		<ConsideracionesAdicionalesDG NoConsideracion="4">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>0</ValorRegla>
		</ConsideracionesAdicionalesDG>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		<TipoRegla>41</TipoRegla>
		<ValorRegla>1</ValorRegla>
		</ConsideracionesAdicionalesDA>
	  </DatosGenerales>
	  <Primas>
		<PrimaNeta/>
		<Derecho>520</Derecho>
		<Recargo/>
		<Impuesto/>
		<PrimaTotal/>
		<Comision/>
	  </Primas>
	  <CodigoError/>
	</Movimiento>
  </Movimientos>
XML;
				break;
			case "Limitada":
				// code...
				$xml=<<<XML
  <Movimientos>
	<Movimiento TipoMovimiento="2" NoPoliza="" NoCotizacion="" NoEndoso="" TipoEndoso="" NoOTra="" NoNegocio="05545">
	  <DatosAsegurado NoAsegurado="">
		<Nombre/>
		<Direccion/>
		<Colonia/>
		<Poblacion/>
		<Estado>$cliente->cestado</Estado>
		<CodigoPostal>$cliente->cp</CodigoPostal>
		<NoEmpleado/>
		<Agrupador/>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>1</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>2</TipoRegla>
		  <ValorRegla>2</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>3</TipoRegla>
		  <ValorRegla>MEXICO</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>4</TipoRegla>
		  <ValorRegla>$cliente->nombre</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>5</TipoRegla>
		  <ValorRegla>$cliente->appaterno</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>6</TipoRegla>
		  <ValorRegla>$cliente->apmaterno</ValorRegla>
		</ConsideracionesAdicionalesDA>
	  </DatosAsegurado>
	  <DatosVehiculo NoInciso="1">
		<ClaveAmis>$camis</ClaveAmis>
		<Modelo>$modelo</Modelo>
		<DescripcionVehiculo></DescripcionVehiculo>
		<Uso>$cliente->uso</Uso>
		<Servicio>$cliente->servicio</Servicio>
		<Paquete>3</Paquete>
		<Motor/>
		<Serie/>
		<Coberturas NoCobertura="1">
		  <SumaAsegurada>0</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>5</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="3">
		  <SumaAsegurada>0</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>10</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="4">
		  <SumaAsegurada>2000000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="5">
		  <SumaAsegurada>250000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="7">
		  <SumaAsegurada/>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="6">
		  <SumaAsegurada>100000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="14">
		  <SumaAsegurada>90</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="47">
		  <SumaAsegurada>1000000</SumaAsegurada>
		  <TipoSuma>14</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
	  </DatosVehiculo>
	  <DatosGenerales>
		<FechaEmision>$fecha</FechaEmision>
		<FechaInicio>$fecha</FechaInicio>
		<FechaTermino>$fecha_t</FechaTermino>
		<Moneda>0</Moneda>
		<Agente>74285</Agente>
		<FormaPago>C</FormaPago>
		<TarifaValores>LINEA</TarifaValores>
		<TarifaCuotas>LINEA</TarifaCuotas>
		<TarifaDerechos>LINEA</TarifaDerechos>
		<Plazo/>
		<Agencia/>
		<Contrato/>
		<PorcentajeDescuento>20</PorcentajeDescuento>
		<ConsideracionesAdicionalesDG NoConsideracion="1">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>$dig</ValorRegla>
		</ConsideracionesAdicionalesDG>
		<ConsideracionesAdicionalesDG NoConsideracion="4">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>0</ValorRegla>
		</ConsideracionesAdicionalesDG>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		<TipoRegla>41</TipoRegla>
		<ValorRegla>1</ValorRegla>
		</ConsideracionesAdicionalesDA>
	  </DatosGenerales>
	  <Primas>
		<PrimaNeta/>
		<Derecho>520</Derecho>
		<Recargo/>
		<Impuesto/>
		<PrimaTotal/>
		<Comision/>
	  </Primas>
	  <CodigoError/>
	</Movimiento>
  </Movimientos>
XML;
//XML para obtener semestral 
$xmlS=<<<XML
  <Movimientos>
	<Movimiento TipoMovimiento="2" NoPoliza="" NoCotizacion="" NoEndoso="" TipoEndoso="" NoOTra="" NoNegocio="05545">
	  <DatosAsegurado NoAsegurado="">
		<Nombre/>
		<Direccion/>
		<Colonia/>
		<Poblacion/>
		<Estado>$cliente->cestado</Estado>
		<CodigoPostal>$cliente->cp</CodigoPostal>
		<NoEmpleado/>
		<Agrupador/>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>1</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>2</TipoRegla>
		  <ValorRegla>2</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>3</TipoRegla>
		  <ValorRegla>MEXICO</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>4</TipoRegla>
		  <ValorRegla>$cliente->nombre</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>5</TipoRegla>
		  <ValorRegla>$cliente->appaterno</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>6</TipoRegla>
		  <ValorRegla>$cliente->apmaterno</ValorRegla>
		</ConsideracionesAdicionalesDA>
	  </DatosAsegurado>
	  <DatosVehiculo NoInciso="1">
		<ClaveAmis>$camis</ClaveAmis>
		<Modelo>$modelo</Modelo>
		<DescripcionVehiculo></DescripcionVehiculo>
		<Uso>$cliente->uso</Uso>
		<Servicio>$cliente->servicio</Servicio>
		<Paquete>3</Paquete>
		<Motor/>
		<Serie/>
		<Coberturas NoCobertura="1">
		  <SumaAsegurada>0</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>5</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="3">
		  <SumaAsegurada>0</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>10</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="4">
		  <SumaAsegurada>2000000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="5">
		  <SumaAsegurada>250000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="7">
		  <SumaAsegurada/>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="6">
		  <SumaAsegurada>100000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="14">
		  <SumaAsegurada>90</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="47">
		  <SumaAsegurada>1000000</SumaAsegurada>
		  <TipoSuma>14</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
	  </DatosVehiculo>
	  <DatosGenerales>
		<FechaEmision>$fecha</FechaEmision>
		<FechaInicio>$fecha</FechaInicio>
		<FechaTermino>$fecha_t</FechaTermino>
		<Moneda>0</Moneda>
		<Agente>74285</Agente>
		<FormaPago>S</FormaPago>
		<TarifaValores>LINEA</TarifaValores>
		<TarifaCuotas>LINEA</TarifaCuotas>
		<TarifaDerechos>LINEA</TarifaDerechos>
		<Plazo/>
		<Agencia/>
		<Contrato/>
		<PorcentajeDescuento>20</PorcentajeDescuento>
		<ConsideracionesAdicionalesDG NoConsideracion="1">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>$dig</ValorRegla>
		</ConsideracionesAdicionalesDG>
		<ConsideracionesAdicionalesDG NoConsideracion="4">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>0</ValorRegla>
		</ConsideracionesAdicionalesDG>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		<TipoRegla>41</TipoRegla>
		<ValorRegla>1</ValorRegla>
		</ConsideracionesAdicionalesDA>
	  </DatosGenerales>
	  <Primas>
		<PrimaNeta/>
		<Derecho>520</Derecho>
		<Recargo/>
		<Impuesto/>
		<PrimaTotal/>
		<Comision/>
	  </Primas>
	  <CodigoError/>
	</Movimiento>
  </Movimientos>
XML;
//XML para mensual
$xmlM=<<<XML
  <Movimientos>
	<Movimiento TipoMovimiento="2" NoPoliza="" NoCotizacion="" NoEndoso="" TipoEndoso="" NoOTra="" NoNegocio="05545">
	  <DatosAsegurado NoAsegurado="">
		<Nombre/>
		<Direccion/>
		<Colonia/>
		<Poblacion/>
		<Estado>$cliente->cestado</Estado>
		<CodigoPostal>$cliente->cp</CodigoPostal>
		<NoEmpleado/>
		<Agrupador/>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>1</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>2</TipoRegla>
		  <ValorRegla>2</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>3</TipoRegla>
		  <ValorRegla>MEXICO</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>4</TipoRegla>
		  <ValorRegla>$cliente->nombre</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>5</TipoRegla>
		  <ValorRegla>$cliente->appaterno</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>6</TipoRegla>
		  <ValorRegla>$cliente->apmaterno</ValorRegla>
		</ConsideracionesAdicionalesDA>
	  </DatosAsegurado>
	  <DatosVehiculo NoInciso="1">
		<ClaveAmis>$camis</ClaveAmis>
		<Modelo>$modelo</Modelo>
		<DescripcionVehiculo></DescripcionVehiculo>
		<Uso>$cliente->uso</Uso>
		<Servicio>$cliente->servicio</Servicio>
		<Paquete>3</Paquete>
		<Motor/>
		<Serie/>
		<Coberturas NoCobertura="1">
		  <SumaAsegurada>0</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>5</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="3">
		  <SumaAsegurada>0</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>10</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="4">
		  <SumaAsegurada>2000000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="5">
		  <SumaAsegurada>250000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="7">
		  <SumaAsegurada/>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="6">
		  <SumaAsegurada>100000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="14">
		  <SumaAsegurada>90</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="47">
		  <SumaAsegurada>1000000</SumaAsegurada>
		  <TipoSuma>14</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
	  </DatosVehiculo>
	  <DatosGenerales>
		<FechaEmision>$fecha</FechaEmision>
		<FechaInicio>$fecha</FechaInicio>
		<FechaTermino>$fecha_t</FechaTermino>
		<Moneda>0</Moneda>
		<Agente>74285</Agente>
		<FormaPago>C</FormaPago>
		<TarifaValores>LINEA</TarifaValores>
		<TarifaCuotas>LINEA</TarifaCuotas>
		<TarifaDerechos>LINEA</TarifaDerechos>
		<Plazo/>
		<Agencia/>
		<Contrato/>
		<PorcentajeDescuento>20</PorcentajeDescuento>
		<ConsideracionesAdicionalesDG NoConsideracion="1">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>$dig</ValorRegla>
		</ConsideracionesAdicionalesDG>
		<ConsideracionesAdicionalesDG NoConsideracion="4">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>0</ValorRegla>
		</ConsideracionesAdicionalesDG>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		<TipoRegla>41</TipoRegla>
		<ValorRegla>1</ValorRegla>
		</ConsideracionesAdicionalesDA>
	  </DatosGenerales>
	  <Primas>
		<PrimaNeta/>
		<Derecho>520</Derecho>
		<Recargo/>
		<Impuesto/>
		<PrimaTotal/>
		<Comision/>
	  </Primas>
	  <CodigoError/>
	</Movimiento>
  </Movimientos>
XML;
//TRIMESTRAL
$xmlT=<<<XML
  <Movimientos>
	<Movimiento TipoMovimiento="2" NoPoliza="" NoCotizacion="" NoEndoso="" TipoEndoso="" NoOTra="" NoNegocio="05545">
	  <DatosAsegurado NoAsegurado="">
		<Nombre/>
		<Direccion/>
		<Colonia/>
		<Poblacion/>
		<Estado>$cliente->cestado</Estado>
		<CodigoPostal>$cliente->cp</CodigoPostal>
		<NoEmpleado/>
		<Agrupador/>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>1</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>2</TipoRegla>
		  <ValorRegla>2</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>3</TipoRegla>
		  <ValorRegla>MEXICO</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>4</TipoRegla>
		  <ValorRegla>$cliente->nombre</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>5</TipoRegla>
		  <ValorRegla>$cliente->appaterno</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>6</TipoRegla>
		  <ValorRegla>$cliente->apmaterno</ValorRegla>
		</ConsideracionesAdicionalesDA>
	  </DatosAsegurado>
	  <DatosVehiculo NoInciso="1">
		<ClaveAmis>$camis</ClaveAmis>
		<Modelo>$modelo</Modelo>
		<DescripcionVehiculo></DescripcionVehiculo>
		<Uso>$cliente->uso</Uso>
		<Servicio>$cliente->servicio</Servicio>
		<Paquete>3</Paquete>
		<Motor/>
		<Serie/>
		<Coberturas NoCobertura="1">
		  <SumaAsegurada>0</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>5</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="3">
		  <SumaAsegurada>0</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>10</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="4">
		  <SumaAsegurada>2000000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="5">
		  <SumaAsegurada>250000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="7">
		  <SumaAsegurada/>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="6">
		  <SumaAsegurada>100000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="14">
		  <SumaAsegurada>90</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="47">
		  <SumaAsegurada>1000000</SumaAsegurada>
		  <TipoSuma>14</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
	  </DatosVehiculo>
	  <DatosGenerales>
		<FechaEmision>$fecha</FechaEmision>
		<FechaInicio>$fecha</FechaInicio>
		<FechaTermino>$fecha_t</FechaTermino>
		<Moneda>0</Moneda>
		<Agente>74285</Agente>
		<FormaPago>T</FormaPago>
		<TarifaValores>LINEA</TarifaValores>
		<TarifaCuotas>LINEA</TarifaCuotas>
		<TarifaDerechos>LINEA</TarifaDerechos>
		<Plazo/>
		<Agencia/>
		<Contrato/>
		<PorcentajeDescuento>20</PorcentajeDescuento>
		<ConsideracionesAdicionalesDG NoConsideracion="1">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>$dig</ValorRegla>
		</ConsideracionesAdicionalesDG>
		<ConsideracionesAdicionalesDG NoConsideracion="4">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>0</ValorRegla>
		</ConsideracionesAdicionalesDG>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		<TipoRegla>41</TipoRegla>
		<ValorRegla>1</ValorRegla>
		</ConsideracionesAdicionalesDA>
	  </DatosGenerales>
	  <Primas>
		<PrimaNeta/>
		<Derecho>520</Derecho>
		<Recargo/>
		<Impuesto/>
		<PrimaTotal/>
		<Comision/>
	  </Primas>
	  <CodigoError/>
	</Movimiento>
  </Movimientos>
XML;
				break;
			case "RC":
				// code...
				$xml=<<<XML
  <Movimientos>
	<Movimiento TipoMovimiento="2" NoPoliza="" NoCotizacion="" NoEndoso="" TipoEndoso="" NoOTra="" NoNegocio="05545">
	  <DatosAsegurado NoAsegurado="">
		<Nombre/>
		<Direccion/>
		<Colonia/>
		<Poblacion/>
		<Estado>$cliente->cestado</Estado>
		<CodigoPostal>$cliente->cp</CodigoPostal>
		<NoEmpleado/>
		<Agrupador/>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>1</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>2</TipoRegla>
		  <ValorRegla>2</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>3</TipoRegla>
		  <ValorRegla>MEXICO</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>4</TipoRegla>
		  <ValorRegla>$cliente->nombre</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>5</TipoRegla>
		  <ValorRegla>$cliente->appaterno</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>6</TipoRegla>
		  <ValorRegla>$cliente->apmaterno</ValorRegla>
		</ConsideracionesAdicionalesDA>
	  </DatosAsegurado>
	  <DatosVehiculo NoInciso="1">
		<ClaveAmis>$camis</ClaveAmis>
		<Modelo>$modelo</Modelo>
		<DescripcionVehiculo></DescripcionVehiculo>
		<Uso>$cliente->uso</Uso>
		<Servicio>$cliente->servicio</Servicio>
		<Paquete>4</Paquete>
		<Motor/>
		<Serie/>
		<Coberturas NoCobertura="1">
		  <SumaAsegurada>0</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>5</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="3">
		  <SumaAsegurada>0</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>10</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="4">
		  <SumaAsegurada>2000000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="5">
		  <SumaAsegurada>250000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="7">
		  <SumaAsegurada/>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="6">
		  <SumaAsegurada>100000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="14">
		  <SumaAsegurada>90</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="47">
		  <SumaAsegurada>1000000</SumaAsegurada>
		  <TipoSuma>14</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
	  </DatosVehiculo>
	  <DatosGenerales>
		<FechaEmision>$fecha</FechaEmision>
		<FechaInicio>$fecha</FechaInicio>
		<FechaTermino>$fecha_t</FechaTermino>
		<Moneda>0</Moneda>
		<Agente>74285</Agente>
		<FormaPago>C</FormaPago>
		<TarifaValores>LINEA</TarifaValores>
		<TarifaCuotas>LINEA</TarifaCuotas>
		<TarifaDerechos>LINEA</TarifaDerechos>
		<Plazo/>
		<Agencia/>
		<Contrato/>
		<PorcentajeDescuento>20</PorcentajeDescuento>
		<ConsideracionesAdicionalesDG NoConsideracion="1">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>$dig</ValorRegla>
		</ConsideracionesAdicionalesDG>
		<ConsideracionesAdicionalesDG NoConsideracion="4">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>0</ValorRegla>
		</ConsideracionesAdicionalesDG>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		<TipoRegla>41</TipoRegla>
		<ValorRegla>1</ValorRegla>
		</ConsideracionesAdicionalesDA>
	  </DatosGenerales>
	  <Primas>
		<PrimaNeta/>
		<Derecho>520</Derecho>
		<Recargo/>
		<Impuesto/>
		<PrimaTotal/>
		<Comision/>
	  </Primas>
	  <CodigoError/>
	</Movimiento>
  </Movimientos>
XML;
//XML PARA SEMESTRAL
$xmlS=<<<XML
  <Movimientos>
	<Movimiento TipoMovimiento="2" NoPoliza="" NoCotizacion="" NoEndoso="" TipoEndoso="" NoOTra="" NoNegocio="05545">
	  <DatosAsegurado NoAsegurado="">
		<Nombre/>
		<Direccion/>
		<Colonia/>
		<Poblacion/>
		<Estado>$cliente->cestado</Estado>
		<CodigoPostal>$cliente->cp</CodigoPostal>
		<NoEmpleado/>
		<Agrupador/>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>1</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>2</TipoRegla>
		  <ValorRegla>2</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>3</TipoRegla>
		  <ValorRegla>MEXICO</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>4</TipoRegla>
		  <ValorRegla>$cliente->nombre</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>5</TipoRegla>
		  <ValorRegla>$cliente->appaterno</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>6</TipoRegla>
		  <ValorRegla>$cliente->apmaterno</ValorRegla>
		</ConsideracionesAdicionalesDA>
	  </DatosAsegurado>
	  <DatosVehiculo NoInciso="1">
		<ClaveAmis>$camis</ClaveAmis>
		<Modelo>$modelo</Modelo>
		<DescripcionVehiculo></DescripcionVehiculo>
		<Uso>$cliente->uso</Uso>
		<Servicio>$cliente->servicio</Servicio>
		<Paquete>4</Paquete>
		<Motor/>
		<Serie/>
		<Coberturas NoCobertura="1">
		  <SumaAsegurada>0</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>5</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="3">
		  <SumaAsegurada>0</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>10</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="4">
		  <SumaAsegurada>2000000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="5">
		  <SumaAsegurada>250000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="7">
		  <SumaAsegurada/>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="6">
		  <SumaAsegurada>100000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="14">
		  <SumaAsegurada>90</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="47">
		  <SumaAsegurada>1000000</SumaAsegurada>
		  <TipoSuma>14</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
	  </DatosVehiculo>
	  <DatosGenerales>
		<FechaEmision>$fecha</FechaEmision>
		<FechaInicio>$fecha</FechaInicio>
		<FechaTermino>$fecha_t</FechaTermino>
		<Moneda>0</Moneda>
		<Agente>74285</Agente>
		<FormaPago>S</FormaPago>
		<TarifaValores>LINEA</TarifaValores>
		<TarifaCuotas>LINEA</TarifaCuotas>
		<TarifaDerechos>LINEA</TarifaDerechos>
		<Plazo/>
		<Agencia/>
		<Contrato/>
		<PorcentajeDescuento>20</PorcentajeDescuento>
		<ConsideracionesAdicionalesDG NoConsideracion="1">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>$dig</ValorRegla>
		</ConsideracionesAdicionalesDG>
		<ConsideracionesAdicionalesDG NoConsideracion="4">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>0</ValorRegla>
		</ConsideracionesAdicionalesDG>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		<TipoRegla>41</TipoRegla>
		<ValorRegla>1</ValorRegla>
		</ConsideracionesAdicionalesDA>
	  </DatosGenerales>
	  <Primas>
		<PrimaNeta/>
		<Derecho>520</Derecho>
		<Recargo/>
		<Impuesto/>
		<PrimaTotal/>
		<Comision/>
	  </Primas>
	  <CodigoError/>
	</Movimiento>
  </Movimientos>
XML;
//xml para mensual
$xmlM=<<<XML
  <Movimientos>
	<Movimiento TipoMovimiento="2" NoPoliza="" NoCotizacion="" NoEndoso="" TipoEndoso="" NoOTra="" NoNegocio="05545">
	  <DatosAsegurado NoAsegurado="">
		<Nombre/>
		<Direccion/>
		<Colonia/>
		<Poblacion/>
		<Estado>$cliente->cestado</Estado>
		<CodigoPostal>$cliente->cp</CodigoPostal>
		<NoEmpleado/>
		<Agrupador/>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>1</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>2</TipoRegla>
		  <ValorRegla>2</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>3</TipoRegla>
		  <ValorRegla>MEXICO</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>4</TipoRegla>
		  <ValorRegla>$cliente->nombre</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>5</TipoRegla>
		  <ValorRegla>$cliente->appaterno</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>6</TipoRegla>
		  <ValorRegla>$cliente->apmaterno</ValorRegla>
		</ConsideracionesAdicionalesDA>
	  </DatosAsegurado>
	  <DatosVehiculo NoInciso="1">
		<ClaveAmis>$camis</ClaveAmis>
		<Modelo>$modelo</Modelo>
		<DescripcionVehiculo></DescripcionVehiculo>
		<Uso>$cliente->uso</Uso>
		<Servicio>$cliente->servicio</Servicio>
		<Paquete>4</Paquete>
		<Motor/>
		<Serie/>
		<Coberturas NoCobertura="1">
		  <SumaAsegurada>0</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>5</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="3">
		  <SumaAsegurada>0</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>10</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="4">
		  <SumaAsegurada>2000000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="5">
		  <SumaAsegurada>250000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="7">
		  <SumaAsegurada/>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="6">
		  <SumaAsegurada>100000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="14">
		  <SumaAsegurada>90</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="47">
		  <SumaAsegurada>1000000</SumaAsegurada>
		  <TipoSuma>14</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
	  </DatosVehiculo>
	  <DatosGenerales>
		<FechaEmision>$fecha</FechaEmision>
		<FechaInicio>$fecha</FechaInicio>
		<FechaTermino>$fecha_t</FechaTermino>
		<Moneda>0</Moneda>
		<Agente>74285</Agente>
		<FormaPago>M</FormaPago>
		<TarifaValores>LINEA</TarifaValores>
		<TarifaCuotas>LINEA</TarifaCuotas>
		<TarifaDerechos>LINEA</TarifaDerechos>
		<Plazo/>
		<Agencia/>
		<Contrato/>
		<PorcentajeDescuento>20</PorcentajeDescuento>
		<ConsideracionesAdicionalesDG NoConsideracion="1">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>$dig</ValorRegla>
		</ConsideracionesAdicionalesDG>
		<ConsideracionesAdicionalesDG NoConsideracion="4">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>0</ValorRegla>
		</ConsideracionesAdicionalesDG>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		<TipoRegla>41</TipoRegla>
		<ValorRegla>1</ValorRegla>
		</ConsideracionesAdicionalesDA>
	  </DatosGenerales>
	  <Primas>
		<PrimaNeta/>
		<Derecho>520</Derecho>
		<Recargo/>
		<Impuesto/>
		<PrimaTotal/>
		<Comision/>
	  </Primas>
	  <CodigoError/>
	</Movimiento>
  </Movimientos>
XML;
//TRIMESTRAL
$xmlT=<<<XML
  <Movimientos>
	<Movimiento TipoMovimiento="2" NoPoliza="" NoCotizacion="" NoEndoso="" TipoEndoso="" NoOTra="" NoNegocio="05545">
	  <DatosAsegurado NoAsegurado="">
		<Nombre/>
		<Direccion/>
		<Colonia/>
		<Poblacion/>
		<Estado>$cliente->cestado</Estado>
		<CodigoPostal>$cliente->cp</CodigoPostal>
		<NoEmpleado/>
		<Agrupador/>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>1</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>2</TipoRegla>
		  <ValorRegla>2</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>3</TipoRegla>
		  <ValorRegla>MEXICO</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>4</TipoRegla>
		  <ValorRegla>$cliente->nombre</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>5</TipoRegla>
		  <ValorRegla>$cliente->appaterno</ValorRegla>
		</ConsideracionesAdicionalesDA>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		  <TipoRegla>6</TipoRegla>
		  <ValorRegla>$cliente->apmaterno</ValorRegla>
		</ConsideracionesAdicionalesDA>
	  </DatosAsegurado>
	  <DatosVehiculo NoInciso="1">
		<ClaveAmis>$camis</ClaveAmis>
		<Modelo>$modelo</Modelo>
		<DescripcionVehiculo></DescripcionVehiculo>
		<Uso>$cliente->uso</Uso>
		<Servicio>$cliente->servicio</Servicio>
		<Paquete>4</Paquete>
		<Motor/>
		<Serie/>
		<Coberturas NoCobertura="1">
		  <SumaAsegurada>0</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>5</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="3">
		  <SumaAsegurada>0</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>10</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="4">
		  <SumaAsegurada>2000000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="5">
		  <SumaAsegurada>250000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="7">
		  <SumaAsegurada/>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="6">
		  <SumaAsegurada>100000</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="14">
		  <SumaAsegurada>90</SumaAsegurada>
		  <TipoSuma>0</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
		<Coberturas NoCobertura="47">
		  <SumaAsegurada>1000000</SumaAsegurada>
		  <TipoSuma>14</TipoSuma>
		  <Deducible>0</Deducible>
		  <Prima>0</Prima>
		</Coberturas>
	  </DatosVehiculo>
	  <DatosGenerales>
		<FechaEmision>$fecha</FechaEmision>
		<FechaInicio>$fecha</FechaInicio>
		<FechaTermino>$fecha_t</FechaTermino>
		<Moneda>0</Moneda>
		<Agente>74285</Agente>
		<FormaPago>T</FormaPago>
		<TarifaValores>LINEA</TarifaValores>
		<TarifaCuotas>LINEA</TarifaCuotas>
		<TarifaDerechos>LINEA</TarifaDerechos>
		<Plazo/>
		<Agencia/>
		<Contrato/>
		<PorcentajeDescuento>20</PorcentajeDescuento>
		<ConsideracionesAdicionalesDG NoConsideracion="1">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>$dig</ValorRegla>
		</ConsideracionesAdicionalesDG>
		<ConsideracionesAdicionalesDG NoConsideracion="4">
		  <TipoRegla>1</TipoRegla>
		  <ValorRegla>0</ValorRegla>
		</ConsideracionesAdicionalesDG>
		<ConsideracionesAdicionalesDA NoConsideracion="40">
		<TipoRegla>41</TipoRegla>
		<ValorRegla>1</ValorRegla>
		</ConsideracionesAdicionalesDA>
	  </DatosGenerales>
	  <Primas>
		<PrimaNeta/>
		<Derecho>520</Derecho>
		<Recargo/>
		<Impuesto/>
		<PrimaTotal/>
		<Comision/>
	  </Primas>
	  <CodigoError/>
	</Movimiento>
  </Movimientos>
XML;
				break;
			
			default:
				// code...
				break;
		}
		// $dig_ver = $cliente->dig;
		// dd($dig_ver);
		// var_dump($camis);
		// var_dump(strlen($camis));
	   //  $sum_impar = 0;
	   // if(strlen($camis) <5){
	   //      $camis = str_pad($camis,5,'0',STR_PAD_LEFT);
	   //  }
	   //  for($i=0;$i<strlen($camis);$i++){
	   //      if (($i+1)%2 != 0) {
	   //        // var_dump($camis[$i]);
	   //          // var_dump($i);
	   //          $sum_impar += $camis[$i];
	   //      }
	   //  }
	   //  var_dump($sum_impar);
	   //  $sum_impar = $sum_impar*3;
	   //  $sum_impar = (string)$sum_impar; 
	   //  $sum_par = 0;
	   //  for ($i = 0; $i < strlen($sum_impar); $i++) {
	   //    $sum_par += $sum_impar[$i];
	   //  }
	   //  var_dump($sum_impar);
	   //  var_dump($sum_par);
	   //  $et4 = $sum_impar+$sum_par;
	   //  var_dump($et4);
	   //  $et5 = $et4%10;
	   //  if($et5 != 0){
	   //    $digito = 10-$et5;
	   //  }
	   //  dd($digito);
		$cotizacion = $this->getQualitas($xml);
		$cotizacionS = $this->getQualitas($xmlS);
		$cotizacionM = $this->getQualitas($xmlM);
		$cotizacionT = $this->getQualitas($xmlT);
		// dd($xml,$cotizacion);
		return response()->json(['Qualitas'=>$cotizacion,'QualitasS'=>$cotizacionS,'QualitasM'=>$cotizacionM,'QualitasT'=>$cotizacionT],200);

		
		
		

	 	}

	  // $request->cotizacion
	}

	public function getQualitas($xml){
		$client = $this->clientCotiza->obtenerNuevaEmision(array('xmlEmision'=>$xml));
		$xmlR = simplexml_load_string($client->obtenerNuevaEmisionResult);
		// dd($xmlR);
		$response = json_decode(json_encode($xmlR), true);
		

		if($response['Movimiento']['CodigoError']){
			$error = ['error'=>$response['Movimiento']['CodigoError']];
			$cobertura = [
		  		'Nombre'=>"Qualitas",
				'error'=>$error
		  	];
		  	return $cobertura;
		}
		else{
			$coberturas=[];
		  	foreach ($response['Movimiento']['DatosVehiculo']['Coberturas'] as $cobertura) {
				switch($cobertura["@attributes"]["NoCobertura"]){
					case "1":
						$cobertura['tipo'] = "Daños Materiales";
						break;
					case "2":
						$cobertura['tipo'] = "Perdida Total";
						break;
					case "3":
						$cobertura['tipo'] = "Robo Total";
						break;
					case "4":
						$cobertura['tipo'] = "Responsabilidad Civil";
						break;
					case "5":
						$cobertura['tipo'] = "Gastos Médicos";
						break;
					case "6":
						$cobertura['tipo'] = "Muerte del conductor";
						break;
					case "7":
						$cobertura['tipo'] = "Gastos Legales";
						break;
					case "8":
						$cobertura['tipo'] = "Equipo Especial";
						break;
					case "9":
						$cobertura['tipo'] = "Adaptaciones Daños Materiales";
						break;
					case "10":
						$cobertura['tipo'] = "Adaptaciones Robo Total";
						break;
					case "11":
						$cobertura['tipo'] = "Extensión de Responsabilidad Civil";
						break;
					case "12":
						$cobertura['tipo'] = "Exención de Deducible";
						break;
					case "13":
						$cobertura['tipo'] = "Responsabilidad Civil Pasajero";
						break;
					case "14":
						$cobertura['tipo'] = "Asistencia Vial";
						break;
					case "15":
						$cobertura['tipo'] = "Robo Parcial";
						break;
					case "16":
						$cobertura['tipo'] = "Ajuste Automático";
						break;
					case "17":
						$cobertura['tipo'] = "Gastos de Transporte";
						break;
					case "18":
						$cobertura['tipo'] = "Responsabilidad Civil Personas";
						break;
					case "19":
						$cobertura['tipo'] = "Responsabilidad Civil Bienes";
						break;
					case "20":
						$cobertura['tipo'] = "Responsabilidad Civil Catastrófica";
						break;
					case "21":
						$cobertura['tipo'] = "Responsabilidad Civil Ecológica";
						break;
					case "22":
						$cobertura['tipo'] = "Responsabilidad Civil Legal";
						break;
					case "23":
						$cobertura['tipo'] = "CIVA DM";
						break;
					case "24":
						$cobertura['tipo'] = "CIVA RT";
						break;
					case "25":
						$cobertura['tipo'] = "Asistencia Satelital";
						break;
					case "26":
						$cobertura['tipo'] = "EDV";
						break;
					case "27":
						$cobertura['tipo'] = "AVC";
						break;
					case "28":
						$cobertura['tipo'] = "GTP";
						break;
					case "29":
						$cobertura['tipo'] = "PEUG EG";
						break;
					case "30":
						$cobertura['tipo'] = "PEUG SM";
						break;
					case "31":
						$cobertura['tipo'] = "Daños por la carga";
						break;
					case "32":
						$cobertura['tipo'] = "ADAP SPT";
						break;
					case "33":
						$cobertura['tipo'] = "Exención de deducible por prima nivelada.";
						break;
					case "50":
						$cobertura['tipo'] = "Extensión de garantía";
						break;
					case "51":
						$cobertura['tipo'] = "Servicios de asistencia";
						break;
					case "52":
						$cobertura['tipo'] = "Carnét de Mantenimiento";
						break;
				}
				array_push($coberturas, $cobertura);
				
		  	}
		  	  	$cobertura = [
		  		'Nombre'=>"Qualitas",
		  		'NoCotizacion'=>$response['Movimiento']['@attributes']['NoCotizacion'],
				'Primas'=>$response['Movimiento']['Primas'],
				'Recibos'=>$response['Movimiento']['Recibos'],
				'Coberturas'=>$coberturas
		  	];
		  	return $cobertura;
		}
	}

	public function emitirPoliza(Request $request)
	{
		// dd($request->all());
		$cliente = Cliente::where('cotizacion',$request->cotizacion)->first();

		// $descripcion= $cliente->auto->version->descripcion;
		// dd($cliente);
		// dd($camis." ".$cliente->auto->version->dig);
		$fecha = Carbon::now()->toDateString();
		$fecha_t = Carbon::parse($fecha);
		$fecha_t = $fecha_t->addYears(1)->toDateString();
		// dd($cliente);
		// dd($cliente);
		$hoy = Carbon::now();
		$hoyFor = $hoy->format('Y-m-d');
		// dd($hoy->addYear(1)->format('Y-m-d'));
		$vencimiento = $hoy->addYear(1)->format('Y-m-d');
		$nacimiento = new Carbon($request->f_nac);
		$nacimiento = $nacimiento->format('d-m-Y');
		// dd($nacimiento);
		$version = $cliente->auto->version;
		$modelo = (int)$cliente->auto->submarca->anio;
		$camis = $request->camis;
		$dig =(int)$this->getDigVer($camis);
		$xmlpoliza=
<<<XML
<?xml version="1.0" encoding="utf-8"?>
<Movimientos>
	<Movimiento TipoMovimiento="3" NoPoliza="" NoCotizacion="" NoEndoso="" TipoEndoso="" NoOTra="" NoNegocio="05545">
		<DatosAsegurado NoAsegurado="">
			<Nombre>$request->apepat $request->apemat $request->nombre</Nombre>
			<Direccion>$request->calle $request->ext</Direccion>
			<Colonia>$request->poblacion</Colonia>
			<Poblacion>$request->municipio</Poblacion>
			<Estado>$request->cod_estado</Estado>
			<CodigoPostal>$request->cp</CodigoPostal>
			<NoEmpleado/>
			<Agrupador/>
			<?asegurado persona fisica?>
			<ConsideracionesAdicionalesDA NoConsideracion="40">
				<TipoRegla>1</TipoRegla>
				<ValorRegla>$request->ext</ValorRegla>
			</ConsideracionesAdicionalesDA>
			<ConsideracionesAdicionalesDA NoConsideracion="40">
				<TipoRegla>2</TipoRegla>
				<ValorRegla>$request->int</ValorRegla>
			</ConsideracionesAdicionalesDA>
			<ConsideracionesAdicionalesDA NoConsideracion="40">
				<TipoRegla>3</TipoRegla>
				<ValorRegla>México</ValorRegla>
			</ConsideracionesAdicionalesDA>
			<ConsideracionesAdicionalesDA NoConsideracion="40">
				<TipoRegla>4</TipoRegla>
				<ValorRegla>$request->nombre</ValorRegla>
			</ConsideracionesAdicionalesDA>
			<ConsideracionesAdicionalesDA NoConsideracion="40">
				<TipoRegla>5</TipoRegla>
				<ValorRegla>$request->apepat</ValorRegla>
			</ConsideracionesAdicionalesDA>
			<ConsideracionesAdicionalesDA NoConsideracion="40">
				<TipoRegla>6</TipoRegla>
				<ValorRegla>$request->apemat</ValorRegla>
			</ConsideracionesAdicionalesDA>
			<ConsideracionesAdicionalesDA NoConsideracion="40">
				<TipoRegla>7</TipoRegla>
				<ValorRegla>$request->cod_municipio</ValorRegla>
			</ConsideracionesAdicionalesDA>
			<ConsideracionesAdicionalesDA NoConsideracion="40">
				<TipoRegla>19</TipoRegla>
				<ValorRegla>$request->tipo_persona</ValorRegla>
			</ConsideracionesAdicionalesDA>
			<ConsideracionesAdicionalesDA NoConsideracion="40">
				<TipoRegla>20</TipoRegla>
				<ValorRegla>$nacimiento</ValorRegla>
			</ConsideracionesAdicionalesDA>
			<ConsideracionesAdicionalesDA NoConsideracion="40">
				<TipoRegla>21</TipoRegla>
				<ValorRegla>$request->nacionalidad</ValorRegla>
			</ConsideracionesAdicionalesDA>
			<ConsideracionesAdicionalesDA NoConsideracion="40">
				<TipoRegla>23</TipoRegla>
				<ValorRegla>$request->ocupacion</ValorRegla>
			</ConsideracionesAdicionalesDA>
			<ConsideracionesAdicionalesDA NoConsideracion="40">
				<TipoRegla>24</TipoRegla>
				<ValorRegla>$request->giro</ValorRegla>
			</ConsideracionesAdicionalesDA>
			<ConsideracionesAdicionalesDA NoConsideracion="40">
				<TipoRegla>25</TipoRegla>
				<ValorRegla>$request->profesion</ValorRegla>
			</ConsideracionesAdicionalesDA>
			<ConsideracionesAdicionalesDA NoConsideracion="40">
				<TipoRegla>26</TipoRegla>
				<ValorRegla>$request->email</ValorRegla>
			</ConsideracionesAdicionalesDA>	
			<ConsideracionesAdicionalesDA NoConsideracion="40">
				<TipoRegla>27</TipoRegla>
				<ValorRegla>$request->curp</ValorRegla>
			</ConsideracionesAdicionalesDA>
			<ConsideracionesAdicionalesDA NoConsideracion="40">
				<TipoRegla>28</TipoRegla>
				<ValorRegla>$request->rfc</ValorRegla>
			</ConsideracionesAdicionalesDA>
			<ConsideracionesAdicionalesDA NoConsideracion="40">
				<TipoRegla>36</TipoRegla>
				<ValorRegla>$request->nombre_cont</ValorRegla>
			</ConsideracionesAdicionalesDA>
			<ConsideracionesAdicionalesDA NoConsideracion="40">
				<TipoRegla>37</TipoRegla>
				<ValorRegla>$request->apepat_cont</ValorRegla>
			</ConsideracionesAdicionalesDA>
			<ConsideracionesAdicionalesDA NoConsideracion="40">
				<TipoRegla>38</TipoRegla>
				<ValorRegla>$request->apemat_cont</ValorRegla>
			</ConsideracionesAdicionalesDA>
			<ConsideracionesAdicionalesDA NoConsideracion="40">
				<TipoRegla>39</TipoRegla>
				<ValorRegla>$request->tipo_persona_cont</ValorRegla>
			</ConsideracionesAdicionalesDA>
			<ConsideracionesAdicionalesDA NoConsideracion="40">
				<TipoRegla>47</TipoRegla>
				<ValorRegla>$request->curp_cont</ValorRegla>
			</ConsideracionesAdicionalesDA>
			<ConsideracionesAdicionalesDA NoConsideracion="40">
				<TipoRegla>48</TipoRegla>
				<ValorRegla>$request->rfc_cont</ValorRegla>
			</ConsideracionesAdicionalesDA>
			<ConsideracionesAdicionalesDA NoConsideracion="40">
        		<TipoRegla>70</TipoRegla>
        		<ValorRegla>$request->telefono</ValorRegla>
      		</ConsideracionesAdicionalesDA>
      		<ConsideracionesAdicionalesDA NoConsideracion="40">
			<TipoRegla>41</TipoRegla>
			<ValorRegla>1</ValorRegla>
			</ConsideracionesAdicionalesDA>
			<ConsideracionesAdicionalesDA NoConsideracion="40">
			<TipoRegla>39</TipoRegla>
			<ValorRegla>2</ValorRegla>
			</ConsideracionesAdicionalesDA>
		</DatosAsegurado>
		<DatosVehiculo NoInciso="1">
			<ClaveAmis>$camis</ClaveAmis>
			<Modelo>$modelo</Modelo>
			<DescripcionVehiculo/>
			<Uso>1</Uso>
			<Servicio>1</Servicio>
			<Paquete>$request->paquete_id</Paquete>
			<Motor>$request->num_motor</Motor>
			<Serie>$request->serie</Serie>
		</DatosVehiculo>
		<DatosGenerales>
			<FechaEmision>$hoyFor</FechaEmision>
			<FechaInicio>$hoyFor</FechaInicio>
			<FechaTermino>$vencimiento</FechaTermino>
			<Moneda>0</Moneda>
			<Agente>74285</Agente>
			<FormaPago>C</FormaPago>
			<TarifaValores>LINEA</TarifaValores>
			<TarifaCuotas>LINEA</TarifaCuotas>
			<TarifaDerechos>LINEA</TarifaDerechos>
			<Plazo/>
			<Agencia/>
			<Contrato/>
			<PorcentajeDescuento>20</PorcentajeDescuento>
			<ConsideracionesAdicionalesDG NoConsideracion="1">
			  <TipoRegla>1</TipoRegla>
			  <ValorRegla>$dig</ValorRegla>
			</ConsideracionesAdicionalesDG>
			<ConsideracionesAdicionalesDG NoConsideracion="4">
			  <TipoRegla>1</TipoRegla>
			  <ValorRegla>0</ValorRegla>
			</ConsideracionesAdicionalesDG>
		</DatosGenerales>
		<Primas>
			<PrimaNeta/>
			<Derecho>520</Derecho>
			<Recargo/>
			<Impuesto/>
			<PrimaTotal/>
			<Comision/>
	  	</Primas>
		<CodigoError/>
	</Movimiento>
</Movimientos>
XML;

		try{
			// dd($xmlpoliza);
			$client = $this->clientCotiza->obtenerNuevaEmision(array('xmlEmision'=>$xmlpoliza));
			$xml = simplexml_load_string($client->obtenerNuevaEmisionResult);
			$response = json_decode(json_encode($xml), true);
			// dd($xmlpoliza,$response);
			if($response['Movimiento']['CodigoError']){
				if (substr($response['Movimiento']['CodigoError'],0,4) == "0041") {
					// dd($response['Movimiento']['CodigoError']);
					// "0003433914"
					$noPoliza = substr( $response['Movimiento']['CodigoError'],-16,10);
					// dd($noPoliza);
					$noEndoso = substr( $response['Movimiento']['CodigoError'],-6,6);
					// dd($noEndoso);
					$ramo = substr( $response['Movimiento']['CodigoError'],-18,2);
					// dd($ramo);
					$impresion = $this->clientCotizaImpresion->RecuperaImpresionM15(['nPoliza'=>$noPoliza,'URLPoliza'=>"",'URLRecibo'=>"",'URLTextos'=>"",'Inciso'=>"0001",'ImpPol'=>0,'ImpRec'=>0,'ImpAnexo'=>0,'Ramo'=>$ramo,'formaPol'=>"polizaf1_logoQ_pdf",'formaRec'=>"recibo_logoQ_pdf",'formaAnexo'=>"polizaf2_logoQ_pdf",'Endoso'=>$noEndoso,'NoNegocio'=>"5545",'Agente'=>"74285",'Usuario'=>"Hola",'Password'=>"102030"]);
					// dd($impresion);
					$urlString = $impresion->RecuperaImpresionM15Result;

				}
				else{

					return response()->json(['error'=>$response['Movimiento']['CodigoError']],500);
				}
			}
			else{
				$noPoliza = substr( $response['Movimiento']['@attributes']['NoPoliza'],2,10);
				$noEndoso = substr( $response['Movimiento']['@attributes']['NoPoliza'],12,6);
				$ramo = substr( $response['Movimiento']['@attributes']['NoPoliza'],0,2);
				$noNegocio= $response['Movimiento']['@attributes']['NoNegocio'];
				$agente = $response['Movimiento']['DatosGenerales']['Agente'];
				$noInciso = $response['Movimiento']['DatosVehiculo']['@attributes']['NoInciso'];
				// var_dump($noPoliza);
				// var_dump($noEndoso);
				// var_dump($ramo);
				$impresion = $this->clientCotizaImpresion->RecuperaImpresionM15(['nPoliza'=>$noPoliza,'URLPoliza'=>"",'URLRecibo'=>"",'URLTextos'=>"",'Inciso'=>$noInciso,'ImpPol'=>0,'ImpRec'=>0,'ImpAnexo'=>0,'Ramo'=>$ramo,'formaPol'=>"polizaf1_logoQ_pdf",'formaRec'=>"recibo_logoQ_pdf",'formaAnexo'=>"polizaf2_logoQ_pdf",'Endoso'=>$noEndoso,'NoNegocio'=>$noNegocio,'Agente'=>$agente,'Usuario'=>"Hola",'Password'=>"102030"]);
				$urlString = $impresion->RecuperaImpresionM15Result;
			}
			$urls=[];

			foreach (explode('|',$urlString) as $url) {
				array_push($urls,$url);
			}

			  Mail::to($request->email)->send(new EmisionPoliza($urls,'QA'));

			return view('qualitas.pago',['urls'=>$urls,'noPoliza'=>$noPoliza]);
			// dd($urls);
		}
		catch(SoapFault $fault){
			dd($fault);
		}

	}
}
