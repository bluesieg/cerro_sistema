<?php

namespace App\Http\Controllers\adm_tributaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Instalaciones;
use App\Models\Predios\Predios_Anio;
use App\Models\Predios\Predios_Contribuyentes;

class InstalacionesController extends Controller
{

    public function index()
    {
        //
    }
    public function calculos_ivpp($id)
    {
        DB::select("select adm_tri.actualiza_base_predio(".$id.")");
        $Predios_Anio=new Predios_Anio;
        $Predios_Anio=  $Predios_Anio::where("id_pred_anio","=",$id )->first();
        $Predios_Contribuyentes=new Predios_Contribuyentes;
        $Predios_Contribuyentes=  $Predios_Contribuyentes::where("id_pred","=",$Predios_Anio->id_pred )->first();
        DB::select("select adm_tri.calcular_ivpp($Predios_Anio->anio,$Predios_Contribuyentes->id_contrib)");
    }
    public function create(Request $request)
    {
        $insta=new Instalaciones;
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
            if($cat_instal->unid_medida=="UND")
            {
                $insta->pro_tot =$request['cant'];
            }
            if($cat_instal->unid_medida=="M3")
            {
                $insta->pro_tot =$request['largo']*$request['ancho']*$request['alto'];
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
        $insta->id_pred_anio = $request['id_pre'];
        $insta->antiguedad = date("Y")-$request['anio'];
        $insta->glosa = strtoupper($request['glosa']);
        $insta->save();
        $this->calculos_ivpp($insta->id_pred_anio);
        return $insta->id_inst;
    }

  
    public function store(Request $request)
    {
        return "llego store";
    }

    public function show($id)
    {
        $instvw= DB::table('adm_tri.vw_instalaciones')->where('id_inst',$id)->get();
        return $instvw;
    }

    public function edit(Request $request,$id)
    {
        $insta=new Instalaciones;
        $val=  $insta::where("id_inst","=",$id )->first();
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
            $val->glosa = strtoupper($request['glosa']);
            
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
        $pred_anio=0;
        $insta=new Instalaciones;
        $val=  $insta::where("id_inst","=",$request['id'] )->first();
        if(count($val)>=1)
        {
            $pred_anio=$val->id_pred_anio;
            $val->delete();
        }
        $this->calculos_ivpp($pred_anio);
        return "destroy ".$request['id'];
    }
    
    public function listinsta($id)
    {
        header('Content-type: application/json');
        $totalg = DB::select("select count(id_inst) as total from adm_tri.vw_instalaciones where id_pred_anio='$id'");
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
        
        $sql = DB::select("select * from adm_tri.vw_instalaciones where id_pred_anio='$id' order by id_inst asc");
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;

        foreach ($sql as $Index => $Datos) {
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
            );
        }
        return response()->json($Lista);
    }
}
