<?php

namespace App\Http\Controllers\adm_tributaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Predios;
use App\Models\Predios\Predios_Anio;
use App\Models\Predios\Predios_Contribuyentes;
use Illuminate\Support\Facades\Auth;


class PredioController extends Controller
{
    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_preurb' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        $sectores = DB::select('select * from catastro.sectores where id_sec>0 order by sector');
        $manzanas = DB::select('select * from catastro.manzanas where id_mzna>0 and id_sect=(select id_sec from catastro.sectores where id_sec>0 order by sector limit 1) ');
        $condicion = DB::select('select * from adm_tri.cond_prop order by id_cond ');
        $ecc = DB::select('select * from adm_tri.ecc order by id_ecc ');
        $tpre = DB::select('select * from adm_tri.tip_predio order by id_tip_p ');
        $fadq = DB::select('select * from adm_tri.form_adq order by id_for ');
        $pisclasi = DB::select('select * from adm_tri.clas_predio where id_cla_pre>0 order by id_cla_pre');
        $pismat = DB::select('select * from adm_tri.mep order by id_mep');
        $pisecs = DB::select('select * from adm_tri.ecs order by id_ecs');
        $condi_pen = DB::select('select * from adm_tri.condi_pensionista order by id_con');
        $upa = DB::select('select * from adm_tri.uso_predio_arbitrios order by id_uso_arb ');
        return view('adm_tributaria/vw_predio', compact('anio_tra','sectores','manzanas','condicion','ecc','tpre','fadq','pisclasi','pismat','pisecs','condi_pen','menu','permisos','upa'));
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
        $predio->id_lote = $request['lote'];
        $predio->piso = strtoupper($request['piso']);
        $predio->mzna_dist = strtoupper($request['mz']);
        $predio->lote_dist = strtoupper($request['lt']);
        $predio->nro_mun = strtoupper($request['n']);
        $predio->nro_int = strtoupper($request['int']);
        $predio->dpto = strtoupper($request['dpto']);
        $predio->id_via = strtoupper($request['cvia']);
        $predio->tip_pre_u_r = 1;
        $predio->zona = strtoupper($request['zn']);
        $predio->secc = strtoupper($request['secc']);
        $predio->referencia = strtoupper($request['ref']);
        $predio->sup_mzna = strtoupper($request['supmzna']);
        $predio->gpo_zonal = strtoupper($request['gpozonal']);
       
        $predio->save();
        if($predio->id_pred)
        {
            $id_pre_anio=$this->predio_anio_create($predio->id_pred,$request);
            $this->predio_contribuyente_create($predio->id_pred,$id_pre_anio, $request);
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
        $predio_anio->val_ter = ($request['areterr']+$request['arecomter'])*$request['aranc'];
        $predio_anio->id_cond_prop = $request['condpre'];
        $predio_anio->id_est_const = $request['ecc'];
        $predio_anio->id_tip_pred = $request['tpre'];
        $predio_anio->luz_nro_sum = $request['luz'];
        $predio_anio->agua_nro_sum = $request['agua'];
        $predio_anio->fech_adquis = $request['ffor'];
        $predio_anio->nro_condominios = $request['condos'];
        $predio_anio->licen_const = $request['liccon'];
        $predio_anio->id_uso_predio = $request['tipuso'];
        $predio_anio->conform_obra = $request['confobr'];
        $predio_anio->declar_fabrica = $request['defra'];
        $predio_anio->are_com_terr = $request['arecomter'];
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
        $predio_contribuyentes->desc_otros = strtoupper($request['otros']);
        $predio_contribuyentes->save();////////
    }

