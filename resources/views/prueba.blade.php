@extends('layouts.app')
@section('content')



			<form action="{{ route('año') }}">

				{{ csrf_field() }}
			<input type="text" id="año" name="año" placeholder="AÑO MODELO">
			<button type="submit">Enviar</button>
			</form>		

@endsection