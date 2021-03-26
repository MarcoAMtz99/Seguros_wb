<?php

namespace App\Services\Ana;

use Illuminate\Http\Request;
use Carbon\Carbon;
use SoapClient;

class EmitirPolizaService
{

    protected $opts;
    protected $params;
    protected $url;
    protected $urlPHP;
    protected $response;

    public function __construct(Request $request)
    {
        $this->setOptions($request);
        $this->setParams($request);
        $this->url = "https://server.anaseguros.com.mx/ananetws/service.asmx?wsdl";
        $this->urlPHP = "https://server.anaseguros.com.mx/ananetws/servicetext.asmx?wsdl";

        // dd($request->all());
        $estado = str_pad($request->estado, 2, "0", STR_PAD_LEFT);
        $municipio = str_pad($request->municipio_id, 3, "0", STR_PAD_LEFT);
        $estadoANA = $estado . $municipio;
        // dd($estadoANA);
        // dd($municipio);
        $fecha = Carbon::now();
        $fecha_hoy = $fecha->format('d/m/Y');
        // dd($fecha_hoy);
        $fecha_t = Carbon::parse($fecha);
        $fecha_t = $fecha_t->addYears(1)->format('d/m/Y');
        // dd($request->all());
        if ($request->plan == "Amplia") {
            if ($request->tipo_pago == "Tarjeta") {
                $vencimiento = $request->expiracionMM . substr($request->expiracionYY, 2, 2);
                $xml =
                    <<<XML
                        <transacciones xmlns="">
                        <transaccion version="1" tipotransaccion="E" cotizacion="" negocio="1196" tiponegocio="">
                            <vehiculo id="1" amis="$request->amis" modelo="$request->modelo" descripcion="" uso="1" servicio="1" plan="1" motor="$request->motor" serie="$request->serie" repuve="" placas="$request->placas" conductor="" conductorliciencia="" conductorfecnac="" conductorocupacion="" estado="$estadoANA" poblacion="$request->poblacion" color="$request->color" dispositivo="" fecdispositivo="" tipocarga="" tipocargadescripcion="">
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
                                <asegurado id="" nombre="$request->nombre" paterno="$request->apepat" materno="$request->apemat" calle="$request->calle" numerointerior="$request->num_int" numeroexterior="$request->num_ext" colonia="$request->poblacion" poblacion="$request->municipio_id" estado="$estadoANA" cp="$request->codigo_postal" pais="MEXICO" tipopersona="$request->tipo_persona">
                                    <argumento id="2" tipo="" campo="" valor="$request->correo"/>
                                    <argumento id="3" tipo="" campo="" valor="$request->telefono"/>
                                    <argumento id="4" tipo="" campo="" valor="$request->rfc"/>
                                    <argumento id="5" tipo="" campo="" valor="$request->curp"/>
                                    <argumento id="6" tipo="" campo="" valor="$request->nacionalidad"/>
                                    <argumento id="7" tipo="" campo="" valor="$request->identificacion"/>
                                    <argumento id="8" tipo="" campo="" valor="$request->num_identif"/>
                                    <argumento id="9" tipo="" campo="" valor="$request->ocupacion"/>
                                    <argumento id="10" tipo="" campo="" valor="$request->giro"/>
                                    <argumento id="11" tipo="" campo="" valor="$request->administrador"/>
                                    <argumento id="12" tipo="" campo="" valor="$request->nacionalidad_adm"/>
                                    <argumento id="13" tipo="" campo="" valor="$request->representante"/>
                                    <argumento id="14" tipo="" campo="" valor="$request->nacionalidad_representante"/>
                                </asegurado>
                                <poliza id="" tipo="A" endoso="" fecemision="" feciniciovig="$fecha_hoy" fecterminovig="$fecha_t" moneda="0" bonificacion="0" formapago="C" agente="14275" tarifacuotas="1804" tarifavalores="1804" tarifaderechos="1804" beneficiario="" politicacancelacion="1"/>
                                <prima primaneta="" derecho="" recargo="" impuesto="" primatotal="" comision=""/>
                                <recibo id="" feciniciovig="" fecterminovig="" primaneta="" derecho="" recargo="" impuesto="" primatotal="" comision="" cadenaoriginal="" sellodigital="" fecemision="" serie="" folio="" horaemision="" numeroaprobacion="" anoaprobacion="" numseriecertificado=""/>
                                <tarjetacredito cliente="$request->tarjeta_nombre" numero="$request->numero" vencimiento="$vencimiento" codigoseguridad="$request->codigo_seguridad"/>
                                <domiciliacion banco="$request->banco" direcciontarjetahabiente="$request->direccion_tarjeta" envio="N" rfc="$request->rfc_tarjeta" fiscal="N"/>
                                <error/>
                            </transaccion>
                        </transacciones>
                    XML;
            } else {

                $xml =
                    <<<XML
                    <transacciones xmlns="">
                    <transaccion version="1" tipotransaccion="E" cotizacion="" negocio="1195" tiponegocio="">
                        <vehiculo id="1" amis="$request->amis" modelo="$request->modelo" descripcion="" uso="1" servicio="1" plan="1" motor="$request->motor" serie="$request->serie" repuve="" placas="$request->placas" conductor="" conductorliciencia="" conductorfecnac="" conductorocupacion="" estado="$estadoANA" poblacion="$request->poblacion" color="$request->color" dispositivo="" fecdispositivo="" tipocarga="" tipocargadescripcion="">
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
                            <asegurado id="" nombre="$request->nombre" paterno="$request->apepat" materno="$request->apemat" calle="$request->calle" numerointerior="$request->num_int" numeroexterior="$request->num_ext" colonia="$request->poblacion" poblacion="$request->municipio_id" estado="$estadoANA" cp="$request->codigo_postal" pais="MEXICO" tipopersona="$request->tipo_persona">
                                <argumento id="2" tipo="" campo="" valor="$request->correo"/>
                                <argumento id="3" tipo="" campo="" valor="$request->telefono"/>
                                <argumento id="4" tipo="" campo="" valor="$request->rfc"/>
                                <argumento id="5" tipo="" campo="" valor="$request->curp"/>
                                <argumento id="6" tipo="" campo="" valor="$request->nacionalidad"/>
                                <argumento id="7" tipo="" campo="" valor="$request->identificacion"/>
                                <argumento id="8" tipo="" campo="" valor="$request->num_identif"/>
                                <argumento id="9" tipo="" campo="" valor="$request->ocupacion"/>
                                <argumento id="10" tipo="" campo="" valor="$request->giro"/>
                                <argumento id="11" tipo="" campo="" valor="$request->administrador"/>
                                <argumento id="12" tipo="" campo="" valor="$request->nacionalidad_adm"/>
                                <argumento id="13" tipo="" campo="" valor="$request->representante"/>
                                <argumento id="14" tipo="" campo="" valor="$request->nacionalidad_representante"/>
                            </asegurado>
                            <poliza id="" tipo="A" endoso="" fecemision="" feciniciovig="$fecha_hoy" fecterminovig="$fecha_t" moneda="0" bonificacion="0" formapago="C" agente="14275" tarifacuotas="1804" tarifavalores="1804" tarifaderechos="1804" beneficiario="" politicacancelacion="1"/>
                            <prima primaneta="" derecho="" recargo="" impuesto="" primatotal="" comision=""/>
                            <recibo id="" feciniciovig="" fecterminovig="" primaneta="" derecho="" recargo="" impuesto="" primatotal="" comision="" cadenaoriginal="" sellodigital="" fecemision="" serie="" folio="" horaemision="" numeroaprobacion="" anoaprobacion="" numseriecertificado=""/>
                            <error/>
                        </transaccion>
                    </transacciones>
                    XML;
            }
            // dd($xml);
        } elseif ($request->plan == "Limitada") {
            if ($request->tipo_pago == "Tarjeta") {
                $vencimiento = $request->expiracionMM . substr($request->expiracionYY, 2, 2);
                $xml =
                    <<<XML
                    <transacciones xmlns="">
                    <transaccion version="1" tipotransaccion="E" cotizacion="" negocio="1196" tiponegocio="">
                        <vehiculo id="1" amis="$request->amis" modelo="$request->modelo" descripcion="" uso="1" servicio="1" plan="3" motor="$request->motor" serie="$request->serie" repuve="" placas="$request->placas" conductor="" conductorliciencia="" conductorfecnac="" conductorocupacion="" estado="$estadoANA" poblacion="$request->poblacion" color="$request->color" dispositivo="" fecdispositivo="" tipocarga="" tipocargadescripcion="">
                                <cobertura id="04" desc="" sa="" tipo="3" ded="10" pma=""/>
                                <cobertura id="06" desc="" sa="200000" tipo="" ded="" pma=""/>
                                <cobertura id="07" desc="" sa="" tipo="" ded="" pma=""/>
                                <cobertura id="10" desc="" sa="" tipo="B" ded="" pma=""/>
                                <cobertura id="13" desc="" sa="2" tipo="" ded="" pma=""/>
                                <cobertura id="25" desc="" sa="1000000" tipo="" ded="" pma=""/>
                                <cobertura id="26" desc="" sa="1000000" tipo="" ded="" pma=""/>
                                <cobertura id="34" desc="" sa="2000000" tipo="" ded="" pma=""/>
                            </vehiculo>
                            <asegurado id="" nombre="$request->nombre" paterno="$request->apepat" materno="$request->apemat" calle="$request->calle" numerointerior="$request->num_int" numeroexterior="$request->num_ext" colonia="$request->poblacion" poblacion="$request->municipio_id" estado="$estadoANA" cp="$request->codigo_postal" pais="MEXICO" tipopersona="$request->tipo_persona">
                                <argumento id="2" tipo="" campo="" valor="$request->correo"/>
                                <argumento id="3" tipo="" campo="" valor="$request->telefono"/>
                                <argumento id="4" tipo="" campo="" valor="$request->rfc"/>
                                <argumento id="5" tipo="" campo="" valor="$request->curp"/>
                                <argumento id="6" tipo="" campo="" valor="$request->nacionalidad"/>
                                <argumento id="7" tipo="" campo="" valor="$request->identificacion"/>
                                <argumento id="8" tipo="" campo="" valor="$request->num_identif"/>
                                <argumento id="9" tipo="" campo="" valor="$request->ocupacion"/>
                                <argumento id="10" tipo="" campo="" valor="$request->giro"/>
                                <argumento id="11" tipo="" campo="" valor="$request->administrador"/>
                                <argumento id="12" tipo="" campo="" valor="$request->nacionalidad_adm"/>
                                <argumento id="13" tipo="" campo="" valor="$request->representante"/>
                                <argumento id="14" tipo="" campo="" valor="$request->nacionalidad_representante"/>
                            </asegurado>
                            <poliza id="" tipo="A" endoso="" fecemision="" feciniciovig="$fecha_hoy" fecterminovig="$fecha_t" moneda="0" bonificacion="0" formapago="C" agente="14275" tarifacuotas="1804" tarifavalores="1804" tarifaderechos="1804" beneficiario="" politicacancelacion="1"/>
                            <prima primaneta="" derecho="" recargo="" impuesto="" primatotal="" comision=""/>
                            <recibo id="" feciniciovig="" fecterminovig="" primaneta="" derecho="" recargo="" impuesto="" primatotal="" comision="" cadenaoriginal="" sellodigital="" fecemision="" serie="" folio="" horaemision="" numeroaprobacion="" anoaprobacion="" numseriecertificado=""/>
                            <tarjetacredito cliente="$request->tarjeta_nombre" numero="$request->numero" vencimiento="$vencimiento" codigoseguridad="$request->codigo_seguridad"/>
                            <domiciliacion banco="$request->banco" direcciontarjetahabiente="$request->direccion_tarjeta" envio="N" rfc="$request->rfc_tarjeta" fiscal="N"/>
                            <error/>
                        </transaccion>
                    </transacciones>
                    XML;
            } else {
                $xml =
                    <<<XML
                        <transacciones xmlns="">
                        <transaccion version="1" tipotransaccion="E" cotizacion="" negocio="1195" tiponegocio="">
                            <vehiculo id="1" amis="$request->amis" modelo="$request->modelo" descripcion="" uso="1" servicio="1" plan="3" motor="$request->motor" serie="$request->serie" repuve="" placas="$request->placas" conductor="" conductorliciencia="" conductorfecnac="" conductorocupacion="" estado="$estadoANA" poblacion="$request->poblacion" color="$request->color" dispositivo="" fecdispositivo="" tipocarga="" tipocargadescripcion="">
                                    <cobertura id="04" desc="" sa="" tipo="3" ded="10" pma=""/>
                                    <cobertura id="06" desc="" sa="200000" tipo="" ded="" pma=""/>
                                    <cobertura id="07" desc="" sa="" tipo="" ded="" pma=""/>
                                    <cobertura id="10" desc="" sa="" tipo="B" ded="" pma=""/>
                                    <cobertura id="13" desc="" sa="2" tipo="" ded="" pma=""/>
                                    <cobertura id="25" desc="" sa="1000000" tipo="" ded="" pma=""/>
                                    <cobertura id="26" desc="" sa="1000000" tipo="" ded="" pma=""/>
                                    <cobertura id="34" desc="" sa="2000000" tipo="" ded="" pma=""/>
                                </vehiculo>
                                <asegurado id="" nombre="$request->nombre" paterno="$request->apepat" materno="$request->apemat" calle="$request->calle" numerointerior="$request->num_int" numeroexterior="$request->num_ext" colonia="$request->poblacion" poblacion="$request->municipio_id" estado="$estadoANA" cp="$request->codigo_postal" pais="MEXICO" tipopersona="$request->tipo_persona">
                                    <argumento id="2" tipo="" campo="" valor="$request->correo"/>
                                    <argumento id="3" tipo="" campo="" valor="$request->telefono"/>
                                    <argumento id="4" tipo="" campo="" valor="$request->rfc"/>
                                    <argumento id="5" tipo="" campo="" valor="$request->curp"/>
                                    <argumento id="6" tipo="" campo="" valor="$request->nacionalidad"/>
                                    <argumento id="7" tipo="" campo="" valor="$request->identificacion"/>
                                    <argumento id="8" tipo="" campo="" valor="$request->num_identif"/>
                                    <argumento id="9" tipo="" campo="" valor="$request->ocupacion"/>
                                    <argumento id="10" tipo="" campo="" valor="$request->giro"/>
                                    <argumento id="11" tipo="" campo="" valor="$request->administrador"/>
                                    <argumento id="12" tipo="" campo="" valor="$request->nacionalidad_adm"/>
                                    <argumento id="13" tipo="" campo="" valor="$request->representante"/>
                                    <argumento id="14" tipo="" campo="" valor="$request->nacionalidad_representante"/>
                                </asegurado>
                                <poliza id="" tipo="A" endoso="" fecemision="" feciniciovig="$fecha_hoy" fecterminovig="$fecha_t" moneda="0" bonificacion="0" formapago="C" agente="14275" tarifacuotas="1804" tarifavalores="1804" tarifaderechos="1804" beneficiario="" politicacancelacion="1"/>
                                <prima primaneta="" derecho="" recargo="" impuesto="" primatotal="" comision=""/>
                                <recibo id="" feciniciovig="" fecterminovig="" primaneta="" derecho="" recargo="" impuesto="" primatotal="" comision="" cadenaoriginal="" sellodigital="" fecemision="" serie="" folio="" horaemision="" numeroaprobacion="" anoaprobacion="" numseriecertificado=""/>
                                <error/>
                            </transaccion>
                        </transacciones>
                    XML;
            }
        } elseif ($request->plan == "RC") {
            if ($request->tipo_pago == "Tarjeta") {
                $vencimiento = $request->expiracionMM . substr($request->expiracionYY, 2, 2);
                $xml =
                    <<<XML
                    <transacciones xmlns="">
                    <transaccion version="1" tipotransaccion="E" cotizacion="" negocio="1196" tiponegocio="">
                        <vehiculo id="1" amis="$request->amis" modelo="$request->modelo" descripcion="" uso="1" servicio="1" plan="4" motor="$request->motor" serie="$request->serie" repuve="" placas="$request->placas" conductor="" conductorliciencia="" conductorfecnac="" conductorocupacion="" estado="$estadoANA" poblacion="$request->poblacion" color="$request->color" dispositivo="" fecdispositivo="" tipocarga="" tipocargadescripcion="">
                                <cobertura id="06" desc="" sa="200000" tipo="" ded="" pma=""/>
                                <cobertura id="07" desc="" sa="" tipo="" ded="" pma=""/>
                                <cobertura id="10" desc="" sa="" tipo="B" ded="" pma=""/>
                                <cobertura id="13" desc="" sa="2" tipo="" ded="" pma=""/>
                                <cobertura id="25" desc="" sa="1000000" tipo="" ded="" pma=""/>
                                <cobertura id="26" desc="" sa="1000000" tipo="" ded="" pma=""/>
                                <cobertura id="34" desc="" sa="2000000" tipo="" ded="" pma=""/>
                            </vehiculo>
                            <asegurado id="" nombre="$request->nombre" paterno="$request->apepat" materno="$request->apemat" calle="$request->calle" numerointerior="$request->num_int" numeroexterior="$request->num_ext" colonia="$request->poblacion" poblacion="$request->municipio_id" estado="$estadoANA" cp="$request->codigo_postal" pais="MEXICO" tipopersona="$request->tipo_persona">
                                <argumento id="2" tipo="" campo="" valor="$request->correo"/>
                                <argumento id="3" tipo="" campo="" valor="$request->telefono"/>
                                <argumento id="4" tipo="" campo="" valor="$request->rfc"/>
                                <argumento id="5" tipo="" campo="" valor="$request->curp"/>
                                <argumento id="6" tipo="" campo="" valor="$request->nacionalidad"/>
                                <argumento id="7" tipo="" campo="" valor="$request->identificacion"/>
                                <argumento id="8" tipo="" campo="" valor="$request->num_identif"/>
                                <argumento id="9" tipo="" campo="" valor="$request->ocupacion"/>
                                <argumento id="10" tipo="" campo="" valor="$request->giro"/>
                                <argumento id="11" tipo="" campo="" valor="$request->administrador"/>
                                <argumento id="12" tipo="" campo="" valor="$request->nacionalidad_adm"/>
                                <argumento id="13" tipo="" campo="" valor="$request->representante"/>
                                <argumento id="14" tipo="" campo="" valor="$request->nacionalidad_representante"/>
                            </asegurado>
                            <poliza id="" tipo="A" endoso="" fecemision="" feciniciovig="$fecha_hoy" fecterminovig="$fecha_t" moneda="0" bonificacion="0" formapago="C" agente="14275" tarifacuotas="1804" tarifavalores="1804" tarifaderechos="1804" beneficiario="" politicacancelacion="1"/>
                            <prima primaneta="" derecho="" recargo="" impuesto="" primatotal="" comision=""/>
                            <recibo id="" feciniciovig="" fecterminovig="" primaneta="" derecho="" recargo="" impuesto="" primatotal="" comision="" cadenaoriginal="" sellodigital="" fecemision="" serie="" folio="" horaemision="" numeroaprobacion="" anoaprobacion="" numseriecertificado=""/>
                            <tarjetacredito cliente="$request->tarjeta_nombre" numero="$request->numero" vencimiento="$vencimiento" codigoseguridad="$request->codigo_seguridad"/>
                            <domiciliacion banco="$request->banco" direcciontarjetahabiente="$request->direccion_tarjeta" envio="N" rfc="$request->rfc_tarjeta" fiscal="N"/>
                            <error/>
                        </transaccion>
                    </transacciones>
                    XML;
            } else {
                $xml = <<<XML
                    <transacciones xmlns="">
                    <transaccion version="1" tipotransaccion="E" cotizacion="" negocio="1195" tiponegocio="">
                        <vehiculo id="1" amis="$request->amis" modelo="$request->modelo" descripcion="" uso="1" servicio="1" plan="4" motor="$request->motor" serie="$request->serie" repuve="" placas="$request->placas" conductor="" conductorliciencia="" conductorfecnac="" conductorocupacion="" estado="$estadoANA" poblacion="$request->poblacion" color="$request->color" dispositivo="" fecdispositivo="" tipocarga="" tipocargadescripcion="">
                                <cobertura id="06" desc="" sa="200000" tipo="" ded="" pma=""/>
                                <cobertura id="07" desc="" sa="" tipo="" ded="" pma=""/>
                                <cobertura id="10" desc="" sa="" tipo="B" ded="" pma=""/>
                                <cobertura id="13" desc="" sa="2" tipo="" ded="" pma=""/>
                                <cobertura id="25" desc="" sa="1000000" tipo="" ded="" pma=""/>
                                <cobertura id="26" desc="" sa="1000000" tipo="" ded="" pma=""/>
                                <cobertura id="34" desc="" sa="2000000" tipo="" ded="" pma=""/>
                            </vehiculo>
                            <asegurado id="" nombre="$request->nombre" paterno="$request->apepat" materno="$request->apemat" calle="$request->calle" numerointerior="$request->num_int" numeroexterior="$request->num_ext" colonia="$request->poblacion" poblacion="$request->municipio_id" estado="$estadoANA" cp="$request->codigo_postal" pais="MEXICO" tipopersona="$request->tipo_persona">
                                <argumento id="2" tipo="" campo="" valor="$request->correo"/>
                                <argumento id="3" tipo="" campo="" valor="$request->telefono"/>
                                <argumento id="4" tipo="" campo="" valor="$request->rfc"/>
                                <argumento id="5" tipo="" campo="" valor="$request->curp"/>
                                <argumento id="6" tipo="" campo="" valor="$request->nacionalidad"/>
                                <argumento id="7" tipo="" campo="" valor="$request->identificacion"/>
                                <argumento id="8" tipo="" campo="" valor="$request->num_identif"/>
                                <argumento id="9" tipo="" campo="" valor="$request->ocupacion"/>
                                <argumento id="10" tipo="" campo="" valor="$request->giro"/>
                                <argumento id="11" tipo="" campo="" valor="$request->administrador"/>
                                <argumento id="12" tipo="" campo="" valor="$request->nacionalidad_adm"/>
                                <argumento id="13" tipo="" campo="" valor="$request->representante"/>
                                <argumento id="14" tipo="" campo="" valor="$request->nacionalidad_representante"/>
                            </asegurado>
                            <poliza id="" tipo="A" endoso="" fecemision="" feciniciovig="$fecha_hoy" fecterminovig="$fecha_t" moneda="0" bonificacion="0" formapago="C" agente="14275" tarifacuotas="1804" tarifavalores="1804" tarifaderechos="1804" beneficiario="" politicacancelacion="1"/>
                            <prima primaneta="" derecho="" recargo="" impuesto="" primatotal="" comision=""/>
                            <recibo id="" feciniciovig="" fecterminovig="" primaneta="" derecho="" recargo="" impuesto="" primatotal="" comision="" cadenaoriginal="" sellodigital="" fecemision="" serie="" folio="" horaemision="" numeroaprobacion="" anoaprobacion="" numseriecertificado=""/>
                            <error/>
                        </transaccion>
                    </transacciones>
                    XML;
            }
        }
        try {
            $client = new SoapClient($this->urlPHP, $this->params);
            $res = $client->TransaccionText(["XML" => $xml, "Tipo" => "Emision", "Usuario" => "14275", "Clave" => "kdEDyC9F"]);
            $array = json_decode(json_encode(simplexml_load_string($res->TransaccionTextResult)), true);
            dd($array);
            $error = is_string($array['transaccion']['error']);
            if (!$error) {
                // dd('primer if');
                $poliza_id = $array["transaccion"]["poliza"]["@attributes"]["id"];
                $noPoliza = substr($poliza_id, 2, 9);
                $endoso = substr($poliza_id, 11, 6);
                $polizaResp = $this->imprimirPoliza($noPoliza, $endoso, $request->tipo_pago);
                // dd($polizaResp);
                $this->response = view('ana.pago', ['response' => $polizaResp]);
            } elseif (is_string($array["transaccion"]["error"]) && strstr($array["transaccion"]["error"], "No se puede emitir la póliza debido a que existe una poliza vigente")) {
                // dd('segundo if');
                $noPoliza = substr($array['transaccion']['error'], 84, 9);
                $endoso = substr($array["transaccion"]["error"], 104, 6);
                $polizaResp = $this->imprimirPoliza($noPoliza, $endoso, $request->tipo_pago);
                // dd($polizaResp);
                $this->response = view('ana.pago', ['response' => $polizaResp]);
                // dd($endoso);
                // dd($array["transaccion"]["error"]);
            } else {
                // dd('Cayó aqui');
                // return redirect()->back();
                // dd($array);
                // dd("ninguno");
                $this->response = $array;
            }
            // dd($array);
        } catch (SoapFault $fault) {
            dd($fault);
        }
    }

