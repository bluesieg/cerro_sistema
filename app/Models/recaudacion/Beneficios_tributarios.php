<?php

namespace App\Models\recaudacion;

use Illuminate\Database\Eloquent\Model;

class Beneficios_tributarios extends Model
{
    public $timestamps = false;
    protected $table = 'configuracion.beneficios_tributarios';
    protected $primaryKey='id_bene_trib';
}
