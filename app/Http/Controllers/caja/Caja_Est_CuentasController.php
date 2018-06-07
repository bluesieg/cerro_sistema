<?php

namespace App\Http\Controllers\caja;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Traits\DatesTranslator;
use Illuminate\Support\Facades\Auth;

class Caja_Est_CuentasController extends Controller
{
    use DatesTranslator;

    public function index(){
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_vent_est_cta' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio = DB::select('select anio from adm_tri.uit order by anio desc');
        $anio1 = DB::select('select anio from adm_tri.uit order by anio asc');
        return view('caja/vw_caja_est_cuentas',compact('anio','anio1','menu','permisos'));
    }
    function vw_fracc_est_cta(){
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_vent_est_cta_fracc' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio = DB::select('select anio from adm_tri.uit order by anio desc');
        return view('caja/vw_fracc_est_cta',compact('anio','menu','permisos'));
    }
    function conv_fracc_estcta(Request $request){
        $id_contrib=$request['id_contrib'];      
        $totalg = DB::select("select count(id_conv) as total from fraccionamiento.vw_convenios where id_contribuyente='".$id_contrib."'");
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
    function get_det_fracc(Request $request){
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
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        $fch_q_pago="";
        foreach ($sql as $Index => $Datos) {
            if($Datos->fecha_q_pago){
                $fch_q_pago=$this->getCreatedAtAttribute($Datos->fecha_q_pago)->format('d-F-Y');
            }else{
                $fch_q_pago="";
            }
            $Lista->rows[$Index]['id'] = $Datos->id_det_conv;
            $Lista->rows[$Index]['cell'] = array(
                $Datos->nro_cuota,
                $this->getCreatedAtAttribute($Datos->fec_pago)->format('d-F-Y'),
                $Datos->estado,
                $fch_q_pago,
                $Datos->total,
            );
        }        
        return response()->json($Lista);
    }
    
    function caja_est_cuentas(Request $request){
        $id_pers = $request['id_pers'];
        $desde = $request['desde'];
        $hasta = $request['hasta'];
        $totalg = DB::select("select count(id_contrib) as total from adm_tri.estado_cuentas_vlady where id_contrib='".$id_pers."' and ano_cta between ".$desde." and ".$hasta);
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

        $sql = DB::table('adm_tri.estado_cuentas_vlady')->where('id_contrib',$id_pers)->whereBetween('ano_cta',[$desde,$hasta])
                ->orderBy($sidx, $sord)->orderBy('trim','asc')->limit($limit)->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        
        $cc=0;
        foreach ($sql as $Index => $Datos) {  
            $cc++;
            $Lista->rows[$Index]['id'] = $cc;
            $Lista->rows[$Index]['cell'] = array(
                $cc,
                trim($Datos->id_contrib),
                trim($Datos->ano_cta),
                trim($Datos->trim),                
                trim($Datos->descrip_tributo),                
                trim($Datos->cuota),
                trim($Datos->abono),
                trim($Datos->fecha),
                trim($Datos->total)               
            );
        }        
        return response()->json($Lista);
    }
    
    function print_est_cta_contrib($id_contrib,$desde,$hasta,Request $request){
        $foto_estado=$request['foto']; 
        $fracc="";
        $foto=DB::select('select pers_foto from adm_tri.vw_contribuyentes1 where id_contrib='.$id_contrib);
        $contrib1=DB::select('select * from adm_tri.vw_contribuyentes where id_contrib='.$id_contrib);
        $contrib = DB::table("adm_tri.vw_est_cta_2018")->where('id_pers',$id_contrib)->whereBetween('ano_cta', [$desde, $hasta])->get();
        $convenio=DB::select('select * from fraccionamiento.vw_convenios where id_contribuyente='.$id_contrib);
        if(count($convenio) > 1){
            $fracc = DB::select("select * from fraccionamiento.detalle_convenio where id_conv_mtr=".$convenio[0]->id_conv." order by nro_cuota");
        }
        $usuario = DB::select('SELECT * from public.usuarios where id='.Auth::user()->id);
        $arb = DB::select('select * from arbitrios.vw_cta_arbi_x_trim where id_contrib='.$id_contrib.' and anio between '.$desde.' and '.$hasta);
        $imp=DB::select('select adm_tri.calcula_reajuste_ipm('.$id_contrib.','.$desde.')');
        $imp=DB::select('select adm_tri.calcula_reajuste_ipm('.$id_contrib.','.$hasta.')');
        //$tim=DB::select('select adm_tri.calcula_tim('.$id_contrib.','.$desde.')');
        //$tim=DB::select('select adm_tri.calcula_tim('.$id_contrib.','.$hasta.')');
        $pred = DB::select('select * from adm_tri.vw_cta_cte2 where id_contrib='.$id_contrib.' and ano_cta between '.$desde.' and '.$hasta);
        $institucion = DB::select('SELECT * FROM maysa.institucion');
        $fecha = (date('d/m/Y H:i:s'));
        $view = \View::make('caja.reportes.est_cta_contrib',compact('contrib','contrib1','fecha','pred','desde','hasta','convenio','fracc','foto','usuario','foto_estado','hora','institucion'))->render();
        
//        $sql=DB::select("select * from adm_tri.cta_cte where id_pers=".$id_contrib."and ano_cta='".$hasta."'");
//        
//        $view = \View::make('caja.reportes.est_cta_contrib',compact('contrib','fecha_larga','hasta'))->render();
        
        
        
        $pdf = \App::make('dompdf.wrapper');            
        $pdf->loadHTML($view)->setPaper('a5','landscape');
        return $pdf->stream();
    }
        
    function print_estcta_fracc($id_contrib,$id_conv){
        
        $conv = DB::select('select * from fraccionamiento.vw_convenios where id_contribuyente='.$id_contrib);
        $fracc = DB::select("select * from fraccionamiento.vw_trae_cuota_conv where id_conv_mtr=".$id_conv." order by nro_cuota");
        $cc=$fracc[0]->total;
        $contrib=DB::select('select * from adm_tri.vw_contribuyentes where id_contrib='.$id_contrib);
        
        $fecha_larga = mb_strtoupper($this->getCreatedAtAttribute(date('d-m-Y'))->format('l, d \d\e F \d\e\l Y'));
        $view = \View::make('caja.reportes.est_cta_fracc',compact('contrib','conv','fecha_larga','fracc'))->render();
        $pdf = \App::make('dompdf.wrapper');            
        $pdf->loadHTML($view)->setPaper('a4');
        return $pdf->stream();            

    }
    
    public function correo(request $request){

        $pathToFile="";
        $containfile=false; 
        if($request->hasFile('file') ){
           $containfile=true; 
           $file = $request->file('file');
           $nombre=$file->getClientOriginalName();
           $pathToFile= storage_path('app') ."/". $nombre;
        }
        
        $persona=$request['persona'];
        $correo=$request['correo'];
        
        set_time_limit(0);
        ini_set('memory_limit', '1G');
        
        $email = \Mail::send('caja.reportes.email', compact('persona'), function ($message) use($persona,$correo,$containfile,$pathToFile) {

            $message->from('gzlcentenoz@gmail.com', 'Municipalidad Distrital de Cerro Colorado');

            $message->to($correo)->subject('Reporte de Estado de Cuenta');
            
            $message->cc('gzlcentenoz@gmail.com');
            
            if($containfile){
                $message->attach($pathToFile);
            }

        });
        
        if(count($email)>0){          
            return "Hubo un problema con el correo";   
        }
        else
        {     
            if($containfile){ \Storage::disk('local')->delete($nombre); } 
            return "Tú email ha sido enviado correctamente";   
        }

    }
    
    public function cargar_archivo_correo(request $request){


        if($request->hasFile('file') ){ 
         
        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $nombre=$file->getClientOriginalName();
        $r= \Storage::disk('local')->put($nombre,  \File::get($file));
       

         } 
         else{

            return "no";
         } 

        if($r){
            return $nombre ;
        }
        else
        {
            return "error vuelva a intentarlo";
        }
    }
}
