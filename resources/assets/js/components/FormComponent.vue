<template>
	<div>
		<div class="row m-3">
			<div class="col-12 m-2 p-2">
				<!-- <form> -->
				<!-- <h6>{{cliente}}</h6> -->
				<form v-if="cotizacion.nombre === 'GS'" @submit="sendGS" method="POST" action="./sendGS">
					<input type="hidden" name="_token" :value="csrf" />
					<div class="row">
						<div class="offset-1 col-5 offset-md-2 col-md-4 w-md-150">
							<img width="100%" height="100%" :src="img.gsImageForm">
						</div>
						<div class="offset-1 col-5 col-md-3">
							<h5 class="mt-3 ml-3">General de Seguros</h5>
						</div>
					</div>
					
					<div class="row">
						<input type="hidden" name="cotizacion_id" v-model="generalseguro.cotizacion.id_cotizacion">
						<div class="col-12 mt-3">
							<h6>Datos del asegurado:</h6>

						</div>
						<div class="form-group col-12">
							<label class="control-label"><i class="fas fa-asterisk"></i> Tipo de persona:</label>
							<!-- INPUT TIPO PERSONA (FISICA) -->
	                        <div class="form-check col-12">
	                            <input class="form-check-input" type="radio" name="tipo_persona" id="radioF" v-model="generalseguro.cliente.tipo_persona" value="F" required="" checked>
	                            <label class="form-check-label" for="radioF">
	                             Fisica
	                            </label>
	                        </div>
							<!-- INPUT TIPO PERSONAL (MORAL) -->
	                        <div class="form-check col-12">
	                            <input class="form-check-input" type="radio" name="tipo_persona" id="radioM" v-model="generalseguro.cliente.tipo_persona" value="M">
	                            <label class="form-check-label" for="radioM">
	                             Moral
	                            </label>
	                        </div>
						</div>
					</div>
					<div class="row" v-if="generalseguro.cliente.tipo_persona == 'F' ">
						<div class="form-group col-12 col-md-4">
							<label class="control-label">
								<i class="fas fa-asterisk"></i> Nombre(s)
							</label>
							<input class="form-control" type="text" name="nombre" v-model="generalseguro.cliente.nombre" required="" value="cliente.nombre">
							
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label">
								<i class="fas fa-asterisk"></i> Apellido Paterno
							</label>
							<input type="text" name="apepat" class="form-control" v-model="generalseguro.cliente.apepat" required="" value="cliente.appaterno">
							
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label">
								Apellido Materno
							</label>
							<input type="text" name="apemat" class="form-control" v-model="generalseguro.cliente.apemat"value="cliente.apmaterno">
							
						</div>
					</div>
					<div class="row" v-if="generalseguro.cliente.tipo_persona == 'M'">
						<div class="form-group col-12">
							<label class="control-label">
								<i class="fas fa-asterisk"></i> Razón Social
							</label>
							<input type="text" name="razsoc" class="form-control" v-model="generalseguro.cliente.razsoc" required>
						</div>
					</div>



						<div class="row" v-if="generalseguro.cliente.tipo_persona == 'M' ">
						<div class="offset-1 col-5 col-12">
							<h5 class="mt-3 ml-3">Datos del contratante</h5>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label">
								<i class="fas fa-asterisk"></i> Nombre(s)
							</label>
							<input class="form-control" type="text" name="nombre" v-model="generalseguro.cliente.nombre" placeholder="NOMBRE EMPRESA" required="">
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label">
								<i class="fas fa-asterisk"></i> Apellido Paterno
							</label>
							<input type="text" name="apepat" class="form-control" v-model="generalseguro.cliente.apepat" placeholder="SA" required="">
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label">
								Apellido Materno
							</label>
							<input type="text" name="apemat" class="form-control" v-model="generalseguro.cliente.apemat" placeholder="DE CV">
						</div>
					</div>
					<div class="row">
						<div class="form-group col-12 col-md-4">
							<label class="control-label">
								<i class="fas fa-asterisk"></i> RFC de la empresa
							</label>
							<input type="text" name="rfc" class="form-control" v-model="generalseguro.cliente.rfc" required="">
						</div>
						<div class="form-group col-12 col-md-4">
							<label for="elector" class="control-label">Clave de credencial de Elector/INE</label>
							<input type="text" name="elector" class="form-control" v-model="generalseguro.cliente.elector">
						</div>
						<div class="form-group col-12 col-md-4">
							<label for="curp" class="control-label">CURP</label>
							<input type="text" name="curp" class="form-control" v-model="generalseguro.cliente.curp">
						</div>
						<div class="form-group col-12 col-md-4" v-if="generalseguro.cliente.tipo_persona == 'F'">
							<label for="sexo" class="control-label"><i class="fas fa-asterisk"></i> Sexo</label>
							<select name="sexo" class="form-control" v-model="generalseguro.cliente.sexo" required>
								<option value="">Seleccione su sexo</option>
								<option value="1">Masculino</option>
								<option value="2">Femenino</option>
							</select>
						</div>
						<div class="form-group col-12 col-md-4" v-if="generalseguro.cliente.tipo_persona == 'F'">
							<label class="control-label" for="edoCivil"><i class="fas fa-asterisk"></i> Estado Civil</label>
							<select name="edoCivil" class="form-control" v-model="generalseguro.cliente.edoCivil" required="">
								<option value="">Seleccione su estado civil</option>
								<option v-for="edocivil in estadosCiviles" :value="edocivil.id">{{edocivil.descripcion}}</option>
							</select>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label" for="calle"><i class="fas fa-asterisk"></i> Calle</label>
							<input type="text" name="calle" class="form-control" v-model="generalseguro.cliente.calle" required>
						</div>
						<div class="form-group col-12 col-md-4">
							<label for="num" class="control-label"><i class="fas fa-asterisk"></i> Número exterior</label>
							<input type="text" name="num" class="form-control" v-model="generalseguro.cliente.num" required>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label" for="cp"><i class="fas fa-asterisk"></i> Código Postal</label>
							<input type="text" name="cp" class="form-control" v-model="generalseguro.cliente.cp" required disabled="true">
						</div>
						<div class="form-group col-12 col-md-4">
							

							<label class="control-label" for="colonia"><i class="fas fa-asterisk"></i> Colonia</label>
							<select class="form-control" name="colonia" v-model="generalseguro.cliente.colonia" required>
								<option value="">Seleccione su colonia</option>
								<option v-for="colonia in generalColonias" :value="colonia.colonias">{{colonia.descripciones}}</option>
							</select>
						<!-- 	<input type="text" name="colonia" class="form-control" v-model="generalseguro.cliente.colonia" required> -->


						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label" for="municip"><i class="fas fa-asterisk"></i>Municipio</label>
							<input type="text" name="municip" class="form-control" v-model="generalseguro.cliente.municip" required>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label" for="poblaci"><i class="fas fa-asterisk"></i> Alcaldia o Población</label>
							<input type="text" name="poblaci" class="form-control" v-model="generalseguro.cliente.poblaci" required>
						</div>
						<div class="form-group col-12 col-md-4">
							<label for="fnac" class="control-label"><i class='fas fa-asterisk'></i> {{generalseguro.cliente.tipo_persona == 'F' ? 'Fecha de nacimiento' : 'Fecha de constitución'}}</label>
							<input type="date" name="fnac" class="form-control" v-model="generalseguro.cliente.fnac" required>
						</div>
						<div class="form-group col-12 col-md-4">
							<label for="nacionalidad" class="control-label"><i class="fas fa-asterisk"></i> Nacionalidad</label>
							<select name="nacionalidad" id="nacionalidad" class="form-control" v-model="generalseguro.cliente.nacionalidad" required>
								<option value="">Seleccione su nacionalidad</option>
								<option value="1">Mexicana</option>
								<option value="2">Extranjera</option>
							</select>
						</div>
						<div class="form-group col-12" v-if="generalseguro.cliente.nacionalidad == 2">
							<label for="domicilio_original" class="control-label"><i class="fas fa-asterisk"></i> Domicilio original (extranjeros)</label>
							<input type="text" name="domicilio_original" class="form-control" v-model="generalseguro.cliente.domicilio_original" required>
						</div>
						<div class="form-group col-12 col-md-4"  v-if="generalseguro.cliente.tipo_persona == 'F'">
							<label for="ocupacion" class="control-label"><i class="fas fa-asterisk"></i> Ocupación</label>
							<select name="ocupacion" id="ocupacion" class="form-control" v-model="generalseguro.cliente.ocupacion" required>
								<option value="">Seleccione su ocupacion</option>
								<option v-for="ocupacion in ocupaciones" :value="ocupacion.cveTit">{{ocupacion.desTit}}</option>
							</select>
						</div>
						<div class="form-group col-12 col-md-4">
							<label for="giro" class="control-label"><i class="fas fa-asterisk"></i> Actividad o Giro</label>
							<select name="giro" id="giro" class="form-control" v-model="generalseguro.cliente.giro" required>
								<option value="">Seleccione el giro o actividad</option>
								<option v-for="giro in giros" :value="giro.id.cveGir">{{giro.id.descGir}}</option>
							</select>
						</div>
						<div class="form-group col-12 col-md-4">
							<label for="telefono1" class="control-label"><i class="fas fa-asterisk"></i> Telefono</label>
							<input type="tel" name="telefono1" id="telefono" class="form-control" v-model="generalseguro.cliente.telefono1" required>
						</div>
						<div class="form-group col-12 col-md-4">
							<label for="telefono2" class="control-label">Telefono</label>
							<input type="tel" name="telefono2" id="telefono" class="form-control" v-model="generalseguro.cliente.telefono2">
						</div>
						<div class="form-group col-12 col-md-4">
							<label for="telefono3" class="control-label">Telefono</label>
							<input type="tel" name="telefono3" id="telefono" class="form-control" v-model="generalseguro.cliente.telefono3">
						</div>
						<div class="form-group col-12 col-md-4">
							<label for="email" class="control-label"><i class="fas fa-asterisk"></i> Correo electronico</label>
							<input type="email" name="email" class="form-control" v-model="generalseguro.cliente.email" required>
						</div>
						<div class="form-group col-12 col-md-4">
							<label for="web" class="control-label">Pagina web</label>
							<input type="url" name="web" class="form-control" v-model="generalseguro.cliente.web">
						</div>
						<div class="form-group col-12 col-md-4">
							<label for="contacto" class="control-label"><i class="fas fa-asterisk"></i> Forma en que se entero de la pagina</label>
							<select name="contacto" id="contacto" class="form-control" v-model="generalseguro.cliente.contacto" required>
								<option value="">Seleccione el medío por el que se entero</option>
								<option v-for="contacto in tipocontactos" :value="contacto.id.cveCco">{{contacto.id.desCco}}</option>
							</select>
						</div>
						<div class="form-group col-12 col-md-4">
							<label for="ingresos" class="control-label"><i class="fas fa-asterisk"></i> Fuente de ingresos</label>
							<input type="text" name="ingresos" class="form-control" v-model="generalseguro.cliente.ingresos" required>
						</div>
						<div class="form-group col-12 col-md-4" v-if="generalseguro.cliente.tipo_persona == 'M'">
							<label for="administrador" class="control-label"><i class="fas fa-asterisk"></i> Tipo de administrador de la empresa</label>
							<select name="administrador" id="administrador" class="form-control" v-model="generalseguro.cliente.administrador" required>
								<option value="">Seleccione el tipo de administrador</option>
								<option value="u">Administrador único</option>
								<option value="c">Consejo</option>
							</select>
						</div>
						<div class="form-group col-12 col-md-4">
							<label for="cargo_pub" class="control-label"><i class="fas fa-asterisk"></i> ¿Ocupo algun cargo público?</label>
							<div class="form-check col-12">
	                            <input class="form-check-input" type="radio" name="cargo_pub" id="radioSCargo" v-model="generalseguro.cliente.cargo_pub" value="S" required="">
	                            <label class="form-check-label" for="radioSCargo">
	                             Si
	                            </label>
	                        </div>
	                        <div class="form-check col-12">
	                            <input class="form-check-input" type="radio" name="cargo_pub" id="radioNCargo" v-model="generalseguro.cliente.cargo_pub" value="N" checked>
	                            <label class="form-check-label" for="radioNCargo">
	                             No
	                            </label>
	                        </div>
						</div>
						<div class="form-group col-12 col-md-4" v-if="generalseguro.cliente.cargo_pub == 'S'">
							<label for="nombre_cargo" class="control-label"><i class="fas fa-asterisk"></i> Nombre del cargo público ejercido</label>
							<input type="text" name="nombre_cargo" class="form-control" v-model="generalseguro.cliente.nombre_cargo" required>
						</div>
						<div class="form-group col-12 col-md-4" v-if="generalseguro.cliente.cargo_pub == 'S'">
							<label for="periodo_cargo" class="control-label"><i class="fas fa-asterisk"></i> Periodo en donde ejercio el cargo</label>
							<input type="text" name="periodo_cargo" class="form-control" v-model="generalseguro.cliente.periodo_cargo" required>
						</div>
						<div class="form-group col-12 col-md-4">
							<label for="apoderado" class="control-label"><i class="fas fa-asterisk"></i> ¿Tiene apoderado legal?</label>
							<div class="form-check col">
	                            <input class="form-check-input" type="radio" name="apoderado" id="radioAS" v-model="generalseguro.cliente.apoderado" value="S" required="">
	                            <label class="form-check-label" for="radioAS">
	                            	Si
	                            </label>
	                        </div>
	                        <div class="form-check col">
	                            <input class="form-check-input" type="radio" name="apoderado" id="radioAN" v-model="generalseguro.cliente.apoderado" value="N" checked>
	                            <label class="form-check-label" for="radioAN">
	                            	No
	                            </label>
	                        </div>
						</div>
						<div class="form-group col-12 col-md-4">
							<label for="pasaporte" class="control-label">Número de pasaporte</label>
							<input type="number" name="pasaporte" class="form-control" min="0" v-model="generalseguro.cliente.pasaporte">
						</div>
					</div>
					<div class="row">
						<div class="col-12 mt-3">
							<h6>Datos del automovil:</h6>
						</div>
						<div class="form-group col-12 col-md-4">
							<label for="num_motor" class="control-label"><i class="fas fa-asterisk"></i> Número de motor</label>
							<input type="text" name="num_motor" class="form-control" v-model="generalseguro.auto.num_motor" required>
						</div>
						<div class="form-group col-12 col-md-4">
							<label for="num_placas" class="control-label"><i class="fas fa-asterisk"></i> Placas</label>
							<input type="text" name="num_placas" class="form-control" v-model="generalseguro.auto.num_placas" required>
						</div>
						<div class="form-group col-12 col-md-4">
							<label for="num_serie" class="control-label"><i class="fas fa-asterisk"></i> Número de serie</label>
							<input type="text" name="num_serie" class="form-control" v-model="generalseguro.auto.num_serie" required>
						</div>
					</div>
					<div class="row">
						<div class="col-12 mt-3">
							<h6>Seguro a contratar: {{cotizacion.paquete.nombre}}</h6>
						</div>
						<input type="hidden" name="idpaquete" v-model="generalseguro.cotizacion.idpaquete">
						<div class="form-group col-12 col-md-4">
							<label for="id_pago" class="control-label"><i class="fas fa-asterisk"></i> Forma de pago</label>
							<select name="id_pago" id="id_pago" class="form-control" v-model="generalseguro.cotizacion.id_pago" required @change="formaPago()">
								<option value="">Seleccione su forma de pago</option>
								<option v-for="pago in cotizacion.paquete.formasPagoDTO" :value="pago.idFormaPago">{{pago.nombre}}</option>
							</select>
						</div>
						<div class="col-8" v-if="JSON.stringify(detallePago)!='{}'">
							<h6>PAGO {{detallePago.nombre}}</h6>
							<p class="control-label">Total de pagos: {{detallePago.divisor}}</p>
							<p class="control-label">Pago Inicial: ${{detallePago.reciboini | int}}</p>
							<p class="control-label" v-if="detallePago.nombre === 'SEMESTRAL'">Semestre: ${{detallePago.recibosub | int}}</p>
                            <p class="control-label" v-else-if="detallePago.nombre === 'TRIMESTRAL'">Trimeste: ${{detallePago.recibosub | int}}</p>
                            <p class="control-label" v-else-if="detallePago.nombre === 'MENSUAL'">Mensualidad: ${{detallePago.recibosub | int}}</p>
                            <p class="control-label">Prima total: ${{detallePago.primaTotal | int}}</p>
						</div>
					</div>
					<div class="row">
						<div class="col d-flex justify-content-center">
							<button type="submit" class="btn btn-primary btn-lg">Enviar</button>
						</div>
					</div>
				</form>
				<form v-if="cotizacion.Nombre === 'Qualitas'" @submit="sendQua" method="POST" action="./sendQua">
					<input type="hidden" name="_token" :value="csrf" />
					<input type="hidden" name="cotizacion" :value="qualitas.cotizacion">
					<input type="hidden" name="paquete_id" :value="qualitas.vehiculo.paquete">
					<input type="hidden" name="poblacion" :value="qualitas.cliente.poblacion">
					<input type="hidden" name="camis" :value="qualitas.vehiculo.camis">
					<input type="hidden" name="municipio" :value="qualitas.cliente.municipio">
					<input type="hidden" name="estado" :value="qualitas.cliente.estado">
					<input type="hidden" name="ciudad" :value="qualitas.cliente.ciudad">
					<input type="hidden" name="cod_estado" :value="qualitas.cliente.cod_estado">
					<input type="hidden" name="cod_municipio" :value="qualitas.cliente.cod_municipio">
					<div v-if="qualitas.cliente.contratante == '1'">
						<input type="hidden" name="nombre_cont" v-model="qualitas.cliente.nombre_cont">
						<input type="hidden" name="apepat_cont" v-model="qualitas.cliente.appaterno_cont">
						<input type="hidden" name="apemat_cont" v-model="qualitas.cliente.apmaterno_cont">
						<input type="hidden" name="curp_cont" v-model="qualitas.cliente.curp_cont">
						<input type="hidden" name="rfc_cont" v-model="qualitas.cliente.rfc_cont">
						<input type="hidden" name="tipo_persona_cont" v-model="qualitas.cliente.tipo_persona_cont">
					</div>
					<div class="row">
						<div class="offset-1 col-5 offset-md-2 col-md-4 w-md-150">
							<img width="100%" height="100%" :src="img.quaImageBG">
						</div>
						<div class="offset-1 col-5 col-md-3">
							<h5 class="mt-3 ml-3">Qualitas</h5>
						</div>
					</div>
					<br>
					<hr>
					<div class="row">
						<div class="col-12 mt-3">
							<h6>Datos del asegurado:</h6>
						</div>
						<div class="form-group col-4">
							<label class="control-label"><i class="fas fa-asterisk"></i> Tipo de persona:</label>
	                        <div class="form-check col-12">
	                            <input class="form-check-input" type="radio" name="tipo_persona" id="radioF" v-model="qualitas.cliente.tipo_persona" value="1" required="" checked>
	                            <label class="form-check-label" for="radioF">
	                             Fisica
	                            </label>
	                        </div>
	                        <div class="form-check col-12">
	                            <input class="form-check-input" type="radio" name="tipo_persona" id="radioM" v-model="qualitas.cliente.tipo_persona" value="2">
	                            <label class="form-check-label" for="radioM">
	                             Moral
	                            </label>
	                        </div>
						</div>
						<div class="form-group col-4">
							<label class="control-label"><i class="fas fa-asterisk"></i> Tipo de pago:</label>
	                        <div class="form-check col-12">
	                            <input class="form-check-input" type="radio" name="tipo_pago" id="radioC" v-model="qualitas.cliente.tipo_pago" value="C" required="" checked>
	                            <label class="form-check-label" for="radioC">
	                             Contado
	                            </label>
	                        </div>
	                        <div class="form-check col-12">
	                            <input class="form-check-input" type="radio" name="tipo_pago" id="radioS" v-model="qualitas.cliente.tipo_pago" value="S">
	                            <label class="form-check-label" for="radioS">
	                             Semestral
	                            </label>
	                         </div>
	                          <div class="form-check col-12">
	                            <input class="form-check-input" type="radio" name="tipo_pago" id="radioMe" v-model="qualitas.cliente.tipo_pago" value="M">
	                            <label class="form-check-label" for="radioMe">
	                             Mensual
	                            </label>
	                           </div>
						</div>
						<div class="form-group col-4">
							<label class="control-label"><i class="fas fa-asterisk"></i> El asegurado y el contratante son la misma persona:</label>
	                        <div class="form-check col-12">
	                            <input class="form-check-input" type="radio" id="radioCS" v-model="qualitas.cliente.contratante" value="1" required="" checked>
	                            <label class="form-check-label" for="radioCS">
	                             Si
	                            </label>
	                        </div>
	                        <div class="form-check col-12">
	                            <input class="form-check-input" type="radio" id="radioCN" v-model="qualitas.cliente.contratante" value="0">
	                            <label class="form-check-label" for="radioCN">
	                             No
	                            </label>
	                        </div>
						</div>
					</div>
					<br>
					<hr>
					<div class="row" v-if="qualitas.cliente.tipo_persona == '1'">
						<div class="form-group col-12 col-md-4">
							<label class="control-label">
								<i class="fas fa-asterisk"></i> Nombre(s)
							</label>
							<input class="form-control" type="text" name="nombre" v-model="qualitas.cliente.nombre" required>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label">
								<i class="fas fa-asterisk"></i> Apellido Paterno
							</label>
							<input type="text" name="apepat" class="form-control" v-model="qualitas.cliente.appaterno" required>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label">
								Apellido Materno
							</label>
							<input type="text" name="apemat" class="form-control" v-model="qualitas.cliente.apmaterno">
						</div>
					</div>
					<div class="row" v-if="qualitas.cliente.tipo_persona == '2'">
						<div class="form-group col-12">
							<label class="control-label">
								<i class="fas fa-asterisk"></i> Razón Social
							</label>
							<input type="text" name="razsoc" class="form-control" v-model="qualitas.cliente.razsoc" required>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-12 col-md-4">
							<label for="curp" class="control-label">C.U.R.P.</label>
							<input class="form-control" type="text" name="curp" v-model="qualitas.cliente.curp">
						</div>
						<div class="form-group col-12 col-md-4">
							<label for="rfc" class="control-label"><i class="fas fa-asterisk"></i> R.F.C.</label>
							<input class="form-control" type="text" name="rfc" v-model="qualitas.cliente.rfc" required="">
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label"><i class="fas fa-asterisk"></i> Fecha de nacimiento</label>
							<input class="form-control" type="date" name="f_nac" v-model="qualitas.cliente.f_nac" required="">
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label"><i class="fas fa-asterisk"></i> Email</label>
							<input class="form-control" type="email" name="email" v-model="qualitas.cliente.email" required="">
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label"><i class="fas fa-asterisk"></i> Número telefonico</label>
							<input class="form-control" type="text" name="telefono" v-model="qualitas.cliente.telefono" required="">
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label"><i class="fas fa-asterisk"></i> Calle</label>
							<input class="form-control" type="text" name="calle" v-model="qualitas.cliente.calle" required="">
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label"><i class="fas fa-asterisk"></i> Número Exterior</label>
							<input class="form-control" type="text" name="ext" v-model="qualitas.cliente.ext" required="">
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label">Número Interior</label>
							<input class="form-control" type="text" name="int" v-model="qualitas.cliente.int">
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label"><i class="fas fa-asterisk"></i> C.P.</label>
							<input class="form-control" type="text" name="cp" v-model="qualitas.cliente.cp" required="">
						</div>
						<div class="form-group col-8">
							<label class="control-label"><i class="fas fa-asterisk"></i> Población</label>
							<select class="form-control" v-model="selectPobla" required="">
								<option value="">Seleccione su Población</option>
								<option v-for="poblacion in qualitasPobla" :value="poblacion">{{poblacion.poblacion}}, {{poblacion.municipio}}, {{poblacion.estado}}</option>
							</select>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label"><i class="fas fa-asterisk"></i> Nacionalidad</label>
							<select class="form-control" name="nacionalidad" v-model="qualitas.cliente.nacionalidad" required="">
								<option value="">Seleccione su nacionalidad</option>
								<option value="1">Mexicana</option>
								<option value="2">Extranjera</option>
							</select>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label"><i class="fas fa-asterisk"></i> Ocupación</label>
							<select class="form-control" name="ocupacion" v-model="qualitas.cliente.ocupacion" required="">
								<option value="">Seleccione su ocupación</option>
								<option v-for="ocupacion in qualitasOcupaciones" :value="ocupacion.codigo">{{ocupacion.descripcion}}</option>
							</select>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label"><i class="fas fa-asterisk"></i> Giro comercial</label>
							<select class="form-control" name="giro" v-model="qualitas.cliente.giro" required="">
								<option value="">Seleccione su giro comercial</option>
								<option v-for="giro in qualitasGiros" :value="giro.codigo">{{giro.descripcion}}</option>
							</select>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label"><i class="fas fa-asterisk"></i> Profesión</label>
							<select class="form-control" name="profesion" v-model="qualitas.cliente.profesion" required="">
								<option value="">Seleccione su profesión</option>
								<option v-for="profesion in qualitasProfesiones" :value="profesion.codigo">{{profesion.descripcion}}</option>
							</select>
						</div>
					</div>
					<div class="row" v-if="qualitas.cliente.contratante == 0">
						<div class="col-12 mt-3">
							<h6>Datos del contratante:</h6>
						</div>
						<div class="form-group col-6">
							<label class="control-label"><i class="fas fa-asterisk"></i> Tipo de persona:</label>
	                        <div class="form-check col-12">
	                            <input class="form-check-input" type="radio" name="tipo_persona_cont" id="radioF_cont" v-model="qualitas.cliente.tipo_persona_cont" value="1" required="" checked>
	                            <label class="form-check-label" for="radioF_cont">
	                             Fisica
	                            </label>
	                        </div>
	                        <div class="form-check col-12">
	                            <input class="form-check-input" type="radio" name="tipo_persona_cont" id="radioM_cont" v-model="qualitas.cliente.tipo_persona_cont" value="2">
	                            <label class="form-check-label" for="radioM_cont">
	                             Moral
	                            </label>
	                        </div>
						</div>
					</div>
					<div class="row" v-if="qualitas.cliente.contratante == 0 && qualitas.cliente.tipo_persona_cont == '1'">
						<div class="form-group col-12 col-md-4">
							<label class="control-label">
								<i class="fas fa-asterisk"></i> Nombre(s)
							</label>
							<input class="form-control" type="text" name="nombre_cont" v-model="qualitas.cliente.nombre_cont" required>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label">
								<i class="fas fa-asterisk"></i> Apellido Paterno
							</label>
							<input type="text" name="apepat_cont" class="form-control" v-model="qualitas.cliente.appaterno_cont" required>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label">
								Apellido Materno
							</label>
							<input type="text" name="apemat_cont" class="form-control" v-model="qualitas.cliente.apmaterno_cont">
						</div>
					</div>
					<div class="row" v-if="qualitas.cliente.tipo_persona_cont == '2'">
						<div class="form-group col-12">
							<label class="control-label">
								<i class="fas fa-asterisk"></i> Razón Social
							</label>
							<input type="text" name="razsoc_cont" class="form-control" v-model="qualitas.cliente.razsoc_cont" required>
						</div>
					</div>
					<div class="row" v-if="qualitas.cliente.contratante == 0">
						<div class="form-group col-12 col-md-4">
							<label for="curp_cont" class="control-label">C.U.R.P.</label>
							<input class="form-control" type="text" name="curp_cont" v-model="qualitas.cliente.curp_cont">
						</div>
						<div class="form-group col-12 col-md-4">
							<label for="rfc_cont" class="control-label"><i class="fas fa-asterisk"></i> R.F.C.</label>
							<input class="form-control" type="text" name="rfc_cont" v-model="qualitas.cliente.rfc_cont" required="">
						</div>
					</div>
					<div class="row">
						<div class="col-12 mt-3">
							<h6>Datos del automovil:</h6>
						</div>
						<div class="col-6">
							<label for="serie" class="control-label"><i class="fas fa-asterisk"></i> Número de serie</label>
							<input type="text" class="form-control" name="serie" v-model="qualitas.vehiculo.serie" required="">
						</div>
						<div class="col-6">
							<label class="control-label"><i class="fas fa-asterisk"></i> Número de motor</label>
							<input type="text" class="form-control" name="num_motor" v-model="qualitas.vehiculo.num_motor" required="">
						</div>
					</div>
					<div class="row">
						<div class="mt-3 col d-flex justify-content-center">
							<button type="submit" class="btn btn-primary btn-lg">Enviar</button>
						</div>
						<div class="m-2 ml-2 flex-shrink-1 d-flex justify-content-right">
							<i class="fa fa-asterisk" aria-hidden="true"></i> Campos Obligatorios
						</div>
					</div>
				</form>
				<form v-if="cotizacion.nombre === 'ANASeguros'" @submit="sendANA" method="POST" action="./sendANA">
					<input type="hidden" name="_token" :value="csrf" />
					<div class="row">
						<div class="offset-1 col-5 offset-md-2 col-md-4 w-md-150" >
							<img width="100%" height="100%" :src="img.anaImageForm">
						</div>
						<div class="offset-1 col-5 col-md-3">
							<h5 class="mt-3 ml-3">ANA Seguros 13/01</h5>
						</div>
					</div>
					<div class="row">
						<div class="col-12 mt-3">
							<h6>Datos del asegurado:</h6>
						</div>
						<div class="form-group col-6">
							<label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Tipo de persona:</label>
	                        <div class="form-check col-12">
	                            <input class="form-check-input" type="radio" name="tipo_persona" id="radioF" v-model="ana.cliente.tipo_persona" value="1" required="" checked>
	                            <label class="form-check-label" for="radioF">
	                             Fisica
	                            </label>
	                        </div>
	                        <div class="form-check col-12">
	                            <input class="form-check-input" type="radio" name="tipo_persona" id="radioM" v-model="ana.cliente.tipo_persona" value="2">
	                            <label class="form-check-label" for="radioM">
	                             Moral
	                            </label>
	                        </div>
						</div>
					</div>
					<div class="row" v-if="ana.cliente.tipo_persona == '1'">
						<div class="form-group col-12 col-md-4">
							<label class="control-label">
								<i class="fa fa-asterisk" aria-hidden="true"></i> Nombre(s)
							</label>
							<input class="form-control" type="text" name="nombre" v-model="ana.cliente.nombre" required>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label">
								<i class="fa fa-asterisk" aria-hidden="true"></i> Apellido Paterno
							</label>
							<input type="text" name="apepat" class="form-control" v-model="ana.cliente.apepat" required>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label">
								Apellido Materno
							</label>
							<input type="text" name="apemat" class="form-control" v-model="ana.cliente.apemat">
						</div>
					</div>
					<div class="row" v-if="ana.cliente.tipo_persona == '2'">
						<div class="form-group col-12">
							<label class="control-label">
								<i class="fa fa-asterisk" aria-hidden="true"></i> Razón Social
							</label>
							<input class="form-control" type="text" name="nombre" v-model="ana.cliente.nombre" required>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-12 col-md-4">
							<label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Correo electrónico:</label>
							<input class="form-control" type="email" name="correo" v-model="ana.cliente.correo" required>
						</div>
						<div class="form-group col-12 col-md-2">
							<label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Telefono</label>
							<input class="form-control" type="text" name="telefono" v-model="ana.cliente.telefono" required>
						</div>
						<div class="row" v-if="ana.cliente.tipo_persona == '1'">
						<div class="form-group col-12 col-md-6">
							<label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> R.F.C :</label>
							<input class="form-control" type="text" name="rfc" v-model="ana.cliente.rfc" required>
						</div>
						</div>
						<div class="row" v-if="ana.cliente.tipo_persona == '2'">
						<div class="form-group col-12 col-md-6">
							<label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> R.F.C de la empresa:</label>
							<input class="form-control" type="text" name="rfc" v-model="ana.cliente.rfc" placeholder="ABC990022X9X"  required>
						</div>
						</div>

						<div class="form-group col-12 col-md-4" v-if="ana.cliente.tipo_persona == '1'">
							<label class="control-label">C.U.R.P.:</label>
							<input class="form-control" type="text" name="curp" v-model="ana.cliente.curp">
						</div>
						<div class="form-group col-12 col-md-4" v-if="ana.cliente.tipo_persona == '1'">
							<label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Nacionalidad:</label>
							<select class="form-control" name="nacionalidad" v-model="ana.cliente.nacionalidad" required>
								<option value="">Seleccione su nacionalidad</option>
								<option v-for="nacion in nacionalidadANA" :value="nacion.id">{{nacion.descripcion}}</option>
							</select>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Estado:</label>
							<select class="form-control" name="estado" v-model="ana.cliente.estado" required>
								<option value="">Seleccione el estado en donde vive</option>
								<option v-for="estado in anaestados" :value="estado.id">{{estado.descripcion}}</option>
							</select>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Alcaldía o Municipio:</label>
							<select class="form-control" name="municipio_id" v-model="ana.cliente.municipio_id" required>
								<option value="">Seleccione la alcaldía o municipio en donde vive</option>
								<option v-for="municipio in anamunicipios" :value="municipio.id" @click="ana.cliente.municipio = municipio.descripcion">{{municipio.descripcion}}</option>
							</select>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Código Postal:</label>
							<select class="form-control" name="codigo_postal" v-model="ana.cliente.codigo_postal" required>
								<option value="">Seleccione el código postal en donde vive</option>
								<option v-for="cp in anacp" :value="cp.id">{{cp.descripcion}}</option>
							</select>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Población:</label>
							<select class="form-control" name="poblacion" v-model="ana.cliente.poblacion" required>
								<option value="">Seleccione la población en donde vive</option>
								<option v-for="poblacion in anacolonias" :value="poblacion.descripcion">{{poblacion.descripcion}}</option>
							</select>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Calle:</label>
							<input type="text" name="calle" v-model="ana.cliente.calle" class="form-control" required>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Número exterior:</label>
							<input type="text" name="num_ext" v-model="ana.cliente.num_ext" class="form-control" required>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label">Número interior:</label>
							<input type="text" name="num_int" v-model="ana.cliente.num_int" class="form-control">
						</div>
						<div class="form-group col-12 col-md-4" v-if="ana.cliente.tipo_persona == '1'">
							<label class="control-label"> Tipo de Identificación:</label>
							<select class="form-control" name="identificacion" v-model="ana.cliente.identificacion" >
								<option value="">Seleccione su identificacion</option>
								<option v-for="identificacion in anaidentificaciones" :value="identificacion.id">{{identificacion.descripcion}}</option>
							</select>
						</div>
						<div class="form-group col-12 col-md-4" v-if="ana.cliente.tipo_persona == '1'">
							<label class="control-label">Número de identificacion:</label>
							<input class="form-control" type="text" name="num_identif" v-model="ana.cliente.num_identif" >
						</div>
						<div class="form-group col-12 col-md-4" v-if="ana.cliente.tipo_persona == '1'">
							<label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Ocupación:</label>
							<select class="form-control" name="ocupacion" v-model="ana.cliente.ocupacion" required>
								<option value="">Seleccione su ocupación</option>
								<option v-for="ocupacion in anaocupaciones" :value="ocupacion.id">{{ocupacion.descripcion}}</option>
							</select>
						</div>
						<div class="form-group col-12 col-md-4" v-if="ana.cliente.tipo_persona == '1'">
							<label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Fecha de nacimiento:</label>
							<input class="form-control" type="date" name="f_nac" v-model="ana.cliente.f_nac" required>
						</div>
						<div class="form-group col-12 col-md-4" v-if="ana.cliente.tipo_persona == '2'">
							<label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Giro de la empresa:</label>
							<select class="form-control" name="giro" v-model="ana.cliente.giro" required>
								<option value="">Seleccione su ocupación</option>
								<option v-for="giro in anagiros" :value="giro.id">{{giro.descripcion}}</option>
							</select>
						</div>
						<div class="form-group col-12 col-md-4" v-if="ana.cliente.tipo_persona == '2'">
							<label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Nombre completo del administrador:</label>
							<input class="form-control" type="text" name="administrador" v-model="ana.cliente.administrador" required>
						</div>
						<div class="form-group col-12 col-md-4" v-if="ana.cliente.tipo_persona == '2'">
							<label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Nacionalidad del administrador:</label>
							<select class="form-control" name="nacionalidad_adm" v-model="ana.cliente.nacionalidad_adm" required>
								<option value=""><i class="fa fa-asterisk" aria-hidden="true"></i> Seleccione la nacionalidad de su administrador</option>
								<option v-for="nacion in nacionalidadANA" :value="nacion.id">{{nacion.descripcion}}</option>
							</select>
						</div>
						<div class="form-group col-12 col-md-4" v-if="ana.cliente.tipo_persona == '2'">
							<label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Nombre completo del representante legal:</label>
							<input class="form-control" type="text" name="representante" v-model="ana.cliente.representante" required>
						</div>
						<div class="form-group col-12 col-md-4" v-if="ana.cliente.tipo_persona == '2'">
							<label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Nacionalidad del representante legal:</label>
							<select class="form-control" name="nacionalidad_representante" v-model="ana.cliente.nacionalidad_representante" required>
								<option value="">Seleccione la nacionalidad de su representante legal</option>
								<option v-for="nacion in nacionalidadANA" :value="nacion.id">{{nacion.descripcion}}</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-12 mt-3">
							<h6>Datos del vehiculo:</h6>
						</div>
						<div class="form-group col-6">
							<label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Color</label>
							<select class="form-control" name="color" v-model="ana.vehiculo.color" required>
								<option value="">Seleccione el color de su vehiculo</option>
								<option v-for="color in anacolores" :value="color.id">{{color.descripcion}}</option>
							</select>
						</div>
						<div class="form-group col-6">
							<label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Motor</label>
							<input class="form-control" type="text" name="motor" v-model="ana.vehiculo.motor" required>
						</div>
						<div class="form-group col-6">
							<label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Número de serie</label>
							<input class="form-control" type="text" name="serie" pattern="[A-Z0-9]{17,17}" title="El número de serie debe ser de 17 caracteres" v-model="ana.vehiculo.serie" required>
						</div>
						<div class="form-group col-6">
							<label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Placas</label>
							<input class="form-control" type="text" name="placas" v-model="ana.vehiculo.placas" required>
						</div>
						<input type="hidden" name="amis" :value="cotizacion.cotizacion.info[0].CONTADO.vehiculo.amis">
						<input type="hidden" name="modelo" :value="cotizacion.cotizacion.info[0].CONTADO.vehiculo.modelo">
						<input type="hidden" name="id_cotizacion" :value="cotizacion.id_cotizacion">
					</div>
					<div class="row">
						<div class="col-12 mt-3">
							<h6>Seguro a contratar: ANA Seguros {{cotizacion.cotizacion.tipo}}</h6>
						</div>
						<input type="hidden" name="plan" :value="cotizacion.cotizacion.tipo">
						<div class="form-group col-12 col-md-4">
							<label for="pago" class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Forma de pago</label>
							<select name="pago" id="pago" class="form-control" v-model="ana.cotizacion.pago" required @change="formaPagoAna()">
								<option value="">Seleccione su forma de pago</option>
								<option v-for="pago in anapagos" :value="pago.descripcion">{{pago.descripcion}}</option>
							</select>
						</div>
						<div class="col-8" v-if="JSON.stringify(anapagosinfo)!='{}'">
							<h6>PAGO {{anapagosinfo.tipo}}</h6>
						 	<p class="control-label" v-if="anapagosinfo.tipo == 'CONTADO'"><strong>CONTADO:</strong></p>
                            <p class="control-label" v-if="anapagosinfo.tipo == 'CONTADO'">${{anapagosinfo.info.prima.primatotal | int}}</p>
                            <p class="control-label" v-if="anapagosinfo.tipo == 'CONTADO'">PAGO INICIAL:</p>
                            <p class="control-label" v-if="anapagosinfo.tipo == 'CONTADO'">${{anapagosinfo.info.prima.primatotal | int}}</p>
                            <p class="control-label" v-if="anapagosinfo.tipo == 'SEMESTRAL'"><strong>SEMESTRAL:</strong></p>
                            <p class="control-label" v-if="anapagosinfo.tipo == 'SEMESTRAL'">${{anapagosinfo.info.prima.primatotal | int}}</p>
                            <p class="control-label" v-if="anapagosinfo.tipo == 'SEMESTRAL'">PAGO INICIAL:</p>
                            <p class="control-label" v-if="anapagosinfo.tipo == 'SEMESTRAL'">${{anapagosinfo.info.recibos[0].primatotal | int}}</p>
                            <p class="control-label" v-if="anapagosinfo.tipo == 'SEMESTRAL'">SEMESTRE:</p>
                            <p class="control-label" v-if="anapagosinfo.tipo == 'SEMESTRAL'">${{anapagosinfo.info.recibos[1].primatotal | int}}</p>
                            <p class="control-label" v-if="anapagosinfo.tipo == 'TRIMESTRAL'"><strong>TRIMESTRAL:</strong></p>
                            <p class="control-label" v-if="anapagosinfo.tipo == 'TRIMESTRAL'">${{anapagosinfo.info.prima.primatotal | int}}</p>
                            <p class="control-label" v-if="anapagosinfo.tipo == 'TRIMESTRAL'">PAGO INICIAL:</p>
                            <p class="control-label" v-if="anapagosinfo.tipo == 'TRIMESTRAL'">${{anapagosinfo.info.recibos[0].primatotal | int}}</p>
                            <p class="control-label" v-if="anapagosinfo.tipo == 'TRIMESTRAL'">TRIMESTRES:</p>
                            <p class="control-label" v-if="anapagosinfo.tipo == 'TRIMESTRAL'">${{anapagosinfo.info.recibos[1].primatotal | int}}</p>
                            <p class="control-label">Prima total: ${{anapagosinfo.info.prima.primatotal | int}}</p>
						</div>
					</div>
					<div class="row">
						<div class="col-12 mt-3">
							<h6>Pago:</h6>
						</div>
						<div class="form-group col-6">
							<label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Tipo de pago:</label>
	                        <div class="form-check col-12">
	                            <input class="form-check-input" type="radio" name="tipo_pago" id="radioReferenciado" v-model="ana.cliente.tipo_pago" value="Referenciado" required="" checked="">
	                            <label class="form-check-label" for="radioReferenciado">
	                             Referenciado
	                            </label>
	                        </div>
	                        <div class="form-check col-12">
	                            <input class="form-check-input" type="radio" name="tipo_pago" id="radioTarjeta" v-model="ana.cliente.tipo_pago" value="Tarjeta">
	                            <label class="form-check-label" for="radioTarjeta">
	                             Tarjeta Crédito/Débito
	                            </label>
	                        </div>
						</div>
					</div>
					<div class="row" v-if="ana.cliente.tipo_pago == 'Tarjeta'">
						<div class="col-12 mt-3 mb-3">
							<h6>Datos de la tarjeta:</h6>
							<span class="badge badge-secondary text-wrap">AutoSeguroDirecto acepta la mayoría de tarjetas de crédito y débito.</span>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label">
								<i class="fa fa-asterisk" aria-hidden="true"></i> Nombre de la tarjeta
							</label>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text" id="nombre-addon">
										<i class="fas fa-user-circle"></i>
									</span>
								</div>
								<input class="form-control" type="text" name="tarjeta_nombre" v-model="ana.cliente.tarjeta.nombre" required>
							</div>
						</div>
						<div class="form-group col-12 col-md-3">
							<label class="control-label">
								<i class="fa fa-asterisk" aria-hidden="true"></i> Número de tarjeta
							</label>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text" id="numerotarjeta-addon">
										<i class="fas fa-credit-card"></i>
									</span>
								</div>
								<input class="form-control" type="text" name="numero" pattern="[0-9]{13,16}" v-model="ana.cliente.tarjeta.numero" required>
							</div>
						</div>
						<div class="form-group col-6 col-md-3">
							<label class="control-label">
								<i class="fa fa-asterisk" aria-hidden="true"></i> Fecha de expiración:
							</label>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text" id="numerotarjeta-addon">
										<i class="fas fa-credit-card"></i>
									</span>
								</div>
								<select name="expiracionMM" id="expiraciónMM" class="form-control" v-model="ana.cliente.tarjeta.vencimientoMM" required="">
									<option value=''>Mes</option>
									<option value='01'>01/Enero</option>
									<option value='02'>02/February</option>
									<option value='03'>03/Marzo</option>
									<option value='04'>04/Abril</option>
									<option value='05'>05/Mayo</option>
									<option value='06'>06/Junio</option>
									<option value='07'>07/Julio</option>
									<option value='08'>08/Agosto</option>
									<option value='09'>09/Septiembre</option>
									<option value='10'>10/Octubre</option>
									<option value='11'>11/Noviembre</option>
									<option value='12'>12/Diciembre</option>
								</select>
								<select name="expiracionYY" id="expiraciónYY" class="form-control" v-model="ana.cliente.tarjeta.vencimientoYY" required="">
									<option value=''>Año</option>
									<option v-for="anio in anios" :value="anio">{{anio}}</option>
								</select>
							</div>
						</div>
						<div class="form-group col-6 col-md-2">
							<label class="control-label">
								<i class="fa fa-asterisk" aria-hidden="true"></i> Código de seguridad
							</label>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text" id="cvv-addon">
										<i class="fas fa-credit-card"></i>
									</span>
								</div>
								<input class="form-control" type="text" name="codigo_seguridad" pattern="[0-9]{3,4}" v-model="ana.cliente.tarjeta.codigo_seguridad" required>
							</div>
						</div>
						<div class="col-12 mt-3 mb-3">
							<h6>Datos de domiciliación:</h6>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label">
								Banco
							</label>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text" id="banco-addon">
										<i class="fas fa-university"></i>
									</span>
								</div>
								<select name="banco" id="banco" class="form-control" v-model="ana.cliente.tarjeta.banco">
									<option value="">Banco</option>
									<option v-for="banco in anabancos" :value="banco.id">{{banco.descripcion}}</option>
								</select>
							</div>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label">
								Dirección
							</label>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text" id="direccion_tarjeta-addon">
										<i class="fas fa-home"></i>
									</span>
								</div>
								<textarea name="direccion_tarjeta" id="direccion_tarjeta" class="form-control" v-model="ana.cliente.tarjeta.direccion"></textarea>
							</div>
						</div>
						<div class="form-group col-12 col-md-4">
							<label class="control-label">
								RFC
							</label>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text" id="rfc_tarjeta-addon">
										<i class="fas fa-address-card"></i>
									</span>
								</div>
								<input type="text" name="rfc_tarjeta" id="rfc_tarjeta" class="form-control" v-model="ana.cliente.tarjeta.rfc">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col d-flex justify-content-center">
							<button type="submit" class="btn btn-primary btn-lg">Enviar</button>
						</div>
						<div class="m-2 ml-2 flex-shrink-1 d-flex justify-content-right">
							<i class="fa fa-asterisk" aria-hidden="true"></i> Campos Obligatorios
						</div>
					</div>
				</form>
				<template v-if="cotizacion.nombre === 'GNP'">
					<formulariognp :cotizacion="cotizacion" :img="img" :cliente="cliente"></formulariognp>
				</template>
			</div>
		</div>
		<!-- <pre>
			${{data}}
		</pre> -->
	</div>
