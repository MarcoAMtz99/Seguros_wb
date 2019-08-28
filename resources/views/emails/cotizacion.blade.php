@component('mail::message')
# Bienvenido a {{ config('app.name') }} {{$cliente->nombre}} {{$cliente->appaterno}} {{$cliente->apmaterno}}:

La cotización de tú auto {{$cliente->auto->marca->descripcion}} {{$cliente->auto->submarca->descripcion}} {{$cliente->auto->submarca->anio}} se guardo en nuestro sistema con este folio:

{{$cliente->cotizacion}}

@component('mail::button', ['url' => url("/")."?cotizacion=".$cliente->cotizacion])
Ver cotización
@endcomponent

{{-- Los datos de tu cotizacion son los siguientes:

| Elemento 				| Cantidad | Precio |
| :---------------------| :------: | -----: |
| Prima Total   		| 15       | 150€   |
| Daños Materiales      | 3250     | 23,65€ |
| Robo Total   			| 15       | 150€   |
| Responsabilidad Civil | 3250     | 23,65€ |
| Gastos Médicos   		| 15       | 150€   |
| Legal   				| 3250     | 23,65€ |
| Vial   				| 15       | 150€   |
| Otras Coberturas   	| 3250     | 23,65€ |
<table>
	<thead>
		<tr>
			<th>Elemento</th>
			<th>Cantidad</th>
			<th>Precio</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>TT</td>
			<td>HH</td>
			<td>JJ</td>
		</tr>
		<tr>
			<td>OO</td>
			<td>LL</td>
			<td>SS</td>
		</tr>
	</tbody>
</table> --}}

Gracias,<br>
{{ config('app.name') }}
@endcomponent
