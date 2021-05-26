<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class colonias extends Model
{
    //
    protected $table="colonias";

    protected $fillable=[
    	'id',
        'cp',
        'colonia',
        'descripcion'
    ];
}
