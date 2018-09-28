<?php

namespace App\Models\ordenanzas;

use Illuminate\Database\Eloquent\Model;

class orde_arbitrios extends Model
{
    public $timestamps = false;
    protected $table = 'ordenanzas.orde_arbitrios';
    protected $primaryKey='id_orde_arb';
}
