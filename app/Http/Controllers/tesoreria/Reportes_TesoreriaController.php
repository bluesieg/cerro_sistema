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
        $agencias = DB::select('select id_caj,descrip_caja from tesoreria.cajas order by descrip_caja desc');
        return view('tesoreria/vw_reportes_tesoreria', compact('menu','permisos','anio_tra','agencias'));
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
        $caja = $request['caja'];
        $fechainicio = $request['ini'];
        $fechafin = $request['fin'];
        $institucion = DB::select('SELECT * FROM maysa.institucion');
        if($fechainicio != 0 && $fechafin != 0 && $caja == 0)
        {
            //$sql = DB::select("SELECT codigo_2,det_especifica,codigo_1,desc_espec_detalle,id_caj,descrip_caja,SUM(monto) as total  FROM presupuesto.vw_partida_presupuestal_3 where fecha between '$fechainicio' and '$fechafin' GROUP BY codigo_2,det_especifica,codigo_1,desc_espec_detalle,id_caj,descrip_caja order by codigo_2" );
            $sql = DB::table("presupuesto.vw_partida_presupuestal_3")->select('codigo_2','det_especifica','codigo_1','desc_espec_detalle','id_caj','descrip_caja',DB::raw('SUM(monto) as total'))->whereBetween('fecha', [$fechainicio, $fechafin])->groupBy('codigo_2','det_especifica','codigo_1','desc_espec_detalle','id_caj','descrip_caja')->orderBy('codigo_2')->get();
            if(count($sql)>0)
            {
                $aux='0';
                $view =  \View::make('tesoreria.reportes.rep_por_partida', compact('sql','fechainicio','fechafin','aux','caja','institucion'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4');
                return $pdf->stream("PRUEBA".".pdf");
            }
            else
            {
                return 'NO HAY RESULTADOS';
            }
        }
        else
        {
            //$sql = DB::select("SELECT codigo_2,det_especifica,codigo_1,desc_espec_detalle,id_caj,descrip_caja,SUM(monto) as total  FROM presupuesto.vw_partida_presupuestal_3 where id_caj='$caja' and fecha between '$fechainicio' and '$fechafin' GROUP BY codigo_2,det_especifica,codigo_1,desc_espec_detalle,id_caj,descrip_caja order by codigo_2" );
            $sql = DB::table("presupuesto.vw_partida_presupuestal_3")->select('codigo_2','det_especifica','codigo_1','desc_espec_detalle','id_caj','descrip_caja',DB::raw('SUM(monto) as total'))->whereBetween('fecha', [$fechainicio, $fechafin])->groupBy('codigo_2','det_especifica','codigo_1','desc_espec_detalle','id_caj','descrip_caja')->orderBy('codigo_2')->get();
            if(count($sql)>0)
            {
                $aux='0';
                $view =  \View::make('tesoreria.reportes.rep_por_partida', compact('sql','fechainicio','fechafin','aux','caja','institucion'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4');
                return $pdf->stream("PRUEBA".".pdf");
            }
            else
            {
                return 'NO HAY RESULTADOS';
            }
            
        }
        
        
        
        
    }
     public function rep_por_tributo(Request $request)
    {
        $id_tributo = $request['id_tributo'];
        $fechainicio = $request['ini'];
        $fechafin = $request['fin'];
        $institucion = DB::select('SELECT * FROM maysa.institucion');
       // $sql=DB::table('presupuesto.vw_por_tributo')->where('id_tributo',$id_tributo) ->whereBetween('fecha', [$fechainicio, $fechafin])->orderBy('fecha','asc')->get();
        $sql = DB::select(" select  fecha,id_tributo,cod_tributo,descrip_tributo, sum(total) as total from presupuesto.vw_por_tributo where id_tributo='$id_tributo' and fecha between '$fechainicio' and '$fechafin' group by fecha,id_tributo,cod_tributo,descrip_tributo  order by fecha asc" );
        
        if(count($sql)>0)
        {
            $view =  \View::make('tesoreria.reportes.rep_por_tributo', compact('sql','fechainicio','fechafin','institucion'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("PRUEBA".".pdf");
        }
        else
        {
            return 'NO HAY RESULTADOS';
        }
        
    }
    
    function autocompletar_tributos(Request $request) {
        $Consulta = DB::table('presupuesto.vw_tributo_por_anio')->where('anio',$request['anio'])->get();
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
