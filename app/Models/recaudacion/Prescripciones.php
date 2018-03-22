<?php

namespace App\Models\recaudacion;

use Illuminate\Database\Eloquent\Model;

class Prescripciones extends Model
{
    public $timestamps = false;
    protected $table = 'prescripciones.prescripciones';
    protected $primaryKey='id_presc';
}
