<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleXMLElement;
use Carbon\Carbon;
use App\Cliente;
use SoapClient;
use GuzzleHttp\Client;
use \Curl\Curl;
use DB;
use Mail;
use App\Mail\EmisionPoliza;
class GNPController extends Controller
{
    protected $opts;

    /**
     * Método para inicializar las url del WebService y opciones por default para hacer las peticiones
     * @return void
     */
 	public function __construct(){

		// DATOS GNP
		$this->user = env('GNP_USER', true);
		$this->pass = env('GNP_PASSWORD', true);
		$this->unidadOperable = env('GNP_UNIDAD_OPERABLE', true);
		$this->intermediario = env('GNP_INTERMEDIARIO', true);
		try {
			$this->curl = new Curl();
			$this->curl->setHeader('Content-Type', 'application/xml');

		} catch (Exception $e) {
			print_r($e->getMessage());
			print_r($e->getTrace());
			print_r($e->getFile());
			print_r($e->getLine());
		}
 	}

 	/**
 	 * Función para prueba 
 	 * @return string
 	 */
 	public function prueba2(Request $request)
 	{
 		// dd($request);
 		
 		$Modelos = $this->BusquedaModelos($request->año,'SERIE301');
 	
 		return view('prueba',['modelos'=>$Modelos,'submarca'=>$request->submarca]);
 	}

 	public function marcasAño($año){
 		
 		$Modelos = $this->BusquedaModelos($año,'SERIE301');
 		$marcas =[];
 		foreach ($Modelos['ELEMENTOS'] as $key => $value) {

 			$aux =  $value["ELEMENTO"][1]["VALOR"];
 			array_push($marcas, $aux);
 		}
 	
 		// dd();
 		// 
 		
 		return response()->json(['marcas'=>array_unique($marcas)],200);
 	}

 	public function submarcas($año,$marca){

 			// dd($marca,$año);
 			$Modelos = $this->BusquedaModelos($año,'SERIE301');
 			$submarcas =[];
 			$submarcas2 =[];

 			foreach ($Modelos['ELEMENTOS'] as $key => $value) {
 				// dd($value);
 				if ($value["ELEMENTO"][1]["VALOR"] === $marca) {
 					$aux =  $value["ELEMENTO"][3]["VALOR"];

 					$aux2 = array(
 						'CARROCERIA'=>$value["ELEMENTO"][3]["VALOR"],
 						'MODELO'=>$value["ELEMENTO"][2]["VALOR"],
 						'ARMADORA'=>$value["ELEMENTO"][1]["VALOR"],
 						'VERSION'=>$value["ELEMENTO"][4]["VALOR"]

 					);
 					array_push($submarcas, $aux);
 					array_push($submarcas2, $aux2);
 				}
 			
 		}
 			// dd($submarcas,$submarcas2);
 			return response()->json(['submarcas'=>array_unique($submarcas)],200);
 	}

 	public function versiones($año,$marca){

 		dd($año,$marca);

 	}

 	public function prueba(request $request)
 	{

 			 // dd($request);
 		return view('prueba');
 		/* dd($this->getTiposVia()); */
 		/* dd($this->modelos("Ford", "focus", "2015")); */

 		/* return view('prueba'); */
 		/* dd($this->getTiposVia());
		 dd($this->modelos("Ford", "fiesta", "2015")); */
		 $cp ="56334" ;
		  $fecha_inicio= "20210111";
		  $fecha_fin = "20220111"; 
		$modelo = "2015";
		$armadora ="HO";
		 $carroceria="06"; 
		 $version="14"; 
		 $nacimiento="19840101"; 
		 $sexo="M";
		  $edad="22"; 
		 $clavePaquete="PRS0009355"; 
		 $poliza="Amplia";

		try {
			// $modelos    = $this->getModelos($modelo, $armadora, $carroceria);
 		/*  return response()->json(['modelosGNP'=>$modelos],201); 
		 	$this->curl->post("https://api.service.gnp.com.mx/autos/wsp/cotizador/cotizar", $this->getXMLCotizacion($cp, $fecha_inicio, $fecha_fin,  $modelo, $armadora,
			$carroceria, $version, $nacimiento, $sexo, $edad, $clavePaquete, $poliza)); 
	        //convert the XML result into array
	        $array_data = json_decode(json_encode(simplexml_load_string($this->curl->response)), true);
			$prueba =  json_encode($modelos); */
				$xml_1=	'<SOLICITUD>
						<USUARIO>EMOREN927586</USUARIO>
						<PASSWORD>Moreno2021</PASSWORD>										 
						 <TIPO_DOCUMENTO>A</TIPO_DOCUMENTO>								
						<FORMATO>P</FORMATO>
						<POLIZA>00000457650513</POLIZA>
						<INTERMEDIARIO>0018601001</INTERMEDIARIO>
						</SOLICITUD>'
							 ;

$xml ='
<IMPRESION_POLIZA>
<USUARIO>EMOREN927586</USUARIO>
<PASSWORD>Moreno2021</PASSWORD>
<NUM_POLIZA>00000457650513</NUM_POLIZA>
<NUM_VERSION>0</NUM_VERSION>
<EXTENSION_ARCHIVO>PDF</EXTENSION_ARCHIVO>
</IMPRESION_POLIZA>';
			// $xml="  <SOLICITUD_CATALOGO>
			// <USUARIO>EMOREN927586</USUARIO>
			// <PASSWORD>Moreno2021</PASSWORD>
		 //   <TIPO_CATALOGO>VEHICULOS</TIPO_CATALOGO>
		 //   <ID_UNIDAD_OPERABLE>NOP0000016</ID_UNIDAD_OPERABLE>
			// <FECHA>24/01/2021</FECHA> 
		 //   <ELEMENTOS>
		 //   <ELEMENTO>
			//   <CLAVE>AUT</CLAVE>
			// <NOMBRE>TIPO_VEHICULO</NOMBRE>
		 //  </ELEMENTO>
		 //   <ELEMENTO>
		 // <ELEMENTO>
		 //  <ELEMENTO>
				   // <NOMBRE>ARMADORA</NOMBRE>
    			// 	<CLAVE>CH</CLAVE>
				   //  </ELEMENTO>
   //  		<NOMBRE>ARMADORA</NOMBRE>
   //  		<CLAVE>CH</CLAVE>
   //  		</ELEMENTO>
				$modelos = $this->BusquedaModelo('2012', 'SERIE301');
				$modelosAño = $this->BusquedaModelos('2012','SERIE301');
				// // $carroceria = $this->getCarroceria("HO", "CIVIC");
				// // $modelos    = $this->getModelos($modelo, $armadora, $carroceria);
				dd($modelos,json_encode($modelosAño));
		 // </ELEMENTO>
		 // </ELEMENTOS>  
		 // </SOLICITUD_CATALOGO>";
		 // $this->curl->post("https://api.service.gnp.com.mx/autos/wsp/recibofiscal/recibofiscal/recibofiscal", $xml_1);
				
			// $PDF = $this->ObtenerPdfPoliza('00000458783727');

				// $this->curl->post("https://api.service.gnp.com.mx/autos/wsp/impresion/buscarPoliza",$xml);
	        //convert the XML result into array
	        // $array_data = json_decode(json_encode(simplexml_load_string($this->curl->response)), true);
	        // // dd($PDF);
	        // $cadena = $array_data['DOCUMENTO']['CADENA_BINARIA'];
	        // $RESULTADO = base64_decode($cadena,true);
	        	// $RESULTADO2 = imap_utf7_decode($cadena);
	        	// $RESULTADO3 = convert_uudecode($cadena);
	        	// $RESULTADO4 = imap_base64 ($cadena);
	        	// 	$RESULTADO5 = utf8_decode($cadena);
	        	// 		$RESULTADO6 = urlencode($cadena);
	        			
	        // $array_data = json_decode($this->curl->response);
	  //       $armadora="";
	  //       $marca="SPARK";
	  //       if (isset($array_data['ELEMENTOS'])) {
 		// 	foreach ($array_data['ELEMENTOS'] as $value) {
 		// 		// if ($value['ELEMENTO']['NOMBRE'] === strtoupper("CARROCERIA")){
 		// 		// 	$armadora = $value['CLAVE'];
 		// 		// }
 		// 		dd($value);
 		// 	}
 		// }
 			// dd($xml_1, $RESULTADO);
	        // return $RESULTADO;
			
	        // print_r('<pre>');
	        // print_r($prueba);
	        // print_r('</pre>');

		} catch (Exception $e) {
			dd($e);
		}
 		/* return 'Hola hay cambio'; */

 	}

