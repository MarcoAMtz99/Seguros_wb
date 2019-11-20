<template>
    <form  @submit="sendGNP" method="POST" action="./sendGNP">
        <input type="hidden" name="_token" :value="csrf" />
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
                    <input class="form-check-input" type="radio" name="tipo_persona" id="radioF" v-model="gnp.cliente.tipo_persona" value="1" required="" checked>
                    <label class="form-check-label" for="radioF">
                     Fisica
                    </label>
                </div>
                <div class="form-check col-12">
                    <input class="form-check-input" type="radio" name="tipo_persona" id="radioM" v-model="gnp.cliente.tipo_persona" value="2">
                    <label class="form-check-label" for="radioM">
                     Moral
                    </label>
                </div>
            </div>
        </div>
        <div class="row" v-if="gnp.cliente.tipo_persona == '1'">
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
        <div class="row" v-if="gnp.cliente.tipo_persona == '2'">
            <div class="form-group col-12">
                <label class="control-label">
                    <i class="fa fa-asterisk" aria-hidden="true"></i> Razón Social
                </label>
                <input class="form-control" type="text" name="nombre" v-model="gnp.cliente.nombre" required>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-12 col-md-4">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Correo electrónico:</label>
                <input class="form-control" type="email" name="correo" v-model="gnp.cliente.correo" required>
            </div>
            <div class="form-group col-12 col-md-4">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Telefono</label>
                <input class="form-control" type="text" name="telefono" v-model="gnp.cliente.telefono" required>
            </div>
            <div class="form-group col-12 col-md-4" v-if="gnp.cliente.tipo_persona == '1'">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Fecha de nacimiento:</label>
                <input class="form-control" type="date" name="f_nac" v-model="gnp.cliente.f_nac" required>
            </div>
            <div class="form-group col-12 col-md-4" v-if="gnp.cliente.tipo_persona == '1'">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Edad:</label>
                <input class="form-control" type="text" name="edad" v-model="edad" required disabled="">
            </div>
            <div class="form-group col-12 col-md-4">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> R.F.C.:</label>
                <input class="form-control" type="text" name="rfc" v-model="gnp.cliente.rfc" required>
            </div>
            <div class="form-group col-12 col-md-4" v-if="gnp.cliente.tipo_persona == '1'">
                <label for="sexo" class="control-label"><i class="fas fa-asterisk"></i> Sexo</label>
                <select name="sexo" class="form-control" v-model="gnp.cliente.sexo" required>
                    <option value="">Seleccione su sexo</option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                </select>
            </div>
            <div class="form-group col-12 col-md-4">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Código Postal:</label>
                <input type="text" name="codigo_postal" class="form-control" v-model="gnp.cliente.codigo_postal" required maxlength="5">
            </div>
            <div class="form-group col-12 col-md-4">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Estado:</label>
                <input type="text" name="estado" class="form-control" v-model="gnp.cliente.estadoNombre" required disabled="">
            </div>
            <div class="form-group col-12 col-md-4">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Alcaldía o Municipio:</label>
                <input type="text" class="form-control" name="municipio_id" v-model="gnp.cliente.municipioNombre" required disabled="">
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
                        <option v-if="tipo.NOMBRE != ''" value="tipo.CLAVE">{{ tipo.NOMBRE }}</option>
                    </template>
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
                <input class="form-control" type="text" name="serie" pattern="[A-Z0-9]{13,13}[0-9]{4,4}" title="El número de serie debe ser de 17 caracteres y los ultimos 4 deben ser numericos" v-model="gnp.vehiculo.serie" required>
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
                <select name="uso" class="form-control" v-model="gnp.vehiculo.uso" required>
                    <option value="">Seleccione un estado</option>
                    <option v-for="estadoCirculacion in estadosCirculacion" :value="estadoCirculacion.CLAVE">{{estadoCirculacion.NOMBRE}}</option>
                </select>
            </div>
            <input type="hidden" name="cotizacion" :value="cotizacion">
        </div>
        <div class="row">
            <div class="col-12 mt-3">
                <h4>Datos del Contratante:</h4>
            </div>
            <div class="form-group col-4">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Motor</label>
                <input class="form-control" type="text" name="motor" v-model="gnp.vehiculo.motor" required maxlength="20">
            </div>
            <div class="form-group col-4">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Número de serie</label>
                <input class="form-control" type="text" name="serie" pattern="[A-Z0-9]{13,13}[0-9]{4,4}" title="El número de serie debe ser de 17 caracteres y los ultimos 4 deben ser numericos" v-model="gnp.vehiculo.serie" required>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-3">
                <h4>Datos del Conductor:</h4>
            </div>
            <div class="form-group col-4">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Motor</label>
                <input class="form-control" type="text" name="motor" v-model="gnp.vehiculo.motor" required maxlength="20">
            </div>
            <div class="form-group col-4">
                <label class="control-label"><i class="fa fa-asterisk" aria-hidden="true"></i> Número de serie</label>
                <input class="form-control" type="text" name="serie" pattern="[A-Z0-9]{13,13}[0-9]{4,4}" title="El número de serie debe ser de 17 caracteres y los ultimos 4 deben ser numericos" v-model="gnp.vehiculo.serie" required>
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
                        apepat:"",
                        apemat:"",
                        rfc:"",
                        curp:"",
                        f_nac:"",
                        sexo: "",
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
                    },
                    vehiculo:{
                        placas: "",
                        serie: "",
                        motor: "",
                        modelo: "",
                        uso: "",
                    },
                },
                colonias: [],
                usos: [],
                estadosCirculacion: [],
                tiposVia: [],
                csrf: "",
            }
        },
        watch: {
            'gnp.cliente.codigo_postal':function(new_value,old_value){
                if(new_value.length === 5)
                    this.getDatosDomicilio(new_value);
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
        },
        computed:{
            'edad':function(){
                let fecha = this.gnp.cliente.f_nac.split('-');
                let edad = 0;
                if (fecha.length){
                    const hoy = new Date();
                    fecha = new Date(fecha[0], fecha[1] - 1, fecha[2]);
                    console.log('FECHA', fecha);
                    edad = hoy.getFullYear() - fecha.getFullYear();
                    const m = hoy.getMonth() - fecha.getMonth();

                    if (m < 0 || (m === 0 && hoy.getDate() < fecha.getDate())) {
                        edad--;
                    }
                }

                return edad;

            }
        }
    }
</script>