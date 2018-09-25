<?php

namespace App\Http\Controllers\presupuesto;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\presupuesto\Generica;
use Illuminate\Support\Facades\Auth;

class GenericaController extends Controller
{

    public function index(){
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_pres_gen' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $tip_trans= DB::table('presupuesto.tip_transaccion')->get();
        $anio = DB::select('select anio from adm_tri.uit order by anio desc');
        return view('presupuesto/vw_generica',compact('tip_trans','anio','menu','permisos'));
    }

    public function create(Request $request){
        $data = new Generica();
        $data->descr_gen = $request['gen_desc'];
        $data->anio = $request['anio'];
        $data->id_tip_trans = $request['tipo_transaccion'];
        $data->cod_generica = $request['gen_cod'];        
        $data->save();
        return $data->id_gener;
    }

    public function store(Request $request){}

    public function show($id){}

    public function edit(Request $request,$id){
        $data = new Generica();        
        $val = $data::where("id_gener", "=", $id)->first();
        if (count($val) >= 1) {
            $val->descr_gen = $request['gen_desc'];            
            $val->anio = $request['anio'];         
            $val->id_tip_trans = $request['tipo_transaccion'];
            $val->cod_generica = $request['gen_cod'];
            $val->save();  
            return $val->id_gener;
        }
    }

    public function destroy(Request $request,$id){
        $data=new Generica;
        $val=  $data::where("id_gener","=",$request['id'] )->first();
        if(count($val)>=1)
        {
            $val->delete();
        }
    }
    
    function get_generica(Request $request){
        $anio =  $request['anio'];
        $totalg = DB::select("select count(id_gener) as total from presupuesto.vw_generica where anio=".$anio);
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

        $sql = DB::table('presupuesto.vw_generica')->where('anio',$anio)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        
        
        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_gener;
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_gener),
                str_pad(trim($Datos->cod_generica), 3, "0", STR_PAD_LEFT),
                trim($Datos->descr_gen)               
            );
        }        
        return response()->json($Lista);
    }
}
