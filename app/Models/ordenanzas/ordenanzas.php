<?php

namespace App\Models\ordenanzas;

use Illuminate\Database\Eloquent\Model;

class ordenanzas extends Model
{
    public $timestamps = false;
    protected $table = 'ordenanzas.ordenanzas';
    protected $primaryKey='id_orde';
}
