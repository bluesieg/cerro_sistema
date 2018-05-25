<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class General extends Controller {

    public function index() {
        return view('vw_general');
    }

    public function get_tipo_doc(Request $request) {
//        $table = DB::select('select * from tipo_documento');
//        dd($table);

        $doc = DB::table('adm_tri.tipo_documento')->get();

        $datos = array();
        for ($y = 0; $y <= (count($doc) - 1); $y++) {
            $Lista = array();
            $Lista['tip_doc'] = $doc[$y]->tip_doc;
            $Lista['tipo_documento'] = $doc[$y]->tipo_documento;
            array_push($datos, $Lista);
        }
        if ($request->ajax()) {
            return response()->json($datos);
        }
    }

    public function get_cond_exonerac(Request $request) {
        $doc = DB::table('adm_tri.cond_exonerac')->get();

        $datos = array();
        for ($y = 0; $y <= (count($doc) - 1); $y++) {
            $Lista = array();
            $Lista['id_cond_exo'] = $doc[$y]->id_cond_exo;
            $Lista['desc_cond_exon'] = $doc[$y]->desc_cond_exon;
            array_push($datos, $Lista);
        }
        if ($request->ajax()) {
            return response()->json($datos);
        }
    }

    function autocompletar_direccion(Request $request) {
//        header("Content-Type: application/json"); 
        $data = $request->all();
//        $Consulta = DB::table('catastro.vw_vias')->where('nom_via','like','%'.$data['nom_via'].'%')->get();  
        $Consulta = DB::table('catastro.vw_vias')->get();

        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();
            $Lista->value = $Datos->id_via;
            $Lista->label = trim($Datos->abrev).'-'.trim($Datos->nom_via);
//            $Lista->llabel= trim($Datos->abrev).'-'.trim($Datos->nom_via);
            array_push($todo, $Lista);
        }
        return response()->json($todo);
    }

    function autocomplete_hab_urb(Request $request){

        $data = $request->all();
        $Consulta = DB::select('select id_hab_urb,nomb_hab_urba from catastro.hab_urb');
        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();
            $Lista->value = $Datos->id_hab_urb;
            $Lista->label = trim($Datos->nomb_hab_urba);
//            $Lista->llabel= trim($Datos->abrev).'-'.trim($Datos->nom_via);
            array_push($todo, $Lista);
        }

        return response()->json($todo);

    }
    function autocompletar_tipo_uso(Request $request){
        $data = $request->all();

        $Consulta = DB::table('catastro.usos_predio')->get();  

        $todo=array();
        foreach($Consulta as $Datos)
        {
            $Lista=new \stdClass();           
            $Lista->value=$Datos->id_uso;
            $Lista->label=  trim($Datos->desc_uso);           
            $Lista->codi=  trim($Datos->codi_uso);           
            array_push($todo,$Lista);
        }        
        return response()->json($todo);

    }
    function autocompletar_instalaciones(Request $request){
        $Consulta = DB::table('catastro.instalaciones')->where('anio',date("Y"))->get();  
        $todo=array();
        foreach($Consulta as $Datos)
        {
            $Lista=new \stdClass();           
            $Lista->value=$Datos->id_instal;
            $Lista->label=  trim($Datos->cod_instal)."-".trim($Datos->descrip_instal);           
            $Lista->codi=  trim($Datos->cod_instal);           
            $Lista->und=  trim($Datos->unid_medida);           
            array_push($todo,$Lista);
        }        
        return response()->json($todo);
    }
    function sel_viaby_sec(Request $request)
    {
        $Consulta = DB::table('catastro.vw_arancel')->where('anio',$request['an'])->where('sec',$request['sec'])->where('mzna',$request['mzna'])->get();  
        return $Consulta;
    }
    function sel_cat_gruterr(Request $request)
    {
        $Consulta = DB::table('catastro.vw_arancel_pred_rust')->where('anio',$request['an'])->where('id_gpo_tierra',$request['val'])->get();  
        return $Consulta;
    }
    /*****************************************COMBO  DEPARTAMENTO / PROVINCIA / DISTRITO **************************************/
    public function get_dpto() {
        $Consulta = DB::table('maysa.dpto')->get();
        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();
            $Lista->cod = $Datos->cod;
            $Lista->dpto = trim($Datos->dpto);
            array_push($todo, $Lista);
        }
        return response()->json($todo);
    }

    public function get_prov(Request $request) {
        $Consulta = DB::table('maysa.prov')->where('cod', $request['cod_dpto'])->get();
        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();
            $Lista->cod_prov = $Datos->cod_prov;
            $Lista->provinc = trim($Datos->provinc);
            array_push($todo, $Lista);
        }
        return response()->json($todo);
    }

    public function get_dist(Request $request) {
        $Consulta = DB::table('maysa.dist')->where('cod_prov', $request['cod_prov'])->get();
        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();
            $Lista->cod_dist = $Datos->cod_dist;
            $Lista->distrit = trim($Datos->distrit);
            array_push($todo, $Lista);
        }
        return response()->json($todo);
    }

    public function prueba() {
//        $table = DB::select('select * from catastro.vw_vias');
        
        $data=array();
        $data['anio']='2017';
        $data['sec']='01';
        $data['mzna']='001';
        $data['cod_via']='002020';
        $data['val_ara']=400;
        $data['id_via']=8;
        $data['cod_arancel']='201701001002020';
        $table=DB::table('catastro.arancel')->insert($data);
        
        return   response()->json(pg_last_error($table));
//        dd(DB::getQueryLog($table));
//        dd($table);
    }
    
    function fraccionamiento(){
        $tim=1.2/100;
        $tif=0.8*$tim;
        
        $total=10093.64;
        $inicial=3000.00;
        $n_cuotas=6;
        
        $pot= pow(2, 3);
        
        $deuda_total=$total-$inicial;
        
        $cc=(($tif*pow(1+$tif,$n_cuotas))/(pow(1+$tif,$n_cuotas)-1))*$deuda_total;
        
        echo 'TIM: '.$tim.'<br>TIF: '.$tif.'<br>';
        echo 'Total: '.$total.'<br>';
        echo 'Inicial: '.$inicial.'<br>';
        echo 'N° Cuotas: '.$n_cuotas.'<br>';
        echo 'Total: '.$pot.'<br>';
        echo 'Deuda: '.$deuda_total.'<br>'; 
        echo 'Cuota C: '.number_format($cc,2,'.','').'<br>';
        
        $amor=0;$saldo=0;$interes=0;$deuda=0;
        
        for($i=1;$i<=$n_cuotas;$i++){
           
            
            if($i==1){$saldo=$total-$inicial;}
            $interes=$tif*$saldo;
            $amor=$cc-$interes;
            $deuda=$saldo-$amor;
            $saldo=$deuda;
            echo $i.'-'.number_format($deuda,2).'-'.round($amor,2).'-'.round($interes,2).'-'.round($cc,2).'<br>';
        }
        
        dd($tif);
        
        
    }
}
