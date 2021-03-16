@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
	<div class="col-10 col-sm-6 my-5 mx-0 p-0 bg-light rounded shadow-lg">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="text-center">
                            CONTACTANOS
                        </h3>
                    <form id="miForm" name="fvalida" class="contact-form"  method="post" action="{{ url('/contacto') }}" role="form"   onsubmit="return validar()">
                        <!-- Nested Row Starts -->
                            <div class="row">
                            <!-- First Name Filed Starts -->
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="fname" class="sr-only">Nombre: </label>
                                        <input type="text" class="form-control flat" name="fname" id="fname" required="required" placeholder="Nombre">
                                    </div>
                                </div>
                            <!-- First Name Filed Ends -->
                            <!-- Last Name Filed Starts -->
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="lname" class="sr-only">Apellido: </label>
                                        <input type="text" class="form-control flat" name="lname" id="lname" required="required" placeholder="Apellido">
                                    </div>
                                </div>
                            <!-- Last Name Filed Ends -->
                            
                            
                            <!-- Whatsapp Filed Starts -->
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="Whats" class="sr-only">WhatsApp: </label>
                                        <input type="text" class="form-control flat" name="Whats" id="Whats" required="required" placeholder="WhatsApp">
                                    </div>
                                </div>
                            <!-- Whatasapp Filed Ends -->
                            <!--telefono Filed Starts -->
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="Tel" class="sr-only">Teléfono: </label>
                                        <input type="text" class="form-control flat" name="Tel" id="Tel" required="required" placeholder="Teléfono">
                                    </div>
                                </div>
                            <!-- telefono Filed Ends -->
                        
                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="email" class="sr-only">Correo Electrónico: </label>
                                        <input type="text" class="form-control flat" name="email" id="email" required="required" placeholder="Mail">
                                    </div>
                                </div>

                                <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="message" class="sr-only">Mensaje: </label>
                                        <textarea class="form-control flat" rows="8" name="message" id="message" required="required" placeholder="Mensaje"></textarea>
                                    </div>
                                </div>
                            <!-- Message Filed Ends -->
                            <!-- Send Button Starts -->
                                <div class="col-sm-6 col-xs-12">
                                    <input type="submit" class="btn btn-secondary btn-big animation" value="Enviar" disabled="true">
                                </div>
                            <!-- Send Button Ends -->
                            </div>
                        <!-- Nested Row Ends -->
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection