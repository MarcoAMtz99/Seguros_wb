@component('mail::message')
# Gracias por emitir con Autosegurodirecto

Ten un buen dia. {{$mensaje}}

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
