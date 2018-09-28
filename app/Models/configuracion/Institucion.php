<?php

namespace App\Models\configuracion;

use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{
    public $timestamps = false;
    protected $table = 'maysa.institucion';
    protected $primaryKey='ide_inst';
}
