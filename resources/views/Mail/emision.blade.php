@if($aseguradora =="QA")
@component('mail::message')
# Gracias por emitir con Autosegurodirecto.com

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
                 @component('mail::button', ['url' => $links])
             @elseif($key == 1)
                 Recibo de cobro
                  @component('mail::button', ['url' => $links])
             @else
                 Póliza de seguro de Automóvil
                  @component('mail::button', ['url' => $links])
             @endif
	
	@endforeach

@endif


Thanks,<br>
{{ config('app.name') }}
@endcomponent
@endif