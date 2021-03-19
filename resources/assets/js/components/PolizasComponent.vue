<template>
	<div>
		<!-- polizas {{cliente}} -->
        <div class="loading" v-show="loader">Loading&#8230;</div>
		<div class="row p-3 m-0">
			<div class="col">
				<h3>{{cliente.nombre }} {{cliente.appaterno}} {{cliente.apmaterno}} </h3>
                <div class="alert alert-info" role="alert">
                    <h5 class="alert-heading">
                        {{cliente.marca_auto.descripcion}} {{cliente.submarca_auto.descripcion}} {{cliente.modelo_auto}}
                    </h5>
                </div>
               
				<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" id="Amplia" v-model="tipo_poliza" value="Amplia">
                      <label class="form-check-label badge badge-primary" for="Amplia">Amplia</label>
                    </div>
                    <div class="form-check form-check-inline" v-if="cliente.uso_auto == 'Servicio Particular'">
                      <input class="form-check-input" type="radio" id="Limitada" v-model="tipo_poliza" value="Limitada">
                      <label class="form-check-label badge badge-primary" for="Limitada">Limitada</label>
                    </div>
                    <div class="form-check form-check-inline" v-if="cliente.uso_auto == 'Servicio Particular'">
                      <input class="form-check-input" type="radio" id="RC" v-model="tipo_poliza" value="RC">
                      <label class="form-check-label badge badge-primary" for="RC">RC</label>
                    </div>
	                
	            </ul>
	            <div class="tab-content" id="pills-tabContent">
	            	<!-- AMPLIA -->
	            	<div class="tab-pane fade show active" id="pills-Amplia" role="tabpanel" aria-labelledby="pills-Amplia-tab">
	            		<div class="row">
	            			<!--ESCRITORIO-->
                            <div class="col-12 p-2">
                                <div class="row m-2 no-gutters">
                                    <table class="table table-bordered table-striped table-responsive">
                                        <tbody>
                                            <!-- HEADERS -->
                                            <tr>
                                                <th scope="row" class="text-center w-150">
                                                    Aseguradora    
                                                </th>
                                                <th scope="row" class="text-center" v-if="cliente.gnp">
                                                    <img width="150" height="70" :src="img.gnpImage">
                                                </th>
                                                <th scope="row" class="text-center" v-if="cliente.gs">
                                                    <img width="150" height="70" :src="img.gsImage">
                                                </th>
                                                <th scope="row" class="text-center" v-if="cliente.ana">
                                                    <img width="150" height="70" :src="img.anaImage">
                                                </th>
                                                <th scope="row" class="text-center" v-if="cliente.qualitas">
                                                    <img width="150" height="70" :src="img.quaImage">
                                                </th>
                                            </tr>
                                            <!-- TODAS LAS DESCRIPCIONES DE LAS ASEGURADORAS -->
                                            <tr>
                                                <th scope="row" class="text-center">
                                                    Descripción
                                                </th>
                                                <td class="text-center" v-if="cliente.gnp">
                                                    <select class="form-control" v-model="desc_gnp">
                                                        <option value="">COBERTURAS</option>
                                                        <option v-for="descripcion in descripciones_gnp" :value="JSON.stringify(descripcion.ELEMENTO)">{{descripcion.ELEMENTO[4].VALOR}}</option>
                                                    </select>
                                                </td>
                                                <td class="text-center" v-if="cliente.gs">
                                                    <select class="form-control" v-model="desc_gs">
                                                        <option value="">COBERTURAS</option>
                                                        <option v-for="version in descripciones_gs" :value="version">{{version.descripcion}}</option>
                                                    </select>
                                                </td>
                                                <td class="text-center" v-if="cliente.ana">
                                                    <select class="form-control" v-model="desc_ana">
                                                        <option value="">COBERTURAS</option>
                                                        <option v-for="descripcion in descripciones_ana" :value="descripcion.clave">{{descripcion.descripcion}}</option>
                                                    </select>
                                                </td>
                                                <td class="text-center" v-if="cliente.qualitas">
                                                    <select class="form-control" v-model="desc_qualitas">
                                                        <option value="">COBERTURAS</option>
                                                        <option v-for="descripcion in descripciones_qualitas" :value="descripcion.CAMIS">{{descripcion.cVersion}}</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <!-- Primas -->
                                            <tr>
                                                <th scope="row" class="text-center">Prima Total</th>
                                                <td class="text-center" v-if="cliente.gnp">
                                                    <div v-if="cotizacionesGNP && cotizacionesGNP.PAQUETES !== undefined" style="padding">
                                                        <div class="border">{{ cotizacionesGNP.PAQUETES.PAQUETE.TOTALES.TOTAL_PRIMA[0].DESC_PERIODICIDAD }}:1er pago ${{ cotizacionesGNP.PAQUETES.PAQUETE.TOTALES.TOTAL_PRIMA[0].CONCEPTO_ECONOMICO[10].MONTO | int }}</div>
                                                        <div class="border">{{ cotizacionesGNP.PAQUETES.PAQUETE.TOTALES.TOTAL_PRIMA[1].DESC_PERIODICIDAD }}:1er pago ${{ cotizacionesGNP.PAQUETES.PAQUETE.TOTALES.TOTAL_PRIMA[1].CONCEPTO_ECONOMICO[11].MONTO | int }}<br>Subsecuentes(2): .${{cotizacionesGNP.PAQUETES.PAQUETE.TOTALES.TOTAL_PRIMA[1].CONCEPTO_ECONOMICO[12].MONTO}} </div>
                                                        <div class="border">{{ cotizacionesGNP.PAQUETES.PAQUETE.TOTALES.TOTAL_PRIMA[2].DESC_PERIODICIDAD }}:1er pago ${{ cotizacionesGNP.PAQUETES.PAQUETE.TOTALES.TOTAL_PRIMA[2].CONCEPTO_ECONOMICO[11].MONTO | int }} <br> Subsecuentes(3): .${{cotizacionesGNP.PAQUETES.PAQUETE.TOTALES.TOTAL_PRIMA[2].CONCEPTO_ECONOMICO[12].MONTO}}</div>

                                                        <div class="border">{{cotizacionesGNP.PAQUETES.PAQUETE.TOTALES.TOTAL_PRIMA[3].DESC_PERIODICIDAD }}:1er pago .${{ cotizacionesGNP.PAQUETES.PAQUETE.TOTALES.TOTAL_PRIMA[3].CONCEPTO_ECONOMICO[11].MONTO | int }} <br> Subsecuentes(11): .${{cotizacionesGNP.PAQUETES.PAQUETE.TOTALES.TOTAL_PRIMA[3].CONCEPTO_ECONOMICO[12].MONTO | int}} </div>
                                                    </div>
                                                    <div v-else>
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.gs">
                                                    <div v-if="cotizacionesGS.id" style="padding:0">
                                                        <div class="border" v-for="pago in cotizacionesGS.paquete[0].formasPagoDTO">
                                                            {{pago.nombre}}:  ${{pago.primaTotal | int}}
                                                        </div>
                                                    </div>
                                                    <div v-else>
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.ana">
                                                    <div v-if="cotizacionesANA.length" style="padding">
                                                        <div class="border">Contado: ${{cotizacionesANA[0]['CONTADO']['prima']['primatotal'] | int }}</div>
                                                        <div class="border">Semestral: ${{cotizacionesANA[1]['SEMESTRAL']['prima']['primatotal'] | int }}</div>
                                                        <div class="border">Trimestral: ${{cotizacionesANA[2]['TRIMESTRAL']['prima']['primatotal'] | int }}</div>
                                                    </div>
                                                    <div v-else>
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.qualitas">
                                                    <div v-if="cotizacionesQualitas.Primas">
                                                        <div class="border">Contado: ${{cotizacionesQualitas.Primas.PrimaTotal | int }}</div>
                                                    </div>
                                                    <div v-else>
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- SELECCIONAR -->
                                            <tr>
                                                <th scope="row" class="text-center">Seleccionar</th>
                                                <td class="text-center" v-if="cliente.gnp">
                                                    <div v-if="cotizacionesGNP && cotizacionesGNP.PAQUETES !== undefined">
                                                        <button type="button" id="9_1" class="btn btn-primary seleccionador" @click="emitirgnp(cotizacionesGNP, tipo_poliza)">Elegir</button>
                                                    </div>
                                                    <div v-else>
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.gs">
                                                    <div v-if="cotizacionesGS.id">
                                                        <button type="button" id="9_1" class="btn btn-primary seleccionador" @click="emitirgs(cotizacionesGS.id, cotizacionesGS.paquete[0])">Elegir</button>
                                                    </div>
                                                    <div v-else>
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.ana">
                                                    <div v-if="cotizacionesANA.length">
                                                        <button type="button" id="9_1" class="btn btn-primary seleccionador" @click="emitirANA(tipo_poliza,cotizacionesANA)">Elegir</button>
                                                    </div>
                                                    <div v-else>
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.qualitas">
                                                    <div v-if="cotizacionesQualitas.Primas">
                                                        <button type="button" id="9_1" class="btn btn-primary seleccionador" @click="emitirqua(cotizacionesQualitas, 1)">Elegir</button>
                                                    </div>
                                                    <div v-else>
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- DAÑOS MATERIALES SI ES AMPLIA -->
                                            <tr v-if="tipo_poliza == 'Amplia'">
                                                <th scope="row" class="text-center">
                                                    Daños Materiales
                                                </th>
                                                <td class="text-center" v-if="cliente.gnp">
                                                    <div class="text-center" v-if="desc_gnp && tipo_poliza && cotizacionesGNP && cotizacionesGNP.PAQUETES !== undefined" style="padding:0">
                                                        <div v-for="(cobertura,index) in cotizacionesGNP.PAQUETES.PAQUETE.COBERTURAS.COBERTURA" v-if="cobertura.NOMBRE == 'DM PERDIDA TOTAL                                  '">
                                                            <div class="border"><strong>{{cobertura.NOMBRE}}:</strong> {{cobertura.DEDUCIBLE}}</div>
                                                        </div>
                                                        <div v-for="(cobertura,index) in cotizacionesGNP.PAQUETES.PAQUETE.COBERTURAS.COBERTURA" v-if="cobertura.NOMBRE == 'DM PERDIDA PARCIAL                                '">
                                                            <div class="border"><strong>{{cobertura.NOMBRE}}:</strong> {{cobertura.DEDUCIBLE}}</div>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.gs">
                                                    <div class="text-center" v-if="desc_gs && tipo_poliza && cotizacionesGS.id" style="padding:0">
                                                        <div v-for="(cobertura,index) in cotizacionesGS.paquete[0].coberturas" v-if="cobertura.descripcion == 'DM Pérdida Parcial'">
                                                            <div class="border"><strong>{{cobertura.descripcion}}:</strong> {{cobertura.monto}}</div>
                                                        </div>
                                                        <div v-for="(cobertura,index) in cotizacionesGS.paquete[0].coberturas" v-if="cobertura.descripcion == 'DM Pérdida Total'">
                                                            <div class="border"><strong>{{cobertura.descripcion}}:</strong> {{cobertura.monto}}</div>
                 
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.ana">
                                                    <div class="text-center" v-if="desc_ana && tipo_poliza && cotizacionesANA.length != 0">
                                                        <div v-for="(cobertura,index) in cotizacionesANA[0]['CONTADO']['coberturas']" v-if="cobertura.desc == 'DAÑOS MATERIALES'">
                                                            <span><strong>{{cobertura.desc}}:</strong> {{cobertura.sa}}</span>
                                                            <span v-if="cobertura.ded"><strong>Deducible por daños:</strong>{{cobertura.ded}}</span>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.qualitas"> 
                                                    <div class="text-center" v-if="desc_qualitas && tipo_poliza && cotizacionesQualitas['Coberturas']">
                                                        <div v-for="(cobertura,index) in cotizacionesQualitas['Coberturas']" v-if="cobertura.tipo == 'Daños Materiales'">
                                                            <span><strong>{{cobertura.tipo}}:</strong> ${{cobertura['SumaAsegurada']|int}}</span>
                                                            <span v-if="cobertura['Deducible']"><strong>Deducible por daños:</strong> {{cobertura['Deducible']|int}}%</span>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- ROBO TOTAL SI ES AMPLIA O LIMITADA -->
                                            <tr v-if="tipo_poliza == 'Amplia' || tipo_poliza == 'Limitada'">
                                                <th scope="row" class="text-center">
                                                    Robo Total
                                                </th>
                                                <td class="text-center" v-if="cliente.gnp">
                                                    <div class="text-center" v-if="desc_gnp && tipo_poliza && cotizacionesGNP && cotizacionesGNP.PAQUETES !== undefined">
                                                        <div v-for="(cobertura,index) in cotizacionesGNP.PAQUETES.PAQUETE.COBERTURAS.COBERTURA" v-if="cobertura.NOMBRE == 'ROBO TOTAL                                        '">
                                                            <span>
                                                                <strong>{{cobertura.NOMBRE}}:</strong> {{cobertura.DEDUCIBLE}}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.gs">
                                                    <div class="text-center" v-if="desc_gs && tipo_poliza && cotizacionesGS.id">
                                                        <div v-for="(cobertura,index) in cotizacionesGS.paquete[0].coberturas" v-if="cobertura.descripcion == 'Robo Total'">
                                                            <span>
                                                                <strong>{{cobertura.descripcion}}:</strong> {{cobertura.monto}}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.ana">
                                                    <div class="text-center" v-if="desc_ana && tipo_poliza && cotizacionesANA.length != 0">
                                                        <div v-for="(cobertura,index) in cotizacionesANA[0]['CONTADO']['coberturas']" v-if="cobertura.desc == 'ROBO TOTAL'">
                                                            <span><strong>{{cobertura.desc}}:</strong> {{cobertura.sa}}</span>
                                                            <span v-if="cobertura.ded"><strong>Deducible por daños:</strong>{{cobertura.ded}}</span>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.qualitas"> 
                                                    <div class="text-center" v-if="desc_qualitas && tipo_poliza && cotizacionesQualitas['Coberturas']">
                                                        <div v-for="(cobertura,indez) in cotizacionesQualitas['Coberturas']" v-if="cobertura.tipo == 'Robo Total'">
                                                            <span><strong>{{cobertura.tipo}}:</strong> ${{cobertura['SumaAsegurada']|int}}</span>
                                                            <span v-if="cobertura['Deducible']"><strong>Deducible por daños:</strong> {{cobertura['Deducible']|int}}%</span>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- RESPONSABILIDAD CIVIL TODOS -->
                                            <tr>
                                                <th scope="row" class="text-center">
                                                    Responsabilidad Civil
                                                </th>
                                                <td class="text-center" v-if="cliente.gnp">
                                                    <div class="text-center" v-if="desc_gnp && tipo_poliza && cotizacionesGNP && cotizacionesGNP.PAQUETES !== undefined" style="padding:0">
                                                        <div v-for="(cobertura,index) in cotizacionesGNP.PAQUETES.PAQUETE.COBERTURAS.COBERTURA" v-if="cobertura.NOMBRE == 'RESPONSABILIDAD CIVIL POR DA#OS  A TERCEROS       '">
                                                            <div class="border" v-if="cobertura.DEDUCIBLE.length != 0"><strong>{{cobertura.NOMBRE}}:</strong> ${{cobertura.DEDUCIBLE}}</div>
                                                            <div v-else class="text-center"><strong>{{cobertura.NOMBRE}}:</strong> - </div>
                                                            <div class="border" v-if="cobertura.SUMA_ASEGURADA != ''"><strong>Suma asegurada:</strong> {{cobertura.SUMA_ASEGURADA}}</div>
                                                        </div>
                                                        <div v-for="(cobertura,index) in cotizacionesGNP.PAQUETES.PAQUETE.COBERTURAS.COBERTURA" v-if="cobertura.NOMBRE == 'EXTENSION DE RC                                   '">
                                                            <div v-if="cobertura.DEDUCIBLE != 'No aplica'" class="border"><strong>{{cobertura.NOMBRE}}:</strong> ${{cobertura.DEDUCIBLE}}</div>
                                                            <div v-else><strong>{{cobertura.NOMBRE}}:</strong> {{cobertura.DEDUCIBLE}} </div>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.gs">
                                                    <div class="text-center" v-if="desc_gs && tipo_poliza && cotizacionesGS.id" style="padding:0">
                                                        <div v-for="(cobertura,index) in cotizacionesGS.paquete[0].coberturas" v-if="cobertura.descripcion == 'Responsabilidad Civil por Daños a Terceros (LUC)'">
                                                            <div class="border"><strong>{{cobertura.descripcion}}:</strong> ${{cobertura.monto}}</div>
                                                        </div>
                                                        <div v-for="(cobertura,index) in cotizacionesGS.paquete[0].coberturas" v-if="cobertura.descripcion == 'Responsabilidad Civil por Fallecimiento'">
                                                            <div class="border"><strong>{{cobertura.descripcion}}:</strong> ${{cobertura.monto}}</div>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.ana">
                                                    <div class="text-center" v-if="desc_ana && tipo_poliza && cotizacionesANA.length != 0" style="padding:0">
                                                        <div class="border" v-for="(cobertura,index) in cotizacionesANA[0]['CONTADO']['coberturas']" v-if="cobertura.desc == 'RESPONSABILIDAD CIVIL'">
                                                            <span><strong>{{cobertura.desc}}:</strong> {{cobertura.sa}}</span>
                                                            <span v-if="cobertura.ded"><strong>Deducible por daños:</strong>{{cobertura.ded}}</span>
                                                        </div>
                                                        <div class="border" v-for="(cobertura,index) in cotizacionesANA[0]['CONTADO']['coberturas']" v-if="cobertura.desc == '  RC BIENES'">
                                                            <span><strong>{{cobertura.desc}}:</strong> {{cobertura.sa}}</span>
                                                            <span v-if="cobertura.ded"><strong>Deducible por daños:</strong>{{cobertura.ded}}</span>
                                                        </div>
                                                        <div class="border" v-for="(cobertura,index) in cotizacionesANA[0]['CONTADO']['coberturas']" v-if="cobertura.desc == '  RC PERSONAS'">
                                                            <span><strong>{{cobertura.desc}}:</strong> {{cobertura.sa}}</span>
                                                            <span v-if="cobertura.ded"><strong>Deducible por daños:</strong>{{cobertura.ded}}</span>
                                                        </div>
                                                        <div class="border" v-for="(cobertura,index) in cotizacionesANA[0]['CONTADO']['coberturas']" v-if="cobertura.desc == '  EXTENSION RC'">
                                                            <span><strong>{{cobertura.desc}}:</strong> {{cobertura.sa}}</span>
                                                            <span v-if="cobertura.ded"><strong>Deducible por daños:</strong>{{cobertura.ded}}</span>
                                                        </div>
                                                        <div class="border" v-for="(cobertura,index) in cotizacionesANA[0]['CONTADO']['coberturas']" v-if="cobertura.desc == '  RC DEL HIJO MENOR'">
                                                            <span><strong>{{cobertura.desc}}:</strong> {{cobertura.sa}}</span>
                                                            <span v-if="cobertura.ded"><strong>Deducible por daños:</strong>{{cobertura.ded}}</span>
                                                        </div>
                                                        <div class="border" v-for="(cobertura,index) in cotizacionesANA[0]['CONTADO']['coberturas']" v-if="cobertura.desc == '  RC POR REMOLQUES'">
                                                            <span><strong>{{cobertura.desc}}:</strong> {{cobertura.sa}}</span>
                                                            <span v-if="cobertura.ded"><strong>Deducible por daños:</strong>{{cobertura.ded}}</span>
                                                        </div>
                                                        <div class="border" v-for="(cobertura,index) in cotizacionesANA[0]['CONTADO']['coberturas']" v-if="cobertura.desc == 'RC CATASTROFICA POR MUERTE'">
                                                            <span><strong>{{cobertura.desc}}:</strong> {{cobertura.sa}}</span>
                                                            <span v-if="cobertura.ded"><strong>Deducible por daños:</strong>{{cobertura.ded}}</span>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.qualitas">
                                                    <div class="text-center" v-if="desc_qualitas && tipo_poliza && cotizacionesQualitas['Coberturas']">
                                                        <div v-for="(cobertura,indez) in cotizacionesQualitas['Coberturas']" v-if="cobertura.tipo == 'Responsabilidad Civil'">
                                                            <span><strong>{{cobertura.tipo}}:</strong> ${{cobertura['SumaAsegurada']|int}}</span>
                                                            <span v-if="cobertura['Deducible']"><strong>Deducible por daños:</strong> {{cobertura['Deducible']|int}}%</span>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- GASTOS MEDICOS -->
                                            <tr>
                                                <th scope="row" class="text-center">
                                                    Gastos Médicos
                                                </th>
                                                <td class="text-center" v-if="cliente.gnp">
                                                    <div class="text-center" v-if="desc_gnp && tipo_poliza && cotizacionesGNP && cotizacionesGNP.PAQUETES !== undefined" >
                                                        <div v-for="(cobertura,index) in cotizacionesGNP.PAQUETES.PAQUETE.COBERTURAS.COBERTURA" v-if="cobertura.NOMBRE == 'GASTOS MEDICOS OCUP       '">
                                                             <div v-if="cobertura.DEDUCIBLE != 'No aplica'"><span><strong>{{cobertura.NOMBRE}}:</strong> ${{cobertura.DEDUCIBLE}} </span></div>
                                                            <div v-else><strong>{{cobertura.NOMBRE}}:</strong> {{cobertura.DEDUCIBLE}}</div>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.gs">
                                                    <div class="text-center" v-if="desc_gs && tipo_poliza && cotizacionesGS.id" >
                                                        <div v-for="(cobertura,index) in cotizacionesGS.paquete[0].coberturas" v-if="cobertura.descripcion == 'Gastos Médicos'">
                                                             <span><strong>{{cobertura.descripcion}}:</strong> ${{cobertura.monto}} </span>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.ana">
                                                    <div class="text-center" v-if="desc_ana && tipo_poliza && cotizacionesANA.length != 0">
                                                        <div v-for="(cobertura,index) in cotizacionesANA[0]['CONTADO']['coberturas']" v-if="cobertura.desc == 'GASTOS MEDICOS'">
                                                            <span><strong>{{cobertura.desc}}</strong> ${{cobertura.sa}}</span>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.qualitas">
                                                    <div class="text-center" v-if="desc_qualitas && tipo_poliza && cotizacionesQualitas['Coberturas']">
                                                        <div v-for="(cobertura,indez) in cotizacionesQualitas['Coberturas']" v-if="cobertura.tipo == 'Gastos Médicos'">
                                                            <span><strong>{{cobertura.tipo}}:</strong> ${{cobertura['SumaAsegurada']|int}} </span>
                                                            <span v-if="cobertura['Deducible']"><strong>Deducible por daños:</strong> {{cobertura['Deducible']|int}}%</span>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- ASISTENCIA JURIDICA -->
                                            <tr>
                                                <th scope="row" class="text-center">
                                                    Legal
                                                </th>
                                                <td class="text-center" v-if="cliente.gnp">
                                                    <div class="text-center" v-if="desc_gnp && tipo_poliza && cotizacionesGNP && cotizacionesGNP.PAQUETES !== undefined" >
                                                        <div v-for="(cobertura,index) in cotizacionesGNP.PAQUETES.PAQUETE.COBERTURAS.COBERTURA" v-if="cobertura.NOMBRE == 'PROTECCION LEGAL                                  '">
                                                             <span><strong>{{cobertura.NOMBRE}}</strong>Deducible: {{cobertura.DEDUCIBLE}} Suma Asegurada: {{cobertura.SUMA_ASEGURADA}}</span>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.gs">
                                                    <div class="text-center" v-if="desc_gs && tipo_poliza && cotizacionesGS.id" >
                                                        <div v-for="(cobertura,index) in cotizacionesGS.paquete[0].coberturas" v-if="cobertura.descripcion == 'Asistencia Jurídica GS'">
                                                             <span><strong>{{cobertura.descripcion}}:</strong> {{cobertura.monto}} </span>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.ana">
                                                    <div class="text-center" v-if="desc_ana && tipo_poliza && cotizacionesANA.length != 0">
                                                        <div v-for="(cobertura,index) in cotizacionesANA[0]['CONTADO']['coberturas']" v-if="cobertura.desc == 'DEF. JUD. Y ASIS. LEGAL'">
                                                            <span><strong>{{cobertura.desc}}:</strong> {{cobertura.sa}}</span>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.qualitas">
                                                    <div class="text-center" v-if="desc_qualitas && tipo_poliza && cotizacionesQualitas['Coberturas']">
                                                        <div v-for="(cobertura,indez) in cotizacionesQualitas['Coberturas']" v-if="cobertura.tipo == 'Gastos Legales'">
                                                            <span><strong>{{cobertura.tipo}}:</strong> ${{cobertura['SumaAsegurada']|int}} </span>
                                                            <span v-if="cobertura['Deducible']"><strong>Deducible por daños:</strong> {{cobertura['Deducible']|int}}%</span>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- VIAL -->
                                            <tr>
                                                <th class="text-center" scope="row">
                                                    Vial
                                                </th>
                                                <td class="text-center" v-if="cliente.gnp">
                                                    <div class="text-center" v-if="desc_gnp && tipo_poliza && cotizacionesGNP && cotizacionesGNP.PAQUETES !== undefined" >
                                                        <div >
                                                             <span><strong>No aplica</strong></span>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.gs">
                                                    <div class="text-center" v-if="desc_gs && tipo_poliza && cotizacionesGS.id" >
                                                        <div v-for="(cobertura,index) in cotizacionesGS.paquete[0].coberturas" v-if="cobertura.descripcion == 'Asistencia Vial y en Viajes GS'">
                                                             <span>
                                                                <strong>
                                                                    {{cobertura.descripcion}}: 

                                                                </strong>
                                                                {{cobertura.monto}} 
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.ana">
                                                    <div class="text-center" v-if="desc_ana && tipo_poliza && cotizacionesANA.length != 0">
                                                        <div v-for="(cobertura,index) in cotizacionesANA[0]['CONTADO']['coberturas']" v-if="cobertura.desc == 'ANA ASISTENCIA'">
                                                            <span><strong>{{cobertura.desc}}:</strong> {{cobertura.sa}}</span>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.qualitas">
                                                    <div class="text-center" v-if="desc_qualitas && tipo_poliza && cotizacionesQualitas['Coberturas']">
                                                        <div v-for="(cobertura,indez) in cotizacionesQualitas['Coberturas']" v-if="cobertura.tipo == 'Asistencia Vial'">
                                                            <span><strong>{{cobertura.tipo}}:</strong> ${{cobertura['SumaAsegurada']|int}} </span>
                                                            <span v-if="cobertura['Deducible']"><strong>Deducible por daños:</strong> {{cobertura['Deducible']|int}}%</span>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- OTRAS COBERTURAS -->
                                            <tr>
                                                
                                                <th class="text-center" scope="row">
                                                    Otras Coberturas
                                                </th>
                                                <td class="text-center" v-if="cliente.gnp">
                                                    <div  class="text-center" v-if="cotizacionesGNP && cotizacionesGNP.PAQUETES !== undefined" style="padding:0;">
                                                        <div v-for="(cobertura,index) in cotizacionesGNP.PAQUETES.PAQUETE.COBERTURAS.COBERTURA" v-if="(['EXTENSION DE RC                                   ','CLUB GNP                                          '].indexOf(cobertura.NOMBRE) == -1)">
                                                            <span class="border">
                                                                <strong>
                                                                    {{cobertura.NOMBRE}}:
                                                                </strong> 
                                                                {{cobertura.DEDUCIBLE}}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.gs">
                                                    <div  class="text-center" v-if="cotizacionesGS.id" style="padding:0;">
                                                        <div v-for="(cobertura,index) in cotizacionesGS.paquete[0].coberturas" v-if="(['Daños Materiales Pérdida Parcial','Daños Materiales Pérdida Total','Robo Total','Responsabilidad Civil por Daños a Terceros (LUC)','Responsabilidad Civil por Fallecimiento','Gastos Médicos','Asistencia Jurídica GS','Asistencia Vial y en Viajes GS'].indexOf(cobertura.tipo) != -1)">
                                                            <span class="border">
                                                                <strong>
                                                                    {{cobertura.descripcion}}:
                                                                </strong> 
                                                                ${{cobertura.monto}}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.ana">
                                                    <div class="text-center" v-if="cotizacionesANA.length != 0" style="padding:0;">
                                                        <div v-for="(cobertura,index) in cotizacionesANA[0]['CONTADO']['coberturas']" v-if="!(['DAÑOS MATERIALES','ROBO TOTAL','RESPONSABILIDAD CIVIL','  RC BIENES','  RC PERSONAS','  EXTENSION RC','  RC DEL HIJO MENOR','  RC POR REMOLQUES','RC CATASTROFICA POR MUERTE','GASTOS MEDICOS','DEF. JUD. Y ASIS. LEGAL','ANA ASISTENCIA'].indexOf(cobertura.desc) != -1)">
                                                            
                                                            <div>
                                                                <span><strong>{{cobertura.desc}}:</strong> {{cobertura.sa}}</span>
                                                                <span v-if="cobertura.ded"><strong>Deducible:</strong>{{cobertura.ded}}</span>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                                <td class="text-center" v-if="cliente.qualitas">
                                                    <div class="text-center" v-if="cotizacionesQualitas['Coberturas']" style="padding: 0;">
                                                        <div v-for="(cobertura,index) in cotizacionesQualitas['Coberturas']" v-if="cobertura.tipo && !(['','Daños Materiales','Gastos Médicos','Gastos Legales','Asistencia Vial','Robo Total','Responsabilidad Civil'].indexOf(cobertura.tipo) != -1)">
                                                            <div>
                                                                <span><strong>{{cobertura.tipo}}:</strong>${{cobertura.SumaAsegurada|int}}</span>
                                                                <span v-if="cobertura.Deducible"><strong>Deducible:</strong> {{cobertura.Deducible|int}}%</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-else class="text-center">
                                                        Seleccione una descripción
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
	            		</div>
	            	</div>
	            </div>
			</div>
        </div>
       <!--  <pre>
            {{$data}}
        </pre> -->
	</div>
