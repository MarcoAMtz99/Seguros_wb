@component('mail::message')
# Gracias por emitir con Autosegurodirecto

@if(isset($mensaje->nombre))
Ten un buen dia. {{$mensaje->nombre}} {{$mensaje->apepat}} {{$mensaje->apemat}}
@endif
@component('mail::button', ['url' => ''])
Button Text
@endcomponent
{{$mensaje}}
Thanks,<br>
{{ config('app.name') }}
@endcomponent
