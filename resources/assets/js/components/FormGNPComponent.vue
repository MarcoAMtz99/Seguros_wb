<template>
    <form  @submit="sendGNP" method="POST" action="./sendGNP">
        <input type="hidden" name="_token" :value="csrf" />
        <input type="hidden" name="paquete" :value="JSON.stringify(cotizacion.paquete)">
        <input type="hidden" name="numCotizacion" :value="cotizacion.numCotizacion">
        <input type="hidden" name="descripcionAuto" :value="cotizacion.descripcionAuto">
        <input type="hidden" name="tipoPoliza" :value="cotizacion.tipo_poliza">
        <input type="hidden" name="cliente" :value="cliente.cotizacion">
        <div class="row">
            <div class="offset-2 col-5 offset-md-2 col-md-4 w-md-150" >
                <img width="100%" height="100%" :src="img.gnpImage">
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-3">
                <h4>Datos del asegurado:</h4>
            </div>
            <div class="form-group col-6">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Tipo de persona:</label>
                <div class="form-check col-12">
                    <input class="form-check-input" type="radio" name="tipo_persona" id="radioF" v-model="gnp.cliente.tipo_persona" value="F" required="" checked>
                    <label class="form-check-label" for="radioF">
                     Fisica
                    </label>
                </div>
                <div class="form-check col-12">
                    <input class="form-check-input" type="radio" name="tipo_persona" id="radioM" v-model="gnp.cliente.tipo_persona" value="M">
                    <label class="form-check-label" for="radioM">
                     Moral
                    </label>
                </div>
            </div>
        </div>
        <div class="row" v-if="gnp.cliente.tipo_persona == 'F'">
            <div class="form-group col-12 col-md-4">
                <label class="control-label">
                    <i class="fa fa-asterisk" aria-hidden="true"></i> Nombre(s)
                </label>
                <input class="form-control" type="text" name="nombre" v-model="gnp.cliente.nombre" required>
            </div>
            <div class="form-group col-12 col-md-4">
                <label class="control-label">
                    <i class="fa fa-asterisk" aria-hidden="true"></i> Apellido Paterno
                </label>
                <input type="text" name="apepat" class="form-control" v-model="gnp.cliente.apepat" required>
            </div>
            <div class="form-group col-12 col-md-4">
                <label class="control-label">
                    Apellido Materno
                </label>
                <input type="text" name="apemat" class="form-control" v-model="gnp.cliente.apemat">
            </div>
        </div>
        <div class="row" v-if="gnp.cliente.tipo_persona == 'M'">
            <div class="form-group col-12">
                <label class="control-label">
                    <i class="fa fa-asterisk" aria-hidden="true"></i> Razón Social
                </label>
                <input class="form-control" type="text" name="nombre" v-model="gnp.cliente.nombre" required>
            </div>
            <div class="form-group col-12 col-md-4" v-if="gnp.cliente.tipo_persona == 'M'">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Fecha de constitucion:</label>
                <input class="form-control" type="date" name="f_const" v-model="gnp.cliente.f_const" :max="maxDate" required>
            </div>

            <div class="form-group col-12 col-md-4"v-if="gnp.cliente.tipo_persona == 'M'">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> R.F.C de la empresa:</label>
                <input class="form-control" type="text" name="rfc" v-model="gnp.cliente.rfc" pattern="[a-zA-Z]{3,3}[0-9]{6,6}[a-zA-Z0-9]{3,3}" required>
            </div>
             
        </div>
        <hr>
        <div class="row">
            <div class="col-12 mt-3">
                <h4>Datos del contratante:</h4>
            </div>
            <div class="form-group col-12 col-md-4">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Correo electrónico:</label>
                <input class="form-control" type="email" name="correo" v-model="gnp.cliente.correo" required>
            </div>
            <div class="form-group col-12 col-md-4">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Telefono</label>
                <input class="form-control" type="text" name="telefono" v-model="gnp.cliente.telefono" maxlength="10" minlength="10frfc" required>
            </div>
            <div class="form-group col-12 col-md-4" v-if="gnp.cliente.tipo_persona == 'F'">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Fecha de nacimiento:</label>
                <input class="form-control" type="date" name="f_nac" v-model="gnp.cliente.f_nac" :max="maxDate" required>
            </div>
            <div class="form-group col-12 col-md-4" v-if="gnp.cliente.tipo_persona == 'F'">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Edad:</label>
                <input class="form-control" type="text" v-model="edad" disabled>
                <input class="form-control" type="hidden" name="edad" :value="edad" required>
            </div>
             <div class="form-group col-12 col-md-4" v-if="gnp.cliente.tipo_persona == 'F'">

            <div class="form-group col-12 col-md-4">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> R.F.C:</label>
                <input class="form-control" type="text" name="rfc" v-model="gnp.cliente.rfc" pattern="[a-zA-Z]{4,4}[0-9]{6,6}[a-zA-Z0-9]{3,3}" required>
            </div>
             </div>
                
            <div class="form-group col-12 col-md-4" v-if="gnp.cliente.tipo_persona == 'F'">
                <label for="sexo" class="control-label"><i class="fas fa-asterisk"></i> Sexo</label>
                <select name="sexo" class="form-control" v-model="gnp.cliente.sexo" required>
                    <option value="">Seleccione su sexo</option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                </select>
            </div>
            <div class="form-group col-12 col-md-4">
                <label for="estadoCivil" class="control-label"><i class="fas fa-asterisk"></i> Estado Civil:</label>
                <select name="estadoCivil" class="form-control" v-model="gnp.cliente.estadoCivil" required>
                    <option value="">Seleccione el estado civil</option>
                    <option value="C">Casado</option>
                    <option value="S">Soltero</option>
                </select>
            </div>
            <div class="form-group col-12 col-md-4">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Código Postal:</label>
                <input type="text" name="codigo_postal" class="form-control" v-model="gnp.cliente.codigo_postal" required maxlength="5">
            </div>
            <div class="form-group col-12 col-md-4">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Estado:</label>
                <input type="text" class="form-control" v-model="gnp.cliente.estadoNombre" disabled>
                <input type="hidden" name="estado" :value="gnp.cliente.estadoClave" required>
            </div>
            <div class="form-group col-12 col-md-4">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Alcaldía o Municipio:</label>
                <input type="text" class="form-control" v-model="gnp.cliente.municipioNombre" disabled>
                <input type="hidden" name="municipio" :value="gnp.cliente.municipioNombre" required>
            </div>
            <div class="form-group col-12 col-md-4">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Colonia:</label>
                <select class="form-control" name="colonia" v-model="gnp.cliente.colonia" required>
                    <option value="">Seleccione la colonia en donde vive</option>
                    <option v-for="colonia in colonias" :value="colonia.CLAVE">{{colonia.NOMBRE}}</option>
                </select>
            </div>
            <div class="form-group col-12 col-md-4">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Calle:</label>
                <input type="text" name="calle" v-model="gnp.cliente.calle" class="form-control" required>
            </div>
            <div class="form-group col-12 col-md-4">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Número exterior:</label>
                <input type="text" name="num_ext" v-model="gnp.cliente.num_ext" class="form-control" required>
            </div>
            <div class="form-group col-12 col-md-4">
                <label class="control-label">Número interior:</label>
                <input type="text" name="num_int" v-model="gnp.cliente.num_int" class="form-control">
            </div>
            <div class="form-group col-12 col-md-4">
                <label for="tipoVia" class="control-label"><i class="fas fa-asterisk"></i> Tipo de via</label>
                <select name="tipoVia" class="form-control" v-model="gnp.cliente.tipoVia" required>
                    <option value="">Seleccione el tipo de Via</option>
                    <template v-for="tipo in tiposVia">
                        <option v-if="tipo.NOMBRE != ''" :value="tipo.CLAVE">{{ tipo.NOMBRE }}</option>
                    </template>
                </select>
            </div>
        </div>
        <hr>
          <div class="row" v-if="gnp.cliente.tipo_persona == 'M'">
            <div class="col-12 mt-3">
                <h4>Datos del conductor habitual:</h4>
            </div>
            <div class="form-group col-12 col-md-4">
                <label class="control-label">
                    <i class="fa fa-asterisk" aria-hidden="true"></i> Nombre(s)
                </label>
                <input class="form-control" type="text" name="nombre_c" v-model="gnp.cliente.nombre_c" required>
            </div>
            <div class="form-group col-12 col-md-4">
                <label class="control-label">
                    <i class="fa fa-asterisk" aria-hidden="true"></i> Apellido Paterno
                </label>
                <input type="text" name="apepat_c" class="form-control" v-model="gnp.cliente.apepat_c" required>
            </div>
            <div class="form-group col-12 col-md-4">
                <label class="control-label">
                    Apellido Materno
                </label>
                <input type="text" name="apemat_c" class="form-control" v-model="gnp.cliente.apemat_c">
            </div>
            <div class="form-group col-12 col-md-4" v-if="gnp.cliente.tipo_persona == 'M'">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Fecha de nacimiento:</label>
                <input class="form-control" type="date" name="f_nac_c" v-model="gnp.cliente.f_nac_c" :max="maxDate" required>
            </div>
            <div class="form-group col-12 col-md-4" v-if=" gnp.cliente.tipo_persona == 'M'">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Edad:</label>
                <input class="form-control" type="text" v-model="edad" disabled>
                <input class="form-control" type="hidden" name="edad" :value="edad" required>
            </div>
            <div class="form-group col-12 col-md-4">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> R.F.C.:</label>
                <input class="form-control" type="text" name="rfc_c" v-model="gnp.cliente.rfc_c" pattern="[a-zA-Z]{4,4}[0-9]{6,6}[a-zA-Z0-9]{3,3}" required>
            </div>
            <div class="form-group col-12 col-md-4" v-if=" gnp.cliente.tipo_persona == 'M'">
                <label for="sexo" class="control-label"><i class="fas fa-asterisk"></i> Sexo</label>
                <select name="sexo_c" class="form-control" v-model="gnp.cliente.sexo_c" required>
                    <option value="">Seleccione su sexo</option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                </select>
            </div>
            <div class="form-group col-12 col-md-4">
                <label for="estadoCivil" class="control-label"><i class="fas fa-asterisk"></i> Estado Civil:</label>
                <select name="estadoCivil_c" class="form-control" v-model="gnp.cliente.estadoCivil_c" required>
                    <option value="">Seleccione el estado civil</option>
                    <option value="C">Casado</option>
                    <option value="S">Soltero</option>
                </select>
            </div>

        </div>
        <div class="row">
            <div class="col-12 mt-3">
                <h4>Datos del vehiculo:</h4>
            </div>
            <div class="form-group col-4">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Motor</label>
                <input class="form-control" type="text" name="motor" v-model="gnp.vehiculo.motor" required maxlength="20">
            </div>
            <div class="form-group col-4">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Número de serie</label>
                <input class="form-control" type="text" name="serie" pattern="[A-Z0-9]{13,13}[0-9]{4,4}" maxlength="17" title="El número de serie debe ser de 17 caracteres y los ultimos 4 deben ser numericos" v-model="gnp.vehiculo.serie" required>
            </div>
            <div class="form-group col-4">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Placas</label>
                <input class="form-control" type="text" name="placas" v-model="gnp.vehiculo.placas" required maxlength="10">
            </div>
            <div class="form-group col-12 col-md-4" v-if="cliente.uso_auto == 'Servicio Particular'">
                <label for="uso" class="control-label"><i class="fas fa-asterisk"></i> Uso vehiculo</label>
                <select name="uso" class="form-control" v-model="gnp.vehiculo.uso" required>
                    <option value="">Seleccione su uso</option>
                    <option value="01">Particular</option>
                </select>
            </div>
            <div class="form-group col-12 col-md-4" v-else >
                <label for="uso" class="control-label"><i class="fas fa-asterisk"></i> Uso vehiculo</label>
                <select name="uso" class="form-control" v-model="gnp.vehiculo.uso" required>
                    <option value="">Seleccione su uso</option>
                    <option v-for="uso in usos" :value="uso.CLAVE">{{uso.NOMBRE}}</option>
                </select>
            </div>
            <div class="form-group col-12 col-md-4">
                <label for="uso" class="control-label"><i class="fas fa-asterisk"></i> Estado de circulación</label>
                <select name="estadoCirculacion" class="form-control" v-model="gnp.vehiculo.estadoCirculacion" required>
                    <option value="">Seleccione un estado</option>
                    <option v-for="estadoCirculacion in estadosCirculacion" :value="estadoCirculacion.CLAVE">{{estadoCirculacion.NOMBRE}}</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-3">
                <h4>Datos de pago:</h4>
            </div>
            <div class="form-group col-12 col-md-4">
                <label for="periodicidad" class="control-label"><i class="fas fa-asterisk"></i> Periodicidad de Pago:</label>
                <select name="periodicidad" class="form-control" v-model="gnp.pago.periodicidad" required>
                    <option value="">Seleccione la periodicidad</option>
                    <option value="A">Anual</option>
                    <option value="S">Semestral</option>
                    <option value="T">Trimestral</option>
                     <option value="M">Mensual</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-md">Enviar</button>
            </div>
            <div class="m-2 ml-2 flex-shrink-1 d-flex justify-content-right">
                <i class="fa fa-asterisk" aria-hidden="true"></i> Campos Obligatorios
            </div>
        </div>
    </form>
