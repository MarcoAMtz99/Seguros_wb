@extends('layouts.app')
@section('content')



			<form action="{{ route('año') }}" method="POST">

				{{ csrf_field() }}
			<input type="text" id="año" name="año" placeholder="AÑO MODELO">
			<button type="submit">Enviar</button>
			</form>		

			@if(isset($modelos))
				

				@foreach($modelos["ELEMENTOS"] as $key)
				 <th>{{dd($key)}} </th>
				@endforeach
				<table style="width:100%">
						  <tr>
						   
						    <th>DOS</th>
						    <th>TRES</th>
						  </tr>
						  <tr>
						    <td>Jill</td>
						    <td>Smith</td>
						    <td>50</td>
						  </tr>
						  <tr>
						    <td>Eve</td>
						    <td>Jackson</td>
						    <td>94</td>
						  </tr>
						</table>
			@endif


@endsection