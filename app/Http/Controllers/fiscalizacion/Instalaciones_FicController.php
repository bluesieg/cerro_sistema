<?php

namespace App\Http\Controllers\fiscalizacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\fiscalizacion\Instalaciones_Fic;

class Instalaciones_FicController extends Controller
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
        $insta=new Instalaciones_Fic;
        $cat_instal= DB::table('catastro.instalaciones')->where('id_instal',$request['inst'])->get()->first();
        if(count($cat_instal)>=1)
        {
            $insta->val_unit=$cat_instal->precio;
            if($cat_instal->unid_medida=="M2")
            {
                $insta->pro_tot =$request['largo']*$request['ancho'];
            }
            if($cat_instal->unid_medida=="ML")
            {
                $insta->pro_tot =$request['largo']+$request['ancho'];
            }
            if($cat_instal->unid_medida=="M3")
            {
                $insta->pro_tot =$request['largo']*$request['ancho']*$request['alto'];
            }
            if($cat_instal->unid_medida=="UND")
            {
                $insta->pro_tot =$request['cant'];
            }
            $insta->val_obra = $insta->pro_tot*$insta->val_unit;
        }
        $insta->id_instal = $request['inst'];
        $insta->anio = $request['anio'];
        $insta->dim_lar = $request['largo'];
        $insta->dim_anch = $request['ancho'];
        $insta->dim_alt = $request['alto'];
        $insta->mep = $request['mep'];
        $insta->ecs = $request['ecs'];
        $insta->ecc = $request['ecc'];
        $insta->id_cla = $request['cla'];
        $insta->id_fic = $request['id_fic'];
        $insta->id_inst = $request['ins'];
        $insta->antiguedad = date("Y")-$request['anio'];
        $insta->save();
        return $insta->id_inst_fic;
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
        $instvw= DB::table('fiscalizacion.vw_instalaciones_fic')->where('id_inst_fic',$id)->get();
        return $instvw;
    }

    public function edit($id,Request $request)
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
        $insta=new Instalaciones_Fic;
        $val=  $insta::where("id_inst_fic","=",$id )->first();
        if(count($val)>=1)
        {
            $cat_instal= DB::table('catastro.instalaciones')->where('id_instal',$request['inst'])->get()->first();
            if(count($cat_instal)>=1)
            {
                $val->val_unit=$cat_instal->precio;
                if($cat_instal->unid_medida=="M2")
                {
                    $val->pro_tot =$request['largo']*$request['ancho'];
                }
                if($cat_instal->unid_medida=="ML")
                {
                    $val->pro_tot =$request['largo']+$request['ancho'];
                }
                if($cat_instal->unid_medida=="UND")
                {
                    $val->pro_tot =$request['cant'];
                }
                if($cat_instal->unid_medida=="M3")
                {
                    $val->pro_tot =$request['largo']*$request['ancho']*$request['alto'];
                }
                $val->val_obra = $val->pro_tot*$val->val_unit;
            }
            $val->id_instal = $request['inst'];
            $val->anio = $request['anio'];
            $val->dim_lar = $request['largo'];
            $val->dim_anch = $request['ancho'];
            $val->dim_alt = $request['alto'];
            $val->mep = $request['mep'];
            $val->ecs = $request['ecs'];
            $val->ecc = $request['ecc'];
            $val->id_cla = $request['cla'];
            $val->antiguedad = date("Y")-$request['anio'];
            $val->save();
        }
        return $id;
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
    public function listinsta_fic($id,$fic)
    {
        if($id==0)
        {
            return 0;
        }
        header('Content-type: application/json');
        $totalg = DB::select("select count(id_inst) as total from adm_tri.vw_instalaciones where id_pred_anio='$id'");
        $totalficg = DB::select("select count(id_inst_fic) as total from fiscalizacion.vw_instalaciones_fic where id_fic='$fic' and id_inst=0");
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];

        $total_pages = 0;
        if (!$sidx) {
            $sidx = 1;
        }
        $count = $totalg[0]->total+$totalficg[0]->total;
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
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        $ix=-1;
        $sql = DB::select("select * from adm_tri.vw_instalaciones where id_pred_anio='$id' order by id_inst asc");
        foreach ($sql as $Index => $Datos) {
            $fiscalizado='<a href="javascript:void(0);" class="btn btn-danger txt-color-white btn-circle"><i class="glyphicon glyphicon-remove"></i></a>';
            if($Datos->id_inst_fic)
            {
                $fiscalizado='<a href="javascript:void(0);" class="btn bg-color-green txt-color-white btn-circle"><i class="glyphicon glyphicon-ok"></i></a>';
            }
            $ix=$Index;
            $Lista->rows[$Index]['id'] = $Datos->id_inst;
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_inst),
                trim($Datos->cod_instal),
                trim($Datos->descrip_instal),
                trim($Datos->anio),
                trim($Datos->mep),
                trim($Datos->ecs),
                trim($Datos->ecc),
                trim($Datos->dim_lar),
                trim($Datos->dim_anch),
                trim($Datos->dim_alt),
                trim($Datos->unid_medida),
                trim($Datos->tot_inst),
                trim($Datos->id_inst_fic),
                $fiscalizado
            );
        }
        $sqlisnta = DB::select("select * from fiscalizacion.vw_instalaciones_fic where id_fic='$fic' and id_inst=0  order by id_inst_fic asc");
        foreach ($sqlisnta as $Index => $Datos) {
            $fiscalizado='<a href="javascript:void(0);" class="btn btn-danger txt-color-white btn-circle"><i class="glyphicon glyphicon-remove"></i></a>';
            if($Datos->id_inst_fic)
            {
                $fiscalizado='<a href="javascript:void(0);" class="btn bg-color-green txt-color-white btn-circle"><i class="glyphicon glyphicon-ok"></i></a>';
            }
            $ix++;
            $Lista->rows[$ix]['id'] = $Datos->id_inst."-".$ix;
            $Lista->rows[$ix]['cell'] = array(
                trim($Datos->id_inst),
                trim($Datos->cod_instal),
                trim($Datos->descrip_instal),
                trim($Datos->anio),
                trim($Datos->mep),
                trim($Datos->ecs),
                trim($Datos->ecc),
                trim($Datos->dim_lar),
                trim($Datos->dim_anch),
                trim($Datos->dim_alt),
                trim($Datos->unid_medida),
                trim($Datos->tot_inst),
                trim($Datos->id_inst_fic),
                $fiscalizado
            );
        }
        return response()->json($Lista);
    }
}
