<?php

namespace App\Http\Controllers\fraccionamiento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Convenio;
use App\Traits\DatesTranslator;
use Illuminate\Support\Facades\Auth;

class ConvenioController extends Controller
{
    use DatesTranslator;
    public function index(){
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_fraccionamiento' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $cfracc=DB::table('fraccionamiento.vw_config_fracc')->get();
        $anio = DB::select('select anio from adm_tri.uit order by anio desc');
        $tip_f= DB::select('select * from fraccionamiento.tipo_fracc');
        return view('fraccionamiento/vw_conve_fracc',compact('anio','cfracc','tip_f','menu','permisos'));
    }

    public function create(Request $request)
    {
        date_default_timezone_set('America/Lima');
        $data = new Convenio();
        $data->nro_convenio     = $request['nro_convenio'];
        $data->anio             = date('Y');        
        $data->id_contribuyente = $request['id_contribuyente'];
        $data->fec_reg          = date('d-m-Y');
        $data->interes          = $request['interes'];
        $data->nro_cuotas       = $request['nro_cuotas'];        
        $data->total_convenio   = $request['total_convenio'];
        $data->estado           = $request['estado'];
        $data->detalle_fracci   = $request['detalle_fracci'];
        $data->period_desde     = $request['period_desde'];
        $data->period_hast      = $request['period_hast'];
        $data->porc_cuo_inic    = $request['porc_cuo_inic'];
        $data->cuota_inicial    = $request['cuota_inicial'];
        $data->id_tip_fracc     = $request['id_tip_fracc'];
        $data->tipo             = $request['tipo'];
        $data->periodo          = $request['periodo'];
        $data->save();        
        return $data->id_conv;
    }

    public function store(Request $request){}

    public function show($id){}

    public function edit($id){}

    public function update(Request $request, $id){}

    public function destroy($id){}
    
