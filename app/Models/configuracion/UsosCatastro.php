<?php

namespace App\Models\configuracion;

use Illuminate\Database\Eloquent\Model;

class UsosCatastro extends Model
{
    public $timestamps = false;
    protected $table = 'catastro.usos_predio';
    protected $primaryKey='id_uso';
}
