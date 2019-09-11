<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PruebaController extends Controller
{
    public function envio()
    {
    	$username = "+525539361734";
		$debug = true;

		// Create a instance of Registration class.
		$r = new Registration($username, $debug);

		$r->codeRequest('sms'); // could be 'voice' too
		//$r->codeRequest('voice');
		dd($r);
    	//$client = new \Guzzle\Service\Client('https://wa.me/525539361734?text=Hola%20Autosegurodirecto.com.%20Estoy%20interesado%20en%20contratar%20un%20seguro%20con%20ustedes.');
    	$response = $client->get();
    	dd($response);
    }
}