 	/**
 	 * Metodo para obtener los datos necesario para la cotizacion de un modelo, marca y submarca de un auto.
 	 * @param string $marca - nombre de la marca a obtener.
 	 * @param string $submarca - Nombre de la submarca del auto.
 	 * @param string $modelo - (año) del auto.
 	 * @return string Modelos encontrado los cuales contiene, en formato JSON.
 	 */
 	public function modelos($marca, $submarca, $modelo)
 	{
 		$armadora   = $this->getArmadora($modelo, $marca);
 		// dd($armadora);
 		$modelosS="";
 		// if($armadora===""){
 		$modelosS = $this->BusquedaModelo($modelo, $submarca);
 		// dd($modelosS);

 		// }
 		$carroceria = $this->getCarroceria($armadora, $submarca);
 		// dd($carroceria);
 		$modelos    = $this->getModelos($modelo, $armadora, $carroceria);
 		

		 // $carroceria = $this->getCarroceria($armadora, $submarca);
		 //  dd($carroceria); 
		 // $modelos    = $this->getModelos($modelo, $armadora, $carroceria);
		  // dd($modelos,$carroceria,$armadora,$marca, $submarca, $modelo); 
 		// dd($modelos,$modelosS);
 		return response()->json(['modelosGNP'=>$modelos,'modelosGNP2'=>$modelosS],201);
 	}

 	public function BusquedaModelos($modelo, $submarca){
 		$fecha = date('d/m/Y');
 		$xml="	<SOLICITUD_CATALOGO>
				   <USUARIO>$this->user</USUARIO>
				   <PASSWORD>$this->pass</PASSWORD>    
				   <TIPO_CATALOGO>VEHICULOS</TIPO_CATALOGO>
				   <ID_UNIDAD_OPERABLE>$this->unidadOperable</ID_UNIDAD_OPERABLE>
				 	<FECHA>$fecha</FECHA>
				    <ELEMENTOS>
				    <ELEMENTO>
				    <NOMBRE>TIPO_VEHICULO</NOMBRE>
				    <CLAVE>AUT</CLAVE>
				    </ELEMENTO>
				    <ELEMENTO>
    				<NOMBRE>MODELO</NOMBRE>
    				<CLAVE>$modelo</CLAVE>
    				</ELEMENTO>
				   </ELEMENTOS>  
				</SOLICITUD_CATALOGO> ";
			return  $this->buscarEnCatalogo($xml);
 	}

 	public function ObtenerPdfPoliza($poliza){

 			$xml = "
			<IMPRESION_POLIZA>
			<USUARIO>EMOREN927586</USUARIO>
			<PASSWORD>Moreno2021</PASSWORD>
			<NUM_POLIZA>$poliza</NUM_POLIZA>
			<NUM_VERSION>0</NUM_VERSION>
			<EXTENSION_ARCHIVO>PDF</EXTENSION_ARCHIVO>
			</IMPRESION_POLIZA>";

			try {
			
			
				$this->curl->post("https://api.service.gnp.com.mx/autos/wsp/impresion/buscarPoliza",$xml);
 				$array_data = json_decode($this->curl->response);
	        // dd($xml,$array_data,$poliza);
	        return $array_data;
		} catch (Exception $e) {

			return response()->json(['error'=>"Fallo la peticion"],400);
		}
 		
 		}





 		public function BusquedaModelo($modelo, $submarca)
 	{

 		if ($submarca == ' C-HR') {
 			$submarca = 'C HR';
 		}
 		if($submarca == 'SERIE 208'){
			$submarca ='PEUGEOT 208';
 		}
 		if($submarca == 'SERIE 2008'){
			$submarca ='PEUGEOT 2008';
 		}
 		if($submarca == 'SERIE 3008'){
			$submarca ='PEUGEOT 3008';
 		}
 		if($submarca == 'SERIE 308'){
			$submarca ='PEUGEOT 308';
 		}
 		if($submarca == 'SERIE 301'){
			$submarca ='PEUGEOT 301';
 		}
 		if($submarca == 'SERIE 207'){
			$submarca ='PEUGEOT 207';
 		}
 		if($submarca == 'SERIE 508'){
			$submarca ='PEUGEOT 508';
 		}
 		if($submarca == 'CR-V'){
			$submarca ='CR V'; 
 		}
 		if($submarca == 'BR-V'){
			$submarca ='BR V'; 
 		}
 		if($submarca == 'IMPREZA WRX'){
			$submarca ='IMPREZA'; 
 		}
 		if($submarca == 'SPIDER'){
			$submarca ='ALFA ROMEO 147'; 
 		}
 		if($submarca == 'Q7'){
			$submarca ='AUDI Q7'; 
 		}
 		if($submarca == 'Q2'){
			$submarca ='AUDI Q2'; 
 		}
 		if($submarca == 'Q3'){
			$submarca ='AUDI Q3'; 
 		}
 		if($submarca == 'Q5'){
			$submarca ='AUDI Q5'; 
 		}
 		if($submarca == 'Q8'){
			$submarca ='AUDI Q8'; 
 		}
 		if($submarca == 'QT'){
			$submarca ='AUDI QT'; 
 		}
 		if($submarca == 'S-TYPE'){
			$submarca ='S TYPE'; 
 		}
 		if($submarca == 'S-TYPE'){
			$submarca ='S TYPE'; 
 		}
 		if($submarca == 'MARINER'){
			$submarca ='MERCURY MARINER'; 
 		}
 		if($submarca == '156'){
			$submarca ='ALFA ROMEO 147'; 
 		}
 		if($submarca == '159'){
			$submarca ='ALFA ROMEO 147'; 
 		}
 		if($submarca == '166'){
			$submarca ='ALFA ROMEO 147'; 
 		}
 		if($submarca == 'BRERA'){
			$submarca ='ALFA ROMEO 147'; 
 		}

 		$Modelos = $this->BusquedaModelos($modelo,$submarca);
 		// $json_mod = json_decode($Modelos);
 		// $bandera = false;
 		$Mods = [];
 		// dd($Modelos);
 	
 		$Modelo = '';
 		// dd($armadoras);
 			// print_r($Modelos);
 			// dd($Modelos['ELEMENTOS']['Array']['ELEMENTO']);
 			// La longitud de todos los modelos que existen de ese año
 			$longitud = count($Modelos['ELEMENTOS']);


 			for ($i=0; $i <$longitud ; $i++) { 
 				//Buscamos que coincida la submarca para obtener la descripcion del auto
 				if ($Modelos['ELEMENTOS'][$i]['ELEMENTO'][3]['VALOR'] === $submarca) {
 					//Aqui debo guardarlo en un array
 					
 					$Mods2 = array(
 								'CLAVE'=> $Modelos['ELEMENTOS'][$i]['ELEMENTO'][4]['CLAVE'],
 								'NOMBRE' =>$Modelos['ELEMENTOS'][$i]['ELEMENTO'][4]['NOMBRE'],
 								'VALOR' =>$Modelos['ELEMENTOS'][$i]['ELEMENTO'][4]['VALOR'],
 								'CARROCERIA'=>$Modelos['ELEMENTOS'][$i]['ELEMENTO'][3]['CLAVE'],
 								'MODELO'=>$Modelos['ELEMENTOS'][$i]['ELEMENTO'][2]['VALOR'],
 								'ARMADORA'=>$Modelos['ELEMENTOS'][$i]['ELEMENTO'][1]['CLAVE']
 							);
 					array_push($Mods, $Mods2);

 					//Con esta validacion verificamos si la submarca se enceuntra en carroceria con marca
 				}
 				// elseif (str_contains($Modelos['ELEMENTOS'][$i]['ELEMENTO'][3]['VALOR'],$submarca)) {
 				// 	$Mods2 = array(
 				// 				'CLAVE'=> $Modelos['ELEMENTOS'][$i]['ELEMENTO'][4]['CLAVE'],
 				// 				'NOMBRE' =>$Modelos['ELEMENTOS'][$i]['ELEMENTO'][4]['NOMBRE'],
 				// 				'VALOR' =>$Modelos['ELEMENTOS'][$i]['ELEMENTO'][4]['VALOR'],
 				// 				'CARROCERIA'=>$Modelos['ELEMENTOS'][$i]['ELEMENTO'][3]['CLAVE'],
 				// 				'MODELO'=>$Modelos['ELEMENTOS'][$i]['ELEMENTO'][2]['VALOR'],
 				// 				'ARMADORA'=>$Modelos['ELEMENTOS'][$i]['ELEMENTO'][1]['CLAVE']
 				// 			);
 				// 	array_push($Mods, $Mods2);
 				// }

 				//VERSION DEL MODELO ESTE DATO ES LA DESCRIPCION QUE VOY A MOSTRAR 
 				// dd($Modelos['ELEMENTOS'][$i]['ELEMENTO'],$longitud,$submarca);
 			}
 			// var_dump($Modelos);
 			// dd($Mods,$submarca);
 			if (count($Mods) == 0) {
 				$Mods2 = array(
 								'CLAVE'=> '',
 								'NOMBRE' =>'',
 								'VALOR' =>'MODELO NO DISPONIBLE EN GNP',
 								'CARROCERIA'=>'',
 								'MODELO'=>'',
 								'ARMADORA'=>''
 							);
 					array_push($Mods, $Mods2);
 			}
 			
 

 		return $Mods;
 	}

