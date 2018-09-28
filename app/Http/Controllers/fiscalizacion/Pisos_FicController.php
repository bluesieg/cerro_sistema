<?php

namespace App\Http\Controllers\fiscalizacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\fiscalizacion\Pisos_Fic;

class Pisos_FicController extends Controller
{
    public function index()
    {
    }
    public function create(Request $request)
    {
        $nro_hojas=DB::table('fiscalizacion.vw_hoja_liquidacion')->where('id_car',$request['carta'])->get();
        if(count($nro_hojas)>0)
        {
            return 0;
        }
        $anulado=DB::table('fiscalizacion.vw_carta_requerimiento')->where('id_car',$request['carta'])->get();
        if($anulado[0]->flg_anu>0)
        {
            return -1;
        }
        $pisos=new Pisos_Fic;
        $totapisos = DB::select("select count(id_pisos_fic) as total from fiscalizacion.pisos_fic where id_fic='".$request['id_fic']."'");
        $pisos->anio = date("Y");
        $pisos->cod_piso = $request['nro'];
        $pisos->ani_const = $request['fech'];
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
        $pisos->val_areas_com = $request['acomun'];
        $pisos->anio_demolicion = $request['anio_demolicion'];
        $pisos->id_fic = $request['id_fic'];
        $pisos->id_pisos = $request['id_pis'];
        $pisos->num_pis = $totapisos[0]->total+1;
        $pisos->save();
        return $pisos->id_pisos_fic;
    }
    public function store(Request $request)
    {
    }
    public function show($id)
    {
        $pisovw= DB::table('fiscalizacion.vw_pisos_fic')->where('id_pisos_fic',$id)->get();
        return $pisovw;
    }
    public function edit(Request $request,$id)
    {
        $nro_hojas=DB::table('fiscalizacion.vw_hoja_liquidacion')->where('id_car',$request['carta'])->get();
        if(count($nro_hojas)>0)
        {
            return 0;
        }
        $anulado=DB::table('fiscalizacion.vw_carta_requerimiento')->where('id_car',$request['carta'])->get();
        if($anulado[0]->flg_anu>0)
        {
            return -1;
        }
        $pisos=new Pisos_Fic;
        $val=  $pisos::where("id_pisos_fic","=",$id )->first();
        if(count($val)>=1)
        {
            $val->cod_piso = $request['nro'];
            $val->ani_const = $request['fech'];
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
            $val->val_areas_com = $request['acomun'];
            $val->anio_demolicion = $request['anio_demolicion'];
            $val->save();
        }
        return $id;
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
        //
    }
    
    
    public function listpisos_fic($id,$fic)
    {
        if($id==0)
        {
            return 0;
        }
        header('Content-type: application/json');
        $totalg = DB::select("select count(id_pisos) as total from adm_tri.vw_pisos where id_pred_anio='$id'");
        $totalgfic = DB::select("select count(id_pisos_fic) as total from fiscalizacion.vw_pisos_fic where id_fic='$fic' and id_pisos=0");
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];

        $total_pages = 0;
        if (!$sidx) {
            $sidx = 1;
        }
        $count = $totalg[0]->total+$totalgfic[0]->total;
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
        $ix=-1;
        
        foreach ($sql as $Index => $Datos) {
            $fiscalizado='<a href="javascript:void(0);" class="btn btn-danger txt-color-white btn-circle"><i class="glyphicon glyphicon-remove"></i></a>';
            if($Datos->id_pisos_fic)
            {
                $fiscalizado='<a href="javascript:void(0);" class="btn bg-color-green txt-color-white btn-circle"><i class="glyphicon glyphicon-ok"></i></a>';
            }
            $ix=$Index;
            $Lista->rows[$Index]['id'] = $Datos->id_pisos;
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_pisos),
                trim($Datos->cod_piso),
                $Datos->ani_const,
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
                $fiscalizado
            );
        }
        $sqlfic = DB::select("select * from fiscalizacion.vw_pisos_fic where id_fic='$fic' and id_pisos=0 order by id_pisos_fic asc");
        foreach ($sqlfic as $Index => $Datos) {
            $fiscalizado='<a href="javascript:void(0);" class="btn btn-danger txt-color-white btn-circle"><i class="glyphicon glyphicon-remove"></i></a>';
            if($Datos->id_pisos_fic)
            {
                $fiscalizado='<a href="javascript:void(0);" class="btn bg-color-green txt-color-white btn-circle"><i class="glyphicon glyphicon-ok"></i></a>';
            }
            $ix++;
            $Lista->rows[$ix]['id'] = $Datos->id_pisos."-".$ix;
            $Lista->rows[$ix]['cell'] = array(
                trim($Datos->id_pisos),
                trim($Datos->cod_piso),
                $Datos->ani_const,
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
                $fiscalizado
            );
        }
        return response()->json($Lista);
    }
}
