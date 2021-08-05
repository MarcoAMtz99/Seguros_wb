@extends('layouts.app')
@section('content')



			<form action="{{ route('año') }}" method="POST">

				{{ csrf_field() }}
			<input type="text" id="año" name="año" placeholder="AÑO MODELO">
			<input type="text" id="submarca" name="submarca" placeholder="submarca">
			<button type="submit">Enviar</button>
			</form>		

			@if(isset($modelos))
				

				
				<table id="table_id" style="width:100% ; background: white;">
						  <tr>
						   
						    <th>ARMADORA</th>
						    <th>MODELO</th>
						      <th>CARROCERIA</th>
						       <th>VERSION</th>
						  </tr>
						 
						@foreach($modelos["ELEMENTOS"] as $key)
						@if($modelos["ELEMENTO"][3]["VALOR"] === $submarca)
						 <tr>
							 <td>{{$key["ELEMENTO"][1]["VALOR"]}} </td>
							  <td>{{$key["ELEMENTO"][2]["VALOR"]}} </td>
							   <td>{{$key["ELEMENTO"][3]["VALOR"]}} </td>
							    <td>{{$key["ELEMENTO"][4]["VALOR"]}} </td>

							</tr>
							@endif
							@endforeach
						  
					
						</table>
			@endif


@endsection