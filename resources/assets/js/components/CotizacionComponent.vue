<template>
    <div>
  		<!-- Crear cotización -->
    	<div class="tab-pane">
    		<div class="row m-0 p-1 no-gutters">
    			<div class="col-sm-4 d-none d-sm-block p-2">
    				<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
		              <a class="nav-link active" id="v-pills-Uso-tab"  data-toggle="pill" href="#v-pills-Uso" role="tab" aria-controls="v-pills-Uso" aria-selected="true">Uso: {{cliente.uso_auto}}</a>
		              <a class="nav-link disabled" id="v-pills-Modelo-tab" data-toggle="pill"  href="#v-pills-Modelo" role="tab" aria-controls="v-pills-Modelo" aria-selected="false">Modelo: {{cliente.modelos}}</a>
		              <a class="nav-link disabled" id="v-pills-Marca-tab" data-toggle="pill" href="#v-pills-Marca" role="tab" aria-controls="v-pills-Marca" aria-selected="false">Marca: {{cliente.marca_auto.descripcion}}</a>
		              <a class="nav-link disabled" id="v-pills-Submarca-tab" data-toggle="pill" href="#v-pills-Submarca" role="tab" aria-controls="v-pills-Submarca" aria-selected="false">Tipo: {{cliente.submarca_auto.descripcion}}</a>
		              <a class="nav-link disabled" id="v-pills-CP-tab" data-toggle="pill"  href="#v-pills-CP" role="tab" aria-controls="v-pills-CP" aria-selected="false">CP: {{cliente.cp}}</a>
		              <a class="nav-link disabled" id="v-pills-Nombre-tab" data-toggle="pill" href="#v-pills-Nombre"  role="tab" aria-controls="v-pills-Nombre" aria-selected="false">Nombre: {{cliente.nombre}} {{cliente.appaterno}} {{cliente.apmaterno}}</a>
		              <a class="nav-link disabled" id="v-pills-Celular-tab" data-toggle="pill"  href="#v-pills-Celular" role="tab" aria-controls="v-pills-Celular" aria-selected="false">Celular: {{cliente.telefono}}</a>
		              <a class="nav-link disabled" id="v-pills-Correo-tab" data-toggle="pill" href="#v-pills-Correo"  role="tab" aria-controls="v-pills-Correo" aria-selected="false">Correo: {{cliente.email}}</a>
		              <a class="nav-link disabled" id="v-pills-Sexo-tab" data-toggle="pill"  href="#v-pills-Sexo" role="tab" aria-controls="v-pills-Sexo" aria-selected="false">Genero: {{cliente.sexo}}</a>
		              <a class="nav-link disabled" id="v-pills-Nacimiento-tab" data-toggle="pill"  href="#v-pills-Nacimiento" role="tab" aria-controls="v-pills-Nacimiento" aria-selected="false">Nacimiento: {{cliente.f_nac}}</a>
		              <a class="nav-link" id="v-pills-Aseguradoras-tab" data-toggle="pill"  href="#v-pills-Aseguradoras" role="tab" aria-controls="v-pills-Aseguradoras" aria-selected="false">Aseguradoras:</a>
		            </div>
    			</div>

    			<div class="col col-sm-8 p-2" v-show="!searchOption">
    				<div class="tab-content" id="v-pills-tabContent">
    					 
    					
    					<div class="tab-pane fade show active" id="v-pills-Uso" role="tabpanel" aria-albelledby="v-pills-Uso-tab">
    						<div class="row p-0 m-0">
    							<div class="col-12 p-0 m-0">
    								<div class="input-group">
    									<input class="form-control" type="text" v-model="cliente.cotizacion" placeholder="¿Ya tienes código de cotización?" aria-label="No. generado por el sistema">
    									<div class="input-group-append" v-if="cliente.cotizacion">
    										<button class="btn input-group-text" @click="searchCliente(cliente.cotizacion)">Ver</button>
    									</div>
    								</div>
    								<!-- <div class="form-group">
    									<label>Ejecutivo</label>
    									<input class="form-control" type="text" v-model="cliente.ejecutivo" placeholder="Ejecutivo" aria-label="No. Ejecutivo">
    								</div> -->
    								<!-- <div class="form-group">
    									<label>Ejecutivo</label>
    									<input class="form-control" type="text" v-model="cliente.ejecutivo" placeholder="Ejecutivo" aria-label="No. Ejecutivo">
    								</div> -->
    								<div class="form-group">
    									<label>Código de Descuento</label>
    									<input class="form-control" type="text" v-model="cliente.codigo_descuento" placeholder="Código de descuento" aria-label="Código de descuento">
    								</div>
    								<div class="col-12 p-0 my-4">
    									<div class="card">
    										<div class="card-header">
    											Uso:
    										</div>
    										<div class="card-body">
    											<select v-model="cliente.uso_auto" size="3" class="list-group list-group-flush col mr-0 ml-0"  style="overflow-y: hidden;">
    												<option value="" class="list-group-item text-center text-dark seleccionador" style="white-space: normal;">Seleccione el uso que se da al vehiculo</option>
    												<option value="Servicio Particular" class="list-group-item text-center text-dark seleccionador">Servicio Particular</option>
                                    				<option value="Servicio Público" class="list-group-item text-center text-dark seleccionador">Servicio Público</option>
    											</select>
    										</div>
    									</div>
    								</div>
    							</div>
    						</div>
    					</div>
    					<!--MODELO-->
		                <div class="tab-pane fade" v-show="modelo" id="v-pills-Modelo" role="tabpanel" aria-albelledby="v-pills-Modelo-tab">
		               <!--  <select class="list-group list-group-flush col" v-model="cliente.modelo_auto" size="2">
		                            	<option value=""  class="list-group-item text-center text-dark seleccionador">Seleccione su modelo</option>
		                            	<option v-for="anio in modelos" :value="anio" class="list-group-item text-center text-dark seleccionador">{{anio}}</option>
		                            </select> -->
		                            <div class="form-group">
    									<label>Modelo</label>
    									<input class="form-control" type="number" v-model="cliente.modelos" placeholder="2001,2002,2003,2004" aria-label="Modelo" id="valorModelo">
    								</div>
    								<div class="col mt-3 d-flex justify-content-end">
                        					<button type="button" class="btn btn-primary seleccionador" @click="nextPill('Marca')">Siguiente</button>
											
										</div>
		                            <br>
		                            <hr>
		                
						 <div class="row">
										<div class="col-12 mt-3 d-block d-sm-none">
											<button class="btn btn-primary" type="button" onclick="$('#v-pills-Marca-tab').click();">Atras</button>
										</div>
							</div>
						</div>
						 <!--MARCA-->
		                <div class="tab-pane fade" v-show="marca" id="v-pills-Marca" role="tabpanel" aria-albelledby="v-pills-Marca-tab">
		                    <div class="card p-0">
		                        <div class="card-header">
		                            Marca
		                        </div>
		                        <div class="card-body">
		                        	<!-- <div v-show="loader_marca" class="loader"></div>
		                        	<div v-show="!loader_marca && this.marcas.length == 0">
		                        		<label>No se encontraron resultados</label>
		                        	</div>
		                            <select v-show="!loader_marca && this.marcas.length != 0" v-model="cliente.marca_auto" size="7" class="list-group list-group-flush col">
		                            	<option value="" class="list-group-item text-center text-dark seleccionador">Seleccione su marca</option>
										<option v-for="marca in marcas" :value="marca" class="list-group-item text-center text-dark seleccionador" v-if="marca.descripcion !== 'BUIK' ">{{ marca.descripcion }}</option>
									</select>
									<div class="row">
										<div class="col-12 mt-3 d-block d-sm-none">
											<button class="btn btn-primary" type="button" onclick="$('#v-pills-Uso-tab').click();">Atras</button>
										</div>
									</div> -->
									<div class="row">

		  				<div class="col">
		  					<!-- <label for="gnp_lista" class="col">GNP</label> -->
		  					 <label class="form-check-label" for=""><img :src="img.gnpImage" width="120" height="50"></label>
		  				</div>
		  			<!-- 	<div class="col-3">
		  					<select  v-model="modeloGNP" class="form-control" id="gnp_lista">
		                            	<option value="" class="form-control form-control-sm" style="white-space: normal;">Seleccione su año</option>
		                            	<option value="2011">2011</option>
										<option value="2012">2012</option>
										<option value="2013">2013</option>
										<option value="2020">2014</option>
										<option value="2015">2015</option>
										<option value="2016">2016</option>
										<option value="2017">2017</option>
										<option value="2018">2018</option>
										<option value="2019">2019</option>
										<option value="2020">2020</option>
										<option value="2021">2021</option>
										<option value="2022">2022</option>
									</select>	

		  				</div> -->
		  				<div class="col-3">
		  					<select v-model="marcaGNP" class="form-control">
    								<option value="" class="form-control form-control-sm" style="white-space: normal;">Seleccione el modelo</option>

    								<option :value="marcas" v-for="marcas in marcasGNP">{{marcas}}</option>
    								</select>
    								
		  				</div>
		  				<div class="col-3">
		  					<select v-model="cliente.gnpsubMarca" class="form-control">
    								<option value="" class="form-control form-control-sm" style="white-space: normal;">Seleccione la submarca</option>

    								<option v-for="marcas in submarcasGNP" :value="marcas">{{marcas}}</option>
    								</select>
		  				</div>
		  				
						</div>
						<hr>

						<hr>	
						<div class="row">
		  				<div class="col">
		  					<label class="form-check-label" for="checkbox-ana"><img :src="img.anaImage" width="120" height="50"></label>
		  				</div>
		  				<!-- <div class="col-3">
		  					<select  v-model="cliente.modelo_auto" class="form-control" id="gnp_lista">
		                            	<option value="" class="form-control form-control-sm" style="white-space: normal;">Seleccione su año</option>
		                            	<option v-for="anio in modelos" :value="anio" >{{anio}}</option>
									</select>	

		  				</div> -->
		  				<div class="col-3">
		  					<select v-model="cliente.marca_auto" class="form-control">
    								<option value="" class="form-control form-control-sm" style="white-space: normal;">Seleccione el modelo</option>

    								<option v-for="marca in marcas" :value="marca" class="list-group-item text-center text-dark seleccionador">{{marca.descripcion}}</option>
    								</select>
		  				</div>
		  				<div class="col-3">
		  					<select v-model="cliente.submarca_auto" class="form-control">
    								<option value="" class="form-control form-control-sm" style="white-space: normal;">Seleccione la submarca</option>

    								<option v-for="submarca in submarcas" :value="submarca" class="list-group-item text-center text-dark seleccionador">{{submarca.descripcion}}</option>
    								</select>
		  				</div>
		  				
						</div>
						<hr>	

		                        </div>
		                    </div>


		                <hr>

		                
						<div class="row">
		  				<div class="col">
		  					<label class="form-check-label" for="checkbox-gs"><img :src="img.gsImage" width="120" height="50"></label>
		  				</div>
		  				<!-- <div class="col-3">
		  					<select  v-model="modeloGNP" class="form-control" id="gnp_lista">
		                            	<option value="" class="form-control form-control-sm" style="white-space: normal;">Seleccione su año</option>
		                            	<option value="2011">2011</option>
										<option value="2012">2012</option>
										<option value="2013">2013</option>
										<option value="2020">2014</option>
										<option value="2015">2015</option>
										<option value="2016">2016</option>
										<option value="2017">2017</option>
										<option value="2018">2018</option>
										<option value="2019">2019</option>
										<option value="2020">2020</option>
										<option value="2021">2021</option>
										<option value="2022">2022</option>
									</select>	

		  				</div> -->
		  				<div class="col-3">
		  					<select v-model="cliente.gsMarca" class="form-control">
    								<option value="" class="form-control form-control-sm" style="white-space: normal;">Seleccione la marca</option>

    								<option :value="marcas.nombre" v-for="marcas in marcasGS">{{marcas.nombre}}</option>
    								</select>
		  				</div>

		  				<div class="col-3">
		  					<select v-model="cliente.submarcasGS" class="form-control">
    								<option value="" class="form-control form-control-sm" style="white-space: normal;">Seleccione la submarca</option>

    								<option :value="marcas.nombre" v-for="marcas in submarcasGS">{{marcas.nombre}}</option>
    								</select>
		  				</div>
		  				
						</div>
						
						<!-- <div class="row">
		  				<div class="col">
		  					<label class="form-check-label" for="checkbox-qualitas"><img :src="img.quaImage" width="120" height="50"></label>
		  				</div>
		  				<div class="col-3">
		  					<select  v-model="modeloGNP" class="form-control" id="gnp_lista">
		                            	<option value="" class="form-control form-control-sm" style="white-space: normal;">Seleccione su año</option>
		                            	<option value="2011">2011</option>
										<option value="2012">2012</option>
										<option value="2013">2013</option>
										<option value="2020">2014</option>
										<option value="2015">2015</option>
										<option value="2016">2016</option>
										<option value="2017">2017</option>
										<option value="2018">2018</option>
										<option value="2019">2019</option>
										<option value="2020">2020</option>
										<option value="2021">2021</option>
										<option value="2022">2022</option>
									</select>	

		  				</div>
		  				<div class="col-3">
		  					<select v-model="cliente.gnpMarca" class="form-control">
    								<option value="" class="form-control form-control-sm" style="white-space: normal;">Seleccione el modelo</option>

    								<option value="" v-for="marcas in marcasGNP">{{marcas}}</option>
    								</select>
		  				</div>
		  				<div class="col-3">
		  					<select v-model="cliente.submarcaGNP" class="form-control">
    								<option value="" class="form-control form-control-sm" style="white-space: normal;">Seleccione la submarca</option>

    								<option value="" v-for="marcas in marcasGNP">{{marcas}}</option>
    								</select>
		  				</div>
		  				
						</div> -->
						<hr>
						<div class="col mt-3 d-flex justify-content-end">
                        					<button type="button" class="btn btn-primary seleccionador" @click="nextPill('codigo')">Siguiente</button>
											
										</div>

		                </div>





		                 <!--SUBMARCA-->
		                <div class="tab-pane fade" v-show="marca" id="v-pills-Submarca" role="tabpanel" aria-albelledby="v-pills-Submarca-tab">
		                    <div class="card p-0">
		                        <div class="card-header">
		                            Tipo
		                        </div>
		                        <div class="card-body">
		                        	<div v-show="loader_tipo" class="loader"></div>
		                        	<div v-show="!loader_tipo && this.submarca.length == 0">
		                        		<label>No se encontraron resultados</label>
		                        	</div>
		                            <select v-show="!loader_tipo && this.submarca.length != 0" v-model="cliente.submarca_auto" size="7" class="list-group list-group-flush col">
		                            	<option value="" class="list-group-item text-center text-dark seleccionador">Seleccione su tipo</option>
										<option v-for="submarca in submarcas" :value="submarca" class="list-group-item text-center text-dark seleccionador">{{submarca.descripcion}}</option>
									</select>
									<div class="row">
										<div class="col-12 mt-3 d-block d-sm-none">
											<button class="btn btn-primary" type="button" onclick="$('#v-pills-Uso-tab').click();">Atras</button>
										</div>
									</div>
		                        </div>
		                    </div>
		                </div>
		                <div class="tab-pane fade" v-show="cp" id="v-pills-CP" role="tabpanel" aria-albelledby="v-pills-CP-tab">
		                    <div class="card p-0">
		                        <div class="card-header">
		                            CP
		                        </div>
		                        <div class="card-body">
	                             	<div class="form-group">
		                                <input type="number" class="form-control" v-model="cliente.cp" placeholder="CP: 000000" id="valorCP">
		                                <div class="alert alert-danger" v-show="alert_cp">
  											<strong>{{alert_cp}}</strong>
										</div>
		                            </div>
		                            <div class="row">
										<div class="col mt-3 d-block d-sm-none">
											<button class="btn btn-primary" type="button" onclick="$('#v-pills-Descripcion-tab').click();">Atras</button>
										</div>
										<div class="col mt-3 d-flex justify-content-end">
                        					<button type="button" class="btn btn-primary seleccionador" @click="nextPill('cp')">Siguiente</button>
											
										</div>
									</div>
		                        </div>
		                    </div>
		                </div>
		                <!--Nombre-->
		                <div class="tab-pane fade" v-show="nombre" id="v-pills-Nombre" role="tabpanel" aria-albelledby="v-pills-Nombre-tab">
		                    <div class="card p-0">
		                        <div class="card-header">
		                            Nombre
		                        </div>
		                        <div class="card-body">
		                            <!-- TODO -->
		                            <div class="form-row">
		                                <div class="col-12 my-1">
		                                    <input type="text" v-model="cliente.nombre" id="valorNombre" class="form-control" placeholder="Nombre">
		                                </div>
		                                <div class="col-12 my-1">
		                                    <input type="text" v-model="cliente.appaterno" id="valorApellidoP" class="form-control" placeholder="Apellido P.">
		                                </div>
		                                <div class="col-12 my-1">
		                                    <input type="text" v-model="cliente.apmaterno" id="valorApellidoM" class="form-control" placeholder="Apellido M.">
		                                </div>
		                            </div>
		                            <div class="row">
										<div class="col mt-3 d-block d-sm-none">
											<button class="btn btn-primary" type="button" onclick="$('#v-pills-CP-tab').click();">Atras</button>
										</div>
										<div class="col mt-3 d-flex justify-content-end">
                        					<button type="button" id="e_1" @click="nextPill('nombre')" class="btn btn-primary seleccionador">Siguiente</button>
										</div>
									</div>
		                        </div>
		                    </div>
		                </div>
		                <!--Celular-->
		                <div class="tab-pane fade" v-show="celular" id="v-pills-Celular" role="tabpanel" aria-albelledby="v-pills-Celular-tab">
		                    <div class="card p-0">
		                        <div class="card-header">
		                            Celular
		                        </div>
		                        <div class="card-body">
		                        	<div class="form-group">
		                                <input type="number" v-model="cliente.telefono" class="form-control" id="valorCelular" placeholder="55 53 33 11 22">
		                            </div>
		                            <div class="row">
										<div class="col mt-3 d-block d-sm-none">
											<button class="btn btn-primary" type="button" onclick="$('#v-pills-Nombre-tab').click();">Atras</button>
										</div>
										<div class="col mt-3 d-flex justify-content-end">
                        					<button type="button" id="f_1"  @click="nextPill('telefono')" class="btn btn-primary seleccionador">Siguiente</button>
										</div>
									</div>
		                        </div>
		                    </div>
		                </div>
		                <!--Correo-->
		                <div class="tab-pane fade" v-show="correo" id="v-pills-Correo" role="tabpanel" aria-albelledby="v-pills-Correo-tab">
		                    <div class="card p-0">
		                        <div class="card-header">
		                            Correo
		                        </div>
		                        <div class="card-body">
		                             <div class="form-group">
		                                <input type="email" v-model="cliente.email" class="form-control" id="valorCorreo" placeholder="ejemplo@ejemplo.com">
		                            </div>
		                            <div class="row">
										<div class="col mt-3 d-block d-sm-none">
											<button class="btn btn-primary" type="button" onclick="$('#v-pills-Celular-tab').click();">Atras</button>
										</div>
										<div class="col mt-3 d-flex justify-content-end">
                        					<button type="button" id="g_1" @click="nextPill('correo')" class="btn btn-primary seleccionador">Siguiente</button>
										</div>
									</div>
		                        </div>
		                    </div>
		                </div>
		                <!--Sexo-->
		                <div class="tab-pane fade" v-show="sexo" id="v-pills-Sexo" role="tabpanel" aria-albelledby="v-pills-Sexo-tab">
		                    <div class="card p-0">
		                        <div class="card-header">
		                            Genero
		                        </div>
		                      <!--    <div class="card-header">
		                            Genero
		                        </div> -->
		                        <div class="card-body">
		                            <select v-model="cliente.sexo" size="3" class="list-group list-group-flush col"  style="overflow-y: hidden;">

										<option value="Hombre" class="list-group-item text-center text-dark seleccionador">Masculino </option>
                        				<option value="Mujer" class="list-group-item text-center text-dark seleccionador">Femenino</option>
                        				<option value="Otro" class="list-group-item text-center text-dark seleccionador">Otro</option>
									</select>
									<div class="row">
										<div class="col-12 mt-3 d-block d-sm-none">
											<button class="btn btn-primary" type="button" onclick="$('#v-pills-Correo-tab').click();">Atras</button>
										</div>
									</div>
		                        </div>
		                    </div>
		                </div>
						<!--Nacimiento-->
		                <div class="tab-pane fade" v-show="nac" id="v-pills-Nacimiento" role="tabpanel" aria-albelledby="v-pills-Nacimiento-tab">
		                    <div class="card p-0">
		                        <div class="card-header">
		                            Fecha de Nacimiento
		                        </div>
		                        <div class="card-body">
		                            <!-- TODO -->
		                            <div class="form-group">
		                                <input type="date" v-model="cliente.f_nac" id="valorEdad" onchange="cambiarEdad(this.value)" class="form-control col">
		                            </div>

		                            <div class="row">
										<div class="col mt-3 d-block d-sm-none">
											<button class="btn btn-primary" type="button" onclick="$('#v-pills-Sexo-tab').click();">Atras</button>
										</div>
										<div class="col mt-3 d-flex justify-content-end">
		                                    <button type="button" id="8_1" @click="nextPill('aseguradoras')" class="btn btn-primary seleccionador">Siguiente</button>
		                                </div>
									</div>
		                        </div>
		                    </div>
		                </div>
		                <div class="tab-pane fade" v-show="nac"  id="v-pills-Aseguradoras" role="tabpanel" aria-albelledby="v-pills-Aseguradoras-tab">
		                    <div class="card p-0">
		                        <div class="card-header">
		                        	<div class="row text-center">
		                        		<label>
		                            		Aseguradoras
		                        		</label>
		                        		<div class="col mt-3 d-flex justify-content-end">
										  	
										  <!-- 	<label class="form-check-label" for="checkbox-todos"><input class="form-check-input" type="checkbox" id="checkbox-todos" v-model="checkall" @click="selectAll()">Todos</label> -->
		                        		</div>
		                        	</div>
		                        </div>
		                        <div class="card-body">
		                            <div class="form-group">
		                            	<!-- GNP -->
		                            	<div class="form-check form-check-inline">
										  <input class="form-check-input" type="checkbox" id="checkbox-gnp" v-model="cliente.gnp" true-value="1" false-value="0">
										  <label class="form-check-label" for="checkbox-gnp"><img :src="img.gnpImage" width="120" height="50"></label>
										</div>





										<!-- General de seguros -->
										<div class="form-check form-check-inline" v-if="cliente.uso_auto == 'Servicio Particular'">
										  <input class="form-check-input" type="checkbox" id="checkbox-gs" v-model="cliente.gs" true-value="1" false-value="0">
										  <label class="form-check-label" for="checkbox-gs"><img :src="img.gsImage" width="120" height="50"></label>
										</div>
										<!-- Qualitas -->
		                            	<!-- <div class="form-check form-check-inline">
										  <input class="form-check-input" type="checkbox" id="checkbox-qualitas" v-model="cliente.qualitas" true-value="1" false-value="0">
										  <label class="form-check-label" for="checkbox-qualitas"><img :src="img.quaImage" width="120" height="50"></label>
										</div> -->
										<!-- ANA -->
		                            	<div class="form-check form-check-inline" v-if="cliente.uso_auto == 'Servicio Particular'">
										  <input class="form-check-input" type="checkbox" id="checkbox-ana" v-model="cliente.ana" true-value="1" false-value="0">
										  <label class="form-check-label" for="checkbox-ana"><img :src="img.anaImage" width="120" height="50"></label>
										</div>
		                                <!-- <input type="date" v-model="cliente.f_nac" id="valorEdad" onchange="cambiarEdad(this.value)" class="form-control col"> -->
		                            </div>
		                            

		                            <div class="row">
										<div class="col mt-3 d-block d-sm-none">
											<button class="btn btn-primary" type="button" onclick="$('#v-pills-Nacimiento-tab').click();">Atras</button>
										</div>
										<div class="col mt-3 d-flex justify-content-end">
		                                    <button type="button" id="8_1" @click="sendCotizacion(cliente)" class="btn btn-primary seleccionador">Siguiente</button>
		                                </div>
									</div>

		                        </div>
		                    </div>
		                </div>
    				</div>
    			</div>
    		</div>
    	</div>
    	<!-- Fin de crear cotización -->
	    <!-- <pre>
	    	@{{$data}}
	    </pre> -->
    </div>
