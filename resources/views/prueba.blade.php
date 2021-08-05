@extends('layouts.app')
@section('content')



			<form action="{{ route('año') }}" method="POST">

				{{ csrf_field() }}
			<input type="text" id="año" name="año" placeholder="AÑO MODELO">
			<button type="submit">Enviar</button>
			</form>		

			@if(isset($modelos))
				

				
				<table style="width:100% ; background: white;">
						  <tr>
						   
						    <th>ARMADORA</th>
						    <th>MODELO</th>
						      <th>CARROCERIA</th>
						       <th>VERSION</th>
						  </tr>
						 
						@foreach($modelos["ELEMENTOS"] as $key)
						 <tr>
							 <td>{{$key["ELEMENTO"][1]["VALOR"]}} </td>
							  <td>{{$key["ELEMENTO"][2]["VALOR"]}} </td>
							   <td>{{$key["ELEMENTO"][3]["VALOR"]}} </td>
							    <td>{{$key["ELEMENTO"][4]["VALOR"]}} </td>

							</tr>
							@endforeach
						  
						  <tr>
						    <td>Eve</td>
						    <td>Jackson</td>
						    <td>94</td>
						  </tr>
						</table>
			@endif


@endsection