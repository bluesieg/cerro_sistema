<?php

namespace App\Http\Controllers\recaudacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\recaudacion\Compensacion;
use Illuminate\Support\Facades\Auth;

class ControlDeudasController extends Controller
{
    public function index(){
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_control_deudas' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio = DB::select('select anio from adm_tri.uit order by anio desc');
        return view('recaudacion/vw_control_deudas',compact('anio','menu','permisos'));
    }
    public function create(Request $request){
        
    }
    public function edit(Request $request,$id){
        
    }
    public function destroy(Request $request,$id){
        
    }
    
    public function compensacion_predial(Request $request){
        $id_contrib = $request['id_contrib'];
        $anio = $request['anio'];
        $monto = $request['monto'];
        
        $fn_compens_predial = DB::select('select control_deuda.fn_comp_predial('.$id_contrib.','.$anio.','.$monto.')');
        
        $compensacion = new Compensacion;
        $compensacion->tipo = $request['tipo'];
        $compensacion->anio = $anio;
        $compensacion->arbitrios = $request['arbitrio'];
        $compensacion->predial = $request['predial'];
        $compensacion->observacion = $request['observacion'];
        $compensacion->resolucion = $request['resolucion'];
        $compensacion->save();

        if ($fn_compens_predial) {
            return response()->json([
                        'msg' => 'si',
            ]);
        } else{
            return response()->json([
                        'msg' => 'no',
            ]);
        }
    }
    
    function get_est_cta_cte(Request $request){
        $anio =  $request['anio'];
        $id_contrib =  $request['id_contrib'];
        
        //$calcula_tim = DB::select('select adm_tri.calcula_tim('.$id_contrib.','.$anio.')');
        
        $totalg = DB::select("select count(*) as total from control_deuda.vw_deuda_01 where id_contrib = '$id_contrib' and ano_cta = '$anio' ");
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];

