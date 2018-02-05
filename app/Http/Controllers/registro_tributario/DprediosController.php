<?php

namespace App\Http\Controllers\registro_tributario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DprediosController extends Controller
{

    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_descarga_predios' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio = DB::select('SELECT anio FROM adm_tri.uit order by anio desc');
        $motivos = DB::select('SELECT * FROM transferencias.motivo order by id_motivo asc');
        return view('registro_tributario/vw_descarga_predios', compact('menu','permisos','anio','motivos'));
    }

    public function create()
    {
        //
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
       $tim = DB::table('fraccionamiento.tim')->where('id_tim',$id)->get();
       return $tim;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }
    
    public function insertar_nuevo_tim(Request $request){
        header('Content-type: application/json');
        $data = $request->all();
        $insert=DB::table('fraccionamiento.tim')->insert($data);

        if ($insert) return response()->json($data);
        else return false;
    }
    
    function modificar_tim(Request $request) {
        $data = $request->all();
        unset($data['id_tim']);
        $update=DB::table('fraccionamiento.tim')->where('id_tim',$request['id_tim'])->update($data);
        if ($update){
            return response()->json([
                'msg' => 'si',
            ]);
        }else return false;
    }
    
    function eliminar_predio(Request $request){
        $delete = DB::table('adm_tri.predios_contribuyentes')->where('id_pred_contri', $request['id_pred_contri'])->delete();

        if ($delete) {
            return response()->json([
                'msg' => 'si',
            ]);
        }
    }
    
    public function getTim(Request $request){
        header('Content-type: application/json');

        $totalg = DB::select("select count(id_tim) as total from fraccionamiento.vw_tim where anio='".$request['anio']."'");
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

     

        $sql = DB::table('fraccionamiento.vw_tim')->where('anio',$request['anio'])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;

        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_tim;
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_tim),
                trim($Datos->documento_aprob),
                trim($Datos->tim),
                trim($Datos->anio),
            );
        }

        return response()->json($Lista);

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
    
    function get_predios(Request $request){
        
        $id_contrib = $request['id_contrib'];
        
        $totalg = DB::select("select count(id_contrib) as total from transferencias.vw_predios where id_contrib='$id_contrib' ");
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

        $sql = DB::table('transferencias.vw_predios')->where('id_contrib',$id_contrib)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        
        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_contrib;            
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_contrib),
                trim($Datos->id_pred_contri),
                trim($Datos->cod_via),
                trim($Datos->sector),
                trim($Datos->mzna),
                trim($Datos->lote),
                trim($Datos->referencia)
            );
        }
        return response()->json($Lista);
    }
    
}