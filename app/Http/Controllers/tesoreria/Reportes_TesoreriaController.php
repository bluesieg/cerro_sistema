<?php

namespace App\Http\Controllers\tesoreria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\DatesTranslator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;


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
         if($tip=='2')
        {
            return $this->rep_por_tributo($request);
        }
       
    }
    
    

      public function rep_por_partida(Request $request)
    {
        //gonzalo
        $fechainicio = $request['ini'];
        $fechafin = $request['fin'];
        $sql=DB::table('presupuesto.vw_partida_presupuestal')->whereBetween('fecha', [$fechainicio, $fechafin])->orderBy('codigo','asc')->get();
        
       
        
        if(count($sql)>0)
        {
            $view =  \View::make('tesoreria.reportes.rep_por_partida', compact('sql','fechainicio','fechafin'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("PRUEBA".".pdf");
        }
        else
        {
            return 'NO HAY RESULTADOS';
        }
        
    }
     public function rep_por_tributo(Request $request)
    {
        //gonzalo
        $id_tributo = $request['id_tributo'];
        $fechainicio = $request['ini'];
        $fechafin = $request['fin'];
       // $sql=DB::table('presupuesto.vw_por_tributo')->where('id_tributo',$id_tributo) ->whereBetween('fecha', [$fechainicio, $fechafin])->orderBy('fecha','asc')->get();
        $sql = DB::select(" select  fecha,descrip_tributo, sum(total) as total from presupuesto.vw_por_tributo where id_tributo='$id_tributo' and fecha between '$fechainicio' and '$fechafin' group by fecha,descrip_tributo  order by fecha asc" );
        
        if(count($sql)>0)
        {
            $view =  \View::make('tesoreria.reportes.rep_por_tributo', compact('sql','fechainicio','fechafin'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("PRUEBA".".pdf");
        }
        else
        {
            return 'NO HAY RESULTADOS';
        }
        
    }
    
    function autocompletar_tributos() {
        $Consulta = DB::table('presupuesto.sub_proced_tributos')->get();
        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();
            $Lista->value = $Datos->id_tributo;
            $Lista->label = trim($Datos->descrip_tributo);
            array_push($todo, $Lista);
        }
        return response()->json($todo);
    }
}
