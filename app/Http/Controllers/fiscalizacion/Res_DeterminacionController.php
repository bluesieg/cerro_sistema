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
use App\Models\Pisos;
use App\Models\Instalaciones;

class Res_DeterminacionController extends Controller
{
    use DatesTranslator;
    public function index()
    {
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        return view('fiscalizacion/vw_res_deter',compact('anio_tra'));
    }

    public function create(Request $request)
    {
        $rd=new Resolucion_Determinacion;
        $rd->id_hoja_liq=$request['hoja'];
        $rd->fec_reg=date("d/m/Y");
        $rd->anio=date("Y");
        $rd->id_usuario=Auth::user()->id;;
        $rd->save();
        $id_pred_anio=$this->create_predio_fis($rd->id_rd);
        $this->calculos_ivpp($id_pred_anio);
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
                $this->predio_contribuyente_create($id_pred_anio,$pre->id_pred_anio);
                $this->create_pisos($pre->id_fic,$id_pred_anio);
                $this->create_instalaciones($pre->id_fic,$id_pred_anio);
                
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
        $val->are_terr = $pre->are_terr;
        $val->flg_act = 1;
        $val->val_ter = ($pre->are_terr+$pre->are_com_terr)*$pre->are_terr;
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
        $val->are_com_terr = $pre->are_com_terr;
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
                $Lista->rows[$Index]['id'] = $Datos->id_rd;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_rd),
                    trim($Datos->nro_rd),
                    trim($Datos->contribuyente),
                    trim($this->getCreatedAtAttribute($Datos->fec_reg)->format('d/m/Y')),
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
            $view =  \View::make('fiscalizacion.reportes.rd', compact('sql','fichas','predios'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("rd.pdf");
        }
    }
}
