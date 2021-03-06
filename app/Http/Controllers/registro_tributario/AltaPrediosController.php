<?php

namespace App\Http\Controllers\registro_tributario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\registro_tributario\Alta_predios;

class AltaPrediosController extends Controller
{

    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_alta_predios' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio = DB::select('SELECT anio FROM adm_tri.uit order by anio desc');
        $motivos = DB::select('SELECT * FROM transferencias.motivo order by id_motivo asc');
        return view('registro_tributario/vw_alta_predios', compact('menu','permisos','anio','motivos'));
    }

    public function create(Request $request)
    {
        $usuario = DB::select('select * from public.usuarios where id='.Auth::user()->id);
        $select=DB::table('transferencias.transferencias')->where('id_pred_contrib',$request['id_pred_contrib'])->where('baja_alta',2)->get();
        
        if (count($select)>0) {
            
                return response()->json([
                    'msg' => 'repetido',
                ]);
                
        }else{
            
                if ($request['id_pred_contrib'] == '') {
                    return response()->json([
                        'msg' => 'si',
                    ]);
                }else{
                    $d_predios = new Alta_predios;

                    $d_predios->id_usuario = $usuario[0]->id;
                    $d_predios->fch_transf = $request['fch_transf'];
                    $d_predios->id_pred_contrib = $request['id_pred_contrib'];
                    $d_predios->glosa = strtoupper($request['glosa']);
                    $d_predios->motivo = $request['motivo'];
                    $d_predios->anio=date('Y'); 
                    $d_predios->baja_alta = 2;
                    $d_predios->id_contribuyente = $request['nuevo_contribuyente'];
                    $d_predios->save();
                    return $d_predios->id_trans;
                }
            
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
    
    public function get_descarga_alta_predios(Request $request){
        header('Content-type: application/json');
        $fecha_desde = $request['fecha_desde'];
        $fecha_hasta = $request['fecha_hasta'];
        $totalg = DB::select("select count(*) as total from transferencias.vw_transferencias where fch_transf between '$fecha_desde' and '$fecha_hasta' and baja_alta = 2");
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

     

        $sql = DB::select("select id_trans,fch_transf,id_pred_contrib, case motivo when 1 then 'DECLARACION JURADA' when 2 then 'AUTOMATICO' when 3 then 'JUDICIAL' when 4 then 'OTROS' end as desc_motivo from transferencias.transferencias where fch_transf between '$fecha_desde' and '$fecha_hasta' and baja_alta = 2 order by $sidx $sord limit $limit offset $start");
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;

        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_trans;
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_trans),
                trim($Datos->fch_transf),
                trim($Datos->desc_motivo),
                '<button class="btn btn-labeled btn-warning" type="button" onclick="verDocumento('.trim($Datos->id_trans).')"><span class="btn-label"><i class="fa fa-file-text-o"></i></span> VER DOCUMENTO</button>',
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
    
    function get_predios_alta(Request $request){
        
        $id_contrib = $request['id_contrib'];
        $anio = $request['anio'];
  
        $totalg = DB::select("select count(id_contrib) as total from transferencias.vw_predios_bajas where id_contrib='$id_contrib' and anio= '$anio' ");
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

        $sql = DB::table('transferencias.vw_predios_bajas')->where('id_contrib',$id_contrib)->where('anio',$anio)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        
        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_pred_contri;            
            $Lista->rows[$Index]['cell'] = array(
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
    
    public function ver_documentos($id_trans)
    {
        $transferencia=DB::table('transferencias.vw_transferencias')->where('id_trans',$id_trans)->first();
        $sql_nuevo=DB::table('transferencias.vw_predios_reportes')->where('id_pred_contri',$transferencia->id_pred_contrib)->get();
        $sql_antiguo=DB::table('transferencias.vw_predios_reportes')->where('id_contrib',$transferencia->id_contribuyente)->get();
        $fecha_hora = (date('d/m/Y H:i:s'));
        $fecha = $transferencia->fch_transf;
        
        if(count($sql_nuevo)>0)
        {
            $view =  \View::make('registro_tributario.reportes.documentos_alta', compact('sql_nuevo','sql_antiguo','fecha_hora','fecha'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("Documentacion_altas".".pdf");
        }
        else
        {   return 'NO HAY DATOS';}
    }
    
}
