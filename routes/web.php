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
Route::get('prueba', 'PruebaController@envio');
Route::post('sendGS','GeneralSegurosController@sendGS');
Route::post('sendQua','QualitasController@emitirPoliza');
Route::post('sendANA','API\AnaController@emitirPoliza');
Route::get('pago','GeneralSegurosController@vista');
Route::get('acerca_nosotros',function(){
	return view('static.acerca');
});