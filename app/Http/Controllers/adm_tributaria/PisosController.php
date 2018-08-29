<?php

namespace App\Http\Controllers\adm_tributaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Pisos;
use App\Models\Predios;
use App\Models\Predios\Predios_Anio;
use App\Models\Predios\Predios_Contribuyentes;
class PisosController extends Controller
{
   
    public function index()
    {
        //
    }
    
    public function create(Request $request)
    {
        
        $pisos=new Pisos;
        $totapisos = DB::select("select count(id_pisos) as total from adm_tri.pisos where id_pred_anio='".$request['id_pre']."'");
        $pisos->anio = date("Y");
        $pisos->cod_piso = $request['nro'];
        $pisos->ani_const = $request['fech'];
        $pisos->fch_const = "01/01/".$request['fech'];
        $pisos->ant_ano = date("Y") - $request['fech'];
        $pisos->clas = "0".$request['clasi'];
        $pisos->mep = $request['mep'];
        $pisos->esc = $request['estconserv'];
        $pisos->ecc = $request['estconst'];
        $pisos->est_mur = substr($request['estru'],0,1);
        $pisos->est_tch = substr($request['estru'],1,1);
        $pisos->aca_pis = substr($request['estru'],2,1);
        $pisos->aca_pta = substr($request['estru'],3,1);
        $pisos->aca_rev = substr($request['estru'],4,1);
        $pisos->aca_ban = substr($request['estru'],5,1);
        $pisos->ins_ele = substr($request['estru'],6,1);
        $pisos->area_const = $request['aconst'];
        //$pisos->val_areas_com = $request['acomun'];
        $pisos->check_are_comun=$request['acomun'];
        $pisos->num_pis = $totapisos[0]->total+1;
        $pisos->id_pred_anio = $request['id_pre'];
        $pisos->save();
        $this->calculos_ivpp($request['id_pre']);
        return $pisos->id_pisos;
    }
    public function calculos_ivpp($id)
    {
        DB::select("select adm_tri.fn_count_pisos(".$id.")");
        DB::select("select adm_tri.actualiza_base_predio(".$id.")");
        $Predios_Anio=new Predios_Anio;
        $Predios_Anio=  $Predios_Anio::where("id_pred_anio","=",$id )->first();
        $Predios_Contribuyentes=new Predios_Contribuyentes;
        $Predios_Contribuyentes=  $Predios_Contribuyentes::where("id_pred","=",$Predios_Anio->id_pred )->first();
        DB::select("select adm_tri.calcular_ivpp($Predios_Anio->anio,$Predios_Contribuyentes->id_contrib)");
    }
    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        $pisovw= DB::table('adm_tri.vw_pisos')->where('id_pisos',$id)->get();
        return $pisovw;
    }

    public function edit(Request $request,$id)
    {
        $pisos=new Pisos;
        $val=  $pisos::where("id_pisos","=",$id )->first();
        if(count($val)>=1)
        {
            $val->cod_piso = $request['nro'];
            $val->ani_const = $request['fech'];
            $val->fch_const = "01/01/".$request['fech'];
            $val->ant_ano = date("Y") - $request['fech'];
            $val->clas = "0".$request['clasi'];
            $val->mep = $request['mep'];
            $val->esc = $request['estconserv'];
            $val->ecc = $request['estconst'];
            $val->est_mur = substr($request['estru'],0,1);
            $val->est_tch = substr($request['estru'],1,1);
            $val->aca_pis = substr($request['estru'],2,1);
            $val->aca_pta = substr($request['estru'],3,1);
            $val->aca_rev = substr($request['estru'],4,1);
            $val->aca_ban = substr($request['estru'],5,1);
            $val->ins_ele = substr($request['estru'],6,1);
            $val->area_const = $request['aconst'];
            //$val->val_areas_com = $request['acomun'];
            $val->check_are_comun=$request['acomun'];
            $val->save();
            $this->calculos_ivpp($val->id_pred_anio);
       
        }
        return "edit".$id;
    }

   
    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Request $request)
    {   
        $pred=0;
        $pisos=new Pisos;
        $val=  $pisos::where("id_pisos","=",$request['id'] )->first();
        if(count($val)>=1)
        {
            $pred_anio=$val->id_pred_anio;
            $val->delete();
        }
        $this->calculos_ivpp($pred_anio);
        return "destroy ".$request['id'];
    }
    public function listpisos($id)
    {
        header('Content-type: application/json');
        $totalg = DB::select("select count(id_pisos) as total from adm_tri.vw_pisos where id_pred_anio='$id'");
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
        $start = ($limit * $page) - $limit; // do not put $limit*($page - 1)  
        if ($start < 0) {
            $start = 0;
        }
        
        $sql = DB::select("select * from adm_tri.vw_pisos where id_pred_anio='$id' order by cod_piso asc");
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;

        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_pisos;
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_pisos),
                trim($Datos->cod_piso),
                date("d/m/Y",strtotime(str_replace("/", "-", $Datos->fch_const))),
                trim($Datos->mep),
                trim($Datos->esc), 
                trim($Datos->ecc),
                trim($Datos->est_mur),
                trim($Datos->est_tch),
                trim($Datos->aca_pis),               
                trim($Datos->aca_pta),               
                trim($Datos->aca_rev),               
                trim($Datos->aca_ban),               
                trim($Datos->ins_ele),               
                trim($Datos->area_const),               
                trim($Datos->id_pisos_fic),               
            );
        }
        return response()->json($Lista);
    }
}
