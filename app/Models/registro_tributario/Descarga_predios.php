<?php

namespace App\Models\registro_tributario;

use Illuminate\Database\Eloquent\Model;

class Descarga_predios extends Model
{
    public $timestamps = false;
    protected $table = 'transferencias.transferencias';
    protected $primaryKey='id_trans';
}
