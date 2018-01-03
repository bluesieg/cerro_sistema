<?php

namespace App\Http\Controllers\Coactiva;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\coactiva\Valores;
use App\Traits\DatesTranslator;

class ReportesController extends Controller
{
    use DatesTranslator;
    public function index(){
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_rep_coa' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        
        $est_exped=DB::select('SELECT * FROM coactiva.estado_exped');
//        $valores=DB::select('SELECT * FROM coactiva.valores');
        return view('coactiva.reportes.vw_reportes_coa',compact('menu','permisos','est_exped'));
    }
    
    function rep_ingresos(){
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_rep_coa_ingresos' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        return view('coactiva.vw_reporte_ingresos',compact('menu','permisos'));
    }
    
    public function create(){}

    public function edit($id){}

    function expedientes(Request $request){
        $desde=$request['desde'];
        $hasta=$request['hasta'];
        $materia=$request['mat'];
        $estado=$request['estado'];
        $valor=$request['valor'];
        
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(isset($materia) && isset($estado) && isset($valor)){
            $totalg = DB::select("select count(id_coa_mtr) as total from coactiva.vw_all_exped "
                . "where fch_ini between '".$desde."' and '".$hasta."' and materia=".$materia." and id_est=".$estado." and id_val=".$valor);
        }elseif(isset($materia) && isset($estado)){
            $totalg = DB::select("select count(id_coa_mtr) as total from coactiva.vw_all_exped "
                . "where fch_ini between '".$desde."' and '".$hasta."' and materia=".$materia." and id_est=".$estado); 
        }elseif(isset($materia) && isset($valor)){            
            $totalg = DB::select("select count(id_coa_mtr) as total from coactiva.vw_all_exped "
                . "where fch_ini between '".$desde."' and '".$hasta."' and materia=".$materia." and id_val=".$valor);
        }elseif(isset($estado) && isset($valor)){
            $totalg = DB::select("select count(id_coa_mtr) as total from coactiva.vw_all_exped "
                . "where fch_ini between '".$desde."' and '".$hasta."' and id_est=".$estado." and id_val=".$valor);
        }elseif(isset($materia)){
            $totalg = DB::select("select count(id_coa_mtr) as total from coactiva.vw_all_exped "
                . "where fch_ini between '".$desde."' and '".$hasta."' and materia=".$materia); 
        }elseif(isset($estado)){
            $totalg = DB::select("select count(id_coa_mtr) as total from coactiva.vw_all_exped "
                . "where fch_ini between '".$desde."' and '".$hasta."' and id_est=".$estado); 
        }elseif(isset($valor)){
            $totalg = DB::select("select count(id_coa_mtr) as total from coactiva.vw_all_exped "
                . "where fch_ini between '".$desde."' and '".$hasta."' and id_val=".$valor);
        }else{
            $totalg = DB::select("select count(id_coa_mtr) as total from coactiva.vw_all_exped "
                . "where fch_ini between '".$desde."' and '".$hasta."'"); 
        }
        
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
        
        
        if(isset($materia) && isset($estado) && isset($valor)){
            $sql = DB::table('coactiva.vw_all_exped')
                ->whereBetween('fch_ini',[$desde,$hasta])
                ->where('materia',$materia)
                ->where('id_est',$estado)
                ->where('id_val',$valor)
                ->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        }elseif(isset($materia) && isset($estado)){
            $sql = DB::table('coactiva.vw_all_exped')
                ->whereBetween('fch_ini',[$desde,$hasta])
                ->where('materia',$materia)
                ->where('id_est',$estado)
                ->orderBy($sidx, $sord)->limit($limit)->offset($start)->get(); 
        }elseif(isset($materia) && isset($valor)){            
            $sql = DB::table('coactiva.vw_all_exped')
                ->whereBetween('fch_ini',[$desde,$hasta])
                ->where('materia',$materia)
                ->where('id_val',$valor)
                ->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        }elseif(isset($estado) && isset($valor)){
            $sql = DB::table('coactiva.vw_all_exped')
                ->whereBetween('fch_ini',[$desde,$hasta])
                ->where('id_val',$valor)
                ->where('id_est',$estado)
                ->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        }elseif(isset($materia)){
            $sql = DB::table('coactiva.vw_all_exped')
                ->whereBetween('fch_ini',[$desde,$hasta])
                ->where('materia',$materia)
                ->orderBy($sidx, $sord)->limit($limit)->offset($start)->get(); 
        }elseif(isset($estado)){
            $sql = DB::table('coactiva.vw_all_exped')
                ->whereBetween('fch_ini',[$desde,$hasta])
                ->where('id_est',$estado)
                ->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        }elseif(isset($valor)){
            $sql = DB::table('coactiva.vw_all_exped')
                ->whereBetween('fch_ini',[$desde,$hasta])
                ->where('id_val',$valor)
                ->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        }else{
            $sql = DB::table('coactiva.vw_all_exped')
                ->whereBetween('fch_ini',[$desde,$hasta])
                ->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        }
        
                
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;

        foreach ($sql as $Index => $Datos) {
            $fecha = DB::table('coactiva.coactiva_documentos')->where([['id_coa_mtr',$Datos->id_coa_mtr],['id_tip_doc',6]])->orderBy('id_doc', 'desc')
                    ->limit(1)->value('fch_recep');
            if(isset($fecha)){
                $nuevafecha = strtotime ( '+7 day' , strtotime ( $fecha ) ) ;
                $nuevafecha = date ( 'd-m-Y' , $nuevafecha );
                $dias = (strtotime(date('Y-m-d'))-strtotime($nuevafecha))/86400;
                $dias = floor($dias).' DIAS';
            }else{
                $dias='';
            }
            if($dias<0){
                $dias='';                
            }
            
            $Lista->rows[$Index]['id'] = $Datos->id_coa_mtr;            
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->nro_exped).'-'.$Datos->anio,
                str_replace('-','',trim($Datos->contribuyente)),
                trim($Datos->desc_mat),
                trim($Datos->ult_gestion),
                trim($Datos->monto),
                $Datos->estado,
                trim($Datos->doc_ini),
                $dias
            );
        }
        return response()->json($Lista); 
    }
    
    function report_exped_coa(Request $request){
        $desde=date('Y-m-d', strtotime(str_replace('/', '-', $request['desde'])));
        $hasta=date('Y-m-d', strtotime(str_replace('/', '-', $request['hasta'])));
        $materia=$request['materia'];
        $estado=$request['estado'];
        $valor=$request['valor'];
        if(isset($materia) && isset($estado) && isset($valor)){
            $sql = DB::table('coactiva.vw_all_exped')
                ->whereBetween('fch_ini',[$desde,$hasta])
                ->where('materia',$materia)
                ->where('id_est',$estado)
                ->where('id_val',$valor)->orderBy('nro_exped','asc')->get();                
        }elseif(isset($materia) && isset($estado)){
            $sql = DB::table('coactiva.vw_all_exped')
                ->whereBetween('fch_ini',[$desde,$hasta])
                ->where('materia',$materia)
                ->where('id_est',$estado)->orderBy('nro_exped','asc')->get();
        }elseif(isset($materia) && isset($valor)){            
            $sql = DB::table('coactiva.vw_all_exped')
                ->whereBetween('fch_ini',[$desde,$hasta])
                ->where('materia',$materia)
                ->where('id_val',$valor)->orderBy('nro_exped','asc')->get();
        }elseif(isset($estado) && isset($valor)){
            $sql = DB::table('coactiva.vw_all_exped')
                ->whereBetween('fch_ini',[$desde,$hasta])
                ->where('id_val',$valor)
                ->where('id_est',$estado)->orderBy('nro_exped','asc')->get();
        }elseif(isset($materia)){
            $sql = DB::table('coactiva.vw_all_exped')
                ->whereBetween('fch_ini',[$desde,$hasta])
                ->where('materia',$materia)->orderBy('nro_exped','asc')->get();
        }elseif(isset($estado)){
            $sql = DB::table('coactiva.vw_all_exped')
                ->whereBetween('fch_ini',[$desde,$hasta])
                ->where('id_est',$estado)->orderBy('nro_exped','asc')->get();
        }elseif(isset($valor)){
            $sql = DB::table('coactiva.vw_all_exped')
                ->whereBetween('fch_ini',[$desde,$hasta])
                ->where('id_val',$valor)->orderBy('nro_exped','asc')->get();
        }else{
            $sql = DB::table('coactiva.vw_all_exped')
                ->whereBetween('fch_ini',[$desde,$hasta])->orderBy('nro_exped','asc')->get();
        }
        
        $desde=date('Y-m-d', strtotime(str_replace('/', '-', $request['desde'])));
        $hasta=date('Y-m-d', strtotime(str_replace('/', '-', $request['hasta'])));
        $desde=$this->getCreatedAtAttribute($desde)->format('d-F-Y');
        $hasta=$this->getCreatedAtAttribute($hasta)->format('d-F-Y');
        $materia2=$request['materia2'] ?? 'TODOS';
        $estado2=$request['estado2'] ?? 'TODOS';
        $valor2=$request['valor2'] ?? 'TODOS';
        
        
        
        $cc=1;
        $ttotal=0;
        $todo = array();
        foreach ($sql as $Datos){
            if($Datos->id_val==2){
                $nro_op = DB::table('recaudacion.orden_pago_master')->where('id_coa_mtr',$Datos->id_coa_mtr)->value('nro_fis');
                $anio_op = DB::table('recaudacion.orden_pago_master')->where('id_coa_mtr',$Datos->id_coa_mtr)->value('anio');
            }
            if($Datos->id_val==1){
//                $nro_op = DB::table('recaudacion.orden_pago_master')->where('id_coa_mtr',$Datos->id_coa_mtr)->value('nro_fis');
//                $anio_op = DB::table('recaudacion.orden_pago_master')->where('id_coa_mtr',$Datos->id_coa_mtr)->value('anio');
            }
            $resol=new \stdClass();
            $resol->cc=$cc++;
            $resol->nro_exped=$Datos->nro_exped;
            $resol->anio=$Datos->anio;
            $resol->estado=$Datos->estado;            
            $resol->doc_ini=$Datos->doc_ini.' - '.$nro_op.' '.$anio_op;
            $resol->monto= number_format($Datos->monto,3,'.',',');
            $resol->contribuyente=$Datos->contribuyente;
            $resol->desc_mat=$Datos->desc_mat;
            $resol->ult_gestion=$Datos->ult_gestion;
            $ttotal=$ttotal+$Datos->monto;
            
            array_push($todo, $resol);
        }
//        dd($todo);
        $view = \View::make('coactiva.reportes.reporte_expedientes_coa',compact('desde','hasta','materia2','estado2','valor2','todo','ttotal'))->render();
        $pdf = \App::make('dompdf.wrapper');            
        $pdf->loadHTML($view)->setPaper('a4','landscape');
        return $pdf->stream();
    }
    
    function trae_valores(Request $request){
        $cod_mat=$request['cod_mat'];
        
        $Consulta = DB::select("SELECT * FROM coactiva.valores where cod_materia=".$cod_mat." and not(id_val) IN (1,2)");

        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();
            $Lista->value = $Datos->id_val;
            $Lista->label = trim($Datos->desc_val);
            array_push($todo, $Lista);
        }
        return response()->json($todo);
    }
    function cbo_valores(Request $request){
        $cod_mat=$request['materia'];
        
        $Consulta = DB::select("SELECT * FROM coactiva.valores where cod_materia=".$cod_mat);

        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();
            $Lista->id_val = $Datos->id_val;
            $Lista->desc_val = trim($Datos->desc_val);
            array_push($todo, $Lista);
        }
        return response()->json($todo);
    }
    
    function new_valor(Request $request){
        $data = new Valores();        
        $data->desc_val = $request['desc_val'];
        $data->abrev_val = $request['abrev'];
        $data->cod_materia = $request['cod_mat'];   
        $data->materia = $request['desc_mat']; 
        $data->save();
        
        return response()->json([
            'id_val' => $data->id_val,
            'desc_val'=>$data->desc_val
        ]);
//        return $data->id_val;
    }
}