</template>
<script>
	export default{
		props:[
			'cliente',
			'alert',
			'cotizacion',
			'img'
		],
		data(){
			return{
				
				csrf: null,
				ana:{
					cliente:{
						tipo_persona:"1",
						nombre:"",
						apepat:"",
						apemat:"",
						rfc:"",
						curp:"",
						calle:"",
						num_int:"",
						num_ext:"",
						poblacion:"",
						municipio_id:"",
						estado:"",
						codigo_postal:"",
						pais:"MEXICO",
						telefono:"",
						correo:"",
						nacionalidad:"",
						identificacion:"",
						num_identif:"",
						ocupacion:"",
						f_nac:"",
						giro:"",
						administrador:"",
						nacionalidad_adm:"",
						representante:"",
						nacionalidad_representante:"",
						tipo_pago:'Referenciado',
						id_cotizacion: this.cotizacion.id_cotizacion,
						tarjeta:{
							nombre:'',
							numero:'',
							vencimientoMM:'',
							vencimientoYY:'',
							codigo_seguridad:'',
							banco:'',
							direccion:'',
							rfc:''
						}
					},
					vehiculo:{
						amis:"",
						placas:"",
						serie:"",
						motor:"",
						modelo:"",
						color:"",
					},
					cotizacion:{
						plan:"",
						pago:"",
					}
				},
				qualitas:{
					cotizacion:"",
					cliente:{
						cliente_id:"",
						contratante:"1",
						razsoc:"",
						nombre:"",
						appaterno:"",
						apmaterno:"",
						calle:"",
						ext:"",
						int:"",
						poblacion:"",
						municipio:"",
						estado:"",
						ciudad:"",
						cod_estado:"",
						cod_municipio:"",
						cp:"",
						pais:"",
						tipo_persona:"1",
						f_nac:"",
						nacionalidad:"",
						ocupacion:"",
						giro:"",
						profesion:"",
						email:"",
						curp:"",
						rfc:"",
						razsoc_cont:"",
						nombre_cont:"",
						appaterno_cont:"",
						apmaterno_cont:"",
						tipo_persona_cont:"1",
						curp_cont:"",
						rfc_cont:"",
						telefono:""
					},
					vehiculo:{
						num_motor:"",
						serie:"",
						paquete:"",
						camis:""
					}
				},
				generalseguro:{
					cliente:{
						tipo_persona:"F",
						nombre:"",
						apepat:"",
						apemat:"",
						razsoc:"",
						rfc:"",
						elector:"",
						curp:"",
						sexo:"",
						edoCivil:"",
						calle:"",
						num:"",
						cp:"",
						colonia:"",
						municip:"",
						poblaci:"",
						fnac:"",
						nacionalidad:"",
						ocupacion:"",
						giro:"",
						telefono1:"",
						telefono2:"",
						telefono3:"",
						email:"",
						web:"",
						contacto:"",
						ingresos:"",
						administrador:"",
						cargo_pub:"N",
						nombre_cargo:"",
						periodo_cargo:"",
						apoderado:"N",
						domicilio_original:"",
						pasaporte:"",
					},
					auto:{
						num_motor:"",
						num_placas:"",
						num_serie:"",
					},
					cotizacion:{
						id_cotizacion:null,
						id_pago:"",
						idpaquete:""
					},
				},
				selectPobla:{},
				qualitasPobla:[],
				qualitasGiros:[],
				qualitasProfesiones:[],
				qualitasOcupaciones:[],
				estadosCiviles:[],
				generalColonias:[],
				anaestados:[],
				anamunicipios:[],
				anacp:[],
				anacolonias:[],
				anaNacionalidades:[],
				anaidentificaciones:[],
				anagiros:[],
				anacolores:[],
				anapagos:[],
				anapagosinfo:{},
				anabancos:[],
				ocupaciones:[],
				giros:[],
				tipocontactos:[],
				detallePago:{},

			}
		},
		watch:{
			"cotizacion.id":function (new_value,old_value) {
				// body...
				console.log('NUEVO VALOR',new_value);
				this.generalseguro.cotizacion.id_cotizacion = new_value;
				this.generalseguro.cliente.nombre = this.cliente.nombre;
				this.generalseguro.cliente.apepat = this.cliente.apmaterno;
				this.generalseguro.cliente.apemat = this.cliente.appaterno;
				this.generalseguro.cliente.cp = this.cliente.cp; 
				this.generalseguro.cliente.fnac = this.cliente.f_nac;
				this.generalseguro.cliente.email = this.cliente.email;
				this.generalseguro.cliente.telefono1 = this.cliente.telefono;
				this.searchColonia(this.generalseguro.cliente.cp);

			},
			'cotizacion.paquete.id':function(new_value,old_value){
				console.log(new_value);
				this.generalseguro.cotizacion.idpaquete = new_value;
			},
			'cotizacion.paquetequa':function(new_value,old_value){
				console.log(new_value);
				this.qualitas.vehiculo.paquete = new_value;
			},
			'cotizacion.camis':function(new_value,old_value){
				console.log(new_value);
				this.qualitas.vehiculo.camis = new_value;
			},
			'cliente.cotizacion':function(new_value,old_value){
				console.log(new_value);
				this.qualitas.cotizacion = new_value;
			},
			'qualitas.cliente.cp':_.debounce(function(new_value){
				this.selectPobla = "";
				this.searchCP(new_value);
			},500),
			'selectPobla':function (new_value,old_value) {
				console.log(new_value);
				if (new_value !== "") {
					this.qualitas.cliente.poblacion = new_value.poblacion;
					this.qualitas.cliente.municipio = new_value.municipio;
					this.qualitas.cliente.ciudad = new_value.ciudad;
					this.qualitas.cliente.estado= new_value.estado;
					this.qualitas.cliente.cod_estado = new_value.cestado;
					this.qualitas.cliente.cod_municipio = new_value.codigo_municipio;
				}
				else{
					this.qualitas.cliente.poblacion = "";
					this.qualitas.cliente.municipio = "";
					this.qualitas.cliente.ciudad = "";
					this.qualitas.cliente.estado="";
					this.qualitas.cliente.cod_estado = "";
					this.qualitas.cliente.cod_municipio = "";
				}
			},
			'qualitas.cliente.nombre': function(new_value,old_value){
				if (this.qualitas.cliente.contratante == 1) {
					this.qualitas.cliente.nombre_cont = new_value;
				} else {}
			},
			'qualitas.cliente.appaterno': function(new_value,old_value){
				if (this.qualitas.cliente.contratante == 1) {
					this.qualitas.cliente.appaterno_cont = new_value;
				} else {}
			},
			'qualitas.cliente.apmaterno': function(new_value,old_value){
				if (this.qualitas.cliente.contratante == 1) {
					this.qualitas.cliente.apmaterno_cont = new_value;
				} else {}
			},
			'qualitas.cliente.curp': function(new_value,old_value){
				if (this.qualitas.cliente.contratante == 1) {
					this.qualitas.cliente.curp_cont = new_value;
				} else {}
			},
			'qualitas.cliente.rfc': function(new_value,old_value){
				if (this.qualitas.cliente.contratante == 1) {
					this.qualitas.cliente.rfc_cont = new_value;
				} else {}
			},
			'qualitas.cliente.tipo_persona': function(new_value,old_value){
				if (this.qualitas.cliente.contratante == 1) {
					this.qualitas.cliente.tipo_persona_cont = new_value;
				} else {}
			},
			'ana.cliente.tipo_persona':function (new_value,old_value) {
						
				if (new_value=="2") {
					this.ana.cliente.nombre="";
					this.getGirosANA();
				}
			},
			'ana.cliente.estado':function(new_value,old_value){
				this.getMunicipios(new_value);
				this.ana.cliente.municipio_id = "";
			},
			'ana.cliente.municipio_id':function(new_value,old_value){
				this.getCP(this.ana.cliente.estado,new_value);
				this.ana.cliente.codigo_postal="";
			},
			'ana.cliente.codigo_postal':function(new_value,old_value){
				this.getColonias(this.ana.cliente.estado,this.ana.cliente.municipio_id,new_value);
				this.ana.cliente.poblacion="";
			},
			'generalseguro.cliente.cp':function(new_value,old_value){
				this.searchColonia(this.generalseguro.cliente.cp);
				// this.ana.cliente.poblacion="";
			}

		},
		created(){
		},
		methods:{
			'getGirosANA':function(){
				let url="./api/giroEmpresaANA";
				axios.get(url).then(res=>{
					console.log(res.data);
					this.anagiros = res.data.giros;
				}).catch(err=>{
					console.log(err);
				})
			},
			'getNacionalidadANA':function(){
				let url='./api/nacionalidadANA';
				axios.get(url).then(res=>{
					console.log(res.data);
					this.nacionalidadANA=res.data.nacionalidades;
				}).catch(err=>{
					console.log(err)
				})
			},
			'getIdentificacionesANA':function(){
				let url="./api/identificacionesANA";
				axios.get(url).then(res=>{
					// console.log(res.data);
					this.anaidentificaciones=res.data.identificaciones;
				}).catch(err=>{
					console.log(err)
				});
			},
			'getOcupacionANA':function(){
				let url="./api/ocupacionANA";
				axios.get(url).then(res=>{
					// console.log(res.data);
					this.anaocupaciones=res.data.ocupaciones;
				}).catch(err=>{
					console.log(err);
				});
			},
			'getColorANA':function(){
				let url="./api/colorANA";
				axios.get(url).then(res=>{
					this.anacolores=res.data.colores;
				}).catch(err=>{
					console.log(err)
				})
			},
			'getPagosANA':function () {
				let url="./api/formaPagosANA";
				axios.get(url).then(res=>{
					this.anapagos=res.data.formapagos;
				}).catch(err=>{
					console.log(err);
				})
			},
			'formaPagoAna':function() {
				var pago = this.ana.cotizacion.pago
				for (var i = 0; i < this.cotizacion.cotizacion.info.length; i++) {
					console.log(this.cotizacion.cotizacion.info[i]);
					if (this.cotizacion.cotizacion.info[i][pago]) {
						this.anapagosinfo = {"tipo":pago,'info':this.cotizacion.cotizacion.info[i][pago]};
					}
				}
				// this.anapagosinfo= 
			},
			'getBancosANA':function () {
				let url="./api/bancosANA";
				axios.get(url).then(res=>{
					this.anabancos = res.data.bancos;
				}).catch(err=>{
					console.log(err);
				})
			},
			'searchCP':function (cp) {
				// body...
				let url= `./api/cp/${cp}`;
				axios.get(url).then(res=>{
					console.log(res.data);
					if(res.data.response){
						this.qualitasPobla = res.data.response;
					}
				}).catch(err=>{
					console.log(err);
				})
			},
			'searchColonia':function (cp) {
				// body...
				let url= `./api/getColonias/${cp}`;
				axios.get(url).then(res=>{
					console.log("COLONIAS GS",res.data);
					if(res.data.response){
						this.generalColonias =res.data.response;
						// this.generalseguro.cliente.fnac = res.data.response;
					}
				}).catch(err=>{
					console.log(err);
				})
			},




			'sendGS':function(){
				console.log('enviado');
			},
			'sendQua': function(){
				// TODO
			},
			'sendANA':function(){
				// TODO
			},
			'formaPago':function(){
				if (this.generalseguro.cotizacion.id_pago == "" ) {
					this.detallePago = {};
					console.log(this.detallePago);
				} else {
					var result = this.cotizacion.paquete.formasPagoDTO.find(obj=>{
						// console.log(obj);
						if (obj.idFormaPago === this.generalseguro.cotizacion.id_pago) {
							return obj;
						}
						// var obj.id === this.generalseguro.cotizacion.id_pago;
					});
					this.detallePago = result;
					
				}
				console.log(this.detallePago);
			},
			'getEstados':function(){
				let url="./api/estadosANA";
				axios.get(url).then(res=>{
					// console.log(res.data);
					if (res.data.estados) {
						this.anaestados=res.data.estados
					}
				}).catch(err=>{
					console.log(err);
				});
			},
			'getMunicipios':function(estado_id){
				let url = `./api/municipiosANA/${estado_id}`;
				axios.get(url).then(res=>{
					console.log(res.data);
					console.log('-- CLIENTE -- ', this.cotizacion);
					if (res.data.municipios) {
						this.anamunicipios=res.data.municipios;
					}
					// 
				}).catch(err=>{
					console.log(err);
				});

			},
			'getCP':function(estado_id,municipio_id){
				let url=`./api/cpANA/${estado_id}/${municipio_id}`;
				axios.get(url).then(res=>{
					// console.log(res.data);
					if(res.data['Codigo Postal']){
						this.anacp=res.data['Codigo Postal'];
					}
				}).catch(err=>{
					console.log(err)
				});
			},
			'getColonias':function(estado_id,municipio_id,cp_id){
				let url=`./api/coloniaANA/${estado_id}/${municipio_id}/${cp_id}`;
				axios.get(url).then(res=>{
					console.log(res.data);
					if (res.data.colonias) {
						this.anacolonias=res.data.colonias;
					}
				}).catch(err=>{
					console.log(err);
				})
			},
			'getEdoCivil':function(){
				let url="./api/getEstadoCivil";
				axios.get(url).then(res=>{
					console.log(res);
					if(res.data.estadosCivil){
						this.estadosCiviles = res.data.estadosCivil.sort();
					}
				}).catch(err=>{
					console.log(err);
				})
			},
			'getOcupaciones':function(){
				let url="./api/getTitulos";
				axios.get(url).then(res=>{
					console.log(res);
					if(res.data.titulos){
						this.ocupaciones = res.data.titulos.sort();
					}
				}).catch(err=>{
					console.log(err);
				})
			},
			'getGiros':function(){
				let url = "./api/getGiros";
				axios.get(url).then(res=>{
					console.log(res);
					if(res.data.giros){
						this.giros = res.data.giros.sort();
					}
				}).catch(err=>{
					console.log(err);
				});
			},
			'getContactos':function(){
				let url="./api/getContactos";
				axios.get(url).then(res=>{
					console.log(res.data);
					if(res.data.tiposContacto){
						this.tipocontactos = res.data.tiposContacto.sort();
					}
				}).catch(err=>{
					console.log(err);
				})
			}
		},
		filters:{
    		'int': function (value) {
			    if (!value) return ''
			    let val = (value/1).toFixed(2).replace(',', '.')
        		return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
			  }
    	},
		mounted(){
			console.log(this.cotizacion);
			this.getEdoCivil();
			this.getOcupaciones();
			this.getGiros();
			this.getContactos();
			this.getEstados();
			this.getNacionalidadANA();
			this.getIdentificacionesANA();
			this.getOcupacionANA();
			this.getColorANA();
			this.getPagosANA();
			this.getBancosANA();
			// this.anaImage="./img/ana1.png";
			// this.gsImage = "./img/GENERAL-DE-SEGUROS-LOGO.png";
			// this.quaImage = "./img/qua.png";
			this.csrf = document.head.querySelector('meta[name="csrf-token"]').content;
			this.qualitasGiros=[
				{ codigo: '0010', descripcion: 'Industrial'},
				{ codigo: '0020', descripcion: 'Comercial'},
				{ codigo: '0030', descripcion: 'Servicios'},
				{ codigo: '0031', descripcion: 'Servicio Públicos'},
				{ codigo: '0032', descripcion: 'Servicio Privados'},
				{ codigo: '0033', descripcion: 'Transporte'},
				{ codigo: '0034', descripcion: 'Turismo'},
				{ codigo: '0035', descripcion: 'Instituciones Financieras'},
				{ codigo: '0036', descripcion: 'Educación'},
				{ codigo: '0037', descripcion: 'Salubridad'},
				{ codigo: '0038', descripcion: 'Fianzas y Seguros'}
			];
			this.qualitasOcupaciones=[
				{codigo:'0001',descripcion:'Comerciante'},
				{codigo:'0002',descripcion:'Empleado'},
				{codigo:'0003',descripcion:'Empresario'},
				{codigo:'0004',descripcion:'Permisionario'},
				{codigo:'0005',descripcion:'Otro'},
				{codigo:'0006',descripcion:'Transportista'},
				{codigo:'0007',descripcion:'Hogar'}
			];
			this.qualitasProfesiones=[
				{codigo:'0001',descripcion:'Administrador'},
				{codigo:'0002',descripcion:'Abogado'},
				{codigo:'0003',descripcion:'Arquitecto'},
				{codigo:'0004',descripcion:'Actuario'},
				{codigo:'0005',descripcion:'Contador'},
				{codigo:'0006',descripcion:'Docente'},
				{codigo:'0007',descripcion:'Economista'},
				{codigo:'0008',descripcion:'Ingeniero'},
				{codigo:'0009',descripcion:'Medico'},
				{codigo:'0010',descripcion:'Psicólogo'},
				{codigo:'0011',descripcion:'Odontólogo'},
				{codigo:'0012',descripcion:'Químico'},
				{codigo:'0013',descripcion:'Biólogo'},
				{codigo:'0014',descripcion:'Sociólogo'},
				{codigo:'0015',descripcion:'Periodista'},
				{codigo:'0016',descripcion:'Otro'},
				{codigo:'0017',descripcion:'Ninguna'}
			];
		},
		computed:{
			'anios':function(){
				const year = new Date().getFullYear();
				return Array.from({length: year+10 - year}, (value, index) => year+1 + index);

			}
		}
	}
</script>