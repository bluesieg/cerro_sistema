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
    
    public function get_predios(Request $request){
        $direccion = $request['direccion'];
        
       if($direccion=='0')
        {
            return 0;
        }
        else
        { 
        header('Content-type: application/json');
        
        
        $consulta="";$iniciador=0;
        $direcs = explode(" ", strtoupper($direccion));
        foreach($direcs as $dirs)
        {
            if($iniciador==1)
            {
                $consulta.=" AND ";
            }
            if($dirs!="")
            {
                $consulta.="todo like '%$dirs%'";
            }
            if($iniciador==0)
            {
                $iniciador=1;
            }
        }
        $totalg = DB::select("select count(*) as total from reg_tributario.vw_buscar_pred where $consulta ");

        //$totalg = DB::select("select count(*) as total from reg_tributario.vw_buscar_pred where todo like '%".$direccion."%'");
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

     

        //$sql = DB::table('reg_tributario.vw_buscar_pred')->where('todo','like', '%'.strtoupper($direccion).'%')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        $sql = DB::select("select * from reg_tributario.vw_buscar_pred where $consulta order by $sidx $sord limit $limit offset $start");
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;

        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->cod_catastral;
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->cod_catastral),
                trim($Datos->todo),
                trim($Datos->contribuyente)
            );
        }

        return response()->json($Lista);
        }
        
    }
    
    
    public function get_predios_contribuyente(Request $request) 
    {
        $id_contrib = $request['id_contrib'];
        header('Content-type: application/json');
        $totalg = DB::select("select count(*) as total from reg_tributario.vw_buscar_pred where id_contrib = '$id_contrib'");
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

        $sql = DB::table('reg_tributario.vw_buscar_pred')->where('id_contrib',$id_contrib)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        
        
        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->cod_catastral;            
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->cod_catastral),
                trim($Datos->todo),
                trim($Datos->contribuyente)
            );
        }
        return response()->json($Lista);
       
    }
    
    
}
