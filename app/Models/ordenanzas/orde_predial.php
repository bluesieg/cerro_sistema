<?php

namespace App\Models\ordenanzas;

use Illuminate\Database\Eloquent\Model;

class orde_predial extends Model
{
    public $timestamps = false;
    protected $table = 'ordenanzas.orde_predial';
    protected $primaryKey='id_orde_pred';
}
