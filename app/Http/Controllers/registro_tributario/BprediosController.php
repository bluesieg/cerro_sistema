<?php

namespace App\Http\Controllers\registro_tributario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BprediosController extends Controller
{

    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_buscar_predios' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio = DB::select('SELECT * FROM adm_tri.uit order by anio desc');
        $motivos = DB::select('SELECT * FROM transferencias.motivo order by id_motivo asc');
        return view('registro_tributario/vw_buscar_predios', compact('menu','permisos','anio','motivos'));
    }
    
    function autocompletar_hab_urb() {

        $Consulta = DB::table('catastro.hab_urb')->get();
        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();
            $Lista->value = $Datos->id_hab_urb;
            $Lista->label = trim($Datos->nomb_hab_urba);
            array_push($todo, $Lista);
        }
        return response()->json($todo);

    }
    
    function autocompletar_via() {

        $Consulta = DB::table('catastro.vias')->get();
        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();
            $Lista->value = $Datos->id_via;
            $Lista->label = trim($Datos->nom_via);
            array_push($todo, $Lista);
        }
        return response()->json($todo);

    }
    
    public function get_contribuyentes(Request $request) 
    {
        if($request['dat']=='0')
        {
            return 0;
        }
        else
        {
        header('Content-type: application/json');
        $totalg = DB::select("select count(id_contrib) as total from transferencias.vw_contirbuyentes where contribuyente like '%".$request['dat']."%'");
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

        $sql = DB::table('transferencias.vw_contirbuyentes')->where('contribuyente','like', '%'.strtoupper($request['dat']).'%')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        
        
        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_contrib;            
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_contrib),
                trim($Datos->pers_nro_doc),
                trim($Datos->contribuyente)
            );
        }
        return response()->json($Lista);
        }
    }
    
    function buscar_predios(Request $request){
        
        $id_hab_urb = $request['direccion'];
        
        $totalg = DB::select("select count(*) as total from reg_tributario.vw_buscar_pred where id_hab_urb=".$id_hab_urb);
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

        $sql = DB::table('reg_tributario.vw_buscar_pred')->where('id_hab_urb',$id_hab_urb)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        
        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->cod_catastral;            
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->cod_catastral),
                trim($Datos->direccion),
                trim($Datos->contribuyente)
            );
        }
        return response()->json($Lista);
    }
    
}
