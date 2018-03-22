<?php

namespace App\Http\Controllers\recaudacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\recaudacion\Prescripciones;

class PrescripcionesController extends Controller
{

    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_prescripciones' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio = DB::select('SELECT anio FROM adm_tri.uit order by anio desc');
        return view('recaudacion/vw_prescripciones', compact('menu','permisos','anio'));
    }

    public function create(Request $request)
    {
        $cta_cte=explode("and",$request['cta_cte']);
        $prescripciones = new Prescripciones;

        $prescripciones->id_contrib = $request['id_contrib'];
        $prescripciones->nro_resolucion = $request['nro_resolucion'];
        $prescripciones->fecha_resolucion = $request['fecha_resolucion'];
        $prescripciones->total = $request['total'];
        $prescripciones->save();
        
        $j = 0;
        foreach($cta_cte as $datos){
            DB::select('select prescripciones.prescribir_deuda(' . $datos . ')');
            ++$j;
        }

        //$function = DB::select('select prescripciones.prescribir_deuda(' . $cta_cte . ')');
        //if($function){}
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
    public function show()
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
       
    }
    
    public function actualizar_estado(){
    }


    public function get_prescripciones(Request $request){
        header('Content-type: application/json');
        
        $anio = $request['anio'];
        $totalg = DB::select("select count(*) as total from prescripciones.vw_prescripc where anio= '$anio' ");
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

        $sql = DB::table('prescripciones.vw_prescripc')->where('anio',$anio)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $array = array();
        $sum = DB::select("select sum(total) as sum from prescripciones.vw_prescripc where anio='$anio' ");
        $array['sum'] = $sum[0]->sum;
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        $Lista->userdata = $array;

        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_presc;
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_presc),
                trim($Datos->contribuyente),
                trim($Datos->nro_resolucion),
                trim($Datos->fecha_resolucion),
                trim(number_format($Datos->total,2,'.',','))
            );
        }

        return response()->json($Lista);

    }
    
    function Obtener_Deudas(Request $request){
        
        $id_contrib = $request['id_contrib'];
        
        $totalg = DB::select("select count(*) as total from prescripciones.vw_deuda_actual where id_contrib='$id_contrib' ");
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

        $sql = DB::table('prescripciones.vw_deuda_actual')->where('id_contrib',$id_contrib)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $array = array();
        $sum = DB::select("select sum(deuda) as sum from prescripciones.vw_deuda_actual where id_contrib='$id_contrib'");
        $array['sum'] = $sum[0]->sum;
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        $Lista->userdata = $array;
        
        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_cta_cte;            
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_cta_cte),
                trim($Datos->anio_deu),
                trim($Datos->tipo),
                trim($Datos->deuda),
                "<input type='checkbox' value='".$Datos->id_cta_cte."' id='cta_cte' onclick='check_tot_deuda(".$Datos->deuda.",this)'>",
            );
        }
        return response()->json($Lista);
        
    }
    
    public function reporte_preinscripciones(Request $request)
    {
        $anio = $request['anio'];
        $institucion = DB::select('SELECT * FROM maysa.institucion');
        $usuario = DB::select('SELECT * from public.usuarios where id='.Auth::user()->id);
        $fecha = (date('d/m/Y H:i:s'));
        $sql=DB::table('prescripciones.vw_prescripc')->where('anio',$anio)->orderBy('id_presc')->get();

        if(count($sql)>0)
        {
            set_time_limit(0);
            ini_set('memory_limit', '2G');
            $view =  \View::make('recaudacion.reportes.rep_preinscripciones', compact('sql','anio','institucion','usuario','fecha'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("Reporte Preinscripciones".".pdf");
        }
        else
        {   return 'No hay datos';}
    }
    
}
