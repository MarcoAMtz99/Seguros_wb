<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleXMLElement;
use Carbon\Carbon;
use App\Cliente;
use SoapClient;
use GuzzleHttp\Client;
use \Curl\Curl;

class GNPController extends Controller
{
    protected $opts;

    /**
     * Método para inicializar las url del WebService y opciones por default para hacer las peticiones
     * @return void
     */
 	public function __construct(){

		// DATOS GNP
		$this->user = env('GNP_USER', '');
		$this->pass = env('GNP_PASSWORD', '');
		$this->unidadOperable = env('GNP_UNIDAD_OPERABLE', '');
		$this->intermediario = env('GNP_INTERMEDIARIO', '');
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
 	public function prueba()
 	{
 		//return view('prueba');
 		dd($this->getCotizacion("dasd"));
 		dd($this->modelos("Ford", "fiesta", "2015"));
		try {
			
			$this->curl->post("https://api.service.gnp.com.mx/autos/wsp/cotizador/cotizar", $this->getXMLCotizacion());
			// dd($curl->response);
	        //convert the XML result into array
	        $array_data = json_decode(json_encode(simplexml_load_string($this->curl->response)), true);

	        print_r('<pre>');
	        print_r($array_data);
	        print_r('</pre>');

		} catch (Exception $e) {
			dd($e);
		}
 		return 'Hola';

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
 		$carroceria = $this->getCarroceria($armadora, $submarca);
 		$modelos    = $this->getModelos($modelo, $armadora, $carroceria);
 		return response()->json(['modelosGNP'=>$modelos],201);
 	}

 	private function buscarEnCatalogo($xmlBody)
 	{
 		try {
			
			$this->curl->post("https://api.service.gnp.com.mx/autos/wsp/catalogos/catalogo", $xmlBody);
	        //convert the XML result into array
	        $array_data = json_decode(json_encode(simplexml_load_string($this->curl->response)), true);
	        return $array_data;
		} catch (Exception $e) {
			dd($e);
		}
 	}

 	/**
 	 * Metodo usado para obtener la cadena XML necesaria para el WebService y hacer una cotizacion
 	 * @param string $cliente
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
 	public function getXMLCotizacion($cliente, $fecha_inicio, $fecha_fin,  $modelo, $armadora,
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
				    <CODIGO_POSTAL>$cliente->cp</CODIGO_POSTAL>
				  </CONTRATANTE>
				  <CONDUCTOR>
				    <FCH_NACIMIENTO>$nacimiento</FCH_NACIMIENTO>
				    <SEXO>$sexo</SEXO>
				    <EDAD>$edad</EDAD>
				    <CODIGO_POSTAL>$cliente->cp</CODIGO_POSTAL>
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
 		$armadora = '';
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
					   <ID_UNIDAD_OPERABLE/>
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
					   <ID_UNIDAD_OPERABLE/>
					   <FECHA>$fecha</FECHA> 
					   <ELEMENTOS>
					      <ELEMENTO>
					         <CLAVE>AUT</CLAVE>
					         <NOMBRE>TIPO_VEHICULO</NOMBRE>
					      </ELEMENTO>
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
 		$carroceria = '';

 		if (isset($carrocerias['ELEMENTOS'])) {
 			foreach ($carrocerias['ELEMENTOS']['ELEMENTO'] as $value) {
 				if (stripos($value['NOMBRE'], $submarca)){
 					$carroceria = $value['CLAVE'];
 				}
 			}
 		}

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
					   <ID_UNIDAD_OPERABLE/>
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
 		// dd($vehiculo[2]);
        if($cliente == null){
            return response()->json(['error'=>"datos no encontrado"],404);

        }

 		$fecha_inicio = Carbon::now()->format('Ymd');
 		$fecha_fin    = Carbon::now()->addYear()->format('Ymd');
 		$nacimiento   = Carbon::parse($cliente->f_nac)->format('Ymd');
 		$edad 		  = Carbon::parse($cliente->f_nac)->age;
 		$sexo 		  = $cliente->sexo === "Hombre" ? 'M' : 'F';
 		$modelo 	  = $vehiculo[2]->VALOR;
 		$armadora 	  = $vehiculo[1]->CLAVE;
 		$carroceria   = $vehiculo[3]->CLAVE;
 		$version 	  = $vehiculo[4]->CLAVE;

 		$paquetesPersonaFisica = [
 			'Amplia'   => 'PRP0000287',
 			'Limitada' => 'PRP0000288',
 			'RC' 	   => 'PRP0000289'
 		];

 		$paquetesPersonaMoral = [
 			'Amplia'   => 'PRP0000347',
 			'Limitada' => 'PRP0000348',
 			'RC' 	   => 'PRP0000349'
 		];
 		$clavePaquete = $cliente->uso_auto === "Servicio Particular"
 			? $paquetesPersonaFisica[$request->poliza]
 			: $paquetesPersonaMoral[$request->poliza];

 		$data = $this->getXMLCotizacion($cliente, $fecha_inicio, $fecha_fin, $modelo, $armadora,
 				$carroceria, $version, $nacimiento, $sexo, $edad, $clavePaquete, $request->poliza);
 		// dd($data);
 		try {
			
			$this->curl->post("https://api.service.gnp.com.mx/autos/wsp/cotizador/cotizar", $data);
	        //convert the XML result into array
	        $array_data = json_decode(json_encode(simplexml_load_string($this->curl->response)), true);
	        return response()->json(['cotizacionGNP'=>$array_data],201);
		} catch (Exception $e) {
			return response()->json(['error'=>"Fallo la peticion"],400);
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