</template>

<script>
$(document).ready(function($) {
	if(!Modernizr.inputtypes.date) {
        console.log("The 'date' input type is not supported, so using JQueryUI datepicker instead.");
        $("#valorEdad").datepicker({changeMonth:true, changeYear: true});
    }
});

function Cliente({cotizacion,auto,uso_auto,cp,nombre,appaterno,apmaterno,telefono,email,sexo,f_nac, qualitas, ana,ejecutivo,codigo_descuento,}){
	this.cotizacion = cotizacion;
	this.uso_auto   = uso_auto;
	this.auto       = auto;
	this.cp 	    = cp;
	this.nombre     = nombre;
	this.appaterno  = appaterno;
	this.apmaterno  = apmaterno;
	this.telefono   = telefono;
	this.email 	    = email;
	this.sexo 	    = sexo;
	this.f_nac      = f_nac;
	this.ejecutivo;
	this.codigo_descuento;

}
    export default {
    	// Propiedades que se le asignan desde el componente padre 
    	// (serian como entradas en el componente).
    	props:[
    		'cliente',
    		'getcotizacion',
    		'alert',
    		'img'
    	],
    	data(){
    		// Propiedades que tendra el componente mediante this
    		return{
    			descripciones:[],
    			anios:[],
    			marcas: [],
    			submarcas:[],
    			modelos:[],
    			modelosGNP:[],
    			marcasGNP:[],
    			submarcasGNP:[],
    			marcasGS:[],
    			submarcasGS:[],
    			pills:[
    				'v-pills-Uso',
    				'v-pills-Marca',
    				'v-pills-Submarca',
    				'v-pills-Modelo',
    				'v-pills-CP',
    				'v-pills-Nombre',
    				'v-pills-Celular',
    				'v-pills-Correo',
    				'v-pills-Sexo',
    				'v-pills-Nacimiento',
    				'v-pills-Aseguradoras'
    			],
    			alert_cp:"",
    			uso: true,
    			marca: false,
    			submarca:false,
    			modelo: false,
    			loader_marca:true,
    			loader_desc:true,
    			loader_tipo:true,
    			loader_modelo:true,
    			descripcion:false,
    			cp:false,
    			modelos:"",
    			nombre:false,
    			celular:false,
    			correo:false,
    			sexo:false,
    			nac: false,
    			searchOption:false,
    			checkall:false,
    			marcaGNP:"",
    			modeloGNP:'',
    			submarcaGNP:"",
    			gnpsubMarca:"",
    			marcaGS:"",
    			submarcaGS:""
    		}
    	},
    	watch:{
    		'cliente.uso_auto': function(newValue,oldValue){
    			if (newValue != "") {
    				this.marca = true;
    				this.cp = true;
    				$('#v-pills-Modelo-tab').removeClass('disabled');
    				$('#v-pills-Modelo-tab').click();
    				// $('#v-pills-CP-tab').removeClass('disabled');
    				// $('#v-pills-CP-tab').click();
    				this.modelo = true;
    			}
    		},
    		'modeloGNP':function(newValue,oldValue){
    				
					console.log('Aqui se ejecutaron los MODELOS GNP',this.modelos);

					// this.marcaGNP = this.modeloGNP;
    				this.marcasGNP = this.getmarcaGNP(this.modeloGNP);
    		},
    		'cliente.modelos':function(newValue,oldValue){
    			if (newValue != "") {
    					this.modelos = this.cliente.modelos;
						this.modeloGNP = this.cliente.modelos;

						// this.marcaGNP = marcaGNP
						this.cliente.modelo_auto = this.cliente.modelos;
						this.marcasGNP = this.getmarcaGNP(this.modeloGNP);
						this.getMarcas(this.cliente.modelo_auto);
						this.marcasGS = this.getMarcasGS();
					
    			}
    				

    				
    		},
    		'marcaGNP':function(newValue,oldValue){
    				// this.cliente.gnpMarca ="";cliente.gnpMarca
    				// this.marcaGNP = 
					// console.log('SUBMarcas de Gnp en el año:',this.modeloGNP,this.marcaGNP,this.modelos);
						this.cliente.gnpMarca =	this.marcaGNP;
    				this.subMarcasGNP = this.getSubmarcaGNP(this.modeloGNP,this.marcaGNP);
    		},

    		'cliente.gnpsubMarca':function(newValue,oldValue){
    				
    					this.cp = true;
    				// console.log('subMarcas de Gnp en el año:',this.submarcasGNP);
    				// this.submarcasGNP = this.getSubmarcaGNP(this.modeloGNP,this.submarcasGNP);
    				this.submarcaGNP = this.cliente.gnpsubMarca;
    				console.log('subMarca que se envia seleccion',this.cliente.gnpsubMarca );
    				console.log('subMarca que se envia',this.gnpsubMarca,this.submarcaGNP );


    				// $('#v-pills-CP-tab').removeClass('disabled');
    				// $('#v-pills-CP-tab').click();
    				
					
    		},

    		'cliente.modelo_auto':function(newV,oldV){
    			if (newV != "") {
    				// this.marca = true;
    				
    				this.getMarcas(this.cliente.modelo_auto);
    				
    				// $('#v-pills-Marca-tab').removeClass('disabled');
    				// $('#v-pills-Marca-tab').click();
    			}
    		},
    		'cliente.gsMarca':function(newV,oldV){
    			if (newV != "") {
    				console.log('VALOR DE LA MARCA',this.cliente.gsMarca);
    				this.submarcasGS = this.getSubmarcaGS(this.cliente.gsMarca);
    				
    				
    				// $('#v-pills-Marca-tab').removeClass('disabled');
    				// $('#v-pills-Marca-tab').click();
    			}
    		},
    		'cliente.gsSubmarca':function(newV,oldV){
    			if (newV != "") {
    				this.submarcaGS = this.cliente.submarcasGS;
    				
    				console.log('SUBMARCA SELECCIONADA GS:',this.submarcaGS);
    				this.cliente.gssubMarca =this.submarcaGS;
    				// $('#v-pills-Marca-tab').removeClass('disabled');
    				// $('#v-pills-Marca-tab').click();
    			}
    		},
    		'cliente.marca_auto': function(newValue,oldValue){
    			if (newValue != "") {
    				// this.modelo = true;

    				if(this.searchOption == false){
    					this.cliente.descripcion_auto="";
    				}

    				this.getSubmarcas(this.cliente.marca_auto.id);

    				// $('#v-pills-Submarca-tab').removeClass('disabled');
    				// $('#v-pills-Submarca-tab').click();
    			}
    		},
    		'cliente.submarca_auto':function(newV,oldV){
    			if (newV != "") {
    				this.cp = true;
    				$('#v-pills-CP-tab').removeClass('disabled');
    				// $('#v-pills-CP-tab').click();
    			}
    		},
    		'cliente.sexo':function(newV,oldV){
    			if (newV != "") {
    				this.nac = true;
    				
    				$('#v-pills-Nacimiento-tab').removeClass('disabled');
    				$('#v-pills-Nacimiento-tab').click();
    			}	
    		},

    	},
    	created(){
    		this.getModelos();
    	},
    	methods:{
    		searchCliente(cotizacion){
    			//console.log(cotizacion);
    			let url = './api/searchCliente';
    			let params = {cotizacion:cotizacion};
    			this.alert.message = '';
				this.alert.class = '';
    			axios.post(url,params).then(res=>{
    				// console.log("res cot",res);
    				if(res.data.cotizacion){
    					this.searchOption = true;
    					// this.cliente = new Cliente(res.data.cotizacion);
    					this.cliente.cotizacion 	  = res.data.cotizacion.cotizacion;
						this.cliente.uso_auto 		  = res.data.cotizacion.uso_auto;
						this.cliente.descripcion_auto = res.data.cotizacion.auto.version;
						this.cliente.marca_auto    	  = res.data.cotizacion.auto.marca;
						this.cliente.modelo_auto   	  = res.data.cotizacion.auto.submarca.anio;
						this.cliente.submarca_auto 	  = res.data.cotizacion.auto.submarca;
						this.cliente.cp 			  = res.data.cotizacion.cp;
						this.cliente.nombre 		  = res.data.cotizacion.nombre;
						this.cliente.appaterno 		  = res.data.cotizacion.appaterno;
						this.cliente.apmaterno 		  = res.data.cotizacion.apmaterno;
						this.cliente.telefono 		  = res.data.cotizacion.telefono;
						this.cliente.email 			  = res.data.cotizacion.email;
						this.cliente.sexo 			  = res.data.cotizacion.sexo;
						this.cliente.f_nac 			  = res.data.cotizacion.f_nac;
						this.cliente.ana 			  = res.data.cotizacion.ana;
						this.cliente.gs 			  = res.data.cotizacion.gs;
						this.cliente.qualitas 		  = res.data.cotizacion.qualitas;
						this.cliente.gnpMarca 		  = res.data.cotizacion.gnpMarca;
						this.cliente.gnpsubMarca 	  = res.data.cotizacion.gnpsubMarca;
						this.cliente.gssubMarca		  = res.data.cotizacion.gssubMarca;
						this.cliente.gsMarca		  = res.data.cotizacion.gsMarca;

						// alert(this.cliente.gnpMarca,this.cliente.gnpsubMarca);
					 	$("#paso2-tab").removeClass("disabled");
        				$("#paso2-tab").click();
        				this.getcotizacion.value = !this.getcotizacion.value;
    				}
    				
    			}).catch(err=>{
    				if(err.response.data.error){
    					this.alert.message = err.response.data.error;
    					this.alert.class = "alert alert-danger alert-dismissible fade show"
    					$('#alert').alert('show')
    				}
    			});


    		},
    		selectAll(){
    			if (!this.checkall) {
    				this.cliente.qualitas = 1;
    				if (this.cliente.uso_auto == 'Servicio Particular') {
	    				this.cliente.ana = 1;
	    				this.cliente.gs = 1;
	    				this.cliente.gnp = 1;
    				}
    				else{
    					this.cliente.ana = 0;
	    				this.cliente.gs = 0;
    				}
    			}
    			else{
    				this.cliente.ana = 0;
    				this.cliente.qualitas = 0;
    				this.cliente.gs = 0;
    				this.cliente.gnp = 0;
    			}
    		},
    		getMarcas(){
    			let url = './api/getMarcas';
    			axios.get(url).then(res=>{
    				console.log("res",res);
    				this.marcas = res.data.marcas.sort();
    			}).catch(error=>{
    				console.log('error',error);

    			})
    		},
    		getMarcas(modelo){
    			this.loader_marca=true;
    			let url = `./api/marcasANA/${modelo}`;
    			axios.get(url).then(res=>{
    				this.loader_marca = false;
    				//console.log("res marcas",res);
    				this.marcas = res.data.marcas.sort();
    			}).catch(error=>{
    				console.log('error',error);

    			})
    		},
    			getMarcasGS(){
    			this.loader_marca=true;
    			let url = `./api/getMarcas`;
    			axios.get(url).then(res=>{
    				this.loader_marca = false;
    				console.log("res marcas general de seguros",res.data.marcas);
    				// console.log("res marcas general de seguros",res.data.marcas);
    				// console.log("res marcas general de seguros",res.data.marcas);
    				// console.log("res marcas general de seguros",res.data.marcas);
    				// console.log("res marcas general de seguros",res.data.marcas);
    				// console.log("res marcas general de seguros",res.data.marcas);
    				this.marcasGS = res.data.marcas.sort();
    			}).catch(error=>{
    				console.log('error',error);

    			})
    		},
    		getSubmarcas(marca){
    			this.loader_tipo=true;
    			let url = `./api/submarcaANA/${marca}/${this.cliente.modelo_auto}`;
    			$('#descripcion').append('<div class="loader"></div>');
    			axios.get(url).then(res=>{
    				this.loader_tipo = false;
    				console.log('res submarcas',res);
    				if (res.data.submarcas) {
    					this.submarcas = res.data.submarcas.sort();
    				}
    			}).catch(error=>{
    				console.log('error submarcas',error);
				});
    		},
    		getSubmarcaGS(marca){
    			this.loader_tipo=true;
    			let url = `./api/getSubmarcas/${marca}`;
    			// $('#descripcion').append('<div class="loader"></div>');
    			axios.get(url).then(res=>{
    				this.loader_tipo = false;
    				console.log('res submarcas GS',res);
    				if (res.data.submarcas) {
    					this.submarcasGS = res.data.submarcas.sort();
    				}
    			}).catch(error=>{
    				console.log('error submarcas',error);
				});
    		},
    			getSubmarcaGNP(año,marca){
    			this.loader_tipo=true;
    			let url = `./api/submarcas-gnp/${año}/${marca}`;
    			
    			axios.get(url).then(res=>{
    				this.loader_tipo = false;
    				// console.log('SUBMARCAS GNP COMPLETAS',res);
    				
    				if (res.data.submarcas) {
    					this.submarcasGNP = res.data.submarcas;
    					// console.log('RES SUBMARCAS',this.submarcasGNP);
    					// alert('EXITO EN LA CONSULTA SUBMARCAS');
    				}
    				
    			}).catch(error=>{
    				console.log('error submarcas',error);
				});
    		},
    		
    		getmarcaGNP(año){
    			this.loader_tipo=true;
    			let url = `./api/marcas-gnp/${año}`;
    			
    			axios.get(url).then(res=>{
    				this.loader_tipo = false;
    				// console.log('MARCAS GNP COMPLETAS',res);
    				
    				if (res.data.marcas) {
    					this.marcasGNP = res.data.marcas;
    					// console.log('RES MARCAS',this.marcasGNP);
    					// alert('EXITO EN LA CONSULTA');
    				}
    				
    			}).catch(error=>{
    				console.log('error submarcas',error);
				});
    		},
    		getModelos(){
    			let url = `./api/modelosANA`;
    			axios.get(url).then(res=>{
    				console.log('res modelos',res);
    				this.loader_modelo=false;
    				if (res.data.modelos) {
    					this.modelos = res.data.modelos;
    				}

    			}).catch(error=>{
    				console.log('error modelos',error);
				});
    		},
    		getDescripciones(submarca,modelo,marca){
    			this.loader_desc = true;
    			// console.log(marca);
            	$('#descripcion').append('<div class="loader"></div>');
    			let url = `./api/vehiculoANA/${marca}/${submarca}/${modelo}`;
    			axios.get(url).then(res=>{
    				this.loader_desc = false;
    				this.descripciones = res.data.vehiculos;
    			}).catch(err=>{

    				console.log('getDescripciones err',err);
    			})
    		},
    		nextPill(input){
    			if (input == "cp" && this.cliente.cp != "") {
	    			let url = `./api/cp/${this.cliente.cp}`;

    				axios.get(url).then(res=>{
    						if(res.data.response){
    							this.alert_cp = 
    							this.nombre = true;
    							this.alert_cp = "";
    							// console.log('si entra');

			    				this.cliente.cestado = res.data.response[0].cestado;

			    				$('#v-pills-Nombre-tab').removeClass('disabled');
			    				$('#v-pills-Nombre-tab').click();
    						}
    						console.log('CP',res);
    				}).catch(
    					err=>{
    						if(err.response.data.error){
    							this.nombre = false;
    							$('#v-pills-Nombre-tab').addClass('disabled');
    							this.alert_cp = err.response.data.error;
    						}
    					});
    				
    			}
    			if (input == "codigo" && this.cliente.modelos != "") {
    				this.Marca = true;
    				$('#v-pills-CP-tab').removeClass('disabled');
    				$('#v-pills-CP-tab').click();

    			}
    			if (input == "Marca" && this.cliente.modelos != "") {
    				this.Marca = true;
    				$('#v-pills-Marca-tab').removeClass('disabled');
    				$('#v-pills-Marca-tab').click();

    			}
    			if (input == "nombre" && this.cliente.nombre != "" && this.cliente.appaterno != "") {
    				this.celular = true;
    				$('#v-pills-Celular-tab').removeClass('disabled');
    				$('#v-pills-Celular-tab').click();
    			}
    			if (input == "telefono" && this.cliente.telefono != "") {
    				this.correo = true;
    				$('#v-pills-Correo-tab').removeClass('disabled');
    				$('#v-pills-Correo-tab').click();
    			}
    			if (input == "correo" && this.cliente.telefono != "") {
    				this.sexo = true;
    				$('#v-pills-Sexo-tab').removeClass('disabled');
    				$('#v-pills-Sexo-tab').click();
    			}
    			if (input == "sexo" && this.cliente.telefono != "") {
    				this.nac = true;

    				$('#v-pills-Nacimiento-tab').removeClass('disabled');
    				$('#v-pills-Nacimiento-tab').click();
    			}
    			if (input == "aseguradoras" ) {
    				this.aseguradoras = true;
    				$('#v-pills-Aseguradoras-tab').removeClass('disabled');
    				$('#v-pills-Aseguradoras-tab').click();

    			}
    			
    		},

    		sendCotizacion(cliente){
    			let params = cliente;
    			let url = "./api/cotizacion";
    			this.alert.message = '';
				this.alert.class = '';
    			axios.post(url,cliente).then(res=>{
    				if (this.cliente.sexo =="Maculino") {
    					this.cliente.sexo =="Hombre";
    				}else if (this.cliente.sexo =="Femenino") {
    					this.cliente.sexo =="Mujer";
    				}
    				//console.log('res',res);
    				this.cliente.cotizacion 	  = res.data.cotizacion.cotizacion;
					this.cliente.uso_auto 		  = res.data.cotizacion.uso_auto;
					this.cliente.descripcion_auto = res.data.cotizacion.auto.version;
					this.cliente.marca_auto    	  = res.data.cotizacion.auto.marca;
					// if (isset(this.cliente.modelo_auto)) {
						
					// }
					this.cliente.modelo_auto      = res.data.cotizacion.auto.submarca.anio;
					this.cliente.submarca_auto    = res.data.cotizacion.auto.submarca;
					this.cliente.cp 			  = res.data.cotizacion.cp;
					this.cliente.nombre 		  = res.data.cotizacion.nombre;
					this.cliente.appaterno 		  = res.data.cotizacion.appaterno;
					this.cliente.apmaterno 		  = res.data.cotizacion.apmaterno;
					this.cliente.telefono 		  = res.data.cotizacion.telefono;
					this.cliente.email 			  = res.data.cotizacion.email;
					this.cliente.sexo 			  = res.data.cotizacion.sexo;
					this.cliente.f_nac 			  = res.data.cotizacion.f_nac;
					this.cliente.ana 			  = res.data.cotizacion.ana;
					this.cliente.gs 			  = res.data.cotizacion.gs;
					this.cliente.qualitas 		  = res.data.cotizacion.qualitas;
					this.cliente.gnpsubMarca 	  = res.data.cotizacion.gnpsubMarca;
					this.cliente.gnpMarca		  = res.data.cotizacion.gnpMarca;
					this.cliente.gssubMarca		  = res.data.cotizacion.gssubMarca;
					this.cliente.gsMarca		  = res.data.cotizacion.gsMarca;
    				this.getcotizacion.value 	  = !this.getcotizacion.value;

    				this.alert.message 			  = `${this.cliente.nombre} ${this.cliente.appaterno} ${this.cliente.apmaterno} su cotización se guardo con el folio ${this.cliente.cotizacion}`;
    				this.alert.class 			  = "alert alert-success alert-dismissible fade show";

    				$("#paso2-tab").removeClass("disabled");
        			$("#paso2-tab").click();
    			}).catch(err=>{
    				console.log('sendCotizacion err', err);
    			})
    		}
    	},
        mounted() {
            console.log('Component mounted.');
            let uri = window.location.href.split('?');
            if (uri.length == 2) {

				let vars = uri[1].split('&');
				let getVars = {};
				let tmp = '';

				vars.forEach(function(v) {
					tmp = v.split('=');
					if(tmp.length == 2)
						getVars[tmp[0]] = tmp[1];
				});

				if(getVars.cotizacion) {
					this.cliente.cotizacion = getVars.cotizacion
					this.searchCliente(this.cliente.cotizacion);
				}
		    }
        }
    }
</script>