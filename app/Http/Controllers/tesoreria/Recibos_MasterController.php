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
use App\Models\CtaCte;

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
        $data->direccion      = $request['direccion'];
        $data->id_tip_pago= 0;
        $data->id_contrib = $request['id_pers'];
        $data->id_tribut_master=0;
        $data->cod_fracc  = $request['cod_fracc'] ?? 0 ;
        $data->n_cuot     = 0;
        $data->clase_recibo=$request['clase_recibo'];
        $data->t1=$request['t1'];
        $data->t2=$request['t2'];
        $data->t3=$request['t3'];
        $data->t4=$request['t4'];
        $data->fracc_check = $request['fracc_check'] ?? 0;
        $data->pgo_acta = $request['acuenta'];
        if($request['acuenta']=='1')
        {
            $data->total      = $request['monto_acuenta'];
        }
        else
        {
            $data->total      = $request['total'];
        }   
        if($request->recibo == null){
            $data->id_alcab=0;
        }else{
            $data->id_alcab = $request['recibo'];
        }
        $data->save();
        if($request['montoform']>0&&$request['acuenta']==0)
        {
            $count=$this->edit_cta_cte('1-2-3-4',$data->id_rec_mtr,$request['id_pers'],$request['periodo'],$request['id_trib_form']);
            $this->detalle_create($request['periodo'],$data->id_rec_mtr,$request['id_trib_form'],$request['montoform'],1,'del '.$request['periodo']);
        }
        if($request['montopre']>0&&$request['acuenta']==0)
        {
            $count=$this->edit_cta_cte($request['trimestres'],$data->id_rec_mtr,$request['id_pers'],$request['periodo'],$request['id_trib_pred']);
            $this->detalle_create($request['periodo'],$data->id_rec_mtr,$request['id_trib_pred'],$request['montopre'],$count,$request['detalle_trimestres'].' del '.$request['periodo']);
        }
        if($request['acuenta']==1)
        {
            $this->detalle_create($request['periodo'],$data->id_rec_mtr,$request['id_trib_pred'],$request['monto_acuenta'],1,'PAGO A CUENTA PREDIAL');
        }
        if($request['tim']>0)
        {
            $trib_tim = DB::select('SELECT * from presupuesto.vw_tim where anio='.$request['periodo']);
            $this->detalle_create($request['periodo'],$data->id_rec_mtr,$trib_tim[0]->id_tributo,$request['tim'],1,'del '.$request['periodo']);
        }
        if($request['multa']>0&&$request['acuenta']==0)
        {
            $count=$this->edit_cta_cte('1',$data->id_rec_mtr,$request['id_pers'],$request['periodo'],$request['id_trib_multa']);
            $this->detalle_create($request['periodo'],$data->id_rec_mtr,$request['id_trib_multa'],$request['multa'],1,'Multa del '.$request['periodo']);
        }
        if($request['tim_multa']>0)
        {
            $trib_tim = DB::select('SELECT * from presupuesto.vw_tim where anio='.$request['periodo']);
            $this->detalle_create($request['periodo'],$data->id_rec_mtr,$trib_tim[0]->id_tributo,$request['tim_multa'],1,'de Multa del '.$request['periodo']);
        }
        return $data->id_rec_mtr;
    }
    function edit_cta_cte($trimestres,$id_rec_mtr,$id_contrib,$anio,$id_tribu){
        $check=explode("-",$trimestres);
        $cuenta=new CtaCte();
        $val = $cuenta::where("id_pers", $id_contrib)->where("id_tribu",$id_tribu)->where("ano_cta",$anio)->first();
        if (count($val) >= 1) 
        {   
            $count=0;
            foreach($check as $cta)
            {
                $count++;
                if($cta=='1')
                {
                    $val->id_rec_trim1=$id_rec_mtr;
                    $val->flg_rec_trim1=1;
                }
                if($cta=='2')
                {
                    $val->id_rec_trim2=$id_rec_mtr;
                    $val->flg_rec_trim2=1;
                }
                if($cta=='3')
                {
                    $val->id_rec_trim3=$id_rec_mtr;
                    $val->flg_rec_trim3=1;
                }
                if($cta=='4')
                {
                    $val->id_rec_trim4=$id_rec_mtr;
                    $val->flg_rec_trim4=1;
                }
            }
        }
        $val->save();
        return $count;
    }
    public function detalle_create($periodo,$id_rec_mtr,$id_trib,$monto,$cant,$detalle_trimestres)
    {
        date_default_timezone_set('America/Lima');
        $rec_det = new Recibos_Detalle(); 
        $rec_det->id_rec_master=$id_rec_mtr;
        $rec_det->periodo=$periodo;
        $rec_det->id_ofi=0;
        $rec_det->id_trib=$id_trib;
        $rec_det->monto=$monto;
        $rec_det->cant=$cant;
        $rec_det->p_unit=$monto/$cant;
        $rec_det->detalle_trimestres=$detalle_trimestres;
        $rec_det->save();
        return $rec_det->id_rec_det;
    }
   
    public function store(Request $request)
    {
    }
   
    public function show($id, Request $request)
    {
        if ($id > 0) 
        {
            
        }
        else
        {
            if ($request['tipo'] == 1) 
            {
                return $this->validaciones($request);
            }
            if ($request['tipo'] == 2) 
            {
                return $this->validar_alcabala($request);
            }
        }
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
    
    function tabla_cta_cte_2(Request $request){
        $id_contrib = $request['id_contrib'];
        $ano_cta = $request['ano_cta'];
        $imp=DB::select('select adm_tri.calcula_reajuste_ipm('.$id_contrib.','.$ano_cta.')');
        $tim=DB::select('select adm_tri.calcula_tim('.$id_contrib.','.$ano_cta.')');
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
            $saldo=number_format(($Datos->car1_cta-$Datos->abo1_cta)+($Datos->car2_cta-$Datos->abo2_cta)+($Datos->car3_cta-$Datos->abo3_cta)+($Datos->car4_cta-$Datos->abo4_cta),2,".",",");
            if($saldo<0){
                $saldo=0;
            }
            if($Datos->flg_rec_trim1==1)
            {   $Datos->abo1_cta='Recibo Pendiente:'.$Datos->id_rec_trim1; }
            else{
                $Datos->abo1_cta=trim($Datos->car1_cta-$Datos->abo1_cta);
                if($Datos->abo1_cta<0){
                    $Datos->abo1_cta=0;
                }
            }
            if($Datos->flg_rec_trim2==1)
            {   $Datos->abo2_cta='Recibo Pendiente:'.$Datos->id_rec_trim2; }
            else
            {
                $Datos->abo2_cta=trim($Datos->car2_cta-$Datos->abo2_cta);
                 if($Datos->abo2_cta<0){
                    $Datos->abo2_cta=0;
                }
            }
            if($Datos->flg_rec_trim3==1)
            {   $Datos->abo3_cta='Recibo Pendiente:'.$Datos->id_rec_trim3; }
            else
            {
                $Datos->abo3_cta=trim($Datos->car3_cta-$Datos->abo3_cta);
                 if($Datos->abo3_cta<0){
                    $Datos->abo3_cta=0;
                }
            }
            if($Datos->flg_rec_trim4==1)
            {   $Datos->abo4_cta='Recibo Pendiente:'.$Datos->id_rec_trim4; }
            else
            {
                $Datos->abo4_cta=trim($Datos->car4_cta-$Datos->abo4_cta);
                 if($Datos->abo4_cta<0){
                    $Datos->abo4_cta=0;
                }
            }
            $Lista->rows[$Index]['id'] = $Datos->id_tribu;
            $Lista->rows[$Index]['cell'] = array(
                $Datos->id_tribu,
                //$Datos->id_contrib,
                trim($Datos->descrip_tributo),
                //trim($Datos->ivpp),
                number_format($Datos->car1_cta+$Datos->car2_cta+$Datos->car3_cta+$Datos->car4_cta,2,".",","),
                //trim($Datos->saldo), 
                $saldo,
                //number_format(($Datos->car1_cta-$Datos->abo1_cta)+($Datos->car2_cta-$Datos->abo2_cta)+($Datos->car3_cta-$Datos->abo3_cta)+($Datos->car4_cta-$Datos->abo4_cta),2,".",","),
                $Datos->abo1_cta,                
                $Datos->abo2_cta,
                $Datos->abo3_cta,
                $Datos->abo4_cta,
                $Datos->id_conv_mtr,
                $Datos->id_coa_mtr,
                trim($Datos->tim1_cta),                
                trim($Datos->tim2_cta),
                trim($Datos->tim3_cta),
                trim($Datos->tim4_cta),
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
            $totalg = DB::select("select count(id_contrib) as total from adm_tri.vw_grid_predios where id_contrib='".$id_contrib."' and tip_pre_u_r=1 and anio=".$anio);
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

            $sql = DB::table('adm_tri.vw_grid_predios')->where('id_contrib',$id_contrib)->where('tip_pre_u_r',1)->where('anio',$anio)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        $cont=0;
        foreach ($sql as $Index => $Datos) {
            $dir=$Datos->nom_via;
            if($Datos->nro_mun!=null&&$Datos->nro_mun!="-")
            {
                $dir=$dir." ".$Datos->nro_mun;
            }
            if($Datos->mzna_dist!=null&&$Datos->mzna_dist!="-")
            {
                $dir=$dir." Mzna ".$Datos->mzna_dist;
            }
            if($Datos->lote_dist!=null&&$Datos->lote_dist!="-")
            {
                $dir=$dir." Lt ".$Datos->lote_dist;
            }
            if($Datos->referencia!=null&&$Datos->referencia!="-")
            {
                $dir=$dir." ".$Datos->referencia;
            }
            $cont++;
                $Lista->rows[$Index]['id'] = $Datos->id_pred_anio;
            $Lista->rows[$Index]['cell'] = array(
                    $Datos->id_pred_anio,
                trim($Datos->id_contrib), 
                $Datos->sec.$Datos->mzna.$Datos->lote,
                trim($dir),
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
        if($totalg[0]->total>0)
        {
            $trib_barrido=DB::table('presupuesto.vw_barrido')->select('id_tributo')->where('anio',$sql[0]->anio)->first();
            $trib_recojo=DB::table('presupuesto.vw_limpieza')->select('id_tributo')->where('anio',$sql[0]->anio)->first();
            $trib_seguridad=DB::table('presupuesto.vw_serenazgo')->select('id_tributo')->where('anio',$sql[0]->anio)->first();
            $trib_parques=DB::table('presupuesto.vw_parques')->select('id_tributo')->where('anio',$sql[0]->anio)->first();
        
        }
        
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        $cont=0;
        foreach ($sql as $Index => $Datos) {
            if(trim($Datos->descripcion)=='PARQUES Y JARDINES')
            {
                $tributo=$trib_parques->id_tributo;
            }
            if(trim($Datos->descripcion)=='SEGURIDAD CIUDADANA')
            {
                $tributo=$trib_seguridad->id_tributo;
            }
            if(trim($Datos->descripcion)=='RECOJO DE RESIDUOS SÓLIDOS')
            {
                $tributo=$trib_recojo->id_tributo;
            }
            if(trim($Datos->descripcion)=='BARRIDO DE CALLES')
            {
                $tributo=$trib_barrido->id_tributo;
            }
            if($Datos->flg_rec_ene==1)
            {   $Datos->abo_ene='Recibo Pendiente:'.$Datos->id_rec_ene; }
            if($Datos->flg_rec_feb==1)
            {   $Datos->abo_feb='Recibo Pendiente:'.$Datos->id_rec_feb; }
            if($Datos->flg_rec_mar==1)
            {   $Datos->abo_mar='Recibo Pendiente:'.$Datos->id_rec_mar; }
            if($Datos->flg_rec_abr==1)
            {   $Datos->abo_abr='Recibo Pendiente:'.$Datos->id_rec_abr; }
            if($Datos->flg_rec_may==1)
            {   $Datos->abo_may='Recibo Pendiente:'.$Datos->id_rec_may; }
            if($Datos->flg_rec_jun==1)
            {   $Datos->abo_jun='Recibo Pendiente:'.$Datos->id_rec_jun; }
            if($Datos->flg_rec_jul==1)
            {   $Datos->abo_jul='Recibo Pendiente:'.$Datos->id_rec_jul; }
            if($Datos->flg_rec_ago==1)
            {   $Datos->abo_ago='Recibo Pendiente:'.$Datos->id_rec_ago; }
            if($Datos->flg_rec_sep==1)
            {   $Datos->abo_sep='Recibo Pendiente:'.$Datos->id_rec_sep; }
            if($Datos->flg_rec_oct==1)
            {   $Datos->abo_oct='Recibo Pendiente:'.$Datos->id_rec_oct; }
            if($Datos->flg_rec_nov==1)
            {   $Datos->abo_nov='Recibo Pendiente:'.$Datos->id_rec_nov; }
            if($Datos->flg_rec_dic==1)
            {   $Datos->abo_dic='Recibo Pendiente:'.$Datos->id_rec_dic; }
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
                    $Datos->deuda_arb,
                    $tributo
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
        $personas = DB::table('adm_tri.vw_personas')->where('pers_nro_doc',$nro_doc)->get();
        $contribuyentes =DB::table('adm_tri.vw_contribuyentes')->where('nro_doc',$nro_doc)->get();
        if(count($personas)>0){
            if(count($contribuyentes)>0)
            {
                return response()->json([
                'contrib' => trim($personas[0]->contribuyente),
                'id_pers' => trim(str_replace('-','',$personas[0]->id_pers)),
                'direccion'=> $contribuyentes[0]->dom_fis,
                'msg'=>'si',
                ]);
            }
            else{
                return response()->json([
                'contrib' => trim($personas[0]->contribuyente),
                'id_pers' => trim(str_replace('-','',$personas[0]->id_pers)),
                'direccion'=> "-",
                'msg'=>'si',
                ]);        
            }   
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
        
        if ($request['check'] != null) {
            $check=str_split($request['check']);
            $id_contrib=$request['id_contrib'];
            $anio=$request['anio'];
            $recpred = DB::table('presupuesto.vw_impuesto_predial')->where('anio',$anio)->first();
            $array =  array();
            for($i=$check[0];$i<=end($check);$i++){
                $sql = DB::table('adm_tri.vw_cta_cte2')->where('id_contrib',$id_contrib)->where('id_tribu',$recpred->id_tributo)->value('trim'.$i.'_est');
                if($sql==2){ 
                    $array[]=$i;
                }
            }
            return $array;
        }
        
    }
    
    function verif_est_cta_fraccionamiento(Request $request){
        $id_contrib=$request['id_contrib'];
       
        $sql = DB::table('fraccionamiento.convenio')->where('id_contribuyente',$id_contrib)->first();
        
        if (count($sql) > 0 && $sql->estado == 1) {
              return response()->json([
              'msg' => 'si',
              ]);
            
        }else{
            return response()->json([
                'msg' => 'no',
            ]);
        } 
    }
    
    function edit_arbitrio(Request $request){
        $check=explode("and",$request['check']);
        $id_contrib=$request['id_contrib'];
        $anio=$request['anio'];
        $idmaster=$this->create_rec_arb($request['total'],$id_contrib,$anio,1,0,2);
        $trib_barrido=DB::table('presupuesto.vw_barrido')->select('id_tributo')->where('anio',$anio)->first();
        $trib_recojo=DB::table('presupuesto.vw_limpieza')->select('id_tributo')->where('anio',$anio)->first();
        $trib_seguridad=DB::table('presupuesto.vw_serenazgo')->select('id_tributo')->where('anio',$anio)->first();
        $trib_parques=DB::table('presupuesto.vw_parques')->select('id_tributo')->where('anio',$anio)->first();
        
        $totalbarrido=0;
        $totalrecojo=0;
        $totalseguridad=0;
        $totalparques=0;
        $des_barrido="";
        $des_recojo="";
        $des_serenazgo="";
        $des_parque="";
        foreach($check as $arbitrios)
        {
            $pago=explode("-",$arbitrios); 
            if($trib_barrido->id_tributo==$pago[2])
            {
                $totalbarrido+=$this->edit_pgo_arbtrio($pago[0],$pago[1],$idmaster);
                $des_barrido=$des_barrido.",".$pago[1];
            }
            if($trib_recojo->id_tributo==$pago[2])
            {
                $totalrecojo+=$this->edit_pgo_arbtrio($pago[0],$pago[1],$idmaster);
                $des_recojo=$des_recojo.",".$pago[1];
            }
            if($trib_seguridad->id_tributo==$pago[2])
            {
                $totalseguridad+=$this->edit_pgo_arbtrio($pago[0],$pago[1],$idmaster);
                $des_serenazgo=$des_serenazgo.",".$pago[1];
            }
            if($trib_parques->id_tributo==$pago[2])
            {
                $totalparques+=$this->edit_pgo_arbtrio($pago[0],$pago[1],$idmaster);
                $des_parque=$des_parque.",".$pago[1];
            }
        }
        
        if($totalbarrido>0)
        {
            $this->create_rec_det_arb($idmaster,$anio,$totalbarrido,$trib_barrido->id_tributo,0,$des_barrido);
        }
        if($totalrecojo>0)
        {
            $this->create_rec_det_arb($idmaster,$anio,$totalrecojo,$trib_recojo->id_tributo,0,$des_recojo);
        }
        if($totalseguridad>0)
        {
            $this->create_rec_det_arb($idmaster,$anio,$totalseguridad,$trib_seguridad->id_tributo,0,$des_serenazgo);
        }
        if($totalparques>0)
        {
            $this->create_rec_det_arb($idmaster,$anio,$totalparques,$trib_parques->id_tributo,0,$des_parque);
        }
        return $idmaster;
    }
    function edit_coactivo(Request $request){
        $check=explode("and",$request['check']);
        $id_contrib=$request['id_contrib'];
        $anio=$request['anio'];
        $idmaster=$this->create_rec_arb($request['total'],$id_contrib,$anio,2,1,4);
        
        foreach($check as $coactivo)
        {
            $pago=explode("-",$coactivo); 
            $this->create_rec_det_arb($idmaster,$anio,$pago[2],$pago[1],$pago[0]);
        }
        return $idmaster;
    }
    public function create_rec_arb($total,$id_contrib,$anio,$tip,$pgo_coactivo,$clase)
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
        $data->pgo_coactivo  = $pgo_coactivo;
        if($tip==1)
        {
            $data->glosa      = "PAGO ARBITRIOS ".$anio;
        }
        else
        {
            $data->glosa      = "PAGO COACTIVO ";
        }
        $data->total      = $total;
        $data->id_tip_pago= 0;
        $data->id_contrib = $id_contrib;
        $data->id_tribut_master=0;
        $data->cod_fracc  = 0 ;
        $data->n_cuot     = 0;
        $data->clase_recibo=$clase;
        $data->save();        
        return $data->id_rec_mtr;
    }
    public function create_rec_det_arb($id,$anio,$total,$tributo,$id_aper,$des)
    {
        $rec_det = new Recibos_Detalle(); 
        $rec_det->id_rec_master=$id;
        $rec_det->periodo=$anio;
        $rec_det->id_ofi=0;
        $rec_det->id_trib=$tributo;
        $rec_det->monto=$total;
        $rec_det->cant=1;
        $rec_det->p_unit=$total;
        $rec_det->detalle_trimestres=$des;
        if($id_aper>0)
        {
            $periodo_real= DB::select("select doc_ini,id_doc_ini from coactiva.vw_apersonamiento_reporte where id_aper=".$id_aper);
            if($periodo_real[0]->doc_ini==2)
            {
                $op=DB::select("select anio from recaudacion.orden_pago_master where id_gen_fis=".$periodo_real[0]->id_doc_ini);
                $rec_det->periodo=$op[0]->anio;
            }
            if($periodo_real[0]->doc_ini==1)
            {
                $rp=DB::select("select anio from fiscalizacion.resolucion_determinacion where id_rd=".$periodo_real[0]->id_doc_ini);
                $rec_det->periodo=$rp[0]->anio;
            }
        }
        else
        {
            $rec_det->periodo=$anio;
        }
        $rec_det->id_aper=$id_aper;
        $rec_det->save();
        return $rec_det->id_rec_det;
    }
    
    public function edit_pgo_arbtrio($id,$mes,$recibo)
    {
        $pago=new Pgo_Arbitrios();
        $total=0;
        $val=  $pago::where("id_cta_arb","=",$id )->first();
        if(count($val)>=1)
        {
            $sql = DB::table('arbitrios.cta_arbitrios')->where('id_cta_arb',$id)->get();
            if($mes==1)
            {
                $val->id_rec_ene = $recibo;
                $val->flg_rec_ene = 1;
                $total=$sql[0]->pgo_ene;
            }
            if($mes==2)
            {
                $val->id_rec_feb = $recibo;
                $val->flg_rec_feb = 1;
                $total=$sql[0]->pgo_feb;
            }
            if($mes==3)
            {
                $val->id_rec_mar = $recibo;
                $val->flg_rec_mar = 1;
                $total=$sql[0]->pgo_mar;
            }
            if($mes==4)
            {
                $val->id_rec_abr = $recibo;
                $val->flg_rec_abr = 1;
                $total=$sql[0]->pgo_abr;
            }
            if($mes==5)
            {
                $val->id_rec_may = $recibo;
                $val->flg_rec_may = 1;
                $total=$sql[0]->pgo_may;
            }
            if($mes==6)
            {
                $val->id_rec_jun = $recibo;
                $val->flg_rec_jun = 1;
                $total=$sql[0]->pgo_jun;
            }
            if($mes==7)
            {
                $val->id_rec_jul = $recibo;
                $val->flg_rec_jul = 1;
                $total=$sql[0]->pgo_jul;
            }
            if($mes==8)
            {
                $val->id_rec_ago = $recibo;
                $val->flg_rec_ago = 1;
                $total=$sql[0]->pgo_ago;
            }
            if($mes==9)
            {
                $val->id_rec_sep = $recibo;
                $val->flg_rec_sep = 1;
                $total=$sql[0]->pgo_sep;
            }
            if($mes==10)
            {
                $val->id_rec_oct = $recibo;
                $val->flg_rec_oct = 1;
                $total=$sql[0]->pgo_oct;
            }
            if($mes==11)
            {
                $val->id_rec_nov = $recibo;
                $val->flg_rec_nov = 1;
                $total=$sql[0]->pgo_nov;
            }
            if($mes==12)
            {
                $val->id_rec_dic = $recibo;
                $val->flg_rec_dic = 1;
                $total=$sql[0]->pgo_dic;
            }
            $val->save();
        }
        return $total;
    }
        
    public function validar_alcabala(Request $request){
        $id_alcabala = $request['nro_recibo'];
        $funcion= DB::select("select alcabala.fn_alcab_tim(".$id_alcabala.")");
        $sql= DB::table("alcabala.alcabala")->where('id_alcab',$id_alcabala)->first();
             
        if (count($sql)>0) {
            
            if ($sql->estado == 1) {
                return response()->json([
                'msg' => 'PAGADO',
                'glosa' => 'RECIBO PAGADO',
                ]);
            }elseif ($sql->estado == 0){
                return response()->json([
                'msg' => 'VIGENTE',
                'valor'=> $sql->impuesto_tot,
                'tim'=> $sql->tim_alc,
                'glosa' => $sql->nro_alcab . "-" . $sql->anio,
                ]);
            }else{
                return response()->json([
                'msg' => 'otro',
                ]);
            }
            
        }else{
            return response()->json([
                'msg' => 'no-existe',
            ]);
        }
    }
    
    public function validaciones(Request $request){
        
        $valor = $request['valor'];
        
        $sql= DB::select("select * from presupuesto.vw_tributos_vladi where id_tributo = '$valor' and soles = '0' and anio = (select date_part('year',current_date)) and descrip_tributo not like " . "'%".'ALCABALA'."%'" . "");
                    
        if (count($sql)>0) 
        {
            return response()->json([
            'msg' => 'si',
            ]);  
        }
        else
        {
            $sql_alcabala = DB::select("select * from presupuesto.vw_tributos_vladi where descrip_tributo like " . "'%".'ALCABALA'."%'" . " and anio = (select date_part('year',current_date))");
            if (count($sql_alcabala)>0) 
            {
                if ($sql_alcabala[0]->id_tributo == $valor) 
                {
                    return response()->json([
                            'msg' => 'alcabala_si',
                    ]);
                }
                else
                {
                    return response()->json([
                        'msg' => 'alcabala_no',
                    ]);
                }
            }
        }
    }
    
    public function devuelve_mes($num)
    {
        if($num==1){
            return "Ene";
        }
        if($num==2){
            return "Feb";
        }
        if($num==3){
            return "Mar";
        }
        if($num==4){
            return "Abr";
        }
        if($num==5){
            return "May";
        }
        if($num==6){
            return "Jun";
        }
        if($num==7){
            return "Jul";
        }
        if($num==8){
            return "Ago";
        }
        if($num==9){
            return "Sep";
        }
        if($num==10){
            return "Oct";
        }
        if($num==11){
            return "Nov";
        }
        if($num==12){
            return "Dic";
        }
    }
}