 	private function buscarEnCatalogo($xmlBody)
 	{
 		try {
			
			$this->curl->post("https://api.service.gnp.com.mx/autos/wsp/catalogos/catalogo", $xmlBody);
	        //convert the XML result into array
	        $array_data = json_decode(json_encode(simplexml_load_string($this->curl->response)), true);
	       // var_dump($array_data); 
	        return $array_data;
		} catch (Exception $e) {
			dd($e,$xmlBody);
			/* dd($xmlBody); */

		}
 	}

 	/**
 	 * Metodo usado para obtener la cadena XML necesaria para el WebService y hacer una cotizacion
 	 * @param string $cp
 	 * @param string $fecha_inicio
 	 * @param string $fecha_fin
 	 * @param string $modelo
 	 * @param string $armadora
 	 * @param string $carroceria
 	 * @param string $version
 	 * @param string $nacimiento
 	 * @param string $sexo
 	 * @param string $edad
 	 * @param string $clavePaquete
 	 * @param string $poliza
 	 * @return string Cadena con la estructura para hacer la cotizacion ya con los datos necesarios.
 	 */
 	private function getXMLCotizacion($cp, $fecha_inicio, $fecha_fin,  $modelo, $armadora,
 		$carroceria, $version, $nacimiento, $sexo, $edad, $clavePaquete, $poliza)
 	{
 		return  "<COTIZACION>
				  <SOLICITUD>
				    <USUARIO>$this->user</USUARIO>
				    <PASSWORD>$this->pass</PASSWORD>
				    <ID_UNIDAD_OPERABLE>$this->unidadOperable</ID_UNIDAD_OPERABLE>
				    <FCH_INICIO_VIGENCIA>$fecha_inicio</FCH_INICIO_VIGENCIA>
				    <FCH_FIN_VIGENCIA>$fecha_fin</FCH_FIN_VIGENCIA>
				    <VIA_PAGO>IN</VIA_PAGO>
				    <PERIODICIDAD></PERIODICIDAD>
				    <ELEMENTOS>
				      <ELEMENTO>
				        <NOMBRE>INTERMEDIARIO</NOMBRE>
				        <CLAVE>$this->intermediario</CLAVE>
				        <VALOR>$this->intermediario</VALOR>
				      </ELEMENTO>
				    </ELEMENTOS>
				  </SOLICITUD>
				  <VEHICULO>
				    <SUB_RAMO>01</SUB_RAMO>
				    <TIPO_VEHICULO>AUT</TIPO_VEHICULO>
				    <MODELO>$modelo</MODELO>
				    <ARMADORA>$armadora</ARMADORA>
				    <CARROCERIA>$carroceria</CARROCERIA>
				    <VERSION>$version</VERSION>
				    <USO>01</USO>
				    <FORMA_INDEMNIZACION>03</FORMA_INDEMNIZACION>
				    <VALOR_FACTURA></VALOR_FACTURA>
				  </VEHICULO>
				  <CONTRATANTE>
				    <TIPO_PERSONA>F</TIPO_PERSONA>
				    <CODIGO_POSTAL>$cp</CODIGO_POSTAL>
				  </CONTRATANTE>
				  <CONDUCTOR>
				    <FCH_NACIMIENTO>$nacimiento</FCH_NACIMIENTO>
				    <SEXO>$sexo</SEXO>
				    <EDAD>$edad</EDAD>
				    <CODIGO_POSTAL>$cp</CODIGO_POSTAL>
				  </CONDUCTOR>
				  <PAQUETES>
				    <PAQUETE>
				      <CVE_PAQUETE>$clavePaquete</CVE_PAQUETE>
				      <DESC_PAQUETE>$poliza</DESC_PAQUETE>
				      <COBERTURAS/>
				    </PAQUETE>
				  </PAQUETES>
				</COTIZACION>";
 	}
 	private function getXMLCotizacionM($cp, $fecha_inicio, $fecha_fin,  $modelo, $armadora,
 		$carroceria, $version, $nacimiento, $sexo, $edad, $clavePaquete, $poliza)
 	{
 		return  "<COTIZACION>
				  <SOLICITUD>
				    <USUARIO>$this->user</USUARIO>
				    <PASSWORD>$this->pass</PASSWORD>
				    <ID_UNIDAD_OPERABLE>$this->unidadOperable</ID_UNIDAD_OPERABLE>
				    <FCH_INICIO_VIGENCIA>$fecha_inicio</FCH_INICIO_VIGENCIA>
				    <FCH_FIN_VIGENCIA>$fecha_fin</FCH_FIN_VIGENCIA>
				    <VIA_PAGO>IN</VIA_PAGO>
				    <PERIODICIDAD></PERIODICIDAD>
				    <ELEMENTOS>
				      <ELEMENTO>
				        <NOMBRE>INTERMEDIARIO</NOMBRE>
				        <CLAVE>$this->intermediario</CLAVE>
				        <VALOR>$this->intermediario</VALOR>
				      </ELEMENTO>
				    </ELEMENTOS>
				  </SOLICITUD>
				  <VEHICULO>
				    <SUB_RAMO>01</SUB_RAMO>
				    <TIPO_VEHICULO>AUT</TIPO_VEHICULO>
				    <MODELO>$modelo</MODELO>
				    <ARMADORA>$armadora</ARMADORA>
				    <CARROCERIA>$carroceria</CARROCERIA>
				    <VERSION>$version</VERSION>
				    <USO>01</USO>
				    <FORMA_INDEMNIZACION>03</FORMA_INDEMNIZACION>
				    <VALOR_FACTURA></VALOR_FACTURA>
				  </VEHICULO>
				  <CONTRATANTE>
				    <TIPO_PERSONA>M</TIPO_PERSONA>
				    <CODIGO_POSTAL>$cp</CODIGO_POSTAL>
				  </CONTRATANTE>
				  <CONDUCTOR>
				    <FCH_NACIMIENTO>$nacimiento</FCH_NACIMIENTO>
				    <SEXO>$sexo</SEXO>
				    <EDAD>$edad</EDAD>
				    <CODIGO_POSTAL>$cp</CODIGO_POSTAL>
				  </CONDUCTOR>
				  <PAQUETES>
				    <PAQUETE>
				      <CVE_PAQUETE>$clavePaquete</CVE_PAQUETE>
				      <DESC_PAQUETE>$poliza</DESC_PAQUETE>
				      <COBERTURAS/>
				    </PAQUETE>
				  </PAQUETES>
				</COTIZACION>";
 	}

