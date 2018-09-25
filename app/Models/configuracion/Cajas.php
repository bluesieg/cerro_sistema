<?php

namespace App\Models\configuracion;

use Illuminate\Database\Eloquent\Model;

class Cajas extends Model
{
    public $timestamps = false;
    protected $table = 'tesoreria.cajas';
    protected $primaryKey='id_caj';
}
