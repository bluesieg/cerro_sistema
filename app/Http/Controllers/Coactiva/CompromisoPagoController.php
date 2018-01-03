<?php

namespace App\Http\Controllers\Coactiva;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompromisoPagoController extends Controller
{
   
    public function index(){
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_compromiso_pago' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        return view('coactiva.vw_compromisopago',compact('menu','permisos'));
    }

    function compromisopago(Request $request){
        $id_coa_mtr = $request['id_coa_mtr'];
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        
        $totalg = DB::select("select count(id_aper) as total from coactiva.vw_compromisopago where id_coa_mtr=".$id_coa_mtr); 
        
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
        
        $sql = DB::table('coactiva.vw_compromisopago')->where('id_coa_mtr',$id_coa_mtr)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        $edit = "";
        $dias = "";
        foreach ($sql as $Index => $Datos) {
            if($Datos->estado==0){
                $dias = (strtotime(date('Y-m-d'))-strtotime($Datos->fch_pago))/86400;
                $dias = floor($dias).' DIAS';
            }
            
            if($dias<0){
                $dias='';
                $edit = "";
            }else{                
                $edit="<button class='btn btn-labeled bg-color-green txt-color-white' type='button' onclick='editar_compromiso(".$Datos->id_aper.",".$Datos->estado.")'><span class='btn-label'><i class='fa fa-pencil'></i></span>Editar</button>";                
            }
            $Lista->rows[$Index]['id'] = $Datos->id_aper;            
            $Lista->rows[$Index]['cell'] = array(
                $Datos->nro_cuo,
                date('d-m-Y', strtotime($Datos->fch_pago)),
                trim($Datos->monto).' %'.' de S/. '.$Datos->monto_mtr,
                $Datos->estado,
                trim($Datos->desc_est),
                $dias,
                $edit
            );
        }
        return response()->json($Lista);
    }
    
    function edit_estado_compromiso(Request $request){
        
        $id_aper = $request['id_aper'];
        $estado = $request['estado'];
        $sql = DB::table('coactiva.apersonamiento_cuotas')->where('id_aper',$id_aper)->update(['estado'=>$estado]);
        if($sql){return response()->json(['msg'=>'si']);}
    }
}