    public function store(Request $request)
    {
        echo "store";
    }
    public function show($id)
    {
        $prediovw= DB::table('adm_tri.vw_predi_urba')->where('id_pred_anio',$id)->get();
        $prediovw[0]->fech_adquis=date("d/m/Y",strtotime(str_replace("/", "-", $prediovw[0]->fech_adquis)));
        $prediovw[0]->foto= $this->getfoto($prediovw[0]->sec,$prediovw[0]->mzna,$prediovw[0]->lote);
        return $prediovw;
    }
    public function edit(Request $request,$id)
    {
        $predio_anio=new Predios_Anio;
        $val=  $predio_anio::where("id_pred_anio","=",$id )->first();
        if(count($val)>=1)
        {
            $this->predio_edit($val->id_pred,$request);
            $this->predio_contribuyente_edit($val->id_pred,$request);
            $val->id_cond_prop = $request['condpre'];
            $val->nro_condominios = $request['condos'];
            $val->id_est_const = $request['ecc'];
            $val->id_tip_pred = $request['tpre'];
            $val->id_uso_predio = $request['tipuso'];
            $val->fech_adquis = $request['ffor'];
            $val->luz_nro_sum = $request['luz'];
            $val->agua_nro_sum = $request['agua'];
            $val->licen_const = $request['liccon'];
            $val->conform_obra = $request['confobr'];
            $val->declar_fabrica = $request['defra'];
            $val->are_terr = $request['areterr'];
            $val->are_com_terr = $request['arecomter'];
            $val->arancel = $request['aranc'];
            
            $val->val_ter = ($request['areterr']+$request['arecomter'])*$request['aranc'];
            $val->save();
        }
        $this->calculos_ivpp($id);
        return "edit".$id;
    }
    public function predio_edit($id,Request $request)
    {
        $predio=new Predios;
        $val=  $predio::where("id_pred","=",$id )->first();
        if(count($val)>=1)
        {
            $val->id_via = strtoupper($request['cvia']);
            $val->nro_mun = strtoupper($request['n']);
            $val->mzna_dist = strtoupper($request['mz']);
            $val->lote_dist = strtoupper($request['lt']);
            $val->zona = strtoupper($request['zn']);
            $val->secc = strtoupper($request['secc']);
            $val->piso = strtoupper($request['piso']);
            $val->dpto = strtoupper($request['dpto']);
            $val->nro_int = strtoupper($request['int']);
            $val->referencia = strtoupper($request['ref']);
            $val->sup_mzna = strtoupper($request['supmzna']); 
            $val->gpo_zonal = strtoupper($request['gpozonal']);
            $val->save();
        }
    }
    public function predio_contribuyente_edit($id,Request $request)
    {
        $predio_contribuyentes=new Predios_Contribuyentes;
        $val=  $predio_contribuyentes::where("id_pred","=",$id )->first();
        if(count($val)>=1)
        {
            $val->id_form_adq = $request['ifor'];
            $val->desc_otros = strtoupper($request['otros']);
            $val->save();
        }
    }

    public function update(Request $request, $id)
    {
        return "update";
    }
    public function destroy(Request $request)
    {
        $id_pred=DB::select("select id_pred,anio from adm_tri.predios_anio where id_pred_anio=".$request['id']);
        $return=DB::select("select adm_tri.fn_elimina_predio(".$id_pred[0]->id_pred.")");
    }
    public function ListManz(Request $request)
    {
        $manzanas = DB::table('catastro.manzanas')->where('id_sect',$request['sec'])->orderBy('codi_mzna')->get();
        $todo=array();
        foreach($manzanas as $Datos){      
            $Lista=new \stdClass();
            $Lista->id_mzna      =  trim($Datos->id_mzna);
            $Lista->codi_mzna         =  trim($Datos->codi_mzna);
            array_push($todo, $Lista);
        }        
        return response()->json($todo);
    }
    public function ListLote(Request $request)
    {
        $manzanas = DB::table('catastro.vw_lotes')->where('id_mzna',$request['man'])->orderBy('codi_lote')->get();
        $todo=array();
        foreach($manzanas as $Datos){      
            $Lista=new \stdClass();
            $Lista->id_lote   =  trim($Datos->id_lote);
            $Lista->codi_lote =  trim($Datos->codi_lote);
            array_push($todo, $Lista);
        }        
        return response()->json($todo);
    }
    public function validar(Request $request)
    {
        $count = DB::table('adm_tri.vw_predi_urba')->where('id_lote',$request['lote'])->where('anio',$request['an'])->where('pred_anio_activo',1)->where('pred_contrib_activo',1)->count();
        if($count==0)
        {
            return $count;
        }
        else
        {
            $poseedores=DB::table('adm_tri.vw_predi_urba')->select('contribuyente')->where('id_lote',$request['lote'])->where('anio',$request['an'])->where('pred_anio_activo',1)->where('pred_contrib_activo',1)->get();
            $lista="";
            foreach ($poseedores as $contri)
            {
                $lista=$lista."<br>".$contri->contribuyente;
            }
            return $lista;
        }
    }