 	/**
 	 * Metodo para obtener la clave de una armadora por modelo (año) y el nombre de la armadora.
 	 * 
 	 * @param  $modelo (año) para buscar las armadores disponibles con ese año.
 	 * @param  $armadora nombre de la marca (quien arma el vehiculo).
 	 * @return string[] Datos de la armadora - clave, nombre, valor segun el catalogo de GNP.
 	 */
 	public function getArmadora($modelo, $marca)
 	{
 		$armadoras = $this->getArmadoras($modelo);
 		// dd($armadoras);
 		$armadora = '';
 		// dd($armadoras);
 		if (isset($armadoras['ELEMENTOS'])) {
 			foreach ($armadoras['ELEMENTOS']['ELEMENTO'] as $value) {
 				if ($value['NOMBRE'] === strtoupper($marca)){
 					$armadora = $value['CLAVE'];
 				}
 			}
 		}

 		return $armadora;
 	}

 	/**
 	 * Metodo para obtener las armadoras disponibles por modelo (año).
 	 * 
 	 * @param  $modelo (año) para buscar las armadores disponibles con ese año.
 	 * @return string[] Elementos de todas las armadoras.
 	 */
 	private function getArmadoras($modelo)
 	{
 		$fecha = date('d/m/Y');
 		$xmlBody = "<SOLICITUD_CATALOGO>
					   <USUARIO>$this->user</USUARIO>
					   <PASSWORD>$this->pass</PASSWORD>
					   <TIPO_CATALOGO>ARMADORA_VEHICULO</TIPO_CATALOGO>
					   <ID_UNIDAD_OPERABLE>$this->unidadOperable</ID_UNIDAD_OPERABLE>
					   <FECHA>$fecha</FECHA> 
					   <ELEMENTOS>
					   	<ELEMENTO>
					       <CLAVE>AUT</CLAVE>
					       <NOMBRE>TIPO_VEHICULO</NOMBRE>
					    </ELEMENTO>
					   	<ELEMENTO>
					   		<NOMBRE>MODELO</NOMBRE>
					   		<CLAVE>$modelo</CLAVE> 
					   	</ELEMENTO>
					   </ELEMENTOS>  
					</SOLICITUD_CATALOGO>";
/* 		dd($xmlBody); */
 		return  $this->buscarEnCatalogo($xmlBody);
 		
 	}

 	/**
 	 * Metodo para obtener las marcas disponibles en GNP.
 	 * @param  $modelo (año) del vehiculo.
 	 * @param  $armadora Clave de la armadora (marca).
 	 * @param  $carroceria Clave de la carroceria (submarca) para buscr los modelos (vrsiones) que hay.
 	 * @return string[] Elementos de todas las versiones de un vehiculo.
 	 */
 	public function getModelos($modelo, $armadora, $carroceria)
 	{
 		$fecha = date('d/m/Y');
 		$xmlBody = "<SOLICITUD_CATALOGO>
					   <USUARIO>$this->user</USUARIO>
					   <PASSWORD>$this->pass</PASSWORD>
					   <TIPO_CATALOGO>VEHICULOS</TIPO_CATALOGO>
					   <ID_UNIDAD_OPERABLE>$this->unidadOperable</ID_UNIDAD_OPERABLE>
					   <FECHA>$fecha</FECHA> 
					   <ELEMENTOS>
					      <ELEMENTO>
					         <CLAVE>$modelo</CLAVE>
					         <NOMBRE>MODELO</NOMBRE>
					      </ELEMENTO>
					      <ELEMENTO>
					         <CLAVE>$armadora</CLAVE>
					         <NOMBRE>ARMADORA</NOMBRE>
					      </ELEMENTO>
					      <ELEMENTO>
					         <CLAVE>$carroceria</CLAVE>
					         <NOMBRE>CARROCERIA</NOMBRE>
					      </ELEMENTO>
					   </ELEMENTOS>  
					</SOLICITUD_CATALOGO>";
					 

 		return $this->buscarEnCatalogo($xmlBody);
 	}

 	/**
 	 * Metodo para obtener la(s) clave(s) de una carroceria por medio del modelo y el nombre de la submarca
 	 * 
 	 * @param  $armadora Clave de la armadora (marca) para la busqueda.
 	 * @param  $submarca nombre de la submarca (nombre especifico).
 	 * @return string[] | String  Clave de la(s) carroceria(s).
 	 */
 	public function getCarroceria($armadora, $submarca)
 	{
		 $carrocerias = $this->getCarrocerias($armadora);
		  // dd($carrocerias, $submarca); 
		 $carroceria = '';
		 $aux="";

 		 if (isset($carrocerias['ELEMENTOS'])) { 
 			foreach ($carrocerias['ELEMENTOS']['ELEMENTO'] as $value) {
 				if ($value['NOMBRE'] == $submarca){
					 $carroceria = $value['CLAVE'];
					 /* dd("Si se encontro"); */
				 }
				 if (strcmp($value['NOMBRE'], $submarca) === 0){
					$aux= $value['CLAVE'];
					$carroceria =$aux;
				}
 			}
 		 } 
		/*  dd($carroceria,$submarca,$aux); */
 		return $carroceria;
 	}

 	/**
 	 * Metodo para obtener la(s) clave(s) de una carroceria por medio del modelo y el nombre de la submarca
 	 * 
 	 * @param  $armadora Clave de la armadora (marca) para la busqueda.
 	 * @return string[] | String  Arreglo de elementos segun la especificación de GNP de las carrocerias obtenidas.
 	 */
 	private function getCarrocerias($armadora='')
 	{
 		$fecha = date('d/m/Y');
 		$xmlBody = "<SOLICITUD_CATALOGO>
					   <USUARIO>$this->user</USUARIO>
					   <PASSWORD>$this->pass</PASSWORD>
					   <TIPO_CATALOGO>CARROCERIA_VEHICULO</TIPO_CATALOGO>
					   <ID_UNIDAD_OPERABLE>$this->unidadOperable</ID_UNIDAD_OPERABLE>
					   <FECHA>$fecha</FECHA> 
					   <ELEMENTOS>
					    <ELEMENTO>
					   		<NOMBRE>TIPO_VEHICULO</NOMBRE>
					   		<CLAVE>AUT</CLAVE> 
					   	</ELEMENTO>
					   	<ELEMENTO>
					   		<NOMBRE>ARMADORA</NOMBRE>
					   		<CLAVE>$armadora</CLAVE> 
					   	</ELEMENTO>
					   </ELEMENTOS>  
					</SOLICITUD_CATALOGO>";
		/* dd($xmlBody); */
		return $this->buscarEnCatalogo($xmlBody);
 	}

 	/**
 	 * Obtiene los datos de la cotizacion con los datos del request los cuales viene la informacion del cliente (cotizacion), 
 	 * la descriupcion del auto (descripcionGNP), y poliza (poliza) para el tipo de poliza.
 	 * @
 	 * @param  $armadora Clave de la armadora (marca) para la busqueda.
 	 * @param  $submarca nombre de la submarca (nombre especifico).
 	 * @return string[] | String  Clave de la(s) carroceria(s).
 	 */
 	public function getCotizacion(Request $request)
 	{
 		$cliente = Cliente::where('cotizacion',$request->cotizacion)->first();
 		$vehiculo = json_decode($request->descripcionGNP);
 		// dd($vehiculo,$request);
        if($cliente == null){
            return response()->json(['error'=>"datos no encontrado"],404);

        }

 		$fecha_inicio = Carbon::now()->format('Ymd');
 		$fecha_fin    = Carbon::now()->addYear()->format('Ymd');
 		$nacimiento   = Carbon::parse($cliente->f_nac)->format('Ymd');
 		$edad 		  = Carbon::parse($cliente->f_nac)->age;
 		$sexo 		  = $cliente->sexo === "Hombre" ? 'M' : 'F';
 		//año
 		// $modelo 	  = !is_null($vehiculo) ? $vehiculo[2]->VALOR : null;
 		// //marca
 		// $armadora 	  = !is_null($vehiculo) ? $vehiculo[1]->CLAVE : null;
 		// //submarca o nombre del modelo
 		// $carroceria   = !is_null($vehiculo) ? $vehiculo[3]->CLAVE : null;
 		// //descripcion completa del auto 
 		// $version 	  = !is_null($vehiculo) ? $vehiculo[4]->CLAVE : null;
 		// año
 		$modelo 	  = !is_null($vehiculo) ? $vehiculo->MODELO : null;
 		//marca
 		$armadora 	  = !is_null($vehiculo) ? $vehiculo->ARMADORA : null;
 		//submarca o nombre del modelo
 		$carroceria   = !is_null($vehiculo) ? $vehiculo->CARROCERIA : null;
 		//descripcion completa del auto 
 		$version 	  = !is_null($vehiculo) ? $vehiculo->CLAVE : null;
 		// // dd($version,$carroceria,$armadora ,$modelo);

 		$paquetesPersonaFisica = [
 			'Amplia'   => 'PRS0009355',
 			'Limitada' => 'PRS0009356',
 			'RC' 	   => 'PRP0000289'
 		];

 		$paquetesPersonaMoral = [
 			'Amplia'   => 'PRS0009361',
 			'Limitada' => 'PRS0009362',
 			'RC' 	   => 'PRP0000349'
 		];
 		$clavePaquete = $cliente->uso_auto === "Servicio Particular"
 			? $paquetesPersonaFisica[$request->poliza]
 			: $paquetesPersonaMoral[$request->poliza];

 		$data = $this->getXMLCotizacion($cliente->cp, $fecha_inicio, $fecha_fin, $modelo, $armadora,
 				$carroceria, $version, $nacimiento, $sexo, $edad, $clavePaquete, $request->poliza);
 		// dd($data,$modelo, $armadora,
 		// 		$carroceria, $version);
 		try {
			
			$this->curl->post("https://api.service.gnp.com.mx/autos/wsp/cotizador/cotizar", $data);
	        //convert the XML result into array
	        $array_data = json_decode(json_encode(simplexml_load_string($this->curl->response)), true);
	        // dd($data,$array_data);
	        return response()->json(['cotizacionGNP'=>$array_data],201);
		} catch (Exception $e) {
			return response()->json(['error'=>"Fallo la peticion"],400);
		}

 		
 	}

