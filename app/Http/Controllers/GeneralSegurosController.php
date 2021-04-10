<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SoapClient;
use App\Cliente;


class GeneralSegurosController extends Controller
{
    //

    protected $opts, $params, $urlAuth, $urlCotiza, $token, $urlCat, $urlCatAuto, $urlCober;

    public function __construct()
    {

        $this->opts = array(
            'https' => array('header' => array('Content-Type:application/soap+xml; charset=utf-8'))
        );
        $this->params = array('encoding' => 'UTF-8', 'trace' => false, 'keep_alive' => false, 'soap_version' => SOAP_1_1, 'stream_context' => stream_context_create($this->opts));
        // DATOS GENERAL DE SEGUROS
        // *******************************************************
        // $this->urlAuth = "https://gdswas.mx/gsautos-ws/soap/autenticacionWS?wsdl";
        // $this->urlCotiza = "https://gdswas.mx/gsautos-ws/soap/cotizacionEmisionWS?wsdl";
        // $this->urlCat = "https://gdswas.mx/gsautos-ws/soap/catalogosWS?wsdl";
        // $this->urlCatAuto = "https://gdswas.mx/gsautos-ws/soap/catalogoAutosWS?wsdl";
        // $this->urlCober = "https://gdswas.mx/gsautos-ws/soap/catalogoCoberturasWS?wsdl";
        // *******************************************************
        //                https://serviciosgs.mx/gsautos-ws/soap/catalogoAutosWS?wsdl
        //                https://serviciosgs.mx/gsautos-ws/soap/autenticacionWS?wsdl
        $this->urlAuth = "https://serviciosgs.mx/gsautos-ws/soap/autenticacionWS?wsdl";
        $this->urlCotiza = "https://serviciosgs.mx/gsautos-ws/soap/cotizacionEmisionWS?wsdl";
        $this->urlCat = "https://serviciosgs.mx/gsautos-ws/soap/catalogosWS?wsdl";
        $this->urlCatAuto = "https://serviciosgs.mx/gsautos-ws/soap/catalogoAutosWS?wsdl";
        $this->urlCober = "https://serviciosgs.mx/gsautos-ws/soap/catalogoCoberturasWS?wsdl";
        try {
            $this->clientAuthGS = $this->getClient($this->urlAuth);
            // dd($this->clientAuthGS);
            // $this->clientCotGS = new SoapClient($this->urlCotiza,$this->params);
        } catch (SoapFault $fault) {
            // dd("Fallo",$fault);
        }
        // get token
        $this->token = $this->getToken();
        $this->middleware(function ($request, $next) {
            if ($this->token) {
                return $next($request);
            } else {
                return response()->json(['error' => 'token is null']);
            }
        });
    }

    public function getClient($url)
    {
        try {

            $client = new SoapClient($url, $this->params);
            // dd($client);
            return $client;
        } catch (FatalErrorException $error) {
            dd($error);
        }
    }

    public function getToken()
    {
       
        $result = $this->clientAuthGS->obtenerToken([
            'arg0' => [
                // "usuario" => 'ATC0', 
                //  'password' => '2r2kGdeUA0' 
                'usuario' => 'ATC891',
                'password' => '2HFeACQo1O'
            ]
        ]);
        // return $result;
        $response = json_decode(json_encode($result), true);
        if ($response['return']['exito']) {
            // dd($response);
            return $response['return']['token'];
        } else {
            return null;
        }
    }

    public function setMarca($marca)
    {
        // dd($marca);
        try {

            $client = $this->getClient($this->urlCatAuto);
            $res = $client->wsListarMarcas(['arg0' => ["token" => $this->token]]);
            if ($res->return->exito) {
                foreach ($res->return->marcas as $marca_gs) {
                    if ($marca_gs->nombre == $marca->nombre) {
                        $marca->id_gs = $marca_gs->id;
                        $marca->save();
                        return $marca_gs->id;
                    }
                }
            } else {
            }
        } catch (SoapFault $fault) {
            dd($fault);
        }
    }

    public function setSubMarca($marca_id, $submarca)
    {
        try {
            // dd($marca);
            $client = $this->getClient($this->urlCatAuto);
            $res = $client->wsListarSubMarcas(['arg0' => ['token' => $this->token, 'idMarca' => $marca_id]]);

            if ($res->return->exito) {

                foreach ($res->return->submarcas as $submarca_gs) {

                    if ($submarca_gs->nombre == $submarca->nombre) {
                        $submarca->id_gs = $submarca_gs->id;
                        $submarca->id_seg_gs = $submarca_gs->idSegmento;
                        $submarca->save();
                        return $submarca->id_gs;
                    }
                }
            }
            // dd($res);
        } catch (SoapFault $fault) {
        }
    }