</template>
<script>
	export default{
		props:[
    		'cliente',
    		'getcotizacion',
            'alert',
            'img'
    	],
    	data(){
    		return{
                loader:false,
                loaderQ: true,
                loaderA:true,
                loaderGS:true,
                loaderGNP:true,
    			cotizacion:null,
    			cotizacionesQualitas:[],
                cotizacionesGS:[],
                cotizacionesANA:[],
                cotizacionesGNP:[],
    			error:null,
                setCotizacion: null,
                anaImage:null,
                gsImage:null,
                quaImage:null,
                gnpImage:null,
                descripciones_ana:[],
                descripciones_gs:[],
                descripciones_qualitas:[],
                descripciones_gnp:[],
                desc_ana:"",
                desc_gs:"",
                desc_qualitas:"",
                desc_gnp:"",
                tipo_poliza:"Amplia"
    		}
    	},
    	watch:{
    		'getcotizacion.value': function (newVal,oldVal) {
                if (this.cliente.ana) {
                    this.getDescripcionesANA(this.cliente.marca_auto.id_ana,this.cliente.submarca_auto.id_ana,this.cliente.submarca_auto.anio);
                }
                if (this.cliente.qualitas) {
                    this.getDescripcionesQualitas(this.cliente.marca_auto.descripcion,this.cliente.submarca_auto.descripcion, this.cliente.submarca_auto.anio)
                }
                if(this.cliente.gs){
                    console.log('ESTA ACTIVO DESCRIPCIONES GS');
                    this.getDescripcionesGS(this.cliente.marca_auto.descripcion,this.cliente.submarca_auto.descripcion, this.cliente.submarca_auto.anio);
                }
                if(this.cliente.gnp){
                    this.getDescripcionesGNP(this.cliente.marca_auto.descripcion,this.cliente.submarca_auto.descripcion, this.cliente.submarca_auto.anio);
                }
    		},
            'desc_ana': function (newVal,oldVal){
                this.loader = true;
                this.sendCotizacionANA(this.desc_ana,this.tipo_poliza);
            },
            'desc_qualitas':function (newVal,oldVal) {
                this.loader = true;
                this.sendCotizacionQualitas(this.desc_qualitas, this.tipo_poliza);
            },
            'desc_gs': function(newVal,oldVal) {
                this.loader = true;
                this.sendCotizacionGS(this.desc_gs,this.tipo_poliza);
            },
            'desc_gnp': function(value) {
                this.loader = true;
                console.log('PRIMER PASO GNP');
                this.sendCotizacionGNP(this.desc_gnp,this.tipo_poliza);
            },
            'tipo_poliza':function (newVal,oldVal) {
                this.loader=true;
                this.sendCotizacionANA(this.desc_ana,this.tipo_poliza);
                this.sendCotizacionQualitas(this.desc_qualitas, this.tipo_poliza);
                this.sendCotizacionGS(this.desc_gs,this.tipo_poliza);
                this.sendCotizacionGNP(this.desc_gnp,this.tipo_poliza);
            }
    	},
    	methods:{
            getDescripcionesANA(marca_id,submarca_id,modelo){
                let url = `./api/vehiculoANA/${marca_id}/${submarca_id}/${modelo}`;

                axios.get(url).then(res=>{
                    console.log('descripcion ana',res.data)
                    this.descripciones_ana = res.data.vehiculos;
                    console.log('Get Descripcion ANA',this.descripciones_ana);
                    console.log('Funciona el controlador ANA EN VUE');
                }).catch(err=>{
                    console.log('err',err)
                })
            },
            sendCotizacionANA(descripcion,poliza){
                let url = './api/cotizacionANA';

                let params = {
                    cotizacion : this.cliente.cotizacion,
                    descripcion : descripcion,
                    poliza:poliza
                }

                this.cotizacionesANA=[];

                axios.post(url,params).then(res=>{
                    if(res.data.ANASeguros){
                        this.loader=false;
                        this.cotizacionesANA=res.data.ANASeguros;
                        this.sendCotizacion(this.cliente, this.cotizacionesANA[0], "ANA");
                    }
                }).catch(err=>{
                    this.loader = false;
                    console.log('coberturas ana error',err);
                });

            },
            sendCotizacion(cliente, cotizacion, aseguradora){
                let params = { "cliente": cliente, "cotizacion": cotizacion, "aseguradora": aseguradora};
                let url = "./api/email-cotizacion";
                this.alert.message = '';
                this.alert.class = '';
                axios.post(url,params).then(res=>{
                    console.log('res:');
                    console.log('Metodo sendCotizacion');
                    console.log(res);
                     console.log('ESTA ACTIVO DESCRIPCIONES GS en sencotizacion');
                    // this.cliente.cotizacion =res.data.cotizacion.cotizacion;
                    // this.cliente.uso_auto =res.data.cotizacion.uso_auto;
                    // this.cliente.descripcion_auto = res.data.cotizacion.auto.version;
                    // this.cliente.marca_auto = res.data.cotizacion.auto.marca;
                    // this.cliente.modelo_auto = res.data.cotizacion.auto.submarca.anio;
                    // this.cliente.submarca_auto = res.data.cotizacion.auto.submarca;
                    // // this.cliente.auto = res.data.cotizacion.auto;
                    // this.cliente.cp =res.data.cotizacion.cp;
                    // this.cliente.nombre=res.data.cotizacion.nombre;
                    // this.cliente.appaterno =res.data.cotizacion.appaterno;
                    // this.cliente.apmaterno =res.data.cotizacion.apmaterno;
                    // this.cliente.telefono =res.data.cotizacion.telefono;
                    // this.cliente.email =res.data.cotizacion.email;
                    // this.cliente.sexo =res.data.cotizacion.sexo;
                    // this.cliente.f_nac =res.data.cotizacion.f_nac;
                    // this.cliente.ana = res.data.cotizacion.ana;
                    // this.cliente.gs=res.data.cotizacion.gs;
                    // this.cliente.qualitas = res.data.cotizacion.qualitas;
                    // this.getcotizacion.value = !this.getcotizacion.value;


                    //this.alert.message = `${this.cliente.nombre} ${this.cliente.appaterno} ${this.cliente.apmaterno} su cotización se guardo con el folio ${this.cliente.cotizacion}`;
                    //this.alert.class = "alert alert-success alert-dismissible fade show";
                    //$("#paso2-tab").removeClass("disabled");
                    //$("#paso2-tab").click();
                    // $('#cotizar').modal('show');
                }).catch(err=>{
                    console.log('err',err);
                })
            },
            getDescripcionesQualitas(marca,submarca,modelo){
                let uso = this.cliente.uso_auto
                let url=`./api/modelos/${uso}/${marca}/${submarca}/${modelo}`;
                axios.get(url).then(res=>{
                     console.log("descripcion qualitas",res.data);
                    this.descripciones_qualitas = res.data.descripciones;
                }).catch(err=>{
                    console.log(err);
                })
            },
            sendCotizacionQualitas(camis,poliza){
                let url="./api/getCoberturasQ";
                let params = {
                    cotizacion : this.cliente.cotizacion,
                    camis : camis,
                    poliza : poliza
                };
                this.cotizacionesQualitas=[];
                // this.loader = true;
                axios.post(url,params).then(res=>{
                    this.loader=false;
                    // console.log(res.data);
                    this.cotizacionesQualitas = res.data.Qualitas;
                }).catch(err=>{
                    this.loader=false;
                    console.log(err)
                });
            },

            getDescripcionesGS(marca,submarca,modelo){
                let url=`./api/versionesGS/${marca}/${submarca}/${modelo}`;
                axios.get(url).then(res=>{
                    console.log('RESULTADO GENERAL DE SEGUROS',res);
                    this.descripciones_gs = res.data.versiones_gs;
                }).catch(err=>{
                    console.log('ERROR GENERAL DE SEGUROS',err);
                })
            },
            sendCotizacionGS(descripcion,poliza){
                let url = "./api/getCotizacionGS";
                let params = {
                    cotizacion:this.cliente.cotizacion,
                    descripcion_gs:descripcion,
                    poliza:poliza
                }
                this.cotizacionesGS = [];
                axios.post(url,params).then(res=>{
                    console.log(res);
                    this.loader=false;
                    this.cotizacionesGS=res.data.cotizacion;
                }).catch(err=>{
                    this.loader=false;
                    console.log(err);
                })
            },

            getDescripcionesGNP(marca,submarca,modelo){
                let url=`./api/modelos-gnp/${marca}/${submarca}/${modelo}`;
                axios.get(url).then(res=>{
                     console.log('DESCRIPCIONES GNP', res);
                    this.descripciones_gnp = res.data.modelosGNP.ELEMENTOS;
                    console.log("Hola esto es GNP RESULTADO",this.descripciones_gnp);
                }).catch(err=>{
                     console.log("Error en GNP");
                    console.log(err);
                })

            },
            sendCotizacionGNP(descripcion, poliza){
                let url = "./api/get-cotizacion-gnp";
                let params = {
                    cotizacion: this.cliente.cotizacion,
                    descripcionGNP: descripcion,
                    poliza: poliza
                };
                this.cotizacionesGNP = {};
                axios.post(url,params).then(res=>{
                    this.cotizacionesGNP=res.data.cotizacionGNP;
                    console.log('Cotizacion GNP arreglo',this.cotizacionesGNP);
                    this.loader=false;
                }).catch(err=>{
                    this.loader=false;
                    console.log('GNP error', err);
                });
            },
            getCoberturasGS(cotizacion){
                let url = "./api/getCotizacionGS";
                let params = {cotizacion:cotizacion};
                axios.post(url,params).then(res=>{
                    // console.log("general res",res.data)
                    if (res.data.cotizacion) {
                        this.cotizacionesGS={"img": './img/GENERAL-DE-SEGUROS-LOGO.png','cotizacion':res.data.cotizacion};
                        this.loader = false;

                    }
                }).catch(error=>{
                    this.loaderGS = false;
                    console.log('general err',error);
                })

            },
            infoAna(cotiza){
                // console.log(cotiza[0].CONTADO);
                this.cotizacion=cotiza[0].CONTADO;
                console.log(this.setCotizacion);
            },
            emitirANA(key,cotiza){
                this.setCotizacion={nombre:"ANASeguros",cotizacion:{tipo:key,info:cotiza}, id_cotizacion:this.cliente.cotizacion};
                this.$emit("emitirana" , this.setCotizacion);
                $("#paso3-tab").removeClass("disabled");
                $("#paso3-tab").click();

            },
    		infoCotizacion(cotiza){
                console.log(cotiza);
    			this.cotizacion = cotiza;
    		},
            seleccionarCotizacion(cotizacion){
                $("#paso3-tab").removeClass("disabled");
                $("#paso3-tab").click();
                console.log(cotizacion);
            },
            emitirgs(cotizacion_id,paquete){
                console.log(cotizacion_id,paquete);

                this.setCotizacion = {nombre: "GS",id: cotizacion_id,paquete:paquete};
                this.$emit("emitirgs", this.setCotizacion);
                // console.log(this.gs);
                $("#paso3-tab").removeClass("disabled");
                $("#paso3-tab").click();
            },
            emitirqua(cotizacion,paquete,camis){
                cotizacion.paquetequa = paquete;
                cotizacion.camis = this.desc_qualitas;
                console.log(cotizacion);
                this.setCotizacion = cotizacion;
                this.$emit("emitirqua", this.setCotizacion);
                // console.log(this.gs);
                $("#paso3-tab").removeClass("disabled");
                $("#paso3-tab").click();
            },
            emitirgnp(cotizacion, tipo_poliza){
                this.setCotizacion = {
                    nombre: 'GNP',
                    descripcionAuto: this.desc_gnp,
                    tipo_poliza: tipo_poliza,
                    paquete: this.cotizacionesGNP.PAQUETES.PAQUETE,
                    numCotizacion: this.cotizacionesGNP.SOLICITUD.NUM_COTIZACION,
                };
                console.log('Cotizacion GNP: ', this.setCotizacion);
                this.$emit("emitirgnp" , this.setCotizacion);
                $("#paso3-tab").removeClass("disabled");
                $("#paso3-tab").click();
            }
    	},
    	filters:{
    		'int': function (value) {
			    if (!value) return ''
			    let val = (value/1).toFixed(2).replace(',', '.')
        		return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
			  }
    	},
    	created(){
    	},
    	mounted(){
            // this.anaImage="./img/ana1.png";
            // this.gsImage = "./img/GENERAL-DE-SEGUROS-LOGO.png";
            // this.quaImage = "./img/qua.png";
    	}
	}
</script>