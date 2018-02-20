<?php

namespace App\Models\usuarios_web;

use Illuminate\Database\Eloquent\Model;

class Usuarios_web extends Model
{    
    public $timestamps = false;
    protected $table = 'web.usuarios_web';
    protected $primaryKey='id';
}
