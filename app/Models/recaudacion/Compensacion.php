<?php

namespace App\Models\recaudacion;

use Illuminate\Database\Eloquent\Model;

class Compensacion extends Model
{
    public $timestamps = false;
    protected $table = 'control_deuda.compensacion';
    protected $primaryKey='id';
}
