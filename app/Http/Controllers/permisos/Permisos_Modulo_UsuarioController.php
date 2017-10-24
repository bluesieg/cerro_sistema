<?php

namespace App\Http\Controllers\permisos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\permisos\Permiso_Modulo_Usuario;

class Permisos_Modulo_UsuarioController extends Controller
{

    public function index()
    {
    }

    public function create(Request $request)
    {
        $permiso=new Permiso_Modulo_Usuario;
        $val=  $permiso::where("id_sub_mod","=",$request['submod'] )->where("id_usu","=",$request['usu'] )->first();
        $btn='btn_'.$request['tipo'];
        if(count($val)>=1)
        {
            $val->$btn=$request['val'];
            if($val->btn_new==0&&$val->btn_edit==0&&$val->btn_del==0&&$val->btn_imp==0&&$val->btn_imp==0)
            {
                return $this->destroy($val->id_permiso);
            }
            else
            {
                $val->save();
                return "edit_".$val->id_permiso;
            }
        }
        else
        {
            
            $permiso->id_sub_mod=$request['submod'];
            $permiso->id_usu=$request['usu'];
            $permiso->$btn=$request['val'];
            $permiso->save();
            return $permiso->id_permiso;
        }
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
        $permiso=new Permiso_Modulo_Usuario;
        $val=  $permiso::where("id_permiso","=",$id )->first();
        if(count($val)>=1)
        {
            $val->delete();
        }
        return "destroy ".$id;
    }
}
