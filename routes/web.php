<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
})->name('index');
Route::get('/dev-envio', function(){
	return view('prueba');
});
Route::get('prueba', 'GNPController@prueba');
Route::post('sendGS','GeneralSegurosController@sendGS');
Route::post('sendQua','QualitasController@emitirPoliza');
Route::post('sendANA','API\AnaController@emitirPoliza');
Route::post('sendGNP','GNPController@emitirPoliza');
Route::get('pago','GeneralSegurosController@vista');
Route::get('acerca_nosotros',function(){
	return view('static.acerca');
});
Route::get('contacto',function(){
	return view('static.contacto');
});
Route::get('preguntas',function(){
	return view('static.preguntas');
});
Route::get('noticias',function(){
	return view('static.noticias');
});
Route::get('aviso',function(){
	return view('static.aviso');
});

Route::get('script', 'ScriptController@execute');