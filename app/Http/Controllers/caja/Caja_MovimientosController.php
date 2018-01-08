<?php

namespace App\Http\Controllers\caja;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Recibos_Master;
use App\Models\CtaCte;
use App\Models\Caja_apert_cierr;
//40204770
class Caja_MovimientosController extends Controller {

    public function index() {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_menu_caja_movimientos' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $est_recibos = DB::select('select * from tesoreria.estados_recibos');
        $tipo_pago = DB::select('select * from tesoreria.tipo_pago');
        $cajas = DB::select('select * from tesoreria.cajas order by id_caj');  
//        dd($cajas);
        return view('caja/vw_caja_Movimient', compact('est_recibos', 'tipo_pago', 'cajas','menu','permisos'));
    }

    public function create() {
        //
    }

    public function store(Request $request) {}

    public function show($id) {}
    
    public function edit(Request $request, $id){
        date_default_timezone_set('America/Lima');
        $recibo_master = new Recibos_Master();
        
        $val = $recibo_master::where("id_rec_mtr", "=", $id)->first();
        
        if (count($val) >= 1) {
            
                          
            
            $val->id_tip_pago = $request['id_tip_pago'];
            $val->id_caja = $request['id_caja'];
            $val->fecha = date('d-m-Y');
            $val->hora_pago = date('h:i:s A');
            $val->id_usuario = Auth::user()->id;
            $val->id_est_rec = 2;
            $query = $val->save();
            if ($query) {              
                $function = DB::select('select tesoreria.nro_recibos_cjas(' . $request['id_caja'] . ',' . $id . ')');                
                if($function){                    
                    if($val->clase_recibo==0)
                    {
                        $chk = str_split(trim($val->pred_check));
                        $prim = $chk[0];
                        $ult = array_pop($chk);
                        $cant = count($chk)+1;
                        
//                       
                        $anio_detalle=DB::table('tesoreria.recibos_detalle')->select('periodo')->where('id_rec_master',$val->id_rec_mtr)->first();
                        for($i=$prim;$i<=$ult;$i++){
                            
                            $recpred = DB::select('select * from presupuesto.vw_impuesto_predial where anio='.$anio_detalle->periodo);  
                            $pre_x_trim= DB::table('tesoreria.recibos_detalle')->where('id_rec_master',$val->id_rec_mtr)->where('id_trib',$recpred[0]->id_tributo)->value('p_unit');

                            $update = DB::table('adm_tri.cta_cte')->where('id_pers',$request['id_pers'])->where('id_tribu',$recpred[0]->id_tributo)->where('ano_cta',$anio_detalle->periodo)
                                    ->update(['abo'.$i.'_cta'=>$pre_x_trim,'fec_abo'.$i=>date('d-m-Y')]);
                        }
                        
                            
                            $recformato= DB::select('select * from presupuesto.vw_formatos_ivpp where anio='.$anio_detalle->periodo);  
                            $value_formato_pred= DB::table('adm_tri.vw_cta_cte2')->where('id_contrib',$request['id_pers'])->where('id_tribu',$recformato[0]->id_tributo)->where('ano_cta',$anio_detalle->periodo)->value('ivpp');
                            for($x=1;$x<=4;$x++){
                                $update = DB::table('adm_tri.cta_cte')->where('id_pers',$request['id_pers'])->where('id_tribu',$recformato[0]->id_tributo)->where('ano_cta',$anio_detalle->periodo)
                                        ->update(['abo'.$x.'_cta'=>($value_formato_pred/4),'fec_abo'.$x=>date('d-m-Y')]);
                        }
                        return $id.'predial';                                                
                    }
                    if($val->clase_recibo==3)
                    {
                        $chk = strlen(trim($val->fracc_check));
                        if($chk=='1'){                            
                            DB::table('fraccionamiento.detalle_convenio')->where('id_conv_mtr',trim($val->cod_fracc))->where('nro_cuota',trim($val->fracc_check))
                                    ->update(['estado' => 1,'fecha_q_pago'=> date('Y-m-d')]);                           
                        }else{
                            $array= explode('-',trim($val->fracc_check));
                            $prim = $array[0];
                            $ult = array_pop($array);
                            for($i=$prim;$i<=$ult;$i++){                                
                                DB::table('fraccionamiento.detalle_convenio')->where('id_conv_mtr',trim($val->cod_fracc))->where('nro_cuota',$i)
                                        ->update(['estado' => 1,'fecha_q_pago'=> date('Y-m-d')]);
                            }
                        }
                        $nro_cuotas = DB::select('select * from fraccionamiento.vw_trae_cuota_conv where id_conv_mtr='.$val->cod_fracc.' order by nro_cuota desc');
                        $pagados=DB::select("select sum(cod_estado) as pagados from fraccionamiento.vw_trae_cuota_conv where id_conv_mtr=".$val->cod_fracc);
                        if($nro_cuotas[0]->nro_cuota==$pagados[0]->pagados){
                            DB::table('fraccionamiento.convenio')->where('id_conv',trim($val->cod_fracc))
                                        ->update(['estado' => 3]);
                            $pre_x_trim= DB::table('adm_tri.vw_cta_cte2')->where('id_contrib',$request['id_pers'])->where('id_tribu',103)->where('ano_cta',date('Y'))->value('car1_cta');
                            for($i=1;$i<=4;$i++){                                
                                $update = DB::table('adm_tri.cta_cte')->where('id_pers',$request['id_pers'])->where('id_tribu',103)
                                        ->update(['abo'.$i.'_cta'=>$pre_x_trim,'fec_abo'.$i=>date('d-m-Y')]);
                            }
                        }
                        return $id.'fraccionamiento';
                    }
                    else return $id.'varios';                    
                }
            }
        }
    }

