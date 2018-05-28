<?php

namespace App\Http\Controllers\presupuesto;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\presupuesto\Procedimientos;

class ProcedimientoController extends Controller
{
    public function index(){
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_pres_proced' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio = DB::select('select anio from adm_tri.uit order by anio desc');
        return view('presupuesto.vw_procedimientos',compact('anio','menu','permisos'));
    }

    public function create(Request $request){
       
        
        $data = new Procedimientos();
        $data->descrip_procedim = $request['descrip_procedim'];
        $data->id_ofic = $request['id_ofic'];
        $data->anio = $request['anio'];
        $data->id_espec_det = $request['id_espec_det'];        
        $data->save();
        return $data->id_proced;
    }

    public function edit(Request $request,$id){
        $data = new Procedimientos();        
        $val = $data::where("id_proced", "=", $id)->first();
        if (count($val) >= 1) {
            $val->descrip_procedim=$request['descrip_procedim'];            
            $val->id_ofic=$request['id_ofic'];            
            $val->anio = date('Y');
            $val->id_espec_det = $request['id_espec_det'];
            $val->save();  
            return $val->id_proced;
        }
    }

    public function destroy(Request $request,$id){
        $data=new Procedimientos();
        $val=  $data::where("id_proced","=",$request['id'] )->first();
        if(count($val)>=1)
        {
            $val->delete();
        }
    }
    
    function autocompletar_oficinas() {
        $Consulta = DB::table('adm_tri.mg_oficinas')->get();
        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();
            $Lista->value = $Datos->id_ofi;
            $Lista->label = trim($Datos->nombre);
            array_push($todo, $Lista);
        }
        return response()->json($todo);
    }
    function autocompletar_esp_detalle(Request $request){
        //$Consulta = DB::table('presupuesto.vw_especif_detalle_1')->get();
        $sql = DB::select("select id_espec_det,detalle from presupuesto.vw_especif_detalle_1 where anio='".$request['anio']."' ");
        $todo = array();
        foreach ($sql as $Datos) {
            $Lista = new \stdClass();
            $Lista->value = $Datos->id_espec_det;
            $Lista->label = trim($Datos->detalle);
            array_push($todo, $Lista);
        }
        return response()->json($todo);
    }
    
    function get_procedimientos(Request $request){
        $totalg = DB::select("select count(id_proced) as total from presupuesto.vw_procedimiento where anio='".$request['anio']."' and descrip_procedim like '%".strtoupper($request['procedimiento'])."%' ");
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

        $sql = DB::table('presupuesto.vw_procedimiento')->where('anio',$request['anio'])->where('descrip_procedim','like', '%'.strtoupper($request['procedimiento']).'%')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;        
        
        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_proced;
            $Lista->rows[$Index]['cell'] = array(
                str_pad(trim($Datos->cod_proced), 3, "0", STR_PAD_LEFT),
                trim($Datos->descrip_procedim),
                trim($Datos->id_espec_det),
                trim($Datos->desc_espec_detalle),
                trim($Datos->id_ofic),
                trim($Datos->nombre)
            );
        }        
        return response()->json($Lista);
    }
}
