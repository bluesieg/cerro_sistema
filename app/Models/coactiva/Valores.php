<?php

namespace App\Models\coactiva;

use Illuminate\Database\Eloquent\Model;

class Valores extends Model
{
    public $timestamps = false;
    protected $table = 'coactiva.valores';
    protected $primaryKey='id_val';
}