    public function update(Request $request, $id) {}
    
    function rep_dia_caja($id_caja){
//        $master = DB::table('tesoreria.vw_recibos_resumen')->where([['id_caja',$id_caja],['fecha',date('Y-m-d')],['id_est_rec',2]])->orderBy('nro_recibo_mtr','asc')->get();
        $master = DB::select("select ROW_NUMBER () OVER (ORDER BY nro_recibo_mtr) as nro,lpad(nro_recibo_mtr::text, 7, '0') as nro_recibo,* from tesoreria.vw_recibos_resumen where id_caja=".$id_caja." and fecha ='".date('Y-m-d')."' and id_est_rec=2");
        $total = DB::select("select sum(total) as total from tesoreria.vw_recibos_resumen where id_caja=".$id_caja." and fecha ='".date('Y-m-d')."' and id_est_rec=2");
        $view = \View::make('caja.reportes.rep_diario_caja', compact('master','total'))->render();        
        $pdf = \App::make('dompdf.wrapper');        
        $pdf->loadHTML($view)->setPaper('a4');
        return $pdf->stream();
        
    }
    
    function verif_aper_caja(Request $request){
        $id_caja = $request['id_caja'];
        $caja = DB::table('tesoreria.vw_caja_apertura')->where('id_caja',$id_caja)->where('fecha',date('Y-m-d'))->get();
        if(count($caja)>=1){
            return response()->json(['msg'=>'si','id_caja_dia'=>$caja[0]->id_caj_mov,'estado'=>$caja[0]->estado]);
        }else{
            return response()->json(['msg'=>'no']);
        }
    }
    function apertura_caja(Request $request){
        $data = new Caja_apert_cierr();
        $data->id_usuario=Auth::user()->id;
        $data->id_caja=$request['id_caja'];
        $data->fecha=date('Y-m-d');
        $data->hora=date('H:i A');
        $data->estado=1;
        $sql = $data->save();
        if($sql){
            return response()->json(['msg'=>'si','id_caja_dia'=>$data->id_caj_mov]);
        }else{
            return response()->json(['msg'=>'no']);
        }
    }
    function cierre_caja(Request $request){
        $data = new Caja_apert_cierr();        
        $val = $data::where("id_caj_mov", "=", $request['id_caj_mov'])->first();
        $ult_recib = DB::table('tesoreria.cajas')->where('id_caj',$request['id_caja'])->value('ult_rec_emitido');
        
        if (count($val) >= 1) {
            $val->estado=2;            
            $val->hora_cierre=date('H:i A');            
            $val->ultimo_recibo_mov=$ult_recib;            
            $sql = $val->save();  
            if($sql){
                return response()->json(['msg'=>'si','id_caja_dia'=>$val->id_caj_mov]);
            }else{
                return response()->json(['msg'=>'no']);
            }
        }
        
    }
    public function destroy($id) {}

