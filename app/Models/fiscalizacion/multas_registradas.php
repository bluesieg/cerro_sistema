<?php

namespace App\Models\fiscalizacion;

use Illuminate\Database\Eloquent\Model;

class multas_registradas extends Model
{
    public $timestamps = false;
    protected $table = 'fiscalizacion.multas_registradas';
    protected $primaryKey='id_multa_reg';
}
