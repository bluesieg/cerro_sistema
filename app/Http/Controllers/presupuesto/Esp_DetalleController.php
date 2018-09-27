<?php

namespace App\Http\Controllers\presupuesto;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\presupuesto\EspecificaDetalle;
use Illuminate\Support\Facades\Auth;

class Esp_DetalleController extends Controller
{
    public function index(){
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_pres_especideta' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        $fte_financiamiento = DB::select('select * from presupuesto.vw_fte_financiamiento  order by id_fte_fto asc');
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio = DB::select('select anio from adm_tri.uit order by anio desc');
        return view('presupuesto/vw_especif_det',compact('anio','menu','permisos','fte_financiamiento'));
    }

    public function create(Request $request){
        $data = new EspecificaDetalle();
        $data->id_espec = $request['id_espec'];
        $data->cod_esp_det =$request['cod'];
        $data->desc_espec_detalle = $request['desc'];
        $data->cod_pat_debe = $request['cod_pat_debe'];
        $data->cod_pat_haber = $request['cod_pat_haber'];
        $data->id_fte = $request['id_fte'];
        $data->cta_presup_debe = $request['cta_presup_debe'];
        $data->cta_presup_haber = $request['cta_presup_haber'];
        $data->save();
        return $data->id_espec_det;
    }
    public function edit(Request $request,$id){
        $data = new EspecificaDetalle();
        $val = $data::where("id_espec_det", "=", $id)->first();
        if (count($val) >= 1) {
            $val->desc_espec_detalle=$request['desc'];
            $val->cod_pat_debe=$request['cod_pat_debe'];
            $val->cod_pat_haber=$request['cod_pat_haber'];
            $val->id_fte=$request['id_fte'];
            $val->cta_presup_debe=$request['cta_presup_debe'];
            $val->cta_presup_haber=$request['cta_presup_haber'];
            $val->save();  
            return $val->id_espec_det;
        }
    }
    public function destroy(Request $request,$id){
        $data = new EspecificaDetalle();
        $val=  $data::where("id_espec_det","=",$request['id'] )->first();
        if(count($val)>=1)
        {
            $val->delete();
            return $val->id_espec_det;
        }
    }
    
    function get_esp_detalle(Request $request){
        $anio =  $request['anio'];
        $id_espec =  $request['id_espec'];
        $totalg = DB::select("select count(id_espec_det) as total from presupuesto.vw_especif_detalle where anio=".$anio." and id_espec=".$id_espec);
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

        $sql = DB::table('presupuesto.vw_especif_detalle')->where('anio',$anio)->where('id_espec',$id_espec)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        
        
        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_espec_det;
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->cod_esp_det),
                trim($Datos->codigo),
                trim($Datos->desc_espec_detalle)               
            );
        }        
        return response()->json($Lista);
    }
    public function show($id,Request $request)
    {        
        if($id>0)
        {
            if($request['show']=='esp_detalle' ){
                return $this->show_esp_detalle($id);
            }             
        }
    }
    public function show_esp_detalle($id) {
        $fte_financiamiento = DB::table('presupuesto.especifica_detalle')->where('id_espec_det',$id)->get();
            return $fte_financiamiento;
    }
}
