<?php

namespace App\Http\Controllers\tesoreria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades;
use App\Models\Recibos_Master;
use App\Models\Personas;
use Illuminate\Support\Facades\Auth;
use App\Models\Pgo_Arbitrios;
use App\Models\Recibos_Detalle;

class Recibos_MasterController extends Controller
{    
    public function index(Request $request)
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_tesoreria_emi_rec_pag' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $tip_doc = DB::table('adm_tri.tipo_documento')->get();
        $anio = DB::table('presupuesto.vw_anio_para_recibos')->orderBy('anio','desc')->get();
        return view('tesoreria/vw_emision_rec_pago',compact('tip_doc','anio','menu','permisos'));        
    }
    
    public function create(Request $request)
    {
        date_default_timezone_set('America/Lima');
        $data = new Recibos_Master();
        $data->nro_recibo_mtr=0;
        $data->periodo    = date('Y');
        $data->fecha      = date('d-m-Y');
        $data->hora       = date('h:i:s A');
        $data->id_usuario = Auth::user()->id;
        $data->id_est_rec = $request['id_est_rec'];
        $data->id_caja    = 1;        
        $data->hora_pago  = "";
        $data->glosa      = $request['glosa'];
        $data->total      = $request['total'];
        $data->id_tip_pago= 0;
        $data->id_contrib = $request['id_pers'];
        $data->id_tribut_master=0;
        $data->cod_fracc  = $request['cod_fracc'] ?? 0 ;
        $data->n_cuot     = 0;
        $data->clase_recibo=$request['clase_recibo'];
        $data->pred_check = $request['pred_check'] ?? 0;
        $data->form_pred_check = $request['form_pred_check'] ?? 0;
        $data->fracc_check = $request['fracc_check'] ?? 0;
//        if(isset($request['pred_check'])){
//            $data->pred_check = $request['pred_check'];
//        }else{
//            $data->pred_check = 0;
//        }
//        if(isset($request['form_pred_check'])){
//            $data->form_pred_check = $request['form_pred_check'];
//        }else{
//            $data->form_pred_check = 0;
//        }
//        if(isset($request['fracc_check'])){
//            $data->fracc_check = $request['fracc_check'];
//        }else{
//            $data->fracc_check = 0;
//        }
        $data->save();        
        return $data->id_rec_mtr;
    }
   
    public function store(Request $request)
    {
        //
    }
   
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
   

    
    function tabla_cta_cte_2(Request $request){
        $id_contrib = $request['id_contrib'];
        $ano_cta = $request['ano_cta'];
        $totalg = DB::select("select count(id_contrib) as total from adm_tri.vw_cta_cte2 where id_contrib='".$id_contrib."' and ano_cta='".$ano_cta."'");
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

        $sql = DB::table('adm_tri.vw_cta_cte2')->where('id_contrib',$id_contrib)->where('ano_cta',$ano_cta)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        
        foreach ($sql as $Index => $Datos) {            
            $Lista->rows[$Index]['id'] = $Datos->id_tribu;
            $Lista->rows[$Index]['cell'] = array(
                $Datos->id_tribu,
                //$Datos->id_contrib,
                trim($Datos->descrip_tributo),
                trim($Datos->ivpp),
                trim($Datos->saldo),                
                trim($Datos->abo1_cta),                
                trim($Datos->abo2_cta),
                trim($Datos->abo3_cta),
                trim($Datos->abo4_cta),
                $Datos->id_conv_mtr,
                $Datos->id_coa_mtr                
            );
        }        
        return response()->json($Lista);
    }
    
    function tabla_cta_arbitrios(Request $request){
        $id_contrib = $request['id_contrib'];
        $anio=$request['anio'];
        if($id_contrib=='0')
        {
            return 0;
        }
        else
        {
            $totalg = DB::select("select count(id_contrib) as total from adm_tri.vw_predi_urba where id_contrib='".$id_contrib."' and tip_pre_u_r=1 and anio=".$anio);
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

            $sql = DB::table('adm_tri.vw_predi_urba')->where('id_contrib',$id_contrib)->where('tip_pre_u_r',1)->where('anio',$anio)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        $cont=0;
        foreach ($sql as $Index => $Datos) {
            $cont++;
                $Lista->rows[$Index]['id'] = $Datos->id_pred_anio;
            $Lista->rows[$Index]['cell'] = array(
                    $Datos->id_pred_anio,
                trim($Datos->id_contrib),                
                $Datos->sec,
                $Datos->mzna,
                $Datos->lote,
                trim($Datos->contribuyente),
                trim($Datos->tp),
                trim($Datos->descripcion),                
                trim($Datos->val_ter),                
                trim($Datos->val_const)                       
            );
        }        
        return response()->json($Lista);
    }
    }
    
    function cta_pago_arbitrios(Request $request){
        $id_contrib = $request['id_contrib'];
        if($id_contrib=='0')
        {
            return 0;
        }
        else
        {
        $id_pred = $request['id_pred'];
        $anio=$request['anio'];
            $totalg = DB::select("select count(*) as total from arbitrios.vw_cta_arbitrios where id_contrib='".$id_contrib."' and id_pred_anio='".$id_pred."'");
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

            $sql = DB::table('arbitrios.vw_cta_arbitrios')->where('id_contrib',$id_contrib)->where('id_pred_anio',$id_pred)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        $cont=0;
        foreach ($sql as $Index => $Datos) {
            $cont++;
            $Lista->rows[$Index]['id'] = $Datos->id_cta_arb;
            $Lista->rows[$Index]['cell'] = array(
                $Datos->id_cta_arb,
                    trim($Datos->id_contrib),                
                    trim($Datos->uso_arbitrio),                
                    trim($Datos->cod_piso),                
                trim($Datos->descripcion),                
                trim($Datos->pgo_ene),                
                trim($Datos->abo_ene),
                trim($Datos->pgo_feb),                
                trim($Datos->abo_feb), 
                trim($Datos->pgo_mar),                
                trim($Datos->abo_mar), 
                trim($Datos->pgo_abr),                
                trim($Datos->abo_abr), 
                trim($Datos->pgo_may),                
                trim($Datos->abo_may), 
                trim($Datos->pgo_jun),                
                trim($Datos->abo_jun), 
                trim($Datos->pgo_jul),                
                trim($Datos->abo_jul), 
                trim($Datos->pgo_ago),                
                trim($Datos->abo_ago), 
                trim($Datos->pgo_sep),                
                trim($Datos->abo_sep), 
                trim($Datos->pgo_oct),                
                trim($Datos->abo_oct),
                trim($Datos->pgo_nov),                
                trim($Datos->abo_nov), 
                trim($Datos->pgo_dic),                
                trim($Datos->abo_dic),
                    $Datos->deuda_arb
            );
        }        
        return response()->json($Lista);
    }
    }
    
    function tabla_Resumen_recibos(Request $request){
        $fecha = $request['fecha'];
        $totalg = DB::select("select count(id_rec_mtr) as total from tesoreria.vw_recibos_resumen where fecha='" . $fecha . "'");
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

        $sql = DB::table('tesoreria.vw_recibos_resumen')->where('fecha',$fecha)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
//        $sql=DB::select("select * from tesoreria.vw_recibos_resumen where fecha='".$fecha."' order by id_contrib asc, id_rec_mtr desc limit 20 offset 0");
        
        $suma = DB::select("select sum(total) as sum_total from tesoreria.vw_recibos_resumen where fecha='" . $fecha . "'");
        $array= array();
        $array['sum_total']=$suma[0]->sum_total;
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        $Lista->userdata=$array;
        
        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_rec_mtr;            
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_rec_mtr),
                trim($Datos->id_contrib),
                trim($Datos->nro_recibo_mtr),
                trim(date('d-m-Y', strtotime($Datos->fecha))),
                trim($Datos->glosa),                
                trim($Datos->estad_recibo),
                trim($Datos->descrip_caja),
                trim($Datos->hora_pago),
                trim($Datos->total)
            ); 
           
        }        
        return response()->json($Lista);
    }
    
    function completar_tributo(Request $request){
        $tributo = strtoupper($request['tributo']);

        //$Consulta = DB::table('presupuesto.vw_tributos_vladi')->where('tributo', 'like', '%'.$tributo.'%')->get();
        $sql= DB::select("select id_tributo,tributo,para_recibo,soles from presupuesto.vw_tributos_vladi where tributo like " . "'%".$tributo."%'" . "and anio = (select date_part('year',current_date))");

        $todo = array();
        foreach ($sql as $Datos) {
            $Lista = new \stdClass();
            $Lista->value = $Datos->id_tributo;
            $Lista->p_recibo = $Datos->para_recibo;
            $Lista->label = trim($Datos->tributo);
            $Lista->soles = $Datos->soles;
            
            array_push($todo, $Lista);
        }
        return response()->json($todo);
    }
    
    function buscar_persona(Request $request){
        $nro_doc= $request['nro_doc'];
        $persona = DB::table('adm_tri.vw_personas')->where('pers_nro_doc',$nro_doc)->first();
        if(isset($persona->pers_raz_soc)){
            return response()->json(['raz_soc'=>$persona->contribuyente,'msg'=>'si','id_pers'=>$persona->id_pers]);
        }else{
            return response()->json(['msg'=>'no']);
        }
    }
    
    function insert_new_persona(Request $request){        
        $personas = new Personas();
        $personas->pers_raz_soc=$request['pers_raz_soc'];        
        $personas->pers_nro_doc= $request['pers_nro_doc'];
        $personas->save();        
        return $personas->id_pers;
    }
    
    function verif_est_cta(Request $request){
        $check=str_split($request['check']);
        $id_contrib=$request['id_contrib'];
        
//        echo $check[0].',';
//        echo end($check);
        
        $array =  array();
        for($i=$check[0];$i<=end($check);$i++){
            $sql = DB::table('adm_tri.vw_cta_cte2')->where('id_contrib',$id_contrib)->where('id_tribu',103)->value('trim'.$i.'_est');
            if($sql==2){ 
                $array[]=$i;
            }
        }

//        print_r($array);
        return $array;
//        dd($array);
        
    }
    function edit_arbitrio(Request $request){
        $check=explode("and",$request['check']);
        $id_contrib=$request['id_contrib'];
        $anio=$request['anio'];
        $total=0;
        $array =  array();
        foreach($check as $arbitrios)
        {
            $pago=explode("-",$arbitrios); 
            $total+=$this->edit_pgo_arbtrio($pago[0],$pago[1]);
        }
        $idmaster=$this->create_rec_arb($total,$id_contrib);
        $this->create_rec_det_arb($idmaster,$anio,$total);
        return $idmaster;
    }
    public function create_rec_arb($total,$id_contrib)
    {
        date_default_timezone_set('America/Lima');
        $data = new Recibos_Master();
        $data->nro_recibo_mtr=0;
        $data->periodo    = date('Y');
        $data->fecha      = date('d-m-Y');
        $data->hora       = date('h:i:s A');
        $data->id_usuario = Auth::user()->id;
        $data->id_est_rec = 1;
        $data->id_caja    = 1;        
        $data->hora_pago  = "";
        $data->glosa      = "PAGO ARBITRIOS";
        $data->total      = $total;
        $data->id_tip_pago= 0;
        $data->id_contrib = $id_contrib;
        $data->id_tribut_master=0;
        $data->cod_fracc  = 0 ;
        $data->n_cuot     = 0;
        $data->clase_recibo=2;
        
        $data->save();        
        return $data->id_rec_mtr;
    }
    public function create_rec_det_arb($id,$anio,$total)
    {
        date_default_timezone_set('America/Lima');
        $rec_det = new Recibos_Detalle(); 
        $rec_det->id_rec_master=$id;
        $rec_det->periodo=$anio;
        $rec_det->id_ofi=0;
        $rec_det->id_trib=0;
        $rec_det->monto=$total;
        $rec_det->cant=1;
        $rec_det->p_unit=$total;
        $rec_det->save();
        return $rec_det->id_rec_det;
    }
    
    public function edit_pgo_arbtrio($id,$mes)
    {
        $pago=new Pgo_Arbitrios();
        $total=0;
        $val=  $pago::where("id_cta_arb","=",$id )->first();
        if(count($val)>=1)
        {
            $sql = DB::table('arbitrios.cta_arbitrios')->where('id_cta_arb',$id)->get();
            if($mes==1)
            {
                $val->abo_ene = $sql[0]->pgo_ene;
                $val->fec_pag_ene = date("d/m/Y");
                $total=$sql[0]->pgo_ene;
            }
            if($mes==2)
            {
                $val->abo_feb = $sql[0]->pgo_feb;
                $val->fec_pag_feb = date("d/m/Y");
                $total=$sql[0]->pgo_feb;
            }
            if($mes==3)
            {
                $val->abo_mar = $sql[0]->pgo_mar;
                $val->fec_pag_mar = date("d/m/Y");
                $total=$sql[0]->pgo_mar;
            }
            if($mes==4)
            {
                $val->abo_abr = $sql[0]->pgo_abr;
                $val->fec_pag_abr = date("d/m/Y");
                $total=$sql[0]->pgo_abr;
            }
            if($mes==5)
            {
                $val->abo_may = $sql[0]->pgo_may;
                $val->fec_pag_may = date("d/m/Y");
                $total=$sql[0]->pgo_may;
            }
            if($mes==6)
            {
                $val->abo_jun = $sql[0]->pgo_jun;
                $val->fec_pag_jun = date("d/m/Y");
                $total=$sql[0]->pgo_jun;
            }
            if($mes==7)
            {
                $val->abo_jul = $sql[0]->pgo_jul;
                $val->fec_pag_jul = date("d/m/Y");
                $total=$sql[0]->pgo_jul;
            }
            if($mes==8)
            {
                $val->abo_ago = $sql[0]->pgo_ago;
                $val->fec_pag_ago = date("d/m/Y");
                $total=$sql[0]->pgo_ago;
            }
            if($mes==9)
            {
                $val->abo_sep = $sql[0]->pgo_sep;
                $val->fec_pag_sep = date("d/m/Y");
                $total=$sql[0]->pgo_sep;
            }
            if($mes==10)
            {
                $val->abo_oct = $sql[0]->pgo_oct;
                $val->fec_pag_oct = date("d/m/Y");
                $total=$sql[0]->pgo_oct;
            }
            if($mes==11)
            {
                $val->abo_nov = $sql[0]->pgo_nov;
                $val->fec_pag_nov = date("d/m/Y");
                $total=$sql[0]->pgo_nov;
            }
            if($mes==12)
            {
                $val->abo_dic = $sql[0]->pgo_dic;
                $val->fec_pag_dic = date("d/m/Y");
                $total=$sql[0]->pgo_dic;
            }
            $val->save();
        }
        return $total;
    }
}
