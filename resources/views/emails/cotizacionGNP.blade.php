<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
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
	  background: red;
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

Los datos de tu cotizacion con <strong>GNP</strong> son los siguientes:

<table class="table table-dark">
	<thead class="thead-dark">
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
			<td class="text-center">ANUAL: Unico pago: ${{$cotizacion['PAQUETES']['PAQUETE']['TOTALES']['TOTAL_PRIMA'][0]['CONCEPTO_ECONOMICO'][10]['MONTO'] }} <br> 

			SEMESTRAL:1er pago: 
				${{$cotizacion['PAQUETES']['PAQUETE']['TOTALES']['TOTAL_PRIMA'][1]['CONCEPTO_ECONOMICO'][11]['MONTO'] }}
				<br>
				subsecuente x 1 : ${{$cotizacion['PAQUETES']['PAQUETE']['TOTALES']['TOTAL_PRIMA'][1]['CONCEPTO_ECONOMICO'][12]['MONTO'] }}
				<br>
			TRIMESTRAL:1er pago: 
				${{$cotizacion['PAQUETES']['PAQUETE']['TOTALES']['TOTAL_PRIMA'][2]['CONCEPTO_ECONOMICO'][11]['MONTO'] }}
				<br>
				subsecuente x 3 : ${{$cotizacion['PAQUETES']['PAQUETE']['TOTALES']['TOTAL_PRIMA'][2]['CONCEPTO_ECONOMICO'][12]['MONTO'] }}
				<br>
			MENSUAL:1er pago: 
				${{$cotizacion['PAQUETES']['PAQUETE']['TOTALES']['TOTAL_PRIMA'][3]['CONCEPTO_ECONOMICO'][11]['MONTO'] }}
				<br>
				subsecuente x 11 : ${{$cotizacion['PAQUETES']['PAQUETE']['TOTALES']['TOTAL_PRIMA'][3]['CONCEPTO_ECONOMICO'][12]['MONTO'] }}
				<br> 
			</td>
			<td class="text-center">DM PERDIDA TOTAL : 5% de la suma asegurada <br>
			DM PERDIDA PARCIAL : 5% de la suma asegurada</td>
			<td class="text-center">10% de la Suma Asegurada</td>
			<td class="text-center">Suma asegurada: <br>{{$cotizacion['PAQUETES']['PAQUETE']['COBERTURAS']['COBERTURA'][4]['SUMA_ASEGURADA'] }} <br> EXTENSION DE RC : {{$cotizacion['PAQUETES']['PAQUETE']['COBERTURAS']['COBERTURA'][7]['SUMA_ASEGURADA'] }} </td>
			<td class="text-center"> {{$cotizacion['PAQUETES']['PAQUETE']['COBERTURAS']['COBERTURA'][6]['SUMA_ASEGURADA'] }}</td>
			<td class="text-center"> {{$cotizacion['PAQUETES']['PAQUETE']['COBERTURAS']['COBERTURA'][5]['SUMA_ASEGURADA'] }}</td>
			<td class="text-center"> CLUB GNP:{{$cotizacion['PAQUETES']['PAQUETE']['COBERTURAS']['COBERTURA'][8]['SUMA_ASEGURADA'] }}</td>
			<td class="text-center"></td>
		</tr>
	</tbody>
</table> 

Gracias,<br>
{{ config('app.name') }}

</body>
</html>