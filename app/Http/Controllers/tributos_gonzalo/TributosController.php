<?php

namespace App\Http\Controllers\tributos_gonzalo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\presupuesto\Tributos;

class TributosController extends Controller
{

    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_tributos' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        
        $procedimientos = DB::select('SELECT id_proced,descrip_procedim FROM presupuesto.procedimientos order by descrip_procedim asc');
        $anio = DB::select('SELECT anio FROM adm_tri.uit order by anio desc');
        $oficinas = DB::select('SELECT id_ofi,nombre FROM adm_tri.oficinas order by nombre asc');
        
        return view('tributos_gonzalo/vw_presupuesto_tributos', compact('menu','permisos','procedimientos','anio','oficinas'));
    }

    public function create(Request $request){
        
        $tributo = new  Tributos;
        
        if ($request['id_procedimiento'] == 0) {
            return response()->json([
                'msg' => 'si',
            ]);
        }else{
            $tributo->id_procedimiento = $request['id_procedimiento'];
            $tributo->descrip_tributo = $request['descrip_tributo'];
            $tributo->soles = $request['soles'];
            $tributo->save();

            return $tributo->id_tributo;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $tributos = DB::table('presupuesto.vw_tributos_vladi_1')->where('id_tributo',$id)->get();
       return $tributos;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        $tributo = new  Tributos;
        $val=  $tributo::where("id_tributo","=",$id )->first();
        if(count($val)>=1)
        {
            $val->id_procedimiento = $request['id_procedimiento'];
            $val->descrip_tributo = $request['descrip_tributo'];
            $val->soles = $request['soles'];
            $val->save();
        }
        return $id;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $tributo = new  Tributos;
        $val=  $tributo::where("id_tributo","=",$request['id_tributo'] )->first();
        if(count($val)>=1)
        {
            $val->delete();
        }
        return "destroy ".$request['id_tributo'];
    }
    
    
    public function getTributos(Request $request){
        header('Content-type: application/json');

        //$totalg = DB::select("select count(id_tributo) as total from presupuesto.vw_tributos_vladi_1");
        $totalg = DB::select("select count(id_tributo) as total from presupuesto.vw_tributos_vladi_1 where anio='".$request['anio']."' and id_ofi=".$request['id_ofi']."");
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

     

        //$sql = DB::table('presupuesto.vw_tributos_vladi_1')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        $sql = DB::table('presupuesto.vw_tributos_vladi_1')->where('anio',$request['anio'])->where('id_ofi',$request['id_ofi'])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;

        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_tributo;
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_tributo),
                trim($Datos->descrip_procedim),
                trim($Datos->descrip_tributo),
                trim($Datos->soles),
            );
        }

        return response()->json($Lista);

    }
    
    public function getAnio(Request $request){
        header('Content-type: application/json');
        $anio = DB::select("select * from presupuesto.vw_tributos_vladi_1 where anio = '". $request['anio'] ."' order by id_tributo asc");
        return response()->json($anio);
    }
    
    public function getOficina(Request $request){
        header('Content-type: application/json');
        $oficina = DB::select("select * from presupuesto.vw_tributos_vladi_1 where id_ofi = '". $request['id_ofi'] ."' order by id_tributo asc");
        return response()->json($oficina);
    }
    
    function autocompletar_oficinas() {
        $Consulta = DB::table('adm_tri.oficinas')->get();
        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();
            $Lista->value = $Datos->id_ofi;
            $Lista->label = trim($Datos->nombre);
            array_push($todo, $Lista);
        }
        return response()->json($todo);
    }
    
    function autocompletar_procedimientos(Request $request) {
        $Consulta = DB::table('presupuesto.procedimientos')->where('id_ofic',$request['ofi'])->where('anio',$request['anio'])->get();
        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();
            $Lista->value = $Datos->id_proced;
            $Lista->label = trim($Datos->descrip_procedim);
            array_push($todo, $Lista);
        }
        return response()->json($todo);
    }
    
}
