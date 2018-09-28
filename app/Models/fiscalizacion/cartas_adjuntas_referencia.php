<?php

namespace App\Models\fiscalizacion;

use Illuminate\Database\Eloquent\Model;

class cartas_adjuntas_referencia extends Model
{
    public $timestamps = false;
    protected $table = 'fiscalizacion.cartas_adjuntas_referencia';
    protected $primaryKey='id_car_adj_ref';
}