</template>

<script>
    export default {
        props:[
            'cliente',
            'cotizacion',
            'img'
        ],
        data(){
            return {
                gnp: {
                    cliente: {
                        tipo_persona: "1",
                        nombre:"",
                        nombre_c:"",
                        apepat_c:"",
                        apemat_c:"",
                        rfc_c:"",
                        f_nac_c:"",
                        sexo_c: "",
                        estadoCivil_c: "",

                        apepat:"",
                        apemat:"",
                        rfc:"",
                        curp:"",
                        f_nac:"",
                        f_const:"",
                        sexo: "",
                        estadoCivil: "",
                        calle:"",
                        num_int:"",
                        num_ext:"",
                        colonia:"",
                        municipioClave: "",
                        municipioNombre: "",
                        estadoClave: "",
                        estadoNombre: "",
                        codigo_postal:"",
                        pais:"MEX",
                        telefono:"",
                        correo:"",
                        nacionalidad:"MEX",
                        tipoVia: "",
                        tipo_pago:'Referenciado',
                        estadoCirculacion: "",
                    },
                    vehiculo:{
                        placas: "",
                        serie: "",
                        motor: "",
                        modelo: "",
                        uso: "",
                    },
                    pago: {
                        periodicidad: ""   ,
                    },
                },
                colonias: [],
                usos: [],
                estadosCirculacion: [],
                tiposVia: [],
                csrf: "",
                maxDate: null,
            }
        },
        watch: {
            'gnp.cliente.codigo_postal':function(new_value,old_value){
                if(new_value.length === 5)
                    this.getDatosDomicilio(new_value);
            },
            'gnp.cliente.tipo_persona':function (new_value,old_value) {
                this.gnp.cliente.nombre = this.cliente.nombre;
                this.gnp.cliente.apepat = this.cliente.appaterno;
                this.gnp.cliente.apemat = this.cliente.apmaterno;
                this.gnp.cliente.codigo_postal = this.cliente.cp; 
                this.gnp.cliente.f_nac = this.cliente.f_nac;
                this.gnp.cliente.correo = this.cliente.email;
                this.gnp.cliente.telefono = this.cliente.telefono; 

                if (this.cliente.sexo=="Hombre") {
                    this.gnp.cliente.sexo ='M';
                }else{
                    this.gnp.cliente.sexo ='F';
                }
                if(this.cliente.uso_auto =="Servicio Particular"){
                    this.gnp.vehiculo.uso = '01';
                }
               this.gnp.cliente.estadoCirculacion =this.gnp.cliente.estadoClave ;
               if (this.gnp.cliente.tipo_persona =='M') {
                     this.gnp.cliente.nombre_c = this.cliente.nombre;
                     this.gnp.cliente.apepat_c = this.cliente.appaterno;
                     this.gnp.cliente.apemat_c = this.cliente.apmaterno;
                     this.gnp.cliente.f_nac_c =  this.cliente.f_nac;
                      if (this.cliente.sexo=="Hombre") {
                             this.gnp.cliente.sexo_c ='M';
                         }else{
                             this.gnp.cliente.sexo_c ='F';
                         }
               }

            }
        },
        methods:{
            'getDatosDomicilio':function(cp){
                let url='./api/domicilio-gnp/' + cp;
                axios.get(url).then(res=>{
                    console.log('-- OK --', res);
                    this.gnp.cliente.estadoClave          = res.data.estadoGNP.CLAVE;
                    this.gnp.cliente.estadoNombre         = res.data.estadoGNP.NOMBRE;
                    this.gnp.cliente.municipioClave       = res.data.municipioGNP.CLAVE;
                    this.gnp.cliente.municipioNombre      = res.data.municipioGNP.NOMBRE;
                    if (res.data.colonias.length)
                        this.colonias                     = res.data.colonias;
                    else
                        this.colonias                     = [res.data.colonias];
                }).catch(err=>{
                    alert('El Código Postal es incorrecto favor de verificar.');
                    console.log(err)
                })
            },
            'getUsoVehiculos':function(){
                let url = `./api/usos-vehiculo-gnp`;
                axios.get(url).then(res=>{
                    this.usos = res.data.usos;
                }).catch(err=>{
                    console.log(err);
                });
            },
            'getEstadosCirculacion':function(){
                let url = `./api/estados-circulacion-gnp`;
                axios.get(url).then(res=>{
                    this.estadosCirculacion = res.data.estadosCirculacion;
                }).catch(err=>{
                    console.log(err);
                });
            },
            'getTiposVia':function(){
                let url = `./api/tipos-via-gnp`;
                axios.get(url).then(res=>{
                    this.tiposVia = res.data.tiposVia;
                }).catch(err=>{
                    console.log(err);
                });
            },
            'sendGNP':function(){
                console.log('Enviado');
            },
        },
        mounted() {
            console.log('Component mounted.');
            console.log('CLIENTE', this.cliente);
            console.log('Cotizacion', this.cotizacion);
            this.csrf = document.head.querySelector('meta[name="csrf-token"]').content;
            this.getUsoVehiculos();
            this.getEstadosCirculacion();
            this.getTiposVia();
            this.maxDate = new Date().toISOString().split('T')[0];
        },
        computed:{
            'edad':function(){
                
                    let fecha_c = this.gnp.cliente.f_nac_c.split('-');
                
                    let fecha = this.gnp.cliente.f_nac.split('-');
                
                
                
                let edad = 0;
                if (fecha.length > 1){
                    const hoy = new Date();
                    fecha = new Date(fecha[0], fecha[1] - 1, fecha[2]);
                    edad = hoy.getFullYear() - fecha.getFullYear();
                    const m = hoy.getMonth() - fecha.getMonth();

                    if (m < 0 || (m === 0 && hoy.getDate() < fecha.getDate())) {
                        edad--;
                    }
                }
                if (fecha_c.length > 1){
                    const hoy = new Date();
                    fecha_c = new Date(fecha_c[0], fecha_c[1] - 1, fecha_c[2]);
                    edad = hoy.getFullYear() - fecha_c.getFullYear();
                    const m = hoy.getMonth() - fecha_c.getMonth();

                    if (m < 0 || (m === 0 && hoy.getDate() < fecha_c.getDate())) {
                        edad--;
                    }
                }

                return edad;

            }
        }
    }
</script>