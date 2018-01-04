<?php

namespace App\Http\Controllers\adm_tributaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Predios;
use App\Models\Predios\Predios_Anio;
use App\Models\Predios\Predios_Contribuyentes;
use App\Models\Predios\Predios_Rusticos;
use Illuminate\Support\Facades\Auth;

class PredioRuralController extends Controller
{

    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_preru' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        $condicion = DB::select('select * from adm_tri.cond_prop order by id_cond ');
        $tipoterr = DB::select('select * from ADM_TRI.TIPO_PREDIO_RUSTICO  order by id_tip_pre_rus');
        $usoterr = DB::select('select * from catastro.usos_pr_rustico   order by id_uso_pr');
        $gpoterr = DB::select('select * from adm_tri.gpo_tierras where id_gpo>1 order by id_gpo');
        $ecc = DB::select('select * from adm_tri.ecc order by id_ecc ');
        $tpre = DB::select('select * from adm_tri.tip_predio order by id_tip_p ');
        $upa = DB::select('select * from adm_tri.uso_predio_arbitrios order by id_uso_arb ');
        $fadq = DB::select('select * from adm_tri.form_adq order by id_for ');
        $pisclasi = DB::select('select * from adm_tri.clas_predio where id_cla_pre>0 order by id_cla_pre');
        $pismat = DB::select('select * from adm_tri.mep order by id_mep');
        $pisecs = DB::select('select * from adm_tri.ecs order by id_ecs');
        $condi_pen = DB::select('select * from adm_tri.condi_pensionista order by id_con');
        return view('adm_tributaria/vw_predio_ru', compact('permisos','menu','anio_tra','condicion','tipoterr','usoterr','gpoterr','ecc','tpre','upa','fadq','pisclasi','pismat','pisecs','condi_pen'));
    }

    public function calculos_ivpp($id)
    {
        DB::select("select adm_tri.actualiza_base_predio(".$id.")");
        $Predios_Anio=new Predios_Anio;
        $Predios_Anio=  $Predios_Anio::where("id_pred_anio","=",$id )->first();
        $Predios_Contribuyentes=new Predios_Contribuyentes;
        $Predios_Contribuyentes=  $Predios_Contribuyentes::where("id_pred","=",$Predios_Anio->id_pred )->first();
        DB::select("select adm_tri.calcular_ivpp($Predios_Anio->anio,$Predios_Contribuyentes->id_contrib)");
    }
    
    public function create(Request $request)
    {
        $predio=new Predios;
        $predio->id_lote = 0;
        $predio->id_via = 0;
        $predio->tip_pre_u_r = 2;
        $predio->save();
        if($predio->id_pred)
        {
            $id_pre_anio=$this->predio_anio_create($predio->id_pred,$request);
            $this->predio_contribuyente_create($predio->id_pred,$id_pre_anio, $request);
            $this->create_rus($id_pre_anio, $request);
            $this->calculos_ivpp($id_pre_anio);
        }
        return $id_pre_anio;
    }
    public function predio_anio_create($id_pred,Request $request)
    {
        $predio_anio=new Predios_Anio;
        $predio_anio->id_pred=$id_pred;
        $predio_anio->anio=$request['an'];
        $predio_anio->arancel = $request['aranc'];
        $predio_anio->are_terr = $request['areterr'];
        $predio_anio->flg_act = 1;
        $predio_anio->val_ter = $request['areterr']*$request['aranc'];
        $predio_anio->id_cond_prop = $request['condpre'];
        $predio_anio->id_est_const = $request['ecc'];
        $predio_anio->id_tip_pred = $request['tpre'];
        $predio_anio->fech_adquis = $request['ffor'];
        $predio_anio->nro_condominios = $request['condos'];
        $predio_anio->id_uso_predio = 1;
        $predio_anio->id_usuario = Auth::user()->id;
        $predio_anio->fec_reg = date("d/m/Y");
        $predio_anio->hora_reg = date("H:i");
        $predio_anio->save();
        return $predio_anio->id_pred_anio;
    }
    public function predio_contribuyente_create($id_pred,$id_pre_anio,Request $request)
    {
        $predio_contribuyentes=new Predios_Contribuyentes;
        $predio_contribuyentes->id_pred=$id_pred;
        $predio_contribuyentes->id_contrib=$request['contrib'];
        $predio_contribuyentes->fec_ini = date("d/m/Y");
        $predio_contribuyentes->flg_act = 1;
        $predio_contribuyentes->porcen_titularidad = 100;
        $predio_contribuyentes->id_form_adq = $request['ifor'];
        $predio_contribuyentes->id_pred_anio = $id_pre_anio;
        $predio_contribuyentes->save();
    }
    public function create_rus($id,Request $request)
    {
        $rustico=new Predios_Rusticos;
        $rustico->id_pred_anio = $id;
        $rustico->lugar_pr_rust = $request['valle'];
        $rustico->ubicac_pr_rus  = $request['carretera'];
        $rustico->klm  = $request['km'];
        $rustico->nom_pre_pr_rus   = $request['nompre'];
        $rustico->norte   = $request['norte'];
        $rustico->sur   = $request['sur'];
        $rustico->este   = $request['este'];
        $rustico->oeste   = $request['oeste'];
        $rustico->id_tip_pre_rus = $request['tterr'];
        $rustico->id_uso_pre_rust = $request['uterr'];
        $rustico->id_gpo_tierra=$request['gpt'];
        $rustico->id_cat_gpo_tierra=$request['cgpt'];
        $rustico->save();
        return $rustico->id_pred_rus;
    }
    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $prediovw= DB::table('adm_tri.vw_predi_rustico')->where('id_pred_anio',$id)->get();
        $prediovw[0]->fech_adquis=date("d/m/Y",strtotime(str_replace("/", "-", $prediovw[0]->fech_adquis)));
        $prediovw[0]->foto=0;
        return $prediovw;
    }


    public function predio_rustico_edit($id,Request $request)
    {
        $rustico=new Predios_Rusticos;
        $val=  $rustico::where("id_pred_anio","=",$id )->first();
        if(count($val)>=1)
        {
            $val->lugar_pr_rust = $request['valle'];
            $val->ubicac_pr_rus  = $request['carretera'];
            $val->klm  = $request['km'];
            $val->nom_pre_pr_rus   = $request['nompre'];
            $val->norte   = $request['norte'];
            $val->sur   = $request['sur'];
            $val->este   = $request['este'];
            $val->oeste   = $request['oeste'];
            $val->id_tip_pre_rus = $request['tterr'];
            $val->id_uso_pre_rust = $request['uterr'];
            $val->id_gpo_tierra=$request['gpt'];
            $val->id_cat_gpo_tierra=$request['cgpt'];
            $val->save();
        }
        return "edit".$id;
    }
    public function edit(Request $request,$id)
    {
        $predio_anio=new Predios_Anio;
        $val=  $predio_anio::where("id_pred_anio","=",$id )->first();
        if(count($val)>=1)
        {
            $this->predio_rustico_edit($val->id_pred_anio,$request);
            $this->predio_contribuyente_edit($val->id_pred,$request);
            $val->id_cond_prop = $request['condpre'];
            $val->nro_condominios = $request['condos'];
            $val->id_est_const = $request['ecc'];
            $val->id_tip_pred = $request['tpre'];
            $val->fech_adquis = $request['ffor'];
            $val->are_terr = $request['areterr'];
            $val->arancel = $request['aranc'];
            $val->val_ter = $request['areterr']*$request['aranc'];
            $val->save();
        }
        $this->calculos_ivpp($id);
        return "edit".$id;
    }

    public function predio_contribuyente_edit($id,Request $request)
    {
        $predio_contribuyentes=new Predios_Contribuyentes;
        $val=  $predio_contribuyentes::where("id_pred","=",$id )->first();
        if(count($val)>=1)
        {
            $val->id_form_adq = $request['ifor'];
            $val->save();
        }
    }

 
    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