    public function setVersion($submarca_id, $modelo, $version)
    {
        try {
            $client = $this->getClient($this->urlCatAuto);
            $res = $client->wsListarVersiones(['arg0' => ['idSubmarca' => $submarca_id, 'modelo' => $modelo]]);
            if ($res->return->exito) {
                $porc_piv = 0;
                foreach ($res->return->versiones as $version_gs) {

                    similar_text($version->descripcion, $version_gs->descripcion, $porcentaje);
                    if ($porcentaje >= $porc_piv) {
                        $porc_piv = $porcentaje;
                        $version->amis_gs = $version_gs->amis;
                    }
                }
                $version->save();
                return $version_gs->amis;
            }
        } catch (SoapFault $fault) {
            dd($fault);
        }
    }

    public function getCotizacion(Request $request)
    {
        $cliente = Cliente::where('cotizacion', $request->cotizacion)->first();
        dd($request);
        $input = $request->all();
        
        $claveGs = $input['descripcion_gs']['amis'];
        $modelo = $input['anio'];
        $poliza = $input['poliza'];
        dd($poliza,$request->all(),$claveGs, $modelo);
        switch ($poliza) {
            case 'Amplia':
                $poliza_gs = "CONFORT AMPLIA";
                break;

            case 'Limitada':
                $poliza_gs = "CONFORT LIMITADA";
                break;

            case 'RC':
                $poliza_gs = "CONFORT BASICA";
                break;

            default:
                $poliza_gs = "CONFORT BASICA";
                break;
        }
        // dd($modelo);

        // dd($cliente['tipoServicio']);
        $soapClient = $this->getClient($this->urlCotiza);
        // return $soapClient->__getTypes();
        try {

            ini_set('default_socket_timeout', 600);
            $res = $soapClient->generarCotizacion(['arg0' => [
                'token' => $this->token,
                'configuracionProducto' => "RESIDENTE_INDIVIDUAL",
                'cp'                    => $cliente->cp,
                'descuento'             => 0,
                'vigencia'              => "ANUAL",
                'inciso' => [
                    'claveGs'          => $claveGs,
                    "conductorMenor30" => $cliente->menor30,
                    'modelo'           => $modelo,
                    'tipoServicio'     => $cliente->tipoServicio,
                    'tipoValor'        => "VALOR_COMERCIAL",
                    "tipoVehiculo"     => "AUTO_PICKUP",
                    "valorVehiculo"    => ""
                ]
            ]]);
            $response = json_decode(json_encode($res), true);
            // return $response;
            // dd($response,$res,['arg0' => [
            //     'token' => $this->token,
            //     'configuracionProducto' => "RESIDENTE_INDIVIDUAL",
            //     'cp'                    => $cliente->cp,
            //     'descuento'             => 0,
            //     'vigencia'              => "ANUAL",
            //     'inciso' => [
            //         'claveGs'          => $claveGs,
            //         "conductorMenor30" => $cliente->menor30,
            //         'modelo'           => $modelo,
            //         'tipoServicio'     => $cliente->tipoServicio,
            //         'tipoValor'        => "VALOR_COMERCIAL",
            //         "tipoVehiculo"     => "AUTO_PICKUP",
            //         "valorVehiculo"    => ""
            //     ]
            // ]] );
            if ($response['return']['exito'] && isset($response['return']['paquetes'])) {
                $paquete_gs = [];
                foreach ($response['return']['paquetes'] as $paquete) {
                    if ($paquete['nombre'] == $poliza_gs) {
                        $paquete['coberturas'] = $this->getCoberturas($response['return']['idCotizacion'], $paquete['id']);
                        array_push($paquete_gs, $paquete);
                    }
                }
                // dd($paquetes);
                $cotizacion = ['id' => $response['return']['idCotizacion'], 'paquete' => $paquete_gs];
                return response()->json(['cotizacion' => $cotizacion]);
            } elseif (!$response['return']['exito']) {

                return response()->json(['error' => $response['return']['mensaje']], 500);
            } else {
                return response()->json(['error' => "No hay paquetes"], 404);
            }
        } catch (SoapFault $error) {
            var_dump($error);
        }
    }