    function list_deuda_contrib(Request $request){
        $id_contrib=$request['id_contrib'];
        $desde=$request['desde'];        
        $hasta=$request['hasta'];
        $totalg = DB::select("select count(*) as total from fraccionamiento.vw_deuda_fracc        
                    where id_contrib='$id_contrib' and anio_deu between '".$desde."' and '".$hasta."'");
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
        
        $sql = DB::select("select * from fraccionamiento.vw_deuda_fracc "
                . "where id_contrib='$id_contrib' and anio_deu between '".$desde."' and '".$hasta."'");
//        $sql = DB::select('');
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        
        $cc=0;
        foreach ($sql as $Index => $Datos) {
            $cc++;
            $Lista->rows[$Index]['id'] = $cc;            
            $Lista->rows[$Index]['cell'] = array(
                $Datos->id_tipo,
                trim($Datos->tipo),   
                trim($Datos->deuda),   
                trim($Datos->anio_deu),
                "<input type='checkbox' value='".$Datos->id_tipo."' name='chk_".$Datos->anio_deu."' onclick='check_tot_fracc(".$Datos->deuda.",this)'>",
            );
        }        
        return response()->json($Lista);
    }
    
    function grid_all_convenios(Request $request){
        $anio = $request['anio'];
        
        $totalg = DB::select("select count(id_conv) as total from fraccionamiento.vw_convenios where anio='".$anio."'");
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

        $sql = DB::table('fraccionamiento.vw_convenios')->where('anio',$anio)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        
        foreach ($sql as $Index => $Datos) {            
            $Lista->rows[$Index]['id'] = $Datos->id_conv;
            $Lista->rows[$Index]['cell'] = array(
                $Datos->nro_convenio,
                trim($Datos->anio),                
                $Datos->id_contribuyente,
                str_replace('-','',$Datos->contribuyente),
                $Datos->fec_reg,
                $Datos->interes,
                $Datos->nro_cuotas,
                trim($Datos->est_actual),
                $Datos->total_convenio
            );
        }        
        return response()->json($Lista);
    }
    
    function fracc_de_contrib(Request $request){
        $id_contrib=$request['id_contrib'];
        $totalg = DB::select("select count(id_conv) as total from fraccionamiento.vw_convenios where id_contribuyente=".$id_contrib);
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

        $sql = DB::table('fraccionamiento.vw_convenios')->where('id_contribuyente',$id_contrib)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        
        foreach ($sql as $Index => $Datos) {            
            $Lista->rows[$Index]['id'] = $Datos->id_conv;
            $Lista->rows[$Index]['cell'] = array(                
                $Datos->nro_convenio,
                $Datos->total_convenio,              
                $Datos->cuota_inicial,
                $Datos->periodo,
                $Datos->desc_tipo,
                $Datos->tip_fracc,
                trim($Datos->est_actual),
                date('d-m-Y',strtotime($Datos->fec_reg))
            );
        }        
        return response()->json($Lista);
    }
    
    function detalle_fracc(Request $request){
        $id_conv=$request['id_conv'];
        $totalg = DB::select("select count(id_conv_mtr) as total from fraccionamiento.vw_trae_cuota_conv where id_conv_mtr=".$id_conv);
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

        $sql = DB::table('fraccionamiento.vw_trae_cuota_conv')->where('id_conv_mtr',$id_conv)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        $array = array();
        $sum = DB::select("select sum(cod_estado) as sum from fraccionamiento.vw_trae_cuota_conv where id_conv_mtr=".$id_conv);
        $array['verif_cancela'] = $sum[0]->sum;
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        $Lista->userdata = $array;
        foreach ($sql as $Index => $Datos) {            
            $Lista->rows[$Index]['id'] = $Datos->id_det_conv;
            if($Datos->cod_estado=='0'){
                $check="<input type='checkbox' value=".$Datos->nro_cuota." onclick='check_tot_mes(".$Datos->total.",this)'>";
            }else{
                $check=$this->getCreatedAtAttribute($Datos->fecha_q_pago)->format('d-M-Y');
            }
            
            $Lista->rows[$Index]['cell'] = array(
                $Datos->nro_cuota,
                $Datos->total,
                $Datos->estado,
                $Datos->cod_estado,
                $this->fecha_mes($Datos->fec_pago),
                $check               
            );
        }        
        return response()->json($Lista);
    }
    
    function crono_pago_fracc(Request $request){
        $id_conv=$request['id_conv'];
        $id_contrib=$request['id_contrib'];
        $amo=0;$inter=0;$cc=0;
        date_default_timezone_set('America/Lima');
        $contrib=DB::select('select * from adm_tri.vw_contribuyentes where id_contrib='.$id_contrib);
        $conv = DB::select('select * from fraccionamiento.vw_convenios where id_contribuyente='.$id_contrib);
        
        
        
        $conv_det = DB::select("select * from fraccionamiento.vw_trae_cuota_conv where id_conv_mtr='".$id_conv."' order by nro_cuota asc");
                
        $todo = array();
        foreach ($conv_det as $Datos) {
            $Lista = new \stdClass();
            $Lista->nro_cuot = $Datos->nro_cuota;
            $Lista->saldo = number_format($Datos->saldo,3,'.','');
            $Lista->amor = number_format($Datos->monto,3,'.','');
            $amo=$amo+number_format($Datos->monto,3,'.','');
            $Lista->inter =  number_format($Datos->interes,3,'.','');
            $inter=$inter+number_format($Datos->interes,3,'.','');
            $Lista->total =  number_format($Datos->total,3,'.','');
            $cc=$cc+number_format($Datos->total,3,'.','');            
            $Lista->fec_pago=$this->getCreatedAtAttribute($Datos->fec_pago)->format('d-M-Y');/*$this->fecha_mes($Datos->fec_pago);*/
            array_push($todo, $Lista);
        }
        $totales=array();
        $totales['tot_amo']= number_format($amo,2,'.',',');
        $totales['tot_inter']=number_format($inter,2,'.',',');
        $totales['tot_cc']=number_format($cc,2,'.',',');
        $fecha_larga = mb_strtoupper($this->getCreatedAtAttribute(date('d-m-Y'))->format('l, d \d\e F \d\e\l Y'));/*$this->fecha_letras(date('F j, Y'));*/
        $view = \View::make('fraccionamiento.reporte.cronogramaPagos',compact('contrib','conv','todo','fecha_larga','totales'))->render();
//        return $view;

        $pdf = \App::make('dompdf.wrapper');            
        $pdf->loadHTML($view)->setPaper('a4');
        return $pdf->stream();            

    }
    
    public function fecha_mes($fecha){
        $fecha=explode("-", $fecha);
            
        $mes=$fecha[1];
        switch ($mes) {
            case '01':
                $fecha[1]='ene';
                break;
            case '02':
                $fecha[1]='feb';
                break;
            case '03':
                $fecha[1]='mar';
                break;
            case '04':
                $fecha[1]='abr';
                break;
            case '05':
                $fecha[1]='may';
                break;
            case '06':
                $fecha[1]='jun';
                break;
            case '07':
                $fecha[1]='jul';
                break;
            case '08':
                $fecha[1]='ago';
                break;
            case '09':
                $fecha[1]='sep';
                break;
            case '10':
                $fecha[1]='oct';
                break;
            case '11':
                $fecha[1]='nov';
                break;
            case '12':
                $fecha[1]='dic';
                break;
        }
        return $fecha[2].'-'.$fecha[1].'-'.$fecha[0];
    }

    public function fecha_letras($date){
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        
        
        $timestamp=strtotime($date);
        return $dias[date('w',$timestamp)].", ".date('d',$timestamp)." de ".$meses[date('n',$timestamp)-1]. " del ".date('Y',$timestamp);
    }
    
    
}
