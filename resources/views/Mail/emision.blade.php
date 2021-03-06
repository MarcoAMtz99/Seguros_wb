@if($aseguradora =="QA")
@component('mail::message')
# Gracias por emitir con Autosegurodirecto.com
QUALITAS SEGUROS
@if(isset($mensaje->nombre))
Ten un buen dia. {{$mensaje->nombre}} {{$mensaje->apepat}} {{$mensaje->apemat}}
@endif
@component('mail::button', ['url' => ''])
Button Text
@endcomponent
@if($aseguradora =="QA")

	@foreach ($mensaje as $key=>$links)

   			 @if ($key == 0)
                 (Certificado de Responsabilidad Civil)
                 {{$links}}
             @elseif($key == 1)
                 Recibo de cobro
                 {{$links}}
             @else
                 Póliza de seguro de Automóvil
                 {{$links}}
             @endif
	
	@endforeach

@endif


Gracias,<br>
{{ config('app.name') }}
@endcomponent
@endif


@if($aseguradora =="GS")
@component('mail::message')
# Gracias por emitir con Autosegurodirecto.com
GENERAL DE SEGUROS
@if(isset($mensaje->nombre))
Ten un buen dia. {{$mensaje->nombre}} {{$mensaje->apepat}} {{$mensaje->apemat}}
@endif
@component('mail::button', ['url' => ''])
Button Text
@endcomponent
@if($aseguradora =="GS")
    

    @foreach ($mensaje['return']['listaDocumentos']['SDTDocumentos.SDTDocumentosItem'] as $boton)

        {{$boton['nombre']}}
        {{$boton['liga']}}
                           
     @endforeach
   

@endif


Gracias,<br>
{{ config('app.name') }}
@endcomponent
@endif

@if($aseguradora =="ANA")
@component('mail::message')
# Gracias por emitir con Autosegurodirecto.com
ANA SEGUROS
@if(isset($mensaje->nombre))
Ten un buen dia. {{$mensaje->nombre}} {{$mensaje->apepat}} {{$mensaje->apemat}}
@endif
@component('mail::button', ['url' => ''])
Button Text
@endcomponent

@if($aseguradora =="ANA")
    @if(isset($mensaje->link))
    {{$mensaje->link}}
    @endif
    @if(isset($mensaje['link']))
    {{$mensaje['link']}}
    @endif

@endif


Gracias,<br>
{{ config('app.name') }}
@endcomponent
@endif




@if($aseguradora =="GNP")
@component('mail::message')
# Gracias por emitir con Autosegurodirecto.com
GNP
@if(isset($mensaje->nombre))
Ten un buen dia. {{$mensaje->nombre}} {{$mensaje->apepat}} {{$mensaje->apemat}}
@endif
@component('mail::button', ['url' => ''])
Button Text
@endcomponent
@if($aseguradora =="GNP")
    POLIZA
    {{$mensaje->RESULTADO->NOMBRE_DOCUMENTO}}
    {{$mensaje->RESULTADO->URL_DOCUMENTO}}
    

@endif


Gracias,<br>
{{ config('app.name') }}
@endcomponent
@endif