    public function getCoberturas($cotizacion, $paquete)
    {
        // dd($paquete);
        $soapClient = $this->getClient($this->urlCober);
        $coberturas = $soapClient->wsObtenerCoberturasCotizacion(['arg0' => ['token' => $this->token, 'cotizacion' => $cotizacion, 'paquete' => $paquete]]);
        $response = json_decode(json_encode($coberturas), true);

        if ($response['return']['exito']) {
            return $response['return']['coberturas'];
            // return response()->json(['coberturas'=>$response['return']['coberturas']]);
        }
        // dd($response);

        // dd($soapClient->__getTypes());

    }

    public function versiones($marca, $submarca, $modelo)
    {
        $marca_gs = $this->searchMarca($marca);
        // dd($marca_gs);
        if ($marca_gs) {
            $submarca_gs = $this->searchSubMarca($marca_gs, $submarca);
            if ($submarca_gs) {

                $modelo_gs = $this->searchModelos($submarca_gs, $modelo);
                // dd($submarca_gs,$modelo_gs,$marca_gs);
                if ($modelo_gs) {
                    $versiones_gs = $this->searchVersiones($submarca_gs, $modelo_gs);
                    // dd($marca_gs,$submarca_gs,$modelo_gs,$versiones_gs);
                    $versiones = [];
                    foreach ($versiones_gs as $version) {
                        // dd($version);
                        // $version->marca = $marca_gs;
                        // $version->submarca = $submarca_gs;
                        // $version->modelo = $modelo_gs;
                        array_push($versiones, $version);
                        // dd($versiones, $version);
                    }
                    return response()->json(['versiones_gs' => $versiones], 201);
                }
            }
        }
    }

    public function searchMarca($marca)
    {
        $client = $this->getClient($this->urlCatAuto);
        $res = $client->wsListarMarcas(['arg0' => ["token" => $this->token]]);
        // dd($res);
        if ($res->return->exito) {
            $marcas = $res->return->marcas;
            foreach ($marcas as $marca_gs) {
                if ($marca_gs->nombre == $marca) {
                    return $marca_gs;
                }
            }
        } else {
            return false;
        }
    }

    public function searchSubMarca($marca_gs, $submarca)
    {
        $client = $this->getClient($this->urlCatAuto);
        $res = $client->wsListarSubMarcas(['arg0' => ['token' => $this->token, 'idMarca' => $marca_gs->id]]);
        if ($res->return->exito) {
            $submarcas = $res->return->submarcas;
            // return $submarcas;
            foreach ($submarcas as $submarca_gs) {
                if ($submarca_gs->nombre == $submarca) {
                    return $submarca_gs;
                }
            }
        } else {
            return false;
        }
    }
    public function searchModelos($submarca_gs, $modelo)
    {
        $client = $this->getClient($this->urlCatAuto);
        $res = $client->wsListarModelos(['arg0' => ['idSubmarca' => $submarca_gs->id]]);
        if ($res->return->exito) {
            $modelos = $res->return->modelos;
            foreach ($modelos as $modelo_gs) {
                if ((int) $modelo_gs == (int) $modelo) {
                    return $modelo_gs;
                }
            }
        } else {
            return false;
        }
    }
    public function searchVersiones($submarca_gs, $modelo)
    {
        $client = $this->getClient($this->urlCatAuto);
        $res = $client->wsListarVersiones(['arg0' => ['idSubmarca' => $submarca_gs->id, 'modelo' => $modelo]]);
        if ($res->return->exito) {
            $versiones_gs = $res->return->versiones;
            return $versiones_gs;
        } else {
            return false;
        }
    }

    public function getMarcas()
    {
        $client = $this->getClient($this->urlCatAuto);
        $res = $client->wsListarMarcas(['arg0' => ["token" => $this->token]]);
        return $this->responseJson('marcas', $res);
    }

    public function getSubmarcas($marca_id)
    {
        // dd($marca_id);
        $client = $this->getClient($this->urlCatAuto);
        $res = $client->wsListarSubMarcas(['arg0' => ['token' => $this->token, 'idMarca' => $marca_id]]);
        return $this->responseJson('submarcas', $res);
    }
    public function getModelos($submarca_id)
    {
        $client = $this->getClient($this->urlCatAuto);
        $res = $client->wsListarModelos(['arg0' => ['idSubmarca' => $submarca_id]]);
        return $this->responseJson('modelos', $res);
    }
    public function getVersiones($submarca_id, $modelo)
    {
        $client = $this->getClient($this->urlCatAuto);
        $res = $client->wsListarVersiones(['arg0' => ['idSubmarca' => $submarca_id, 'modelo' => $modelo]]);
        return $this->responseJson('versiones', $res);
    }

