@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
	<div class="col-10 col-sm-6 my-5 mx-0 p-0 bg-light rounded shadow-lg">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link disabled" id="paso1-tab" data-toggle="tab" href="#paso1" role="tab" aria-controls="paso1" aria-selected="true">Datos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" id="paso2-tab" data-toggle="tab" href="#paso2" role="tab" aria-controls="paso2" aria-selected="false">Cotización</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" id="paso3-tab" data-toggle="tab" href="#paso3" role="tab" aria-controls="paso3" aria-selected="false">Auto</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" id="paso4-tab" data-toggle="tab" href="#paso4" role="tab" aria-controls="paso4" aria-selected="false">Pago</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="paso4" role="tabpane4" aria-labelledby="paso4-tab">
            	<div class="row m-3">
            		<div class="col-11 col-md-5 m-2 mt-4 p-2 d-none d-sm-block">
            			<h3 class="mt-3 ml-3">Gracias por utilizar GNP Seguros</h3>
            		</div>
                    <div class="col-12 col-md-5 m-2 p-2">
                        <img src="{{ asset('./img/gnp.png') }}" height="100%" width="100%">
                    </div>
					<div class="col-6">
						<h5>Su poliza se guardo con la siguiente información:</h5>
					</div>
					{{ dd($response) }}
					{{-- <div class="col-6">
						<label class="control-label">Número de poliza:</label>
						@if( isset($response['EMISION']) )
							<p>{{ $response['EMISION']["SOLICITUD"]["NUM_POLIZA"] }}</p>
						@else
							0000000
						@endif
					</div>
					<div class="col-6">
						<label class="control-label">Adicionalmente le llegara al correo proporcionado documentos anexos con más información</label>
					</div>
					@if($response['EMISION'])
					<div class="col-12">
						<div class="d-flex justify-content-center mt-4">
							<a class="btn btn-primary btn-lg" href="{{$response['EMISION']["SOLICITUD"]["DOCUMENTOS"]["DOCUMENTO"]["URL_DOCUMENTO"]}}" target="_blank" role="button">
								Poliza
                            </a>
						</div>
					</div>
					@else
					<div class="col-12">
						<div class="d-flex justify-content-center mt-4">
							{{ dd($response) }}
						</div>
					</div>
					@endif --}}
            	</div>
            </div>
        </div>
    </div>
</div>
@endsection