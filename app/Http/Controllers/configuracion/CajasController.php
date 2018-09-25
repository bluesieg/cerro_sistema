<?php

namespace App\Http\Controllers\configuracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\configuracion\Cajas;

class CajasController extends Controller
{

    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_config_cajas' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        return view('configuracion/vw_cajas', compact('menu','permisos'));
    }

    public function create(Request $request){
        
        $cajas = new  Cajas;
        
        $cajas->descrip_caja = strtoupper($request['descripcion']);
        $cajas->direc_caja = strtoupper($request['direcion']);
        $cajas->serie = $request['serie'];
        $cajas->save();

        return $cajas->id_caj;
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
    public function show($id, Request $request)
    {
        if ($id > 0) 
        {
            if ($request['show'] == 'cajas') 
            {
                return $this->traer_datos_cajas($id);
            }
        }
        else
        {
            if ($request['grid'] == 'cajas') 
            {
                return $this->cargar_datos_cajas($request);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        $cajas = new  Cajas;
        $val=  $cajas::where("id_caj","=",$id)->first();
        if(count($val)>=1)
        {
            $val->descrip_caja = strtoupper($request['descripcion']);
            $val->direc_caja = strtoupper($request['direcion']);
            $val->serie = $request['serie'];
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
       
    }
    
    public function traer_datos_cajas($id)
    {
        $datos = DB::table('tesoreria.cajas')->where('id_caj',$id)->get();
        return $datos;
    }
    
    public function cargar_datos_cajas(Request $request){
        header('Content-type: application/json');
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $start = ($limit * $page) - $limit; // do not put $limit*($page - 1)  
        if ($start < 0) {
            $start = 0;
        }
        
        $totalg = DB::select("select count(*) as total from tesoreria.cajas");
        $sql = DB::table('tesoreria.cajas')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();

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
            $Lista->rows[$Index]['id'] = $Datos->id_caj;            
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_caj),
                trim($Datos->serie),
                trim($Datos->descrip_caja),
                trim($Datos->direc_caja),
            );
        }
        return response()->json($Lista);
    }
    
}
