<?php

namespace App\Models\fiscalizacion;

use Illuminate\Database\Eloquent\Model;

class multas extends Model
{
    public $timestamps = false;
    protected $table = 'fiscalizacion.multas';
    protected $primaryKey='id_multa';
}