    function get_grid_Caja_Mov(Request $request) {
        date_default_timezone_set('America/Lima');
        $est_recibo = $request['est_recibo'];
        $id_recib = $request['id_recib'];
        if(isset($id_recib)){
            $totalg = DB::select("select count(id_rec_mtr) as total from tesoreria.vw_caja_mov"
                        . " where id_rec_mtr=".$id_recib);
        }else{
            $totalg = DB::select("select count(id_rec_mtr) as total from tesoreria.vw_caja_mov"
                        . " where fecha='" . date('d-m-Y') . "' and id_est_rec='" . $est_recibo . "'");
        }
        
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
        
        if(isset($id_recib)){
            $sql = DB::table('tesoreria.vw_caja_mov')
                        ->where('id_rec_mtr', $id_recib)
                        ->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        }else{
            $sql = DB::table('tesoreria.vw_caja_mov')
                        ->where([
                                
                                ['fecha', '=', date('d-m-Y')],
                                ['id_est_rec', '=', $est_recibo]                                
                        ])
                        ->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        }
        
        if(isset($id_recib)){
            $suma = DB::select("select sum(total) as sum_total from tesoreria.vw_caja_mov"
                        . " where id_rec_mtr=".$id_recib);
        }else{
            $suma = DB::select("select sum(total) as sum_total from tesoreria.vw_caja_mov"
                    . " where fecha='" . date('d-m-Y') . "' and id_est_rec='" . $est_recibo . "'");
        }
        
        $array = array();
        $array['sum_total'] = $suma[0]->sum_total;

        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        $Lista->userdata = $array;

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
                trim($Datos->total),
                trim($Datos->clase_recibo)
            );
        }
        return response()->json($Lista);
    }

    function reportes_caja_mov(Request $request) {
        $id_rec = $request['id_rec'];
        $recibo = DB::table('tesoreria.vw_caja_pago_recib')->where('id_rec_mtr', $id_rec)->get();
        
        if($recibo[0]->clase_recibo=='0'|| $recibo[0]->clase_recibo=='3'){
            $contrib = DB::table('adm_tri.vw_contribuyentes')->select('contribuyente')->where('id_contrib',$recibo[0]->id_contrib)->first();
            $recibo[0]->pers_raz_soc= $contrib->contribuyente;
        }
        
        if($recibo[0]->clase_recibo=='3'){
            $detalle = DB::table('tesoreria.vw_recibo_detalle_impresion_fracc')->where('id_rec_master',$id_rec)->get();
        }else{
            $detalle = DB::table('tesoreria.vw_recibo_detalle_impresion')->where('id_rec_master',$id_rec)->get();
        }
        if($recibo[0]->clase_recibo=='1'){
            $contrib = DB::table('adm_tri.personas')->where('id_pers',$recibo[0]->id_contrib)->first();
            $recibo[0]->contribuyente = $contrib->pers_ape_pat.' '.$contrib->pers_ape_mat.' '.$contrib->pers_nombres;
        }
//        dd($recibo);
//        echo $id_rec;
//        
//        dd($detalle);
        $soles= $this->num2letras(round($recibo[0]->total,2));
        date_default_timezone_set('America/Lima');
        $fecha_larga = $this->fecha_letras(date('d-m-Y')).' : '.date('h:i A');        
        
        $view = \View::make('caja.reportes.pago_recibo', compact('recibo','detalle','soles','fecha_larga'))->render();
//        return $view;
        if (count($recibo) >= 1) {
            $pdf = \App::make('dompdf.wrapper');
            $paper_size = array(0, 0, 638, 397);
            $pdf->loadHTML($view)->setPaper($paper_size);
            return $pdf->stream();
        }
    }
    
    public function fecha_letras($date){
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                
        $timestamp=strtotime($date);
        return $dias[date('w',$timestamp)].", ".date('d',$timestamp)." de ".$meses[date('n',$timestamp)-1]. " del ".date('Y',$timestamp);
    }

    /////NUM LETRAS///
    public function num2letras($num, $fem = false, $dec = true) {
        $matuni[2] = "dos";
        $matuni[3] = "tres";
        $matuni[4] = "cuatro";
        $matuni[5] = "cinco";
        $matuni[6] = "seis";
        $matuni[7] = "siete";
        $matuni[8] = "ocho";
        $matuni[9] = "nueve";
        $matuni[10] = "diez";
        $matuni[11] = "once";
        $matuni[12] = "doce";
        $matuni[13] = "trece";
        $matuni[14] = "catorce";
        $matuni[15] = "quince";
        $matuni[16] = "dieciseis";
        $matuni[17] = "diecisiete";
        $matuni[18] = "dieciocho";
        $matuni[19] = "diecinueve";
        $matuni[20] = "veinte";
        $matunisub[2] = "dos";
        $matunisub[3] = "tres";
        $matunisub[4] = "cuatro";
        $matunisub[5] = "quin";
        $matunisub[6] = "seis";
        $matunisub[7] = "sete";
        $matunisub[8] = "ocho";
        $matunisub[9] = "nove";

        $matdec[2] = "veint";
        $matdec[3] = "treinta";
        $matdec[4] = "cuarenta";
        $matdec[5] = "cincuenta";
        $matdec[6] = "sesenta";
        $matdec[7] = "setenta";
        $matdec[8] = "ochenta";
        $matdec[9] = "noventa";
        $matsub[3] = 'mill';
        $matsub[5] = 'bill';
        $matsub[7] = 'mill';
        $matsub[9] = 'trill';
        $matsub[11] = 'mill';
        $matsub[13] = 'bill';
        $matsub[15] = 'mill';
        $matmil[4] = 'millones';
        $matmil[6] = 'billones';
        $matmil[7] = 'de billones';
        $matmil[8] = 'millones de billones';
        $matmil[10] = 'trillones';
        $matmil[11] = 'de trillones';
        $matmil[12] = 'millones de trillones';
        $matmil[13] = 'de trillones';
        $matmil[14] = 'billones de trillones';
        $matmil[15] = 'de billones de trillones';
        $matmil[16] = 'millones de billones de trillones';

        //Zi hack
        $float = explode('.', $num);
        $num = $float[0];

        $num = trim((string) @$num);
        if ($num[0] == '-') {
            $neg = 'menos ';
            $num = substr($num, 1);
        } else
            $neg = '';
        while ($num[0] == '0')
            $num = substr($num, 1);
        if ($num[0] < '1' or $num[0] > 9)
            $num = '0' . $num;
        $zeros = true;
        $punt = false;
        $ent = '';
        $fra = '';
        for ($c = 0; $c < strlen($num); $c++) {
            $n = $num[$c];
            if (!(strpos(".,'''", $n) === false)) {
                if ($punt)
                    break;
                else {
                    $punt = true;
                    continue;
                }
            } elseif (!(strpos('0123456789', $n) === false)) {
                if ($punt) {
                    if ($n != '0')
                        $zeros = false;
                    $fra .= $n;
                } else
                    $ent .= $n;
            } else
                break;
        }
        $ent = '     ' . $ent;
        if ($dec and $fra and ! $zeros) {
            $fin = ' coma';
            for ($n = 0; $n < strlen($fra); $n++) {
                if (($s = $fra[$n]) == '0')
                    $fin .= ' cero';
                elseif ($s == '1')
                    $fin .= $fem ? ' una' : ' un';
                else
                    $fin .= ' ' . $matuni[$s];
            }
        } else
            $fin = '';
        if ((int) $ent === 0)
            return 'Cero ' . $fin;
        $tex = '';
        $sub = 0;
        $mils = 0;
        $neutro = false;
        while (($num = substr($ent, -3)) != '   ') {
            $ent = substr($ent, 0, -3);
            if (++$sub < 3 and $fem) {
                $matuni[1] = 'una';
                $subcent = 'as';
            } else {
                $matuni[1] = $neutro ? 'un' : 'uno';
                $subcent = 'os';
            }
            $t = '';
            $n2 = substr($num, 1);
            if ($n2 == '00') {
                
            } elseif ($n2 < 21)
                $t = ' ' . $matuni[(int) $n2];
            elseif ($n2 < 30) {
                $n3 = $num[2];
                if ($n3 != 0)
                    $t = 'i' . $matuni[$n3];
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            }else {
                $n3 = $num[2];
                if ($n3 != 0)
                    $t = ' y ' . $matuni[$n3];
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            }
            $n = $num[0];
            if ($n == 1) {
                $t = ' ciento' . $t;
            } elseif ($n == 5) {
                $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
            } elseif ($n != 0) {
                $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
            }
            if ($sub == 1) {
                
            } elseif (!isset($matsub[$sub])) {
                if ($num == 1) {
                    $t = ' mil';
                } elseif ($num > 1) {
                    $t .= ' mil';
                }
            } elseif ($num == 1) {
                $t .= ' ' . $matsub[$sub] . '?n';
            } elseif ($num > 1) {
                $t .= ' ' . $matsub[$sub] . 'ones';
            }
            if ($num == '000')
                $mils ++;
            elseif ($mils != 0) {
                if (isset($matmil[$sub]))
                    $t .= ' ' . $matmil[$sub];
                $mils = 0;
            }
            $neutro = true;
            $tex = $t . $tex;
        }
        $tex = $neg . substr($tex, 1) . $fin;
        //Zi hack --> return ucfirst($tex);
        $end_num = ucfirst($tex) . ' con ' . $float[1] . '/100 Nuevos Soles';
        return $end_num;
    }

}
