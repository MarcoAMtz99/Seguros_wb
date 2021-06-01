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

	@foreach ($mensaje as $links)

    
    <button type="button"  href="{{$links}}"class="btn btn-default">{{$links}}</button>
	
	@endforeach

@endif


Thanks,<br>
{{ config('app.name') }}
@endcomponent
@endif