    public function getContactos()
    {
        $client = $this->getClient($this->urlCat);
        $res = $client->wsListarTiposContacto(['arg0' => ['token' => $this->token]]);
        return $this->responseJson('tiposContacto', $res);
    }

    public function getGiros()
    {
        $client = $this->getClient($this->urlCat);
        $res = $client->wsListarGiros(['arg0' => ['token' => $this->token]]);
        return $this->responseJson('giros', $res);
    }

    public function getEstadoCivil()
    {
        $client = $this->getClient($this->urlCat);
        $res = $client->wsListarEstadosCivil(['arg0' => ['token' => $this->token]]);
        return $this->responseJson('estadosCivil', $res);
    }

    public function getTitulos()
    {
        $client = $this->getClient($this->urlCat);
        $res = $client->wsListarTitulos(['arg0' => ['token' => $this->token]]);
        return $this->responseJson('titulos', $res);
    }

    public function responseJson($key, $res)
    {
        $response = json_decode(json_encode($res), true);
        if ($response['return']['exito']) {
            if ($key == "paquetes" && $response['return']['idCotizacion']) {
                $value = $response['return'][$key];
                return response()->json(['cotizacion_id' => $response['return']['idCotizacion'], $key => $value]);
            } else {
                if (array_key_exists(0, $response['return'][$key])) {
                    $value = $response['return'][$key];
                    return response()->json([$key => $value]);
                } else {
                    $value = [$response['return'][$key]];
                    return response()->json([$key => $value]);
                }
            }
        }
    }
    public function sendGS(Request $request)
    {

        ini_set('default_socket_timeout', 600);
        $clientSOAP = $this->getClient($this->urlCotiza);
        $emitir = $clientSOAP->emitirCotizacion([
            'arg0' => [
                'token' => $this->token,
                'cliente' => [
                    'cve_cli' => 0,
                    'suc_emi' => 0,
                    'fis_mor' => $request->tipo_persona,
                    'nom_cli' => $request->nombre,
                    'ape_pat' => $request->apepat,
                    'ape_mat' => $request->apemat,
                    'raz_soc' => $request->razsoc,
                    'ane_cli' => "",
                    'rfc_cli' => 'GUSC961114123',
                    // 'rfc_cli' => $request->rfc,
                    'cve_ele' => $request->elector,
                    'curpcli' => $request->curp,
                    'sexocli' => $request->sexo,
                    'edo_civ' => $request->edoCivil,
                    'cal_cli' => $request->calle,
                    'num_cli' => $request->num,
                    'cod_pos' => '3800',
                    // 'cod_pos' => $request->cp,
                    'colonia' => '27968',
                    // 'colonia' => $request->colonia,
                    'municip' => $request->municip,
                    'poblaci' => $request->poblaci,
                    'cve_est' => "1",
                    'fec_nac' => $request->fnac,
                    'nac_ext' => $request->nacionalidad,
                    'ocu_pro' => $request->ocupacion,
                    'act_gir' => $request->giro,
                    'telefo1' => $request->telefono1,
                    'telefo2' => $request->telefono2,
                    'telefo3' => $request->telefono3,
                    'cor_ele' => $request->email,
                    'pag_web' => $request->web,
                    'can_con' => $request->contacto,
                    'fue_ing' => $request->ingresos,
                    'adm_con' => $request->administrador,
                    'car_pub' => $request->cargo_pub,
                    'nom_car' => $request->nombre_cargo,
                    'per_car' => $request->periodo_cargo,
                    'apo_cli' => $request->apoderado,
                    'dom_ori' => $request->domicilio_original,
                    'num_pas' => $request->pasaporte,
                    'usu_cap' => "",
                    'usu_aut' => "",
                    'fec_alt' => "",
                    'fec_act' => "",
                    'sta_cli' => "",
                    'descuento' => ""
                ],
                'datosIncisoEmision' => [
                    'numeroMotor' => $request->num_motor,
                    'numeroPlacas' => $request->num_placas,
                    'numeroSerie' => $request->num_serie
                ],
                'idAgenteCompartido' => 0,
                'idCliente' => 0,
                'idCotizacion' => (int) $request->cotizacion_id,
                'idFormaPago' => (int) $request->id_pago,
                'idPaquete' => (int) $request->idpaquete,
                'inicioVigencia' => date("Y-m-d"),
                'porcenComisionAgente2' => ""
            ]
        ]);

        $arr = json_decode(json_encode($emitir), true);
        // dd($arr);

        if ($arr['return']['exito']) {
            return view('generalseguros.pago', ['response' => $arr]);
        } else {
            // dd($request->colonia);
            dd($arr);
        }
    }
}
