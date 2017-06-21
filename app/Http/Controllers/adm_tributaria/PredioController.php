<?php

namespace App\Http\Controllers\adm_tributaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Predios;

class PredioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sectores = DB::select('select * from catastro.sectores');
        $manzanas = DB::select('select * from catastro.manzanas where id_sect=1 ');
        $condicion = DB::select('select * from adm_tri.cond_prop order by id_cond ');
        $ecc = DB::select('select * from adm_tri.ecc order by id_ecc ');
        $ecc2=$ecc;
        $ecc3=$ecc;
        $tpre = DB::select('select * from adm_tri.tip_predio order by id_tip_p ');
        $upa = DB::select('select * from adm_tri.uso_predio_arbitrios order by id_uso_arb ');
        $fadq = DB::select('select * from adm_tri.form_adq order by id_for ');
        $pisclasi = DB::select('select * from adm_tri.clas_predio where id_cla_pre>0 order by id_cla_pre');
        $pismat = DB::select('select * from adm_tri.mep order by id_mep');
        $pismat2=$pismat;
        $pisecs = DB::select('select * from adm_tri.ecs order by id_ecs');
        $pisecs2=$pisecs;
        return view('adm_tributaria/vw_predio', compact('sectores','manzanas','condicion','ecc','tpre','upa','fadq','pisclasi','pismat','pisecs','ecc2','pismat2','pisecs2','ecc3'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $predio->id_hab_urb = 1;
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
        $predio->save();
        return $predio->id_pred;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        echo "store";
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $prediovw= DB::table('adm_tri.vw_predi_urba')->where('id_pred',$id)->get();
        $predio= DB::table('adm_tri.predios')->leftJoin('catastro.usos_predio', 'predios.id_uso_predio', '=', 'usos_predio.id_uso')->where('predios.id_pred',$id)->get();
        $prediovw[0]->id_uso_predio=$predio[0]->id_uso_predio;
        $prediovw[0]->codi_uso=$predio[0]->codi_uso;
        $prediovw[0]->desc_uso=$predio[0]->desc_uso;
        $prediovw[0]->id_uso_pred_arbitrio=$predio[0]->id_uso_pred_arbitrio;
        $prediovw[0]->fech_adquis=date("d/m/Y",strtotime(str_replace("/", "-", $predio[0]->fech_adquis)));
        $prediovw[0]->luz_nro_sum=$predio[0]->luz_nro_sum;
        $prediovw[0]->agua_nro_sum=$predio[0]->agua_nro_sum;
        $prediovw[0]->licen_const=$predio[0]->licen_const;
        $prediovw[0]->conform_obra=$predio[0]->conform_obra;
        $prediovw[0]->declar_fabrica=$predio[0]->declar_fabrica;
        $prediovw[0]->declar_fabrica=$predio[0]->declar_fabrica;
        return $prediovw;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
            $val->save();
   
        }
        return "edit".$id;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return "update";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
        $secmn= $request['mnza'];
        $totalg = DB::select("select count(id_pred) as total from adm_tri.vw_predi_urba where id_mzna='$secmn' and anio='".date("Y")."'");
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
        
        $sql = DB::select("select id_pred,tp,lote,cod_cat,mzna_dist,lote_dist,nro_mun,descripcion,contribuyente,nom_via,id_via,are_terr,val_ter,val_const from adm_tri.vw_predi_urba where id_mzna='$secmn' and anio='".date("Y")."'");
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
}
