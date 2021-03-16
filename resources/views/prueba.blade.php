@extends('layouts.app')
@section('content')
<form action="prueba">
	<input type="submit" name="" value="Enviar">
</form>

{{-- <div class="tab-pane fade show active" id="paso1" role="tabpanel" aria-labelledby="paso1-tab">


<div >
	<h2>Hola estas son la spruebas</h2>
	<example-component></example-component>
<example-component></example-component>
</div>
<div class="tab-pane fade show active" id="paso1" role="tabpanel" aria-labelledby="paso1-tab">

	<cotizacion v-bind:cliente="cliente" :img="img" :getcotizacion="getcotizacion" :alert="alert"></cotizacion>
</div>
<div class="tab-pane fade" id="paso2" role="tabpanel" aria-labelledby="paso2-tab">
	<polizas v-bind:cliente="cliente" :img="img" :alert="alert" :getcotizacion="getcotizacion" :cotizacion="cotizacion" @emitirgs="cotizacion=$event" @emitirqua="cotizacion=$event" @emitirana="cotizacion=$event" @emitirgnp="cotizacion=$event">
</div>
<div class="tab-pane fade" id="paso3" role="tabpanel" aria-labelledby="paso3-tab">
	<formulario :cotizacion="cotizacion" :img="img" :cliente="cliente" :alert="alert"></formulario>

</div> --}}
<example-component></example-component>
<example-component></example-component>
<example-component></example-component>
<example-component></example-component>
<div class="tab-pane fade" id="paso3" role="tabpanel" aria-labelledby="paso3-tab">
	<formulario :cotizacion="cotizacion" :img="img" :cliente="cliente" :alert="alert"></formulario>

@endsection