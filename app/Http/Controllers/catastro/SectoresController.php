<?php

namespace App\Http\Controllers\catastro;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Predios;
use Illuminate\Support\Facades\Auth;

class SectoresController extends Controller
{
    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='conf_cat_sect' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        return view('catastro/vw_catastro_sectores',compact('menu','permisos'));
    }

    public function show($id)
    {
        $sector = DB::table('catastro.sectores')->where('id_sec',$id)->get();
        return $sector;
    }

    public function getSectores(){
        header('Content-type: application/json');
        //$sectores = DB::select('select * from catastro.sectores order by sector');
        //return response()->json($sectores);
        
        $sectores = DB::select('select count(id_sec) as total from catastro.sectores ');

        //$totalg = DB::select("select count(id_pag) as total from configuracion.vw_fec_venc_ivpp where id_anio='".$request['anio']."'");
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];

        $total_pages = 0;
        if (!$sidx) {
            $sidx = 1;
        }
        $count = $sectores[0]->total;
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

     

        $sql = DB::table('catastro.sectores')->orderBy($sidx,$sord)->limit($limit)->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;

        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_sec;
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_sec),
                trim($Datos->sector),

            );
        }

        return response()->json($Lista);
    }

    public function insert_new_sector(Request $request){
        header('Content-type: application/json');
        $data = $request->all();

        $insert=DB::table('catastro.sectores')->insert($data);

        if ($insert) return response()->json($data);
        else return false;
    }

    function update_sector(Request $request) {
        $data = $request->all();
        unset($data['id_sec']);
        $update=DB::table('catastro.sectores')->where('id_sec',$request['id_sec'])->update($data);
        if ($update){
            return response()->json([
                'msg' => 'si',
            ]);
        }else return false;
    }

    function delete_sector(Request $request){
//        $user = DB::table('adm_tri.contribuyentes')->select('usuario')->where('id_pers', '=', $request['id'])->get();
        $delete = DB::table('catastro.sectores')->where('id_sec', $request['id_sec'])->delete();

        if ($delete) {
            return response()->json([
                'msg' => 'si',
            ]);
        }
    }

    
    public function create(Request $request)
    {
        $predio=new Predios;
        $predio->id_cond_prop = $request['condpre'];
        $predio->nro_condominios = $request['condos'];
        $predio->id_via = $request['cvia'];
        $predio->nro_mun = $request['n'];
        $predio->mzna_dist = $request['mz'];
        $predio->lote_dist = $request['lt'];
        $predio->zona = $request['zn'];
        $predio->secc = $request['secc'];
        $predio->piso = $request['piso'];
        $predio->dpto = $request['dpto'];
        $predio->nro_int = $request['int'];
        $predio->referencia = $request['ref'];
        $predio->id_contrib = $request['contrib'];
        $predio->id_exon = 1;
        $predio->id_cond_esp_exon = 1;
        $predio->id_hab_urb = 2;
        $predio->mzna = $request['mzna'];
        $predio->sec = $request['sec'];
        $predio->lote = $request['lote'];
        $predio->anio = date("Y");
        $predio->cod_cat = $request['sec'].$request['mzna'].$request['lote'];
        $predio->id_est_const = $request['ecc'];
        $predio->id_tip_pred = $request['tpre'];
        $predio->id_uso_predio = $request['tipuso'];
        $predio->id_uso_pred_arbitrio = $request['uprearb'];
        $predio->id_form_adq = $request['ifor'];
        $predio->fech_adquis = $request['ffor'];
        $predio->luz_nro_sum = $request['luz'];
        $predio->agua_nro_sum = $request['agua'];
        $predio->licen_const = $request['liccon'];
        $predio->conform_obra = $request['confobr'];
        $predio->declar_fabrica = $request['defra'];
        $predio->are_terr = $request['areterr'];
        $predio->are_com_terr = $request['arecomter'];
        $predio->arancel = $request['aranc'];
        $predio->val_ter = ($request['areterr']+$request['arecomter'])*$request['aranc'];
        $predio->tip_pre_u_r = 1;
        $predio->save();
        return $predio->id_pred;
    }

    public function store(Request $request)
    {
        echo "store";
    }

    public function edit(Request $request,$id)
    {
        $predio=new Predios;
        $val=  $predio::where("id_pred","=",$id )->first();
        if(count($val)>=1)
        {
            $val->id_cond_prop = $request['condpre'];
            $val->nro_condominios = $request['condos'];
            $val->id_via = $request['cvia'];
            $val->nro_mun = $request['n'];
            $val->mzna_dist = $request['mz'];
            $val->lote_dist = $request['lt'];
            $val->zona = $request['zn'];
            $val->secc = $request['secc'];
            $val->piso = $request['piso'];
            $val->dpto = $request['dpto'];
            $val->nro_int = $request['int'];
            $val->referencia = $request['ref'];
            $val->id_est_const = $request['ecc'];
            $val->id_tip_pred = $request['tpre'];
            $val->id_uso_predio = $request['tipuso'];
            $val->id_uso_pred_arbitrio = $request['uprearb'];
            $val->id_form_adq = $request['ifor'];
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
        return "edit".$id;
    }

    public function update(Request $request, $id)
    {
        return "update";
    }

    public function destroy($id)
    {
        echo "destroy";
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
    
    public function listpredio(Request $request)
    {
        header('Content-type: application/json');
        if($request['mnza']=='0'&&$request['ctr']>0)
        {
            $totalg = DB::select("select count(id_pred) as total from adm_tri.vw_predi_urba where id_contrib='".$request['ctr']."' and anio='".$request['an']."'");
            $sql = DB::select("select id_pred,tp,lote,cod_cat,mzna_dist,lote_dist,nro_mun,descripcion,contribuyente,nom_via,id_via,are_terr,val_ter,val_const from adm_tri.vw_predi_urba where id_contrib='".$request['ctr']."' and anio='".$request['an']."'");
        
        }
        else
        {
            $totalg = DB::select("select count(id_pred) as total from adm_tri.vw_predi_urba where id_mzna='".$request['mnza']."' and anio='".$request['an']."'");
            $sql = DB::select("select id_pred,tp,lote,cod_cat,mzna_dist,lote_dist,nro_mun,descripcion,contribuyente,nom_via,id_via,are_terr,val_ter,val_const from adm_tri.vw_predi_urba where id_mzna='".$request['mnza']."' and anio='".$request['an']."'");
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
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;

        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_pred;
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_pred),
                trim($Datos->tp),
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
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        return view('adm_tributaria/imp_formatos', compact('anio_tra'));
    }

    public function reporte($tip,$id,$an,$contri) 
    {
        
        if($tip=='HR'||$tip=='hr')
        {
            $sql=DB::table('adm_tri.vw_contrib_hr')->where('id_pers',$contri)->get()->first();
            $sql_pre=DB::table('adm_tri.vw_predi_urba')->where('id_pers',$contri)->where('anio',$an)->get();
            $view =  \View::make('adm_tributaria.reportes.hr', compact('sql','sql_pre'))->render();
        }
        if($tip=='PU'||$tip=='pu')
        {
            $sql=DB::table('adm_tri.vw_pred_pu')->where('id_pred',$id)->where('anio',$an)->get()->first();
            $sql_pis    =DB::table('adm_tri.vw_pisos')->where('id_predio',$id)->orderBy('num_pis')->get();
            $view =  \View::make('adm_tributaria.reportes.pu', compact('sql','sql_pis'))->render();
        }
        if(count($sql)>=1)
        {
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream($tip.$an);
        }
        else
        {   return 'No hay datos';}
    }

    
}