 	/**
 	 * Obtiene los estados que tiene cobertura de GNP
 	 * @param $cp - Código postal para obtener el estado, municipio y las colonias.
 	 * 
 	 * @return string[] arreglo de clave, nombre para cada estado
 	 */
 	public function getDatosDomicilio($cp)
 	{
 		$fecha = date('d/m/Y');
 		$xmlBodyEstado = "<SOLICITUD_CATALOGO>
						   <USUARIO>$this->user</USUARIO>
						   <PASSWORD>$this->pass</PASSWORD>
						   <TIPO_CATALOGO>ESTADO</TIPO_CATALOGO>
						   <ID_UNIDAD_OPERABLE>$this->unidadOperable</ID_UNIDAD_OPERABLE>
						   <FECHA>$fecha</FECHA> 
						   <ELEMENTOS>
						     <ELEMENTO>
						       <CLAVE>$cp</CLAVE>
						       <NOMBRE>CODIGO_POSTAL</NOMBRE>
						       <VALOR></VALOR>
						     </ELEMENTO>
						   </ELEMENTOS>  
						</SOLICITUD_CATALOGO>";

		$estado =  $this->buscarEnCatalogo($xmlBodyEstado)["ELEMENTOS"]["ELEMENTO"];

		$xmlBodyMunicipio = "<SOLICITUD_CATALOGO>
							   <USUARIO>$this->user</USUARIO>
							   <PASSWORD>$this->pass</PASSWORD>
							   <TIPO_CATALOGO>MUNICIPIO</TIPO_CATALOGO>
							   <ID_UNIDAD_OPERABLE>$this->unidadOperable</ID_UNIDAD_OPERABLE>
							   <FECHA>$fecha</FECHA> 
							   <ELEMENTOS>
							     <ELEMENTO>
							       <CLAVE>$cp</CLAVE>
							       <NOMBRE>CODIGO_POSTAL</NOMBRE>
							       <VALOR></VALOR>
							     </ELEMENTO>
							   </ELEMENTOS>  
							</SOLICITUD_CATALOGO>";

		$municipio =  $this->buscarEnCatalogo($xmlBodyMunicipio)["ELEMENTOS"]["ELEMENTO"];

		$xmlBodyColonias = "<SOLICITUD_CATALOGO>
							   <USUARIO>$this->user</USUARIO>
							   <PASSWORD>$this->pass</PASSWORD>
							   <TIPO_CATALOGO>COLONIA</TIPO_CATALOGO>
							   <ID_UNIDAD_OPERABLE>$this->unidadOperable</ID_UNIDAD_OPERABLE>
							   <FECHA>$fecha</FECHA> 
							   <ELEMENTOS>
							     <ELEMENTO>
							       <CLAVE>$cp</CLAVE>
							       <NOMBRE>CODIGO_POSTAL</NOMBRE>
							       <VALOR></VALOR>
							     </ELEMENTO>
							   </ELEMENTOS>  
							</SOLICITUD_CATALOGO>";

		$colonias =  $this->buscarEnCatalogo($xmlBodyColonias)["ELEMENTOS"]["ELEMENTO"];

		return response()->json(['estadoGNP'=>$estado, 'municipioGNP'=>$municipio, 'colonias'=>$colonias],201);
 	}

 	/**
 	 * Obtiene un arreglo con los usos que se le puede dar al vehiculo
 	 * 
 	 * @return string[] arreglo de clave, nombre para cada uso del vehiculo.
 	 */
 	public function getUsosVehiculo()
 	{
 		$fecha = date('d/m/Y');
 		$xmlBody = "<SOLICITUD_CATALOGO>
					   <USUARIO>$this->user</USUARIO>
					   <PASSWORD>$this->pass</PASSWORD>
					   <TIPO_CATALOGO>USO_VEHICULO</TIPO_CATALOGO>
					   <ID_UNIDAD_OPERABLE>$this->unidadOperable</ID_UNIDAD_OPERABLE>
					   <FECHA>$fecha</FECHA> 
					   <ELEMENTOS>
					   </ELEMENTOS>  
					</SOLICITUD_CATALOGO>";

		return response()->json(['usos'=>$this->buscarEnCatalogo($xmlBody)["ELEMENTOS"]["ELEMENTO"]], 201);
 	}

 	/**
 	 * Obtiene los estados que tiene cobertura de GNP
 	 * 
 	 * @return string[] arreglo de clave, nombre para cada estado
 	 */
 	public function getEstadosCirculacion()
 	{
 		$fecha = date('d/m/Y');
 		$xmlBody = "<SOLICITUD_CATALOGO>
					   <USUARIO>$this->user</USUARIO>
					   <PASSWORD>$this->pass</PASSWORD>
					   <TIPO_CATALOGO>ESTADO_CIRCULACION</TIPO_CATALOGO>
					   <ID_UNIDAD_OPERABLE>$this->unidadOperable</ID_UNIDAD_OPERABLE>
					   <FECHA>$fecha</FECHA> 
					   <ELEMENTOS>
					   </ELEMENTOS>  
					</SOLICITUD_CATALOGO>";

		return response()->json(['estadosCirculacion'=>$this->buscarEnCatalogo($xmlBody)["ELEMENTOS"]["ELEMENTO"]], 201);
 	}

 	/**
 	 * Obtiene los tipos de via para la direccion
 	 * 
 	 * @return string[] arreglo de clave, nombre para cada tipo de via
 	 */
 	public function getTiposVia()
 	{
 		$fecha = date('d/m/Y');
 		$xmlBody = "<SOLICITUD_CATALOGO>
					   <USUARIO>$this->user</USUARIO>
					   <PASSWORD>$this->pass</PASSWORD>
					   <TIPO_CATALOGO>TIPO_VIA</TIPO_CATALOGO>
					   <ID_UNIDAD_OPERABLE>$this->unidadOperable</ID_UNIDAD_OPERABLE>
					   <FECHA>$fecha</FECHA> 
					   <ELEMENTOS>
					   </ELEMENTOS>  
					</SOLICITUD_CATALOGO>";

		return response()->json(['tiposVia'=>$this->buscarEnCatalogo($xmlBody)["ELEMENTOS"]["ELEMENTO"]], 201);
 	}

