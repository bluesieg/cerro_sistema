<?php

namespace App\Http\Controllers\adm_tributaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Traits\DatesTranslator;
use App\Models\coactiva\coactiva_master;
use App\Models\coactiva\coactiva_documentos;
use App\Models\orden_pago_master;
use Illuminate\Support\Facades\Auth;

class EnvDocCoactivaController extends Controller
{
    use DatesTranslator;
    public function index(){
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_env_doc_a_coac' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');

        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        return view('adm_tributaria.vw_env_doc_coactiva',compact('menu','permisos','anio_tra'));
    }

    public function create_coa_master($id_contrib,$id_gen_fis,$monto){    
        $oficina= DB::select("SELECT * from adm_tri.vw_recaudacion");

        $data = new coactiva_master();
        $data->id_contrib = $id_contrib;
        $data->fch_ini = date('Y-m-d');
        $data->estado = 1;
        $data->anio = date('Y');
        $data->doc_ini=2;
        $data->monto=$monto;
        $data->id_oficina=$oficina[0]->id_ofi;
        $data->materia=1;
        $sql = $data->save();
        if($sql){
            $this->create_coa_documentos($data->id_coa_mtr,$id_gen_fis);
            $i=1;
            for($i==1;$i<=4;$i++){
                $trim = DB::table('recaudacion.vw_op_detalle')->where('id_gen_fis',$id_gen_fis);
                if(count($trim)>=1){
                    if($trim[0]->trimestre.$i>0){
                        $recpred = $trim[0]->id_tributo;
                        DB::table('adm_tri.cta_cte')->where('id_pers','=',$id_contrib)
                                ->where('id_tribu','=',$recpred)
                                ->where('ano_cta',date("Y"))
                        ->update(['trim'.$i.'_estado'=>2]);
                    }                    
                }
            }
            DB::table('adm_tri.cta_cte')->where([['id_pers','=',$id_contrib],['id_tribu','=',$recpred]/*,['ano_cta',date('Y')]*/])
                    ->update(['id_coa_mtr'=>$data->id_coa_mtr]);
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
    
    public function fis_getOP($an,$tipo,Request $request){
        $grid=$request['grid'];
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
   
        $totalg = DB::select("select count(id_per) as total from recaudacion.vw_genera_fisca where anio_reg=$an and env_coactivo=$tipo and fec_notifica is not null ");            
           
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
        
    
        $sql = DB::table('recaudacion.vw_genera_fisca')->where('env_coactivo',$tipo)->where('anio_reg',$an)->whereNotNull('fec_notifica')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
       
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;

        foreach ($sql as $Index => $Datos) {
            if($tipo==0)
            {
                $btn='<button class="btn btn-labeled bg-color-blueDark txt-color-white" type="button" onclick="env_coactiva(1,'.$Datos->id_gen_fis.')"><span class="btn-label"><i class="fa fa-file-text-o"></i></span>Env. a Coactiva</button>';
            }
            else
            {
                $btn='<button class="btn btn-labeled bg-color-blueDark txt-color-white" type="button" onclick="env_coactiva(0,'.$Datos->id_gen_fis.')"><span class="btn-label"><i class="fa fa-file-text-o"></i></span>Retornar </button>';
            }
            $Lista->rows[$Index]['id'] = $Datos->id_gen_fis;            
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_gen_fis),
                trim($Datos->nro_fis),
                date('d-m-Y', strtotime($Datos->fch_env)),
                trim($Datos->hora_env),
                trim($Datos->anio),
                trim($Datos->nro_doc),
                str_replace('-','',trim($Datos->contribuyente)),
                trim($Datos->estado),
                trim($Datos->verif_env),
                $Datos->monto,
                $btn
            );
        }
        return response()->json($Lista);       
    }
    
    function up_env_doc(Request $request){
        $data = new orden_pago_master();        
        
            $val = $data::where("id_gen_fis", "=", $request['id_gen_fis'])->first();
            if (count($val) >= 1) {
                $val->env_coactivo=$request['env_op'];            
                $val->fch_env=date('d-m-Y');            
                $val->hora_env=date('h:i A');
                $val->save();
                return $val->id_gen_fis;
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
