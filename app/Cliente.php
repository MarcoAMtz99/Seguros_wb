<?php

namespace App;

use App\Mail\CreateCotizacion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class Cliente extends Model
{
    //

    protected $table="cliente";

    protected $fillable=[
    	'uso_auto',
    	// 'marca_auto',
    	// 'modelo_auto',
    	// 'descripcion_auto',
     //    'tipo_auto',
     //    'c_amis',
        'cp',
        'cestado',
        'nombre',
        'appaterno',
        'apmaterno',
        'telefono',
        'email',
        'sexo',
        'f_nac',
        'ana',
        'gs',
        'qualitas'
    ];

    protected $hidden=[
        'created_at',
        'updated_at',
    ];

    public function auto()
    {
        return $this->hasOne('App\Auto');
    }

    public function generarCotizacion(){
        return substr(md5(uniqid(mt_rand(), true)), 0, 10);
    }

    public function emailCotizacion($cotizacion){
        Mail::to($this->email)->send(new CreateCotizacion($this, $cotizacion));
        //return (new CreateCotizacion($this, $cotizacion));
    }


    public function getMenor30Attribute()
    {
        $nacimiento = Carbon::parse($this->f_nac);
        $edad = Carbon::createFromDate($nacimiento->year,$nacimiento->month,$nacimiento->day)->age;
        if ($edad >= 30) {
            return 1;
        } else {
            return 0;
        }

    }

    public function getTipoServicioAttribute()
    {
        $uso = $this->uso_auto;
        // $codigo;
        // dd($uso);
        if($uso == "Servicio Público"){
            return "COMERCIAL";
        }
        if($uso == "Servicio Particular"){
            return "PARTICULAR";
        }
    }

    public function getUsoAttribute()
    {
        $uso = $this->uso_auto;
        // $codigo;
        // dd($uso);
        if($uso == "Servicio Público"){
            return "8";
        }
        if($uso == "Servicio Particular"){
            return "1";
        }
        // return $codigo;
    }

    public function getServicioAttribute()
    {
        $uso = $this->uso_auto;
        // $codigo;
        // dd($uso);
        if($uso == "Servicio Público"){
            return "2";
        }
        if($uso == "Servicio Particular"){
            return "1";
        }
        
        // return $codigo;
    }



   

}
