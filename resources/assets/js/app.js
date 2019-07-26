
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
// Vue.filter('capitalize', function (value) {
//   if (!value) return ''
//   value = value.toString()
//   return value.charAt(0).toUpperCase() + value.slice(1)
// });
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('cotizacion', require('./components/CotizacionComponent.vue').default);
Vue.component('formulario',require('./components/FormComponent.vue').default);
Vue.component('polizas',require('./components/PolizasComponent.vue').default);
var emojis = require('emojis-list');
console.log("emoji",emojis[26]);
const app = new Vue({
    el: '#app',
    data:{
    	cliente: {
    		"cotizacion":null,
    		'uso_auto':'',
    		'marca_auto':"",
            'submarca_auto':"",
	    	'modelo_auto':"",
	    	'descripcion_auto':"",
	    	'cp':"",
            'cestado':"",
	    	'nombre':"",
	    	'appaterno':"",
	    	'apmaterno':"",
	    	'telefono':"",
	    	'email':"",
	    	'sexo':"",
	    	'f_nac':"",
            'ana': "",
            'qualitas': "",
            'gs': "",
    	},
        img:{
            anaImage:"./img/ana_logo.png",
            anaImageForm:"./img/ana_logo_alta.jpg",
            gsImage : "./img/GENERAL-DE-SEGUROS-LOGO.png",
            gsImageForm: "./img/general_seguros_form.png",
            quaImage : "./img/qua.png",
            quaImageForm: "./img/Imagotipo_Banner_SitioWebQuálitas.svg",
            quaImageBG: "./img/qualitas_form.png"
        },
    	getcotizacion:{
    		type: Boolean,
    		value:false
    	},
        alert: {
            message:"",
            class:""
        },
        
        cotizacion: {},

    },
    
});

