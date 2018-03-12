<?php

namespace App\Http\Controllers\fiscalizacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Traits\DatesTranslator;
use Illuminate\Support\Facades\Auth;
use App\Models\fiscalizacion\Resolucion_Determinacion;
use App\Models\Predios\Predios_Anio;
use App\Models\Predios\Predios_Contribuyentes;
use App\Models\Predios\Predios_Rusticos;
use App\Models\Pisos;
use App\Models\Instalaciones;

class Res_DeterminacionController extends Controller
{
    use DatesTranslator;
    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_reso_deter' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        return view('fiscalizacion/vw_res_deter',compact('anio_tra','menu','permisos'));
    }

    public function create(Request $request)
    {
        $rd=new Resolucion_Determinacion;
        $rd->id_hoja_liq=$request['hoja'];
        $rd->fec_reg=date("d/m/Y");
        $rd->anio=date("Y");
        $rd->txt_motivacion=str_replace("</p>","<br>",str_replace("<p>", "", $request['moti']));
        $rd->id_usuario=Auth::user()->id;
        $rd->save();
        $cuenta=DB::select("select * from fiscalizacion.vw_resolucion_determinacion where id_rd =".$rd->id_rd);
        $id_tributo = DB::select("select id_tributo from presupuesto.vw_impuesto_predial where anio =".$cuenta[0]->anio_fis);
        DB::select("update adm_tri.cta_cte set id_rd=".$rd->id_rd." where id_pers=".$cuenta[0]->id_contrib." and ano_cta='".$cuenta[0]->anio_fis."' and id_tribu=".$id_tributo[0]->id_tributo);
        $this->create_predio_fis($rd->id_rd);
        return $rd->id_rd;
    }
    public function calculos_ivpp($id)
    {
        DB::select("select adm_tri.fn_count_pisos(".$id.")");
        DB::select("select adm_tri.actualiza_base_predio(".$id.")");
        $Predios_Anio=new Predios_Anio;
        $Predios_Anio=  $Predios_Anio::where("id_pred_anio","=",$id )->first();
        $Predios_Contribuyentes=new Predios_Contribuyentes;
        $Predios_Contribuyentes=  $Predios_Contribuyentes::where("id_pred","=",$Predios_Anio->id_pred )->first();
        DB::select("select adm_tri.calcular_ivpp($Predios_Anio->anio,$Predios_Contribuyentes->id_contrib)");
    }
    public function create_predio_fis($id_rd)
    {
        $id_car = DB::select('select id_car from fiscalizacion.vw_resolucion_determinacion where id_rd='.$id_rd);
        if(count($id_car)>=1)
        {
            $predios = DB::select('select * from fiscalizacion.vw_ficha_verificacion where id_car='.$id_car[0]->id_car);
            foreach($predios as $pre)
            {
                $predio_anio=$this->descativar_predio_anio($pre->id_pred_anio);
                $id_pred_anio=$this->predio_anio_create($pre,$predio_anio);
                $this->predio_contribuyente_create($id_pred_anio,$pre->id_pred_anio,$pre->tip_pre_u_r);
                if($pre->tip_pre_u_r==2)
                {
                    $this->predio_rus_create($id_pred_anio,$pre);
                }
                $this->create_pisos($pre->id_fic,$id_pred_anio);
                $this->create_instalaciones($pre->id_fic,$id_pred_anio);
                $this->calculos_ivpp($id_pred_anio);
                
            }
        }
        return $id_pred_anio;
    }
    public function predio_anio_create($pre,$predio_anio)
    {
        $val=new Predios_Anio;
        $val->id_pred=$pre->id_pred;
        $val->anio=$predio_anio->anio;
        $val->arancel = $pre->arancel;
        if($pre->tip_pre_u_r==2)
        {
            $val->are_terr = $pre->hectareas;
            $val->are_com_terr = 0;
            $val->val_ter = $pre->hectareas*$pre->are_terr;
        }
        else
        {
            $val->are_terr = $pre->are_terr;
            $val->are_com_terr = $pre->are_com_terr;
            $val->val_ter = ($pre->are_terr+$pre->are_com_terr)*$pre->are_terr;
        }
        $val->flg_act = 1;
        $val->id_cond_prop = $pre->id_cond_prop;
        $val->id_est_const = $pre->id_est_const;
        $val->id_tip_pred = $pre->id_tip_pred;
        $val->luz_nro_sum = $predio_anio->luz_nro_sum;
        $val->agua_nro_sum = $predio_anio->agua_nro_sum;
        $val->fech_adquis = $predio_anio->fech_adquis;
        $val->nro_condominios = $pre->nro_condominios;
        $val->licen_const = $predio_anio->licen_const;
        $val->id_uso_predio = $predio_anio->id_uso_predio;
        $val->conform_obra = $predio_anio->conform_obra;
        $val->declar_fabrica = $predio_anio->declar_fabrica;
        
        $val->id_usuario = Auth::user()->id;
        $val->fec_reg = date("d/m/Y");
        $val->hora_reg = date("H:i");
        $val->id_tip_ins = 3;
        $val->save();
        return $val->id_pred_anio;
    }
    public function predio_contribuyente_create($id_pre_anio,$id_and)
    {
        $contris = DB::select('select * from adm_tri.predios_contribuyentes where id_pred_anio='.$id_and);
        foreach ($contris as $con) 
        {
            $predio_contribuyentes=new Predios_Contribuyentes;
            $predio_contribuyentes->id_pred=$con->id_pred;
            $predio_contribuyentes->id_contrib=$con->id_contrib;
            $predio_contribuyentes->fec_ini = $con->fec_ini;
            $predio_contribuyentes->flg_act = 1;
            $predio_contribuyentes->porcen_titularidad = 100;
            $predio_contribuyentes->id_form_adq = $con->id_form_adq;
            $predio_contribuyentes->id_pred_anio = $id_pre_anio;
            $predio_contribuyentes->save();
        }
        
    }
    public function predio_rus_create($id,$pred)
    {
        $rus_ant = DB::select('select * from adm_tri.predios_rusticos where id_pred_anio='.$pred->id_pred_anio);
        $rustico=new Predios_Rusticos;
        $rustico->id_pred_anio = $id;
        $rustico->lugar_pr_rust = $rus_ant[0]->lugar_pr_rust;
        $rustico->ubicac_pr_rus  = $rus_ant[0]->ubicac_pr_rus;
        $rustico->klm  = $rus_ant[0]->klm;
        $rustico->nom_pre_pr_rus   = $rus_ant[0]->nom_pre_pr_rus;
        $rustico->norte   = $rus_ant[0]->norte;
        $rustico->sur   = $rus_ant[0]->sur;
        $rustico->este   = $rus_ant[0]->este;
        $rustico->oeste   = $rus_ant[0]->oeste;
        $rustico->id_tip_pre_rus = $rus_ant[0]->id_tip_pre_rus;
        $rustico->id_uso_pre_rust = $rus_ant[0]->id_uso_pre_rust;
        $rustico->id_gpo_tierra=$pred->id_gpo_tierra;
        $rustico->id_cat_gpo_tierra=$pred->id_cat_gpo_tierra;
        $rustico->save();
    }
    public function descativar_predio_anio($id)
    {
        $predio_anio=new Predios_Anio;
        $val=  $predio_anio::where("id_pred_anio","=",$id )->first();
        if(count($val)>=1)
        {
            $val->flg_act = 0;
            $val->save();
        }
        return $val;
    }
    public function create_pisos($id_fic,$id_pred_anio)
    {
        $pis_fis = DB::select("Select * from fiscalizacion.pisos_fic where id_fic=$id_fic");
        foreach($pis_fis as $pis)
        {
            $pisos=new Pisos;
            $pisos->anio = $pis->anio;
            $pisos->cod_piso = $pis->cod_piso;
            $pisos->ani_const = $pis->ani_const;
            $pisos->fch_const = "01/01/".$pis->ani_const;
            $pisos->ant_ano = $pis->ant_ano;
            $pisos->clas = $pis->clas;
            $pisos->mep = $pis->mep;
            $pisos->esc = $pis->esc;
            $pisos->ecc = $pis->ecc;
            $pisos->est_mur = $pis->est_mur;
            $pisos->est_tch = $pis->est_tch;
            $pisos->aca_pis = $pis->aca_pis;
            $pisos->aca_pta = $pis->aca_pta ;
            $pisos->aca_rev = $pis->aca_rev;
            $pisos->aca_ban = $pis->aca_ban;
            $pisos->ins_ele = $pis->ins_ele ;
            $pisos->area_const = $pis->area_const;
            $pisos->val_areas_com = $pis->val_areas_com;
            $pisos->num_pis = $pis->num_pis;
            $pisos->id_pred_anio = $id_pred_anio;
            $pisos->save();
        }
    }
    public function create_instalaciones($id_fic,$id_pred_anio)
    {
        $inst_fis = DB::select("Select * from fiscalizacion.instalaciones_fic where id_fic=$id_fic");
        foreach($inst_fis as $inst)
        {
            $insta=new Instalaciones;
            $insta->val_unit=$inst->val_unit;
            $insta->pro_tot =$inst->pro_tot;
            $insta->val_obra = $inst->val_obra;
            $insta->id_instal = $inst->id_instal;
            $insta->anio = $inst->anio;
            $insta->dim_lar = $inst->dim_lar;
            $insta->dim_anch = $inst->dim_anch;
            $insta->dim_alt = $inst->dim_alt;
            $insta->mep = $inst->mep;
            $insta->ecs = $inst->ecs ;
            $insta->ecc = $inst->ecc;
            $insta->id_cla = $inst->id_cla;
            $insta->id_pred_anio = $id_pred_anio;
            $insta->antiguedad = $inst->antiguedad;
            $insta->save();
        }
       
    }
    public function store(Request $request)
    {
    }

    public function show($id)
    {
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
    public function get_rd($an,$contrib,$ini,$fin,$num,Request $request)
    {
            header('Content-type: application/json');
            $page = $_GET['page'];
            $limit = $_GET['rows'];
            $sidx = $_GET['sidx'];
            $sord = $_GET['sord'];
            $start = ($limit * $page) - $limit; // do not put $limit*($page - 1)  
            if ($start < 0) {
                $start = 0;
            }
            if($contrib==0)
            {
                if($an==0)
                {
                    $totalg = DB::select("select count(id_rd) as total from  fiscalizacion.vw_resolucion_determinacion where fec_reg between '".$ini."' and '".$fin."'");
                    $sql = DB::table('fiscalizacion.vw_resolucion_determinacion')->wherebetween("fec_reg",[$ini,$fin])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                    
                }
                else
                {
                    if($num==0)
                    {
                        $totalg = DB::select('select count(id_rd) as total from fiscalizacion.vw_resolucion_determinacion where anio='.$an);
                        $sql = DB::table('fiscalizacion.vw_resolucion_determinacion')->where("anio",$an)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                    }
                    else
                    {
                        $totalg = DB::select("select count(id_rd) as total from fiscalizacion.vw_resolucion_determinacion where nro_rd='".$num."' and anio=".$an);
                        $sql = DB::table('fiscalizacion.vw_resolucion_determinacion')->where("anio",$an)->where("nro_hoja",$num)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                    }
                }
            }
            else
            {
              $totalg = DB::select('select count(id_rd) as total from fiscalizacion.vw_resolucion_determinacion where anio='.$an.' and id_contrib='.$contrib);
              $sql = DB::table('fiscalizacion.vw_resolucion_determinacion')->where("anio",$an)->where("id_contrib",$contrib)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
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
            $Lista = new \stdClass();
            $Lista->page = $page;
            $Lista->total = $total_pages;
            $Lista->records = $count;
            foreach ($sql as $Index => $Datos) {
                if($Datos->fecha_notificacion == null)
                {
                    $notifica='<button class="btn btn-labeled bg-color-red txt-color-white" type="button" onclick="ponerfechanoti('."'".trim($Datos->nro_rd)."'".');"><span class="btn-label"><i class="fa fa-edit"></i></span> Ing. Fecha Notificación</button>';
                }
                else
                {
                    $notifica=trim($this->getCreatedAtAttribute($Datos->fecha_notificacion)->format('d/m/Y'));
                }
                $Lista->rows[$Index]['id'] = $Datos->id_rd;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_rd),
                    trim($Datos->nro_rd),
                    trim($Datos->contribuyente),
                    trim($this->getCreatedAtAttribute($Datos->fec_reg)->format('d/m/Y')),
                    $notifica,
                    '<button class="btn btn-labeled btn-warning" type="button" onclick="verrd('.trim($Datos->id_rd).')"><span class="btn-label"><i class="fa fa-file-text-o"></i></span> Ver</button>',
                );
            }
            return response()->json($Lista);
    }
    public function rd_repo($id)
    {
        $sql    =DB::table('fiscalizacion.vw_resolucion_determinacion')->where('id_rd',$id)->get()->first();
        if(count($sql)>=1)
        {
            $fichas    =DB::table('fiscalizacion.vw_ficha_verificacion')->where('id_car',$sql->id_car)->get();
            $predios=DB::table('fiscalizacion.vw_puente_carta_predios')->where('id_car',$sql->id_car)->get();
            $sql->letras = $this->num_letras($sql->ivpp_verif-$sql->pagado+4.64);
            $sql->fec_reg=$this->getCreatedAtAttribute($sql->fec_reg)->format('l d \d\e F \d\e\l Y ');
            $sql->fec_carta=$this->getCreatedAtAttribute($sql->fec_carta)->format('l d \d\e F \d\e\l Y ');
            $reajuste = DB::select('select * from adm_tri.reajuste_actual()');
            $view =  \View::make('fiscalizacion.reportes.rd', compact('sql','fichas','predios','reajuste'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("rd.pdf");
        }
    }
    public function reportes()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_rep_fisca' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        return view('fiscalizacion/vw_reportes',compact('anio_tra','menu','permisos'));
    }
    public function ver_reportes($tipo,$anio,$contrib)
    {
        if($tipo==1)
        {
            $sql    =DB::table('fiscalizacion.vw_ficha_verificacion')->where('anio',$anio)->orderBy('nro_fic')->get();
            $name =Auth::user()->ape_nom;
            if(count($sql)>=1)
            {
                $view =  \View::make('fiscalizacion.reportes.vw_contrib_fiscalizados', compact('sql','name','anio'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4');
                return $pdf->stream("Fiscalizados.pdf");
            }
            else
            {
                Return "No hay Datos";
            }
        }
        if($tipo==2)
        {
            if($contrib==0)
            {
                $sql    =DB::table('fiscalizacion.vw_ficha_verificacion')->where('anio',$anio)->orderBy('nro_fic')->get();
            }
            else
            {
                $sql    =DB::table('fiscalizacion.vw_ficha_verificacion')->where('anio',$anio)->where('id_contrib',$contrib)->orderBy('nro_fic')->get();
            }
            $name =Auth::user()->ape_nom;
            if(count($sql)>=1)
            {
                
                $view =  \View::make('fiscalizacion.reportes.vw_m2_decla_fisca', compact('sql','name','anio'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4');
                return $pdf->stream("Fiscalizados.pdf");
            }
            else
            {
                Return "No hay Datos";
            }
        }
        
        
    }
     public function ver_reporte_estado_hl($tipo,$anio,$estado)
    {
        if($tipo==3) 
        {
            $name =Auth::user()->ape_nom;
            if($estado==0)
            {
                $sql=DB::table('fiscalizacion.vw_hoja_liquidacion')->where('anio',$anio)->orderBy('nro_hoja')->get(); 
                if(count($sql)>=1)
                {
                    $view =  \View::make('fiscalizacion.reportes.vw_reporte_Estado_hl', compact('sql','name','anio','estado'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view)->setPaper('a4');
                    return $pdf->stream("Fiscalizados.pdf");
                }
                else
                {
                    Return "No hay Datos";
                }
            }
            if($estado==1)
            {
                $sql=DB::table('fiscalizacion.vw_hoja_liquidacion')->where('anio',$anio)->where('flg_est','<>',0)->where('fecha_notificacion','<>',NULL)->orderBy('nro_hoja')->get(); 
                if(count($sql)>=1)
                {
                    $view =  \View::make('fiscalizacion.reportes.vw_reporte_estado_hl', compact('sql','name','anio','estado'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view)->setPaper('a4');
                    return $pdf->stream("Fiscalizados.pdf");
                }
                else
                {
                    Return "No hay Datos";
                }
            }
            if($estado==2)
            {
                $sql=DB::table('fiscalizacion.vw_hoja_liquidacion')->where('anio',$anio)->where('flg_est',1)->orderBy('nro_hoja')->get(); 
                if(count($sql)>=1)
                {
                    $view =  \View::make('fiscalizacion.reportes.vw_reporte_Estado_hl', compact('sql','name','anio','estado'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view)->setPaper('a4');
                    return $pdf->stream("Fiscalizados.pdf");
                }
                else
                {
                    Return "No hay Datos";
                }
            }
            
            
        }
       
        
        
    }
     public function edit_rd_fec(Request $request)
    {
        $rd=new Resolucion_Determinacion;
        $val=  $rd::where("id_rd","=",$request['id'] )->first();
        if(count($val)>=1)
        {
            $val->fecha_notificacion=$request['fec'];
            $val->save();
        }
        return $request['id'];
    }
    public function get_motivacion_rd(Request $request)
    {
        $sql    =DB::table('fiscalizacion.vw_hoja_liquidacion')->where('id_hoja_liq',$request['hoja'])->get()->first();
        if(count($sql)>=1)
        {
            $fichas    =DB::table('fiscalizacion.vw_ficha_verificacion')->where('id_car',$sql->id_car)->get();
            $sql->fec_carta=$this->getCreatedAtAttribute($sql->fec_carta)->format('l d \d\e F \d\e\l Y ');
            $html='Que, habiendose realizado el respectivo proceso de fiscalización iniciado con la Carta de Requerimiendo N° '.$sql->nro_car.'-'.$sql->anio_carta.'-SGFT-GAT-MDCC, la misma que fue notificada el '.$sql->fec_carta.'; la verificación realizada in situ en fechas '.$sql->dias_fisca.'; realizando acciones de medición al área construida, categorización de la edificación, su clasificación, estado de conservación y medición y valorización de obras complementarias fijas y permanentes, toma de fotografías, todo ello contenido en Fichas de Inspección N°';
            foreach($fichas as $fic)
            {
                $html.=" ".$fic->nro_fic.",";
            }
            $html.=' Culminando el proceso de Fiscalización se ha detectado que no ha cumplido on sus obligaciones formales y sustanciales motivo por el cual se emite la presente Resolución de Determinación.';
            return $html;
        }
    }
}
