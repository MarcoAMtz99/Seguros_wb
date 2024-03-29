<style>
	body {
	  font-family: "Roboto", helvetica, arial, sans-serif;
	  font-size: 16px;
	  font-weight: 400;
	  text-rendering: optimizeLegibility;
	}

	div.table-title {
	   display: block;
	  margin: auto;
	  max-width: 600px;
	  padding:5px;
	  width: 100%;
	}

	.table-title h3 {
	   color: #fafafa;
	   font-size: 30px;
	   font-weight: 400;
	   font-style:normal;
	   font-family: "Roboto", helvetica, arial, sans-serif;
	   text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1);
	   text-transform:uppercase;
	}


	/*** Table Styles **/

	.table-fill {
	  background: white;
	  border-radius:3px;
	  border-collapse: collapse;
	  height: 320px;
	  margin: auto;
	  max-width: 600px;
	  padding:5px;
	  width: 100%;
	  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
	  animation: float 5s infinite;
	}
	 
	th {
	  color:#D5DDE5;;
	  background:#1b1e24;
	  border-bottom:4px solid #9ea7af;
	  border-right: 1px solid #343a45;
	  font-size:16px;
	  font-weight: 100;
	  padding:24px;
	  text-align:left;
	  text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
	  vertical-align:middle;
	}

	th:first-child {
	  border-top-left-radius:3px;
	}
	 
	th:last-child {
	  border-top-right-radius:3px;
	  border-right:none;
	}
	  
	tr {
	  border-top: 1px solid #C1C3D1;
	  border-bottom-: 1px solid #C1C3D1;
	  color:#666B85;
	  font-size:12px;
	  font-weight:normal;
	  text-shadow: 0 1px 1px rgba(256, 256, 256, 0.1);
	}
	 
	tr:first-child {
	  border-top:none;
	}

	tr:last-child {
	  border-bottom:none;
	}
	 
	tr:nth-child(odd) td {
	  background:#EBEBEB;
	}

	tr:last-child td:first-child {
	  border-bottom-left-radius:3px;
	}
	 
	tr:last-child td:last-child {
	  border-bottom-right-radius:3px;
	}
	 
	td {
	  background:#FFFFFF;
	  padding:20px;
	  text-align:left;
	  vertical-align:middle;
	  font-weight:300;
	  font-size:18px;
	  text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1);
	  border-right: 1px solid #C1C3D1;
	}

	td:last-child {
	  border-right: 0px;
	}

	th.text-left {
	  text-align: left;
	}

	th.text-center {
	  text-align: center;
	}

	th.text-right {
	  text-align: right;
	}

	td.text-left {
	  text-align: left;
	}

	td.text-center {
	  text-align: center;
	}

	td.text-right {
	  text-align: right;
	}

</style>
<h1>Bienvenido a {{ config('app.name') }} {{$cliente->nombre}} {{$cliente->appaterno}} {{$cliente->apmaterno}}:</h1>

La cotización de tú auto {{$cliente->auto->marca->descripcion}} {{$cliente->auto->submarca->descripcion}} {{$cliente->auto->submarca->anio}} se guardo en nuestro sistema con este folio:

{{$cliente->cotizacion}}


<a href="url('/')?"cotizacion=".$cliente->cotizacion" class="btn btn-primary">Ver cotización</a>

Los datos de tu cotizacion con  <strong>QUALITAS</strong> son los siguientes:

<table class="table-fill">
	<thead>
		<tr>
			<th class="text-center">Prima Total</th>
			<th class="text-center">Daños Materiales</th>
			<th class="text-center">Robo Total </th>
			<th class="text-center">Responsabilidad Civil</th>
			<th class="text-center">Gastos Médicos</th>
			<th class="text-center">Legal</th>
			<th class="text-center">Vial</th>
			<th class="text-center">Otras Coberturas</th>
		</tr>
	</thead>
	<tbody class="table-hover">
		<tr>
			<td class="text-center">ANUAL: {{$cotizacion[0]['Primas']['PrimaTotal'] }} <br>
			SEMESTRAL: 1er pago: {{$cotizacion[1]['Recibos'][0]['PrimaTotal'] }}  <br>
			Subsecuente x 1:{{$cotizacion[1]['Recibos'][1]['PrimaTotal'] }} <br>


			TRIMESTRAL: 1er pago: {{$cotizacion[3]['Recibos'][0]['PrimaTotal'] }}<br>
			Subsecuente x 3: {{$cotizacion[3]['Recibos'][1]['PrimaTotal'] }}<br>


			MENSUAL: 1er pago: {{$cotizacion[2]['Recibos'][0]['PrimaTotal'] }}<br>
			Subsecuente x 11: {{$cotizacion[2]['Recibos'][1]['PrimaTotal'] }} </td>
			<td class="text-center"> {{$cotizacion[0]['Coberturas'][0]['SumaAsegurada'] }}</td>
			<td class="text-center"> {{$cotizacion[0]['Coberturas'][1]['SumaAsegurada'] }}</td>
			<td class="text-center">{{$cotizacion[0]['Coberturas'][2]['SumaAsegurada'] }}</td>
			<td class="text-center">{{$cotizacion[0]['Coberturas'][3]['SumaAsegurada'] }}</td>
			<td class="text-center">{{$cotizacion[0]['Coberturas'][5]['SumaAsegurada'] }}</td>
			<td class="text-center">{{$cotizacion[0]['Coberturas'][7]['SumaAsegurada'] }}</td>
			<td class="text-center">{{$cotizacion[0]['Coberturas'][4]['tipo'] }} : {{$cotizacion[0]['Coberturas'][4]['SumaAsegurada'] }}</td>
		</tr>
	</tbody>
</table> 

Gracias,<br>
{{ config('app.name') }}