 	/**
 	 * Obtiene el xml para que se pueda realizar la emision de la poliza.
 	 * 
 	 * @return string[] - Estructura para la poliza de GNP
 	 */
 	private function getXMLPoliza($datos)
 	{
 		// dd($datos);
 		$fecha_inicio      = Carbon::now()->format('Ymd');
 		$fecha_fin         = Carbon::now()->addYear()->format('Ymd');
 		$num_int           = $datos->num_int != null ? $datos->num_int : '';
 		$clavePaquete      = $datos->paquete->CVE_PAQUETE;
 		$poliza  		   = $datos->paquete->DESC_PAQUETE;
 		$estadoCirculacion = substr($datos->estadoCirculacion, 1);
 		$datos->f_nac = str_replace("-", '', $datos->f_nac);
 		$datos->f_const = str_replace("-", '', $datos->f_const);
 		$telefono = substr($datos->telefono, 2);
 		$lada = substr($datos->telefono, 0, 2);
 		$datos->municipio = str_replace([".", ",", ";", ":", "-", "_"], "", $datos->municipio);

 		if ($datos->descripcionAuto->ARMADORA) {
 			# code...
 		$armadora   = $datos->descripcionAuto->ARMADORA;
 		$modelo     = $datos->descripcionAuto->MODELO;
 		$carroceria = $datos->descripcionAuto->CARROCERIA;
 		$version    = $datos->descripcionAuto->CLAVE;
 	
 		}else{
 		
 		$armadora   = $datos->descripcionAuto[1]->CLAVE;
 		$modelo     = $datos->descripcionAuto[2]->CLAVE;
 		$carroceria = $datos->descripcionAuto[3]->CLAVE;
 		$version    = $datos->descripcionAuto[4]->CLAVE;
 		}
 		
 		// dd($version,$carroceria,$modelo,$armadora);
 		// Obtenemos la cotizacion con los datos del formulario.
 		if ($datos->tipo_persona =="F") {
 			
 			$data = $this->getXMLCotizacion($datos->codigo_postal, $fecha_inicio, $fecha_fin, $modelo, $armadora,
 				$carroceria, $version, $datos->f_nac, $datos->sexo, $datos->edad, $clavePaquete, $poliza);

 		}else{
 				
 				if ($datos->tipoPoliza == "Amplia") {
 					$clavePaquete = 'PRS0009361';
 				}elseif ($datos->tipoPoliza =="Limitada") {
 					$clavePaquete = 'PRS0009362';
 				}elseif ($datos->tipoPoliza == "RC") {
 					$clavePaquete = 'PRP0000349';
 				}

 			$data = $this->getXMLCotizacionM($datos->codigo_postal, $fecha_inicio, $fecha_fin, $modelo, $armadora,
 				$carroceria, $version, $datos->f_nac, $datos->sexo, $datos->edad, $clavePaquete, $poliza);

 		}
 		
 		try {
			
			$this->curl->post("https://api.service.gnp.com.mx/autos/wsp/cotizador/cotizar", $data);
	        //convert the XML result into array
	        $array_data = json_decode(json_encode(simplexml_load_string($this->curl->response)), true);
		} catch (Exception $e) {
			return back()->with("Error", "Ocurrio un error al enviar la información");
		}
		
		$num_cotizacion = $array_data["SOLICITUD"]["NUM_COTIZACION"];
		
 		switch ($datos->periodicidad) {
 			case 'A':
 				$primaNeta  = $array_data["PAQUETES"]["PAQUETE"]["TOTALES"]["TOTAL_PRIMA"][0]["CONCEPTO_ECONOMICO"][1]["MONTO"];
 				$importeIVA = $array_data["PAQUETES"]["PAQUETE"]["TOTALES"]["TOTAL_PRIMA"][0]["CONCEPTO_ECONOMICO"][7]["MONTO"];
 				$primaTotal = $array_data["PAQUETES"]["PAQUETE"]["TOTALES"]["TOTAL_PRIMA"][0]["CONCEPTO_ECONOMICO"][10]["MONTO"];
 				break;
 			case 'S':
 				$primaNeta  = $array_data["PAQUETES"]["PAQUETE"]["TOTALES"]["TOTAL_PRIMA"][1]["CONCEPTO_ECONOMICO"][1]["MONTO"];
 				$importeIVA = $array_data["PAQUETES"]["PAQUETE"]["TOTALES"]["TOTAL_PRIMA"][1]["CONCEPTO_ECONOMICO"][7]["MONTO"];
 				$primaTotal = $array_data["PAQUETES"]["PAQUETE"]["TOTALES"]["TOTAL_PRIMA"][1]["CONCEPTO_ECONOMICO"][9]["MONTO"];
 				break;
 			case 'T':
 				$primaNeta  = $array_data["PAQUETES"]["PAQUETE"]["TOTALES"]["TOTAL_PRIMA"][2]["CONCEPTO_ECONOMICO"][1]["MONTO"];
 				$importeIVA = $array_data["PAQUETES"]["PAQUETE"]["TOTALES"]["TOTAL_PRIMA"][2]["CONCEPTO_ECONOMICO"][7]["MONTO"];
 				$primaTotal = $array_data["PAQUETES"]["PAQUETE"]["TOTALES"]["TOTAL_PRIMA"][2]["CONCEPTO_ECONOMICO"][9]["MONTO"];
 				break;
 			case 'M':
 				$primaNeta  = $array_data["PAQUETES"]["PAQUETE"]["TOTALES"]["TOTAL_PRIMA"][3]["CONCEPTO_ECONOMICO"][1]["MONTO"];
 				$importeIVA = $array_data["PAQUETES"]["PAQUETE"]["TOTALES"]["TOTAL_PRIMA"][3]["CONCEPTO_ECONOMICO"][7]["MONTO"];
 				$primaTotal = $array_data["PAQUETES"]["PAQUETE"]["TOTALES"]["TOTAL_PRIMA"][3]["CONCEPTO_ECONOMICO"][9]["MONTO"];
 				break;
 		}
 		// dd($array_data,$data,$datos);
		/*  dd($array_data); */
		if ($datos->tipo_persona =="M") {
			return  "<EMISION>
				  <SOLICITUD>
				    <USUARIO>$this->user</USUARIO>
				    <PASSWORD>$this->pass</PASSWORD>
				    <ID_UNIDAD_OPERABLE>$this->unidadOperable</ID_UNIDAD_OPERABLE>
				    <NUM_COTIZACION>$num_cotizacion</NUM_COTIZACION>
				    <FCH_INICIO_VIGENCIA>$fecha_inicio</FCH_INICIO_VIGENCIA>
				    <FCH_FIN_VIGENCIA>$fecha_fin</FCH_FIN_VIGENCIA>
				    <FCH_EFECTO_MOVIMIENTO>$fecha_inicio</FCH_EFECTO_MOVIMIENTO>
				    <FCH_FIN_EFECTO_MOVIMIENTO>$fecha_fin</FCH_FIN_EFECTO_MOVIMIENTO>
				    <VIA_PAGO>IN</VIA_PAGO>
				    <VIA_PAGO_SUCESIVOS>IN</VIA_PAGO_SUCESIVOS>
				    <PERIODICIDAD>$datos->periodicidad</PERIODICIDAD>
				    <CVE_MONEDA>MXN</CVE_MONEDA>
				    <BAN_RENOVACION_AUTOMATICA>1</BAN_RENOVACION_AUTOMATICA>
				    <BAN_URL_IMPRESION>1</BAN_URL_IMPRESION>
				    <CVE_FORMA_AJUSTE_IRREGULAR>PR</CVE_FORMA_AJUSTE_IRREGULAR>
				    <BAN_CONTRA_IGUAL_CONDUCTOR>0</BAN_CONTRA_IGUAL_CONDUCTOR>
				    <BAN_CONTRA_IGUAL_BENEFICIARIO>1</BAN_CONTRA_IGUAL_BENEFICIARIO>
				    <BAN_AFECTA_BONO>0</BAN_AFECTA_BONO>
				    <OPERACION>E</OPERACION>
				  </SOLICITUD>
				  <ELEMENTOS>
				    <ELEMENTO>
				      <NOMBRE>INTERMEDIARIO</NOMBRE>
				      <CLAVE>$this->intermediario</CLAVE>
				      <VALOR>$this->intermediario</VALOR>
				    </ELEMENTO>
				  </ELEMENTOS>
				  <AGENTES>
				    <AGENTE>
				      <COD_INTERMEDIARIO>$this->intermediario</COD_INTERMEDIARIO>
				      <ID_PARTICIPANTE>CASFSC183ABC</ID_PARTICIPANTE>
				      <CVE_CLASE_INTERMEDIARIO_INFO>A</CVE_CLASE_INTERMEDIARIO_INFO>
				      <BAN_INTERMEDIARIO_PRINCIPAL>1</BAN_INTERMEDIARIO_PRINCIPAL>
				      <FOLIO>P0070295</FOLIO>
				      <CVE_OFICINA_DIRECCION_AGENCIA>0709</CVE_OFICINA_DIRECCION_AGENCIA>
				      <ID_TIPO_BASE_COMISION>PS</ID_TIPO_BASE_COMISION>
				      <PCT_COMISION_PRIMA>10.0</PCT_COMISION_PRIMA>
				      <PCT_PARTICIP_COMISION>100</PCT_PARTICIP_COMISION>
				      <PCT_CESION_COMISION>0</PCT_CESION_COMISION>
				    </AGENTE>
				  </AGENTES>
				  <VEHICULO>
				    <SUB_RAMO>01</SUB_RAMO>
				      <TIPO_VEHICULO>AUT</TIPO_VEHICULO>
				      <MODELO>$modelo</MODELO>
				      <ARMADORA>$armadora</ARMADORA>
				      <CARROCERIA>$carroceria</CARROCERIA>
				      <VERSION>$version</VERSION>
				      <USO>$datos->uso</USO>
				      <FORMA_INDEMNIZACION>03</FORMA_INDEMNIZACION>
				      <VALOR_FACTURA></VALOR_FACTURA>
				      <PLACAS>$datos->placas</PLACAS>
				      <ALTO_RIESGO>0</ALTO_RIESGO>
				      <TIPO_CARGA></TIPO_CARGA>
				      <ESTADO_CIRCULACION>$datos->estado</ESTADO_CIRCULACION>
				      <MOTOR>$datos->motor</MOTOR>
				      <SERIE>$datos->serie</SERIE>
				      <CODIGO_POSTAL>$datos->codigo_postal</CODIGO_POSTAL>
				  </VEHICULO>

				  <CONTRATANTE>
				  	<ID_PARTICIPANTE/>
					<CVE_CLIENTE_ORIGEN/>
				    <TIPO_PERSONA>$datos->tipo_persona</TIPO_PERSONA>
				    <RFC>$datos->rfc</RFC>

				    <RAZON_SOCIAL>$datos->nombre</RAZON_SOCIAL>
				    
				    <FCH_CONSTITUCION>$datos->f_const</FCH_CONSTITUCION>
				    <NACIONALIDAD>MEX</NACIONALIDAD>
				    <PAIS_NACIMIENTO/>
					<DIRECCION>
					
				      <CVE_TIPO_VIA>$datos->tipoVia</CVE_TIPO_VIA>
				      <CALLE>$datos->calle</CALLE>
				      <NUMERO_EXTERIOR>$datos->num_ext</NUMERO_EXTERIOR>
				      <NUMERO_INTERIOR>$num_int</NUMERO_INTERIOR>
				      <COLONIA>$datos->colonia</COLONIA>
				      <DELEGACION_MCPIO>$datos->municipio</DELEGACION_MCPIO>
				      <ESTADO>$datos->estado</ESTADO>
				      <CODIGO_POSTAL>$datos->codigo_postal</CODIGO_POSTAL>
				      <PAIS_DOMICILIO>MEX</PAIS_DOMICILIO>
				    </DIRECCION>
				    <TELEFONOS>
				      <TELEFONO>
				        <CVE_LADA>$lada</CVE_LADA>
				        <CVE_LADA_NACIONAL>$lada</CVE_LADA_NACIONAL>
				        <NUMERO_TELEFONO>$telefono</NUMERO_TELEFONO>
				      </TELEFONO>
				    </TELEFONOS>
				    <CORREOS>
				      <CORREO>
				        <CORREO_ELECTRONICO>$datos->correo</CORREO_ELECTRONICO>
				      </CORREO>
				    </CORREOS>
				  </CONTRATANTE>
					 <CONDUCTOR>
						      <RFC>PUOA840101</RFC>
						      <NOMBRES>Prueba</NOMBRES>
						      <APELLIDO_PATERNO>Omega</APELLIDO_PATERNO>
						      <APELLIDO_MATERNO>Alfa</APELLIDO_MATERNO>
						      <SEXO>M</SEXO>
						      <ESTADO_CIVIL>S</ESTADO_CIVIL>
						      <FCH_NACIMIENTO>19840101</FCH_NACIMIENTO>
						      <EDAD_CONDUCTOR_HABITUAL>36</EDAD_CONDUCTOR_HABITUAL>
					</CONDUCTOR>
				   <BENEFICIARIOS>
				      <BENEFICIARIO>
				         <BAN_IRREVOCABLE>0</BAN_IRREVOCABLE>
				         <NOMBRES>Carlos</NOMBRES>
				      <APELLIDO_PATERNO>Omega</APELLIDO_PATERNO>
				      <APELLIDO_MATERNO>Alfa</APELLIDO_MATERNO>
				         <PCT_BENEFICIO>1</PCT_BENEFICIO>
				         <TIPO_PERSONA>F</TIPO_PERSONA>
				      </BENEFICIARIO>
				   </BENEFICIARIOS>

				  <PAQUETE>
				    <CVE_PAQUETE>$clavePaquete</CVE_PAQUETE>
				  </PAQUETE>
				  <IMPORTES>
				    <PRIMA_TOTAL>$primaTotal</PRIMA_TOTAL>
				    <IMP_IVA>$importeIVA</IMP_IVA>
				    <PRIMA_NETA>$primaNeta</PRIMA_NETA>
				  </IMPORTES>
				</EMISION>";
			
		}else{
			return  "<EMISION>
				  <SOLICITUD>
				    <USUARIO>$this->user</USUARIO>
				    <PASSWORD>$this->pass</PASSWORD>
				    <ID_UNIDAD_OPERABLE>$this->unidadOperable</ID_UNIDAD_OPERABLE>
				    <NUM_COTIZACION>$num_cotizacion</NUM_COTIZACION>
				    <FCH_INICIO_VIGENCIA>$fecha_inicio</FCH_INICIO_VIGENCIA>
				    <FCH_FIN_VIGENCIA>$fecha_fin</FCH_FIN_VIGENCIA>
				    <FCH_EFECTO_MOVIMIENTO>$fecha_inicio</FCH_EFECTO_MOVIMIENTO>
				    <FCH_FIN_EFECTO_MOVIMIENTO>$fecha_fin</FCH_FIN_EFECTO_MOVIMIENTO>
				    <VIA_PAGO>IN</VIA_PAGO>
				    <VIA_PAGO_SUCESIVOS>IN</VIA_PAGO_SUCESIVOS>
				    <PERIODICIDAD>$datos->periodicidad</PERIODICIDAD>
				    <CVE_MONEDA>MXN</CVE_MONEDA>
				    <BAN_RENOVACION_AUTOMATICA>1</BAN_RENOVACION_AUTOMATICA>
				    <BAN_URL_IMPRESION>1</BAN_URL_IMPRESION>
				    <CVE_FORMA_AJUSTE_IRREGULAR>PR</CVE_FORMA_AJUSTE_IRREGULAR>
				    <BAN_CONTRA_IGUAL_CONDUCTOR>1</BAN_CONTRA_IGUAL_CONDUCTOR>
				    <BAN_CONTRA_IGUAL_BENEFICIARIO>1</BAN_CONTRA_IGUAL_BENEFICIARIO>
				    <BAN_AFECTA_BONO>0</BAN_AFECTA_BONO>
				    <OPERACION>E</OPERACION>
				  </SOLICITUD>
				  <ELEMENTOS>
				    <ELEMENTO>
				      <NOMBRE>INTERMEDIARIO</NOMBRE>
				      <CLAVE>$this->intermediario</CLAVE>
				      <VALOR>$this->intermediario</VALOR>
				    </ELEMENTO>
				  </ELEMENTOS>
				  <AGENTES>
				    <AGENTE>
				      <COD_INTERMEDIARIO>$this->intermediario</COD_INTERMEDIARIO>
				      <ID_PARTICIPANTE>CASFSC183ABC</ID_PARTICIPANTE>
				      <CVE_CLASE_INTERMEDIARIO_INFO>A</CVE_CLASE_INTERMEDIARIO_INFO>
				      <BAN_INTERMEDIARIO_PRINCIPAL>1</BAN_INTERMEDIARIO_PRINCIPAL>
				      <FOLIO>P0070295</FOLIO>
				      <CVE_OFICINA_DIRECCION_AGENCIA>0709</CVE_OFICINA_DIRECCION_AGENCIA>
				      <ID_TIPO_BASE_COMISION>PS</ID_TIPO_BASE_COMISION>
				      <PCT_COMISION_PRIMA>10.0</PCT_COMISION_PRIMA>
				      <PCT_PARTICIP_COMISION>100</PCT_PARTICIP_COMISION>
				      <PCT_CESION_COMISION>0</PCT_CESION_COMISION>
				    </AGENTE>
				  </AGENTES>
				  <VEHICULO>
				    <SUB_RAMO>01</SUB_RAMO>
				      <TIPO_VEHICULO>AUT</TIPO_VEHICULO>
				      <MODELO>$modelo</MODELO>
				      <ARMADORA>$armadora</ARMADORA>
				      <CARROCERIA>$carroceria</CARROCERIA>
				      <VERSION>$version</VERSION>
				      <USO>$datos->uso</USO>
				      <FORMA_INDEMNIZACION>03</FORMA_INDEMNIZACION>
				      <VALOR_FACTURA></VALOR_FACTURA>
				      <PLACAS>$datos->placas</PLACAS>
				      <ALTO_RIESGO>0</ALTO_RIESGO>
				      <TIPO_CARGA></TIPO_CARGA>
				      <ESTADO_CIRCULACION>$datos->estado</ESTADO_CIRCULACION>
				      <MOTOR>$datos->motor</MOTOR>
				      <SERIE>$datos->serie</SERIE>
				      <CODIGO_POSTAL>$datos->codigo_postal</CODIGO_POSTAL>
				  </VEHICULO>
				  <CONTRATANTE>
				    <TIPO_PERSONA>$datos->tipo_persona</TIPO_PERSONA>
				    <RFC>$datos->rfc</RFC>
				    <NOMBRES>$datos->nombre</NOMBRES>
				    <APELLIDO_PATERNO>$datos->apepat</APELLIDO_PATERNO>
				    <APELLIDO_MATERNO>$datos->apemat</APELLIDO_MATERNO>
				    <SEXO>$datos->sexo</SEXO>
				    <ESTADO_CIVIL>$datos->estadoCivil</ESTADO_CIVIL>
				    <FCH_NACIMIENTO>$datos->f_nac</FCH_NACIMIENTO>
				    <NACIONALIDAD>MEX</NACIONALIDAD>
				    <PAIS_NACIMIENTO>MEX</PAIS_NACIMIENTO>
				    <DIRECCION>
				      <CVE_TIPO_VIA>$datos->tipoVia</CVE_TIPO_VIA>
				      <CALLE>$datos->calle</CALLE>
				      <NUMERO_EXTERIOR>$datos->num_ext</NUMERO_EXTERIOR>
				      <NUMERO_INTERIOR>$num_int</NUMERO_INTERIOR>
				      <COLONIA>$datos->colonia</COLONIA>
				      <DELEGACION_MCPIO>$datos->municipio</DELEGACION_MCPIO>
				      <ESTADO>$datos->estado</ESTADO>
				      <CODIGO_POSTAL>$datos->codigo_postal</CODIGO_POSTAL>
				      <PAIS_DOMICILIO>MEX</PAIS_DOMICILIO>
				    </DIRECCION>
				    <TELEFONOS>
				      <TELEFONO>
				        <CVE_LADA>$lada</CVE_LADA>
				        <CVE_LADA_NACIONAL>$lada</CVE_LADA_NACIONAL>
				        <NUMERO_TELEFONO>$telefono</NUMERO_TELEFONO>
				      </TELEFONO>
				    </TELEFONOS>
				    <CORREOS>
				      <CORREO>
				        <CORREO_ELECTRONICO>$datos->correo</CORREO_ELECTRONICO>
				      </CORREO>
				    </CORREOS>
				  </CONTRATANTE>
				  <CONDUCTOR>
				    <RFC>$datos->rfc</RFC>
				    <NOMBRES>$datos->nombre</NOMBRES>
				    <APELLIDO_PATERNO>$datos->apepat</APELLIDO_PATERNO>
				    <APELLIDO_MATERNO>$datos->apemat</APELLIDO_MATERNO>
				    <SEXO>$datos->sexo</SEXO>
				    <ESTADO_CIVIL>$datos->estadoCivil</ESTADO_CIVIL>
				    <FCH_NACIMIENTO>$datos->f_nac</FCH_NACIMIENTO>
				    <EDAD_CONDUCTOR_HABITUAL>$datos->edad</EDAD_CONDUCTOR_HABITUAL>
				  </CONDUCTOR>
				  <PAQUETE>
				    <CVE_PAQUETE>$clavePaquete</CVE_PAQUETE>
				  </PAQUETE>
				  <IMPORTES>
				    <PRIMA_TOTAL>$primaTotal</PRIMA_TOTAL>
				    <IMP_IVA>$importeIVA</IMP_IVA>
				    <PRIMA_NETA>$primaNeta</PRIMA_NETA>
				  </IMPORTES>
				</EMISION>";
			
		}
 		
 	}

