<?php

namespace App\Models\registro_tributario;

use Illuminate\Database\Eloquent\Model;

class Predios_contribuyentes extends Model
{
    public $timestamps = false;
    protected $table = 'adm_tri.predios_contribuyentes';
    protected $primaryKey='id_pred_contri';
}
