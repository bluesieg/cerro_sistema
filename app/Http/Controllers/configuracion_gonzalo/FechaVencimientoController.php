<?php

namespace App\Http\Controllers\configuracion_gonzalo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FechaVencimientoController extends Controller
{

    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_fecha_vencimiento' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio = DB::select('SELECT pk_uit,anio FROM adm_tri.uit order by anio desc');
        return view('configuracion_gonzalo/vw_fecha_vencimiento', compact('menu','permisos','anio'));
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
       $fv = DB::table('configuracion.vw_fec_venc_ivpp')->where('id_pag',$id)->get();
       return $fv;
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
    
    public function insertar_nuevo_fv(Request $request){
        header('Content-type: application/json');
        $data = $request->all();
        $insert=DB::table('configuracion.fecha_pago_ivpp')->insert($data);

        if ($insert) return response()->json($data);
        else return false;
    }
    
    function modificar_fv(Request $request) {
        $data = $request->all();
        unset($data['id_pag']);
        $update=DB::table('configuracion.fecha_pago_ivpp')->where('id_pag',$request['id_pag'])->update($data);
        if ($update){
            return response()->json([
                'msg' => 'si',
            ]);
        }else return false;
    }
    
    function eliminar_fv(Request $request){
        $delete = DB::table('configuracion.fecha_pago_ivpp')->where('id_pag', $request['id_pag'])->delete();

        if ($delete) {
            return response()->json([
                'msg' => 'si',
            ]);
        }
    }
    
    public function getFechaVencimiento(Request $request){
        header('Content-type: application/json');

        $totalg = DB::select("select count(id_pag) as total from configuracion.vw_fec_venc_ivpp where id_anio='".$request['anio']."'");
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

     

        $sql = DB::table('configuracion.vw_fec_venc_ivpp')->where('id_anio',$request['anio'])->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;

        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_pag;
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_pag),
                trim($Datos->trimestre),
                trim($Datos->fecha_vencim),
            );
        }

        return response()->json($Lista);

    }
    
}