 	/**
 	 * Construye el XML para emitir una poliza a GNP y recibe los datos de la poliza creada.
 	 * 
 	 * @return string[] - .....
 	 */
 	public function emitirPoliza(Request $request)
 	{
 		$request->paquete = json_decode($request->paquete);
 		$request->descripcionAuto = json_decode($request->descripcionAuto);

 		$data = $this->getXMLPoliza($request);
 		$XML = $data ;
 		// dd($data,$request);
 		try {
			// dd($data);
			$this->curl->post("https://api.service.gnp.com.mx/autos/wsp/emisor/emisor/emitir", $data);
	        //convert the XML result into array
			$array_data = json_decode(json_encode(simplexml_load_string($this->curl->response)), true);
			// LIGA PARA IMPRIMIR
				// https://api.service.gnp.com.mx/autos/wsp/impresion/buscarPoliza
			$data = json_decode(json_encode(simplexml_load_string($data)), true);


					// dd($array_data,$request,$XML);
					if (isset($array_data['SOLICITUD']["NUM_POLIZA"])) {
						$num_poliza =  $array_data['SOLICITUD']["NUM_POLIZA"];
             				$pdf = $this->ObtenerPdfPoliza($num_poliza);
             		// dd($pdf,$array_data,$pdf->RESULTADO->URL_DOCUMENTO);
                		 	 Mail::to($request->correo)->send(new EmisionPoliza($pdf,'GNP'));
	        				return view('gnp.poliza',['response'=>$array_data ,'data'=>$data,'pdf'=>$pdf]);
					}else{

							$descripcion_error = $array_data['DESCRIPCION'];
							return redirect()->back()->withErrors(['msg'=>[$request->cliente],'nombre'=>[$request->nombre], 'error'=>[$descripcion_error]]);
					}

         //     		$num_poliza =  $array_data['SOLICITUD']["NUM_POLIZA"];
         //     		$pdf = $this->ObtenerPdfPoliza($num_poliza);
         //     		// dd($pdf,$array_data,$pdf->RESULTADO->URL_DOCUMENTO);
         //          Mail::to($request->correo)->send(new EmisionPoliza($pdf,'GNP'));
	        // return view('gnp.poliza',['response'=>$array_data ,'data'=>$data,'pdf'=>$pdf]);
	        // return response()->json(['cotizacionGNP'=>$array_data],201);
		} catch (Exception $e) {
			return response()->json(['error'=>"Fallo la petición"],400);
		}
 	}
}

/*
	OTRA FORMA DE REALIZAR LA PETICION
	$url = "https://api.service.gnp.com.mx/autos/wsp/cotizador/cotizar";

	//setting the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);

	// For xml, change the content-type.
	curl_setopt ($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/xml"));

	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlBody);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // ask for results to be returned

	// Send to remote and return data to caller.
	$result = curl_exec($ch);
	// dd($result);
	$err = curl_error($ch);
	curl_close($ch);
	if ($err) {
		dd($err);
	}
*/
