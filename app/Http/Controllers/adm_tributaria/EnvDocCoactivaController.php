<?php

namespace App\Http\Controllers\adm_tributaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Traits\DatesTranslator;
use App\Models\coactiva\coactiva_master;
use App\Models\coactiva\coactiva_documentos;
use App\Models\orden_pago_master;

class EnvDocCoactivaController extends Controller
{
    use DatesTranslator;
    public function index(){
        return view('adm_tributaria.vw_env_doc_coactiva');
    }

    public function create_coa_master($id_contrib,$id_gen_fis){        
        $data = new coactiva_master();
        $data->id_contrib = $id_contrib;
        $data->fch_ini = date('Y-m-d');
        $data->estado = 1;
        $data->anio = date('Y');
        $data->doc_ini=2;
        $sql = $data->save();
        if($sql){
            $this->create_coa_documentos($data->id_coa_mtr,$id_gen_fis);
            return $data->id_coa_mtr;
        }
    }
    public function create_coa_documentos($id_coa_mtr,$id_gen_fis){
        $fch_emi = DB::table('recaudacion.orden_pago_master')->where('id_gen_fis',$id_gen_fis)->value('fch_env');
        $data = new coactiva_documentos();
        $data->id_coa_mtr = $id_coa_mtr;
        $data->id_tip_doc = 1;        
        $data->fch_emi = $fch_emi;        
        $data->anio = date('Y');
        $data->save();
        return $data->id_doc;
    }

    public function edit($id){   }

    public function update(Request $request, $id){   }

    public function destroy($id){   }
    
    public function fis_getOP(Request $request){
        $env_op=$request['env_op'];        
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
                $totalg = DB::select("select count(id_per) as total from recaudacion.vw_genera_fisca where env_op=".$env_op." and verif_env=0 and fec_reg between '".$desde."' and '".$hasta."' ");            
            }else if($tip_bus=='2'){
                $del=str_pad($request['del'], 7, "0", STR_PAD_LEFT);
                $al=str_pad($request['al'], 7, "0", STR_PAD_LEFT);
                $totalg = DB::select("select count(id_per) as total from recaudacion.vw_genera_fisca where env_op=".$env_op." and verif_env=0 and nro_fis between '".$del."' and '".$al."' ");            
            }
        }else{
            if($tip_bus=='1'){
                $desde=date('Y-m-d', strtotime(str_replace('/','-',$request['desde'])));
                $hasta=date('Y-m-d', strtotime(str_replace('/','-',$request['hasta'])));
                $totalg = DB::select("select count(id_per) as total from recaudacion.vw_genera_fisca where env_op=".$env_op." and verif_env=0 and fec_reg between '".$desde."' and '".$hasta."'");            
            }else if($tip_bus=='2'){
                $del=str_pad($request['del'], 7, "0", STR_PAD_LEFT);
                $al=str_pad($request['al'], 7, "0", STR_PAD_LEFT);
                $totalg = DB::select("select count(id_per) as total from recaudacion.vw_genera_fisca where env_op=".$env_op." and verif_env=0 and nro_fis between '".$del."' and '".$al."'");            
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
                $sql = DB::table('recaudacion.vw_genera_fisca')->where('env_op',$env_op)->where('verif_env',0)->whereBetween('fec_reg',[$desde,$hasta])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
            }else if($tip_bus=='2'){
                $sql = DB::table('recaudacion.vw_genera_fisca')->where('env_op',$env_op)->where('verif_env',0)->whereBetween('nro_fis',[$del,$al])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
            }
        }else{
            if($tip_bus=='1'){
                $sql = DB::table('recaudacion.vw_genera_fisca')->where('env_op',$env_op)->where('verif_env',0)->whereBetween('fec_reg',[$desde,$hasta])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
            }else if($tip_bus=='2'){
                $sql = DB::table('recaudacion.vw_genera_fisca')->where('env_op',$env_op)->where('verif_env',0)->whereBetween('nro_fis',[$del,$al])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
            }
        }
        
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;

        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_gen_fis;            
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_gen_fis),
                trim($Datos->nro_fis),
                date('d-m-Y', strtotime($Datos->fec_reg)),
                trim($Datos->hora_env),
                trim($Datos->anio),
                trim($Datos->nro_doc),
                str_replace('-','',trim($Datos->contribuyente)),
                trim($Datos->estado),
                trim($Datos->verif_env),
                $Datos->monto
            );
        }
        return response()->json($Lista);       
    }
    
    function up_env_doc(Request $request){
        $data = new orden_pago_master();        
        
        if($request['env_op']=='2'){
            $val = $data::where("id_gen_fis", "=", $request['id_gen_fis'])->first();
            if (count($val) >= 1) {
                $val->env_op=$request['env_op'];            
                $val->fch_env=date('d-m-Y');            
                $val->hora_env=date('h:i A');
                $val->save();
            }
            $sql = $this->create_coa_master($val->id_contrib,$request['id_gen_fis']);
            if($sql){
                $val = $data::where("id_gen_fis", "=", $request['id_gen_fis'])->first();
                if (count($val) >= 1) {
                    $val->id_coa_mtr=$sql;
                    $val->save();
                }
                return response()->json(['msg'=>'si']);
            }
        }else if($request['env_op']=='1'){
            $val = $data::where("id_gen_fis", "=", $request['id_gen_fis'])->first();
            if (count($val) >= 1) {
                $val->env_op=$request['env_op'];            
                $val->fch_env=null;            
                $val->hora_env=null;
                $val->fch_recep=null;
                $val->hora_recep=null;
                $update = $val->save();                
            }
            if($update){
                $coa_mtr=new coactiva_master;
                $value=  $coa_mtr::where("id_coa_mtr","=",$val->id_coa_mtr)->first();
                if(count($val)>=1){ $value->delete();}
            }
            $val = $data::where("id_gen_fis", "=", $request['id_gen_fis'])->first();
            if (count($val) >= 1) {                
                $val->id_coa_mtr=null;
                $update = $val->save();                
            }
            return response()->json(['msg'=>'no']);
        }
        
    }
    
    function imp_op(){        
        $op = DB::select('select * from recaudacion.vw_genera_fisca where env_op=2 and verif_env=0 order by 4 asc');
        
        $fecha_larga = $this->getCreatedAtAttribute(date('d-m-Y'))->format('l,d \d\e F \d\e\l Y');
        $view = \View::make('adm_tributaria.reportes.listado_op',compact('op','fecha_larga'))->render();

        $pdf = \App::make('dompdf.wrapper');            
        $pdf->loadHTML($view)->setPaper('a4');
        return $pdf->stream();            

    }
}
