<?php

namespace App\Http\Controllers\tesoreria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\DatesTranslator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Models\archivo\digitalizacion;
use App\Models\archivo\auditoria_digitalizacion;

class Reportes_TesoreriaController extends Controller
{
    use DatesTranslator;
    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_rep_teso' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        return view('tesoreria/vw_reportes_tesoreria', compact('menu','permisos','anio_tra'));
    }
     /////////// reportes ///////////
    
    public function ver_reporte_teso($tip,Request $request)
    {
        if($tip=='1')
        {
            return $this->rep_por_partida($request);
        }
       
    }
    
    

      public function rep_por_partida(Request $request)
    {
        //gonzalo
    }
}
