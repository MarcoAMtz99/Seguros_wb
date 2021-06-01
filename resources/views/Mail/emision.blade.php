@component('mail::message')
# Gracias por emitir con Autosegurodirecto.com

@if(isset($mensaje->nombre))
Ten un buen dia. {{$mensaje->nombre}} {{$mensaje->apepat}} {{$mensaje->apemat}}
@endif
@component('mail::button', ['url' => ''])
Button Text
@endcomponent
@if($aseg =="QA")

	@foreach ($mensaje as $links)

    @component('mail::button', ['url' =>$links])
	
	@endforeach

@endif


Thanks,<br>
{{ config('app.name') }}
@endcomponent
