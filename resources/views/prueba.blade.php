@extends('layouts.app')
@section('content')



			<form action="{{ route('año') }}" method="POST">

				{{ csrf_field() }}
			<input type="text" id="año" name="año" placeholder="AÑO MODELO">
			<button type="submit">Enviar</button>
			</form>		

			@if(isset($modelos))
				{{dd($modelos)}}
			@endif


@endsection