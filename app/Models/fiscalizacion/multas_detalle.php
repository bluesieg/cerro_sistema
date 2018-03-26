<?php

namespace App\Models\fiscalizacion;

use Illuminate\Database\Eloquent\Model;

class multas_detalle extends Model
{
    public $timestamps = false;
    protected $table = 'fiscalizacion.multas_detalle';
    protected $primaryKey='id_multa_det';
}
