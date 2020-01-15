<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SoapClient;


class ScriptController extends Controller
{
    public function execute()
    {
        $this->opts = array(
            'http' => array('header' => array('Content-Type:application/soap+xml; charset=utf-8'))
        );
        $this->params = array('encoding' => 'UTF-8', 'trace' => true, 'keep_alive' => false, 'soap_version' => SOAP_1_1, 'stream_context' => stream_context_create($this->opts));
        // DATOS GENERAL DE SEGUROS
        // ******************************************************
        // $this->urlAuth = "https://gdswas.mx/gsautos-ws/soap/autenticacionWS?wsdl";
        // $this->urlCotiza = "https://gdswas.mx/gsautos-ws/soap/cotizacionEmisionWS?wsdl";
        // $this->urlCat = "https://gdswas.mx/gsautos-ws/soap/catalogosWS?wsdl";
        // $this->urlCatAuto = "https://gdswas.mx/gsautos-ws/soap/catalogoAutosWS?wsdl";
        // $this->urlCober = "https://gdswas.mx/gsautos-ws/soap/catalogoCoberturasWS?wsdl";
        // *******************************************************
        $this->urlAuth = "https://gdswas.mx/gsautos-ws/soap/autenticacionWS?wsdl";
        $this->urlCotiza = "https://gdswas.mx/gsautos-ws/soap/cotizacionEmisionWS?wsdl";
        $this->urlCat = "https://gdswas.mx/gsautos-ws/soap/catalogosWS?wsdl";
        $this->urlCatAuto = "https://gdswas.mx/gsautos-ws/soap/catalogoAutosWS?wsdl";
        $this->urlCober = "https://serviciosgs.mx/gsautos-ws/soap/catalogoCoberturasWS?wsdl";
        try {
            $this->clientAuthGS = $this->getClient($this->urlAuth);

            // $this->clientCotGS = new SoapClient($this->urlCotiza,$this->params);
        } catch (SoapFault $fault) {
            dd($fault);
        }
        // get token
        $this->token = $this->getToken();
        dd($this->token);
        $this->middleware(function ($request, $next) {
            if ($this->token) {
                return $next($request);
            } else {
                return response()->json(['error' => 'token is null']);
            }
        });
    }

    public function getToken()
    {

        // dd($this->clientAuthGS->__getTypes());
        $result = $this->clientAuthGS->obtenerToken([
            'arg0' => [
                "usuario" => 'ATC0',
                'password' => '2r2kGdeUA0'
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

    public function getClient($url)
    {
        try {
            $client = new SoapClient($url, $this->params);
            return $client;
        } catch (FatalErrorException $error) {
            dd($error);
        }
    }
}
