@extends('layouts.app')
@section('content')



			<form action="{{ route('año') }}" method="POST">

				{{ csrf_field() }}
			<input type="text" id="año" name="año" placeholder="AÑO MODELO">
			<button type="submit">Enviar</button>
			</form>		

			@if(isset($modelos))
				

				
				<table style="width:100%">
						  <tr>
						   
						    <th>ARMADORA</th>
						    <th>MODELO</th>
						      <th>CARROCERIA</th>
						       <th>VERSION</th>
						  </tr>
						  <tr>
						@foreach($modelos["ELEMENTOS"] as $key)
						
							 <td>{{dd($key["ELEMENTO"][1]["VALOR"])}} </td>
							  <td>{{dd($key["ELEMENTO"][2]["VALOR"])}} </td>
							   <td>{{dd($key["ELEMENTO"][3]["VALOR"])}} </td>
							    <td>{{dd($key["ELEMENTO"][4]["VALOR"])}} </td>


							@endforeach
						  </tr>
						  <tr>
						    <td>Eve</td>
						    <td>Jackson</td>
						    <td>94</td>
						  </tr>
						</table>
			@endif


@endsection