    public function imprimirPoliza($poliza, $endoso, $tipo_pago)
    {
        if ($tipo_pago == "Tarjeta") {
            $xmlImpr = <<<XML
<transacciones xmlns="">
    <transaccion version="1" tipotransaccion="I" negocio="1196">
        <poliza id="$poliza" endoso="$endoso" inciso="1" link=""/>
        <error/>
    </transaccion>
</transacciones>
XML;
        } else {
            $xmlImpr = <<<XML
<transacciones xmlns="">
    <transaccion version="1" tipotransaccion="I" negocio="1195">
        <poliza id="$poliza" endoso="$endoso" inciso="1" link=""/>
        <error/>
    </transaccion>
</transacciones>
XML;
        }
        try {
            $client = new SoapClient($this->urlPHP, $this->params);
            $resImp = $client->TransaccionText(["XML" => $xmlImpr, "Tipo" => "Impresion", "Usuario" => "14275", "Clave" => "kdEDyC9F"]);
            // dd($res);
            $array = json_decode(json_encode(simplexml_load_string($resImp->TransaccionTextResult)), true);
            return $array['transaccion']['poliza']['@attributes'];
        } catch (SoapFault $fault) {
            dd($fault);
        }
    }

    public function response()
    {
        return $this->response;
    }

    /**
     * =======
     * SETTERS
     * =======
     */

    public function setOptions($request)
    {
        $this->opts = array(
            'http' => array(
                'header' => 'Content-Type:application/xml;charset=utf-8',
                'user_agent' => 'PHPSoapClient'
            )
        );
    }

    public function setParams($request)
    {
        $this->params = array(
            'encoding' => 'UTF-8',
            'verifypeer' => false,
            'verifyhost' => false,
            'soap_version' => SOAP_1_1,
            'trace' => 1,
            'exceptions' => 1,
            'connection_timeout' => 5000,
            'stream_context' => stream_context_create($this->opts)
        );
    }
}