        $total_pages = 0;
        if (!$sidx) {
            $sidx = 1;
        }
        $count = $totalg[0]->total;
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        }
        if ($page > $total_pages) {
            $page = $total_pages;
        }
        $start = ($limit * $page) - $limit;
        if ($start < 0) {
            $start = 0;
        }

        $sql = DB::table('control_deuda.vw_deuda_01')->where('id_contrib',$id_contrib)->where('ano_cta',$anio)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        
        $suma = DB::select("select SUM(saldo) as sum_total from control_deuda.vw_deuda_01 where id_contrib = '$id_contrib' and ano_cta = '$anio' ");
        
        
        $array = array();
        $array['sum_total'] = $suma[0]->sum_total;
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        $Lista->userdata = $array;
        
        
        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_cta_cte;
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_cta_cte),
                trim($Datos->ano_cta),
                trim($Datos->descrip_tributo),
                trim(number_format($Datos->deuda_actual,2,'.',',')),
                trim(number_format($Datos->reajuste,2,'.',',')),
                trim(number_format($Datos->interes,2,'.',',')),
                trim(number_format($Datos->tot_deuda,2,'.',',')),
                trim(number_format($Datos->pagado,2,'.',',')),
                trim(number_format($Datos->saldo,2,'.',','))          
            );
        }        
        return response()->json($Lista);
    }
    
    function get_detalle_deuda(Request $request){
        $anio =  $request['anio'];
        $id_cta_cte =  $request['id_cta_cte'];
        
        $totalg = DB::select("select count(*) as total from control_deuda.vw_deuda_02 where id_cta_cte = '$id_cta_cte' and ano_cta = '$anio' ");
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];

        $total_pages = 0;
        if (!$sidx) {
            $sidx = 1;
        }
        $count = $totalg[0]->total;
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        }
        if ($page > $total_pages) {
            $page = $total_pages;
        }
        $start = ($limit * $page) - $limit;
        if ($start < 0) {
            $start = 0;
        }

        $sql = DB::table('control_deuda.vw_deuda_02')->where('id_cta_cte',$id_cta_cte)->where('ano_cta',$anio)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        
        
        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_cta_cte;
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_cta_cte),
                trim($Datos->descrip_tributo),
                trim(number_format($Datos->saldo,2,'.',',')),
                trim(number_format($Datos->trim1,2,'.',',')),
                trim(number_format($Datos->abo1,2,'.',',')),
                trim(number_format($Datos->trim2,2,'.',',')),
                trim(number_format($Datos->abo2,2,'.',',')),
                trim(number_format($Datos->trim3,2,'.',',')),
                trim(number_format($Datos->abo3,2,'.',',')),
                trim(number_format($Datos->trim4,2,'.',',')),
                trim(number_format($Datos->abo4,2,'.',','))
            );
        }        
        return response()->json($Lista);
    }
    
    function get_predios_arbitrios(Request $request){
        $anio =  $request['anio'];
        $id_contrib =  $request['id_contrib'];
        
        $actualizar_tim = DB::select('select arbitrios.actualizar_tim('.$id_contrib.','.$anio.')');
        $totalg = DB::select("select count(*) as total from control_deuda.vw_arbitrios_01 where id_contrib = '$id_contrib' and anio = '$anio' ");
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];

        $total_pages = 0;
        if (!$sidx) {
            $sidx = 1;
        }
        $count = $totalg[0]->total;
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        }
        if ($page > $total_pages) {
            $page = $total_pages;
        }
        $start = ($limit * $page) - $limit;
        if ($start < 0) {
            $start = 0;
        }

        $sql = DB::table('control_deuda.vw_arbitrios_01')->where('id_contrib',$id_contrib)->where('anio',$anio)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        
        
        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_arb;
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_arb),
                trim($Datos->anio),
                trim($Datos->cod_cat),
                trim($Datos->direccion),
                trim(number_format($Datos->deuda_normal,2,'.',',')),
                trim(number_format($Datos->intereses,2,'.',',')),
                trim(number_format($Datos->tot_deuda,2,'.',',')),
                trim(number_format($Datos->pagado,2,'.',',')),
                trim(number_format($Datos->saldo,2,'.',','))
            );
        }        
        return response()->json($Lista);
    }
    
    function get_predios_arbitrios_concepto(Request $request){
        $id_arb =  $request['id_arb'];
        
        $totalg = DB::select("select count(*) as total from control_deuda.vw_arbitrios_02 where id_arb = '$id_arb' ");
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];

        $total_pages = 0;
        if (!$sidx) {
            $sidx = 1;
        }
        $count = $totalg[0]->total;
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        }
        if ($page > $total_pages) {
            $page = $total_pages;
        }
        $start = ($limit * $page) - $limit;
        if ($start < 0) {
            $start = 0;
        }

        $sql = DB::table('control_deuda.vw_arbitrios_02')->where('id_arb',$id_arb)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        
        
        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_cta_arb;
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_cta_arb),
                trim($Datos->descripcion),
                trim(number_format($Datos->deuda,2,'.',',')),
                trim(number_format($Datos->interes,2,'.',',')),
                trim(number_format($Datos->tot_deuda,2,'.',',')),
                trim(number_format($Datos->pagado,2,'.',',')),
                trim(number_format($Datos->saldo,2,'.',','))
            );
        }        
        return response()->json($Lista);
    }
    
    
    function get_meses_arbitrios(Request $request){
        $id_cta_arb =  $request['id_cta_arb'];
        
        $totalg = DB::select("select count(*) as total from control_deuda.fn_meses('$id_cta_arb') ");
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];

        $total_pages = 0;
        if (!$sidx) {
            $sidx = 1;
        }
        $count = $totalg[0]->total;
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        }
        if ($page > $total_pages) {
            $page = $total_pages;
        }
        $start = ($limit * $page) - $limit;
        if ($start < 0) {
            $start = 0;
        }
        
        $sql = DB::select("select * from control_deuda.fn_meses('$id_cta_arb') order by $sidx $sord limit $limit offset $start");
        //$sql = DB::table('control_deuda.vw_arbitrios_02')->where('id_arb',$id_arb)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        
        
        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_arb;
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_arb),
                trim($Datos->mes),
                trim(number_format($Datos->deuda,2,'.',',')),
                trim(number_format($Datos->interes,2,'.',',')),
                trim(number_format($Datos->tot_deuda,2,'.',',')),
                trim(number_format($Datos->pagado,2,'.',',')),
                trim(number_format($Datos->saldo,2,'.',','))
            );
        }        
        return response()->json($Lista);
    }
}