    public function listpredio(Request $request)
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
        if($request['mnza']=='0')
        {
            if($request['tpre']=='0')
            {
                $totalg = DB::select("select count(id_pred) as total from adm_tri.vw_predi_urba where id_contrib='".$request['ctr']."' and anio='".$request['an']."'");
                $sql = DB::select("select id_pred,id_pred_anio,tp,sec,mzna,lote,cod_cat,mzna_dist,lote_dist,nro_mun,descripcion,contribuyente,nom_via,id_via,are_terr,val_ter,val_const from adm_tri.vw_predi_urba where id_contrib='".$request['ctr']."' and anio='".$request['an']."' and pred_anio_activo=1 and pred_contrib_activo=1 ORDER BY $sidx $sord LIMIT $limit offset $start");
            }
            else
            {
                if($request['ctr']=='0')
                {
                    $totalg = DB::select("select count(id_pred) as total from adm_tri.vw_predi_urba where tip_pre_u_r=".$request['tpre']." and anio='".$request['an']."'");
                    $sql = DB::select("select id_pred,id_pred_anio,tp,sec,mzna,lote,cod_cat,mzna_dist,lote_dist,nro_mun,descripcion,contribuyente,nom_via,id_via,are_terr,val_ter,val_const from adm_tri.vw_predi_urba where tip_pre_u_r=".$request['tpre']." and anio='".$request['an']."' and pred_anio_activo=1 and pred_contrib_activo=1 ORDER BY $sidx $sord LIMIT $limit offset $start");
                }
                else
                {
                    $totalg = DB::select("select count(id_pred) as total from adm_tri.vw_predi_urba where tip_pre_u_r=".$request['tpre']." and id_contrib='".$request['ctr']."' and anio='".$request['an']."'");
                    $sql = DB::select("select id_pred,id_pred_anio,tp,sec,mzna,lote,cod_cat,mzna_dist,lote_dist,nro_mun,descripcion,contribuyente,nom_via,id_via,are_terr,val_ter,val_const from adm_tri.vw_predi_urba where tip_pre_u_r=".$request['tpre']." and id_contrib='".$request['ctr']."' and anio='".$request['an']."' and pred_anio_activo=1 and pred_contrib_activo=1 ORDER BY $sidx $sord LIMIT $limit offset $start");
                }
            }
        }
        else
        {
            $totalg = DB::select("select count(id_pred) as total from adm_tri.vw_predi_urba where tip_pre_u_r=".$request['tpre']." and id_mzna='".$request['mnza']."' and anio='".$request['an']."'");
            $sql = DB::select("select id_pred,id_pred_anio,tp,sec,mzna,lote,cod_cat,mzna_dist,lote_dist,nro_mun,descripcion,contribuyente,nom_via,id_via,are_terr,val_ter,val_const from adm_tri.vw_predi_urba where tip_pre_u_r=".$request['tpre']." and id_mzna='".$request['mnza']."' and anio='".$request['an']."' and pred_anio_activo=1 and pred_contrib_activo=1 ORDER BY $sidx $sord LIMIT $limit offset $start");
        }
        $total_pages = 0;
        if (!$sidx){
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
            $Lista->rows[$Index]['id'] = $Datos->id_pred_anio;
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_pred_anio),
                trim($Datos->tp),
                trim($Datos->sec),
                trim($Datos->mzna),
                trim($Datos->lote),
                trim($Datos->cod_cat),
                trim($Datos->mzna_dist), 
                trim($Datos->lote_dist),
                trim($Datos->nro_mun),
                trim($Datos->descripcion),
                trim($Datos->contribuyente),               
                trim($Datos->nom_via),               
                trim($Datos->id_via),               
                trim($Datos->are_terr),               
                trim($Datos->val_ter),               
                trim($Datos->val_const),               
                            
            );
        }
        return response()->json($Lista);
    }
    public function imprimir_formatos()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_impform' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        return view('adm_tributaria/imp_formatos', compact('anio_tra','menu','permisos'));
    }

    public function reporte($tip,$id,$an,$contri) 
    {
        $usuario = DB::select('SELECT * from public.usuarios where id='.Auth::user()->id);
        $fecha = (date('d/m/Y H:i:s'));
        if($tip=='HR'||$tip=='hr')
        {
            $sql = DB::select("select adm_tri.calcular_ivpp($an,$contri)");
            $sql=DB::table('adm_tri.vw_contrib_hr2')->where('id_contrib',$contri)->where('ano_cta',$an)->get()->first();
            $sql_pre=DB::table('adm_tri.vw_predi_urba')->where('id_contrib',$contri)->where('anio',$an)->where('pred_anio_activo',1)->get();
            $view =  \View::make('adm_tributaria.reportes.hr', compact('sql','sql_pre','usuario','fecha','usuario','fecha'))->render();
        }
        if($tip=='PU'||$tip=='pu')
        {
            $sql=DB::table('adm_tri.vw_predi_urba')->where('id_pred_anio',$id)->get()->first();
            $sql_pis    =DB::table('adm_tri.vw_pisos')->where('id_pred_anio',$id)->orderBy('num_pis')->get();
            $sql_ist    =DB::table('adm_tri.vw_instalaciones')->where('id_pred_anio',$id)->orderBy('cod_instal')->get();
            $sql_cond    =DB::table('adm_tri.vw_condominios')->where('id_pred_anio',$id)->orderBy('id_condom')->get();
            $foto = DB::connection('fotos')->select("select encode(foto,'base64') as foto from sect_".$sql->sec." where id_lote='".$sql->sec.$sql->mzna.$sql->lote."' limit 1");
            $view =  \View::make('adm_tributaria.reportes.pu', compact('sql','sql_pis','sql_ist','sql_cond','foto','usuario','fecha'))->render();
        }
        if($tip=='PR'||$tip=='pr')
        {
            $sql=DB::table('adm_tri.vw_predi_rustico')->where('id_pred_anio',$id)->get()->first();
            $sql_pis    =DB::table('adm_tri.vw_pisos')->where('id_pred_anio',$id)->orderBy('num_pis')->get();
            $sql_ist    =DB::table('adm_tri.vw_instalaciones')->where('id_pred_anio',$id)->orderBy('cod_instal')->get();
            $sql_cond    =DB::table('adm_tri.vw_condominios')->where('id_pred_anio',$id)->orderBy('id_condom')->get();
            $view =  \View::make('adm_tributaria.reportes.pr', compact('sql','predio','sql_pis','sql_ist','sql_cond','usuario','fecha'))->render();
        }
        if(count($sql)>=1)
        {
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream($tip.$an.".pdf");
        }
        else
        {   return 'No hay datos';}
    }
    public function getfoto($sec,$mzna,$lote)
    {
        $foto = DB::connection('fotos')->select("select encode(foto,'base64') as foto from sect_".$sec." where id_lote='".$sec.$mzna.$lote."' limit 1");
        if(count($foto)>=1){
           return $foto[0]->foto;
        }
        else{
            return 0; 
        }
    }
    public function getfotoid($lote)
    {
        $lote = DB::select("SELECT id_lote, a.id_mzna, codi_lote, id_hab_urb, b.codi_mzna,c.id_sec,c.sector
                                                    FROM catastro.lotes a
                                                    left join catastro.manzanas b on a.id_mzna=b.id_mzna
                                                    left join catastro.sectores c on b.id_sect=c.id_sec
                                                   where id_lote=".$lote);
        $foto = DB::connection('fotos')->select("select encode(foto,'base64') as foto from sect_".$lote[0]->sector." where id_lote='".$lote[0]->sector.$lote[0]->codi_mzna.$lote[0]->codi_lote."' limit 1");
        if(count($foto)>=1){
           return $foto[0]->foto;
        }
        else{
            return 0; 
        }
    }
}
