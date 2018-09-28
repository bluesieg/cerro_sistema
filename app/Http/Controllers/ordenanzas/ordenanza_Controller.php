<?php

namespace App\Http\Controllers\ordenanzas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ordenanzas\ordenanzas;
use App\Models\ordenanzas\orde_predial;
use App\Models\ordenanzas\orde_arbitrios;
use App\Traits\DatesTranslator;

class ordenanza_Controller extends Controller
{
    use DatesTranslator;
    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_mod_orde' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        $condiciones_arbitrios = DB::select('select * from ordenanzas.condicion_arbitrios order by 1 asc');
        return view('ordenanzas/vw_ordenanza', compact('anio_tra','menu','permisos','condiciones_arbitrios'));
    }

    public function create(Request $request)
    {
        if($request['tipo']=='ordenanza')
        {
            return $this->create_ordenanza($request);
        }
        if($request['tipo']=='predial')
        {
            return $this->create_predial($request);
        }
        if($request['tipo']=='arbitrios')
        {
            return $this->create_arbitrios($request);
        }
        
    }
    public function create_ordenanza(Request $request)
    {
        $ordenanzas=new ordenanzas;
        $ordenanzas->refe_orde = strtoupper($request['refe']);
        $ordenanzas->fec_reg = date('d/m/Y');
        $ordenanzas->fec_ini = $request['fec_ini'];
        $ordenanzas->fec_fin = $request['fec_fin'];
        $ordenanzas->glosa = $request['glosa'];
        $ordenanzas->flg_act = $request['activo'];
        $ordenanzas->id_usu = Auth::user()->id;
        $ordenanzas->save();
        return $ordenanzas->id_orde;
    }
    public function create_predial(Request $request)
    {
        $predial=new orde_predial;
        $predial->id_orde = $request['id_orde'];
        $predial->anio_ini = $request['anio_ini'];
        $predial->anio_fin = $request['anio_fin'];
        $predial->porcent_desc_ip_nat = $request['porcent_desc_ip_nat'];
        $predial->pocent_desc_ip_jur = $request['pocent_desc_ip_jur'];
        $predial->monto_multa_nat = $request['monto_multa_nat'];
        $predial->monto_multa_jur = $request['monto_multa_jur'];
        $predial->pocent_desc_im_nat = date('pocent_desc_im_nat');
        $predial->porcent_desc_im_jur = $request['porcent_desc_im_jur'];
        $predial->fec_reg =  date('d/m/Y');
        $predial->id_usu = Auth::user()->id;
        $predial->save();
        return $predial->id_orde_pred;
    }
    public function create_arbitrios(Request $request)
    {
        $arb=new orde_arbitrios;
        $arb->id_orde = $request['id_orde'];
        $arb->porcent_desc_arb_nat = $request['porcent_desc_arb_nat'];
        $arb->porcent_desc_arb_jur = $request['porcent_desc_arb_jur'];
        $arb->porcent_desc_ia_nat = $request['porcent_desc_ia_nat'];
        $arb->porcent_desc_ia_jur = $request['porcent_desc_ia_jur'];
        $arb->flg_barrido = $request['flg_barrido'];
        $arb->flg_recojo = $request['flg_recojo'];
        $arb->flg_seguridad = $request['flg_seguridad'];
        $arb->flg_parques = $request['flg_parques'];
        $arb->id_cond_arb = $request['id_cond_arb'];
        $arb->fec_reg =  date('d/m/Y');
        $arb->id_usu = Auth::user()->id;
        $arb->save();
        return $arb->id_orde_arb;
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id,Request $request)
    {
        if($id==0&&$request["grid"]=='ordenanzas')
        {
            return $this->grid_ordenanzas($request);
        }
        if($id==0&&$request["grid"]=='ordenanzas_predial')
        {
            return $this->grid_ordenanzas_predial($request);
        }
        if($id==0&&$request["grid"]=='ordenanzas_arbitrios')
        {
            return $this->grid_ordenanzas_arbitrios($request);
        }
        if($id>0)
        {
            $ordenanzas= DB::table('ordenanzas.ordenanzas')->where('id_orde',$id)->get();
            $ordenanzas[0]->fec_ini=$this->getCreatedAtAttribute(trim($ordenanzas[0]->fec_ini))->format('d/m/Y');
            $ordenanzas[0]->fec_fin=$this->getCreatedAtAttribute(trim($ordenanzas[0]->fec_fin))->format('d/m/Y');
            return $ordenanzas;
        }
    }

    public function edit($id,Request $request)
    {
        if($request['tipo']=='ordenanza')
        {
            return $this->edit_ordenanza($id,$request);
        }
        if($request['tipo']=='activa_ordenanza')
        {
            return $this->edit_activa_ordenanza($id,$request);
        }
    }
    public function edit_ordenanza($id,Request $request)
    {
        $ordenanzas=new ordenanzas;
        $val=  $ordenanzas::where("id_orde","=",$id )->first();
        if(count($val)>=1)
        {
            $val->refe_orde = strtoupper($request['refe']);
            $val->fec_ini = $request['fec_ini'];
            $val->fec_fin = $request['fec_fin'];
            $val->glosa = $request['glosa'];
            $val->save();
        }
        return $id;
    }
    public function edit_activa_ordenanza($id,Request $request)
    {
        if($request['activo']==1)
        {
            $sql = DB::table('ordenanzas.ordenanzas')->update(['flg_act' => 0]);
        }
        $ordenanzas=new ordenanzas;
        $val=  $ordenanzas::where("id_orde","=",$id )->first();
        if(count($val)>=1)
        {
            $val->flg_act = $request['activo'];
            $val->save();
        }
        return $id;
    }
    

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
    
    public function grid_ordenanzas(Request $request)
    {
            header('Content-type: application/json');
            $page = $_GET['page'];
            $limit = $_GET['rows'];
            $sidx = $_GET['sidx'];
            $sord = $_GET['sord'];
            $start = ($limit * $page) - $limit; // do not put $limit*($page - 1)  
            if ($start < 0) {
                $start = 0;
            }
            $totalg = DB::select("select count(id_orde) as total from ordenanzas.ordenanzas");
            $sql = DB::table('ordenanzas.ordenanzas')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
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
            

            $Lista = new \stdClass();
            $Lista->page = $page;
            $Lista->total = $total_pages;
            $Lista->records = $count;


            foreach ($sql as $Index => $Datos) {
                $Lista->rows[$Index]['id'] = $Datos->id_orde;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_orde),
                    trim($Datos->refe_orde),
                    $this->getCreatedAtAttribute(trim($Datos->fec_ini))->format('d/m/Y'),
                    $this->getCreatedAtAttribute(trim($Datos->fec_fin))->format('d/m/Y'),
                    trim($Datos->flg_act),
                );
            }
            return response()->json($Lista);
    }
    public function grid_ordenanzas_predial(Request $request)
    {
            header('Content-type: application/json');
            $page = $_GET['page'];
            $limit = $_GET['rows'];
            $sidx = $_GET['sidx'];
            $sord = $_GET['sord'];
            $start = ($limit * $page) - $limit; // do not put $limit*($page - 1)  
            if ($start < 0) {
                $start = 0;
            }
            $totalg = DB::select("select count(id_orde) as total from ordenanzas.orde_predial where id_orde=".$request['id']);
            $sql = DB::table('ordenanzas.orde_predial')->where('id_orde',$request['id'])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
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
            $Lista = new \stdClass();
            $Lista->page = $page;
            $Lista->total = $total_pages;
            $Lista->records = $count;
            foreach ($sql as $Index => $Datos) {
                $Lista->rows[$Index]['id'] = $Datos->id_orde_pred;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_orde_pred),
                    trim($Datos->anio_ini."-".$Datos->anio_fin),
                    trim($Datos->porcent_desc_ip_nat."%"),
                    trim($Datos->pocent_desc_ip_jur."%"),
                    trim($Datos->monto_multa_nat),
                    trim($Datos->monto_multa_jur),
                    trim($Datos->pocent_desc_im_nat."%"),
                    trim($Datos->porcent_desc_im_jur."%"),
                );
            }
            return response()->json($Lista);
    }
    public function grid_ordenanzas_arbitrios(Request $request)
    {
            header('Content-type: application/json');
            $page = $_GET['page'];
            $limit = $_GET['rows'];
            $sidx = $_GET['sidx'];
            $sord = $_GET['sord'];
            $start = ($limit * $page) - $limit; // do not put $limit*($page - 1)  
            if ($start < 0) {
                $start = 0;
            }
            $totalg = DB::select("select count(id_orde) as total from ordenanzas.vw_orde_arbitrios where id_orde=".$request['id']);
            $sql = DB::table('ordenanzas.vw_orde_arbitrios')->where('id_orde',$request['id'])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
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
            $Lista = new \stdClass();
            $Lista->page = $page;
            $Lista->total = $total_pages;
            $Lista->records = $count;
            foreach ($sql as $Index => $Datos) {
                $Lista->rows[$Index]['id'] = $Datos->id_orde_arb;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_orde_arb),
                    trim($Datos->des_cond_arb),
                    trim($Datos->porcent_desc_arb_nat."%"),
                    trim($Datos->porcent_desc_arb_jur."%"),
                    trim($Datos->porcent_desc_ia_nat."%"),
                    trim($Datos->porcent_desc_ia_jur."%"),
                    trim($Datos->flg_barrido),
                    trim($Datos->flg_recojo),
                    trim($Datos->flg_seguridad),
                    trim($Datos->flg_parques),
                    trim($Datos->id_cond_arb)
                );
            }
            return response()->json($Lista);
    }
}
