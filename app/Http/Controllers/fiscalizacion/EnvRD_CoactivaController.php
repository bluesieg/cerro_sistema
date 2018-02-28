<?php

namespace App\Http\Controllers\fiscalizacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Traits\DatesTranslator;
use App\Models\coactiva\coactiva_master;
use App\Models\coactiva\coactiva_documentos;
use App\Models\fiscalizacion\Resolucion_Determinacion;
use Illuminate\Support\Facades\Auth;

class EnvRD_CoactivaController extends Controller
{
    function vw_env_rd_coa()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_env_rd_a_coac' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        return view('fiscalizacion.vw_env_rd_coactiva',compact('menu','permisos'));
    }
    public function create_coa_master($id_contrib,$id_rd,$monto,$anio){        
        $data = new coactiva_master();
        $data->id_contrib = $id_contrib;
        $data->fch_ini = date('Y-m-d');
        $data->estado = 1;
        $data->anio = date('Y');
        $data->doc_ini=1;/*RD*/ 
        $data->monto=$monto;
        $data->materia=1;
        $sql = $data->save();
        $recpred = DB::select('select * from presupuesto.vw_impuesto_predial where anio='.$anio); 
        if($sql){
            DB::table('adm_tri.cta_cte')->where('id_pers','=',$id_contrib)->where('id_tribu','=',$recpred[0]->id_tributo)->where('ano_cta',$anio)
                    ->update(['id_coa_mtr'=>$data->id_coa_mtr,'trim1_estado'=>2,'trim2_estado'=>2,'trim3_estado'=>2,'trim4_estado'=>2]);
            return $data->id_coa_mtr;
        }
    }
    public function create_coa_documentos($id_coa_mtr,$id_rd){
        $fch_emi = DB::table('fiscalizacion.resolucion_determinacion')->where('id_rd',$id_rd)->value('fch_env');
        $data = new coactiva_documentos();
        $data->id_coa_mtr = $id_coa_mtr;
        $data->id_tip_doc = 1;        
        $data->fch_emi = $fch_emi;        
        $data->anio = date('Y');
        $data->save();
        return $data->id_doc;
    }
    
    function fis_get_RD(Request $request){
        $env_rd=$request['env_rd'];        
        $tip_bus=$request['tip_bus'];
        $grid=$request['grid'];
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        
        if(isset($grid)){
            if($tip_bus=='1'){
                $desde=date('Y-m-d', strtotime(str_replace('/','-',$request['desde'])));
                $hasta=date('Y-m-d', strtotime(str_replace('/','-',$request['hasta'])));
                $totalg = DB::select("select count(id_rd) as total from fiscalizacion.vw_resolucion_determinacion where env_rd=".$env_rd." and verif_env=0 and fec_reg between '".$desde."' and '".$hasta."' ");            
            }else if($tip_bus=='2'){
                $del=str_pad($request['del'], 7, "0", STR_PAD_LEFT);
                $al=str_pad($request['al'], 7, "0", STR_PAD_LEFT);
                $totalg = DB::select("select count(id_rd) as total from fiscalizacion.vw_resolucion_determinacion where env_rd=".$env_rd." and verif_env=0 and nro_rd between '".$del."' and '".$al."' ");            
            }
        }else{
            if($tip_bus=='1'){
                $desde=date('Y-m-d', strtotime(str_replace('/','-',$request['desde'])));
                $hasta=date('Y-m-d', strtotime(str_replace('/','-',$request['hasta'])));
                $totalg = DB::select("select count(id_rd) as total from fiscalizacion.vw_resolucion_determinacion where env_rd=".$env_rd." and verif_env=0 and fec_reg between '".$desde."' and '".$hasta."'");            
            }else if($tip_bus=='2'){
                $del=str_pad($request['del'], 7, "0", STR_PAD_LEFT);
                $al=str_pad($request['al'], 7, "0", STR_PAD_LEFT);
                $totalg = DB::select("select count(id_rd) as total from fiscalizacion.vw_resolucion_determinacion where env_rd=".$env_rd." and verif_env=0 and nro_rd between '".$del."' and '".$al."'");            
            }
        }

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
        $start = ($limit * $page) - $limit; // do not put $limit*($page - 1)  
        if ($start < 0) {
            $start = 0;
        }
        
        if(isset($grid)){
            if($tip_bus=='1'){
                $sql = DB::table('fiscalizacion.vw_resolucion_determinacion')->where('env_rd',$env_rd)->where('verif_env',0)->whereBetween('fec_reg',[$desde,$hasta])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
            }else if($tip_bus=='2'){
                $sql = DB::table('fiscalizacion.vw_resolucion_determinacion')->where('env_rd',$env_rd)->where('verif_env',0)->whereBetween('nro_rd',[$del,$al])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
            }
        }else{
            if($tip_bus=='1'){
                $sql = DB::table('fiscalizacion.vw_resolucion_determinacion')->where('env_rd',$env_rd)->where('verif_env',0)->whereBetween('fec_reg',[$desde,$hasta])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
            }else if($tip_bus=='2'){
                $sql = DB::table('fiscalizacion.vw_resolucion_determinacion')->where('env_rd',$env_rd)->where('verif_env',0)->whereBetween('nro_rd',[$del,$al])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
            }
        }
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;

        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_rd;            
            $Lista->rows[$Index]['cell'] = array(                
                trim($Datos->nro_rd),
                date('d-m-Y', strtotime($Datos->fec_reg)),
                trim($Datos->hora_env),
                trim($Datos->anio),                
                str_replace('-','',trim($Datos->contribuyente)),
                trim($Datos->estado),
                trim($Datos->verif_env),
                $Datos->ivpp_verif
            );
        }
        return response()->json($Lista); 
    }
    
    function fis_env_rd(Request $request){
        $id_rd=$request['id_rd'];
        $env_rd=$request['env_rd'];
        $id_contrib=DB::table('fiscalizacion.vw_resolucion_determinacion')->where('id_rd',$id_rd)->value('id_contrib');
        
        $data = new Resolucion_Determinacion();
        if($env_rd=='2'){
            $val = $data::where("id_rd", "=", $id_rd)->first();
            if (count($val) >= 1) {
                $val->env_rd=$env_rd;            
                $val->fch_env=date('d-m-Y');            
                $val->hora_env=date('h:i A');                 
                $val->save();
            }
            $monto = DB::table('fiscalizacion.vw_resolucion_determinacion')->where('id_rd',$id_rd)->value('ivpp_verif'); 
            $sql = $this->create_coa_master($id_contrib,$id_rd,$monto,$val->anio);
            if($sql){
                $val = $data::where("id_rd", "=", $id_rd)->first();
                if (count($val) >= 1) {
                    $val->id_coa_mtr=$sql;
                    $val->save();
                }
                return response()->json(['msg'=>'si']);
            }
        }else if($env_rd=='1'){
            $val = $data::where("id_rd", "=", $id_rd)->first();
            if (count($val) >= 1) {
                $val->env_rd=$env_rd;            
                $val->fch_env=null;            
                $val->hora_env=null;
                $val->fch_recep=null;
                $val->hora_recep=null;
                $update = $val->save();                
            }
            if($update){
                DB::table('adm_tri.cta_cte')->where('id_coa_mtr','=',$val->id_coa_mtr)
                    ->update(['id_coa_mtr'=>null]);
                $coa_mtr=new coactiva_master;
                $value=  $coa_mtr::where("id_coa_mtr","=",$val->id_coa_mtr)->first();
                if(count($val)>=1){ $value->delete();}
            }
            $val = $data::where("id_rd", "=", $id_rd)->first();
            if (count($val) >= 1) {                
                $val->id_coa_mtr=null;
                $update = $val->save();                
            }
            return response()->json(['msg'=>'si']);
        }
    }
}
