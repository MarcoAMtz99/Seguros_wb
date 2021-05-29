@component('mail::message')
# Gracias por emitir con www.autosegurosdirecto.com

Ten un buen dia.

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
