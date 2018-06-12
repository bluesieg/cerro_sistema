<?php

namespace App\Http\Controllers\adm_tributaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Contribuyentes;
use App\Models\Personas;
use Illuminate\Support\Facades\Auth;

class ContribuyentesController extends Controller
{
    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_config_contribuyentes' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $dpto = DB::table('maysa.dpto')->get();
        $condicion=DB::select('select * from adm_tri.exoneracion');
        $tip_doc=DB::select('select * from adm_tri.tipo_documento');
        $tip_contrib=DB::select('select * from adm_tri.tipo_contribuyente');
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        $sectores = DB::select('select * from catastro.sectores order by id_sec');
        $hab_urb = DB::select('select id_hab_urb,nomb_hab_urba from catastro.hab_urb');
        $manzanas = DB::select('select * from catastro.manzanas where id_sect=(select id_sec from catastro.sectores order by id_sec limit 1) ');
        return view('adm_tributaria.vw_contribuyentes', compact('tip_contrib','tip_doc','condicion','dpto','sectores','manzanas','anio_tra','hab_urb','menu','permisos'));
    }

    public function create(Request $request)
    {     
        if($request['id_conv']==0&&$request['contrib_nro_doc_conv']==""&&$request['tipo_persona']==3&&$request['pat_conv']!="")
        {
            $id_conv=$this->create_persona($request);
        }
        else
        {
            $id_conv=$request['id_conv'];
        }
        
        $select=DB::table('adm_tri.contribuyentes')->where('num_expediente',$request['numero_expediente'])->get();
        
        if (count($select)>0) {
            
            if ($request['numero_expediente'] == '') {
                $data = new Contribuyentes();
                $data->tipo_persona=$request['tipo_persona'];
                $data->tlfno_fijo=$request['tlfno_fijo']; 
                $data->tlfono_celular=$request['tlfono_celular'];
                $data->email=$request['email'];
                $data->est_civil=$request['est_civil'];            
                $data->id_dpto=$request['id_dpto'];
                $data->id_prov=$request['id_prov']; 
                $data->id_dist=$request['id_dist'];            
                $data->nro_mun=$request['nro_mun'];
                $data->dpto=$request['dpto'];
                $data->manz=$request['manz'];
                $data->lote=$request['lote'];
                $data->id_cond_exonerac=$request['id_cond_exonerac']; 
                $data->id_via=$request['id_via'];        
                $data->id_pers=$request['id_pers']; 
                $data->id_conv=$id_conv;
                $data->ref_dom_fis= strtoupper($request['ref_dom_fis']);
                $id_persona = $request['tipo_persona'].$request['tipo_doc'].$request['nro_doc'];
                $data->id_persona=$id_persona;
                $data->activo=1;
                $data->fch_inscripcion=date('Y-m-d');
                $data->nom_via_2=$request['nom_via_2'];
                $data->id_usu=Auth::user()->id;
                $data->fec_reg=date("d/m/Y");
                $data->num_expediente=$request['numero_expediente'];
                $data->save();        
                return $data->id_contrib;
            }else{
                return response()->json([
                'msg' => 'si',
                ]);
            }
        }else{
                $data = new Contribuyentes();
                $data->tipo_persona=$request['tipo_persona'];
                $data->tlfno_fijo=$request['tlfno_fijo']; 
                $data->tlfono_celular=$request['tlfono_celular'];
                $data->email=$request['email'];
                $data->est_civil=$request['est_civil'];            
                $data->id_dpto=$request['id_dpto'];
                $data->id_prov=$request['id_prov']; 
                $data->id_dist=$request['id_dist'];            
                $data->nro_mun=$request['nro_mun'];
                $data->dpto=$request['dpto'];
                $data->manz=$request['manz'];
                $data->lote=$request['lote'];
                $data->id_cond_exonerac=$request['id_cond_exonerac']; 
                $data->id_via=$request['id_via'];        
                $data->id_pers=$request['id_pers']; 
                $data->id_conv=$id_conv;
                $data->ref_dom_fis= strtoupper($request['ref_dom_fis']);
                $id_persona = $request['tipo_persona'].$request['tipo_doc'].$request['nro_doc'];
                $data->id_persona=$id_persona;
                $data->activo=1;
                $data->fch_inscripcion=date('Y-m-d');
                $data->nom_via_2=$request['nom_via_2'];
                $data->id_usu=Auth::user()->id;
                $data->fec_reg=date("d/m/Y");
                $data->num_expediente=$request['numero_expediente'];
                $data->save();        
                return $data->id_contrib;
        }
    }
    function create_persona(Request $request){
        $data = new Personas();
        $data->pers_ape_pat = strtoupper($request['pat_conv']);
        $data->pers_ape_mat = strtoupper($request['mat_conv']);
        $data->pers_nombres = strtoupper($request['nom_conv']);
        $data->pers_tip_doc = "02";
        $data->pers_nro_doc = "";
        $data->revisar = 1;
        $data->fec_reg=date("d/m/Y");
        $data->save();        
        return $data->id_pers;
    }

    public function edit(Request $request, $id)
    {
        $contrib = new Contribuyentes();
         if($request['id_conv']==0&&$request['nro_conv']==""&&$request['tipo_persona']==3&&$request['pat_conv']!="")
        {
            $conv=$this->create_persona($request);
        }
        else
        {
            $conv=$request['id_conv'];
        }
        $select=DB::table('adm_tri.contribuyentes')->where('num_expediente',$request['numero_expediente'])->where('id_pers','<>',$request['id_pers'])->get();
        
        if (count($select)>0) {
            if ($request['numero_expediente'] == '') {
                $val = $contrib::where("id_contrib", "=", $id)->first();
                if (count($val) >= 1) {

                    $val->tipo_persona=$request['tipo_persona'];
                    $val->tlfno_fijo=$request['tlfno_fijo']; 
                    $val->tlfono_celular=$request['tlfono_celular'];
                    $val->email=$request['email'];
                    $val->est_civil=$request['est_civil'];            
                    $val->id_dpto=$request['id_dpto'];
                    $val->id_prov=$request['id_prov']; 
                    $val->id_dist=$request['id_dist'];            
                    $val->nro_mun=$request['nro_mun'];
                    $val->dpto=$request['dpto'];
                    $val->manz=$request['manz'];
                    $val->lote=$request['lote'];
                    $val->id_cond_exonerac=$request['id_cond_exonerac']; 
                    $val->id_via=$request['id_via'];        
                    $val->id_pers=$request['id_pers']; 
                    $val->ref_dom_fis= strtoupper($request['ref_dom_fis']);
                    $val->nom_via_2=$request['nom_via_2'];
                    $val->num_expediente=$request['numero_expediente'];
                    $id_persona = $request['tipo_persona'].$request['tipo_doc'].$request['nro_doc'];
                    $val->id_persona=$id_persona;   
                    $val->id_conv=$conv;
                    $val->save();  
                    return $conv;
                }
            }else{
                return response()->json([
                    'msg' => 'si',
                ]);
            }
        }else{
        
        $val = $contrib::where("id_contrib", "=", $id)->first();
        if (count($val) >= 1) {
                       
            $val->tipo_persona=$request['tipo_persona'];
            $val->tlfno_fijo=$request['tlfno_fijo']; 
            $val->tlfono_celular=$request['tlfono_celular'];
            $val->email=$request['email'];
            $val->est_civil=$request['est_civil'];            
            $val->id_dpto=$request['id_dpto'];
            $val->id_prov=$request['id_prov']; 
            $val->id_dist=$request['id_dist'];            
            $val->nro_mun=$request['nro_mun'];
            $val->dpto=$request['dpto'];
            $val->manz=$request['manz'];
            $val->lote=$request['lote'];
            $val->id_cond_exonerac=$request['id_cond_exonerac']; 
            $val->id_via=$request['id_via'];        
            $val->id_pers=$request['id_pers']; 
            $val->ref_dom_fis= strtoupper($request['ref_dom_fis']);
            $val->nom_via_2=$request['nom_via_2'];
            $val->num_expediente=$request['numero_expediente'];
            $id_persona = $request['tipo_persona'].$request['tipo_doc'].$request['nro_doc'];
            $val->id_persona=$id_persona;   
            $val->id_conv=$conv;
            $val->save();  
            return $conv;
        }
      }
    }
    
    function desactiva_contrib(){
//        $id_contrib=$request['id_rd'];
//        $update = DB::table('adm_tri.contribuyentes')->where('$id_contrib',$id_contrib)
//                        ->update(['activo'=>0]);
//        if($update){
//            return response()->json(['msg'=>'si']);
//        }
    }
    
    function insert_persona(Request $request){
        $data = new Personas();
        $data->pers_ape_pat = $request['pers_ape_pat'];
        $data->pers_ape_mat = $request['pers_ape_mat'];
        $data->pers_nombres = $request['pers_nombres'];
        $data->pers_raz_soc = $request['pers_raz_soc'];
        $data->pers_tip_doc = $request['pers_tip_doc'];
        $data->pers_nro_doc = $request['pers_nro_doc'];
        $data->pers_sexo = $request['pers_sexo'];
        $data->pers_fnac = $request['pers_fnac'];
        $image=$request['pers_foto'];
        //$img_file = file_get_contents($image);
        //$data->pers_foto = base64_encode($img_file);
        $data->fec_reg=date("d/m/Y");
        $data->pers_foto = $image;
        $data->entidad_reciproca = $request['entidad_reciproca'];
        $data->save();        
        return $data->id_pers;
    }
    
    function consultar_persona(Request $request){
        $nro_doc = $request['nro_doc'];        
        $contribuyente = DB::table('adm_tri.vw_personas')->where('pers_nro_doc',$nro_doc)->get();
        if(isset($contribuyente[0]->contribuyente)){
            return response()->json([
                    'contrib' => trim(str_replace('-','',$contribuyente[0]->contribuyente)),
                    'id_pers' => trim(str_replace('-','',$contribuyente[0]->id_pers)),
                    'pers_foto'=> $contribuyente[0]->pers_foto,
                    'ape_pat'=> $contribuyente[0]->pers_ape_pat,
                    'ape_mat'=> $contribuyente[0]->pers_ape_mat,
                    'nombres'=> $contribuyente[0]->pers_nombres,
            ]);
        }
    }
    function get_datos_dni(Request $request){
        $jefe = DB::table('public.usuarios')->where('id',Auth::user()->id)->get();
        $acceso = DB::table('adm_tri.conexion_reniec')->where('dni',$jefe[0]->dni_jefe)->get();
        
        $rq		= new \stdClass();
        $rq->data	= new \stdClass();
        $rq->auth	= new \stdClass();

        $rq->auth->dni	= $acceso[0]->dni;		// DNI del usuario
        $rq->auth->pas	= $acceso[0]->clave;           // Contrasenia
        $rq->auth->ruc	= $acceso[0]->ruc;	// RUC de la entida del usuario

        $rq->data->ws	= 'getDatosDni';	// Web Service al que se va a llamar
        $rq->data->dni	= $request['nro_doc'];		// Dato que debe estar acorde al contrato del ws
        $rq->data->cache= 'false';		// Retira informacion del Cache local (true mejora la velocidad de respuesta

        //$url = 'https://ehg.pe/delfos/';		// Endpoint del WS
         //  $url = 'http://ws.ehg.pe/';
       $url = 'http://10.11.10.104/';
        $options = array(
                'http' => array(
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',                
                'content' => json_encode($rq)
            )
        );

        $context  = stream_context_create($options);

        $result = file_get_contents($url, false, $context);

        if ($result === FALSE){  
          echo 'Error de conexion';
        }

        $rpta = json_decode($result);

        if($rpta->resp->code == '0000'){
            $Lista=new \stdClass();
            $Lista->ape_pat=$rpta->data->apPrimer;
            $Lista->ape_mat=$rpta->data->apSegundo;
            $Lista->nombres=$rpta->data->prenombres;
            $Lista->est_civil=$rpta->data->estadoCivil;
            $Lista->dir=$rpta->data->direccion;
            $Lista->ubigeo=$rpta->data->ubigeo;
//            $Lista->foto='http://ws.ehg.pe'.$rpta->data->foto;
            $Lista->foto='http://10.11.10.104'.$rpta->data->foto;
            return response()->json($Lista);
        }
    }
    function get_datos_ruc(Request $request){
        $rq		= new \stdClass();
        $rq->data	= new \stdClass();
        $rq->auth	= new \stdClass();

        $rq->auth->dni	= Auth::user()->dni_jefe;		// DNI del usuario
        $rq->auth->pas	= 'Pr0gr4m4';           // Contrasenia
        $rq->auth->ruc	= '20159515240';	// RUC de la entida del usuario

        $rq->data->ws	= 'getDatosRuc';	// Web Service al que se va a llamar
        $rq->data->ruc	= $request['nro_doc'];		// Dato que debe estar acorde al contrato del ws
        $rq->data->cache= 'true';		// Retira informacion del Cache local (true mejora la velocidad de respuesta

        $url = 'https://ehg.pe/delfos/';		// Endpoint del WS
        $options = array(
                'http' => array(
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($rq)
            )
        );

        $context  = stream_context_create($options);

        $result = file_get_contents($url, false, $context);

        if ($result === FALSE){  
          echo 'Error de conexion';
        }

        $rpta = json_decode($result);

        if($rpta->resp->code == '0000'){
            $Lista=new \stdClass();
            $Lista->raz_soc=$rpta->data->ddp_nombre;
            return response()->json($Lista);
        }else{
            echo $rpta->resp->code.'-'.$rpta->resp->text;
        }
    }
    
    function grid_contrib(Request $request){
        $buscar=$request['buscar'];
        if(isset($request['buscar'])){
            $totalg = DB::select("select count(id_contrib) as total from adm_tri.vw_contribuyentes where contribuyente||' '||nro_doc like '%".$buscar."%'");
        }else{
            $totalg = DB::select('select count(id_contrib) as total from adm_tri.vw_contribuyentes');
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
        if(isset($request['buscar'])){
            $sql = DB::select("select * from adm_tri.vw_contribuyentes_2 where contribuyente||' '||nro_doc like '%".$buscar."%' order by $sidx $sord limit $limit offset $start");
        }else{
            $sql = DB::table('adm_tri.vw_contribuyentes_2')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        }
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        
        
        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_contrib;            
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_persona),
                trim($Datos->persona),
                trim($Datos->tip_doc_desc),
                trim($Datos->nro_doc),
                trim(str_replace("-", "",$Datos->contribuyente)), 
                trim($Datos->cod_via),
                trim($Datos->nom_via),
                trim($Datos->tlfno_fijo),
                trim($Datos->tlfono_celular),
                trim($Datos->tipo_persona),
                trim($Datos->id_cond_exonerac),                
                trim($Datos->est_civil),                
                trim($Datos->email),
                trim($Datos->id_dpto),
                trim($Datos->id_prov),
                trim($Datos->id_dist),
                trim($Datos->id_via),                
                trim($Datos->nro_mun),
                trim($Datos->dpto),
                trim($Datos->manz),
                trim($Datos->lote),
                trim($Datos->ref_dom_fis),                                
                trim($Datos->tip_doc_conv),
                trim($Datos->nro_doc_conv),
                trim($Datos->conv_pat),
                trim($Datos->conv_mat),
                trim($Datos->conv_nombres),
                trim($Datos->num_expediente),
                $Datos->id_pers,
                $Datos->id_conv,
                $Datos->tip_doc
            );
        }
        return response()->json($Lista);
    }
    
    public function get_cotrib_byname(Request $request) {
        if($request['dat']=='0')
        {
            return 0;
        }
        else
        {
        header('Content-type: application/json');
        $totalg = DB::select("select count(id_pers) as total from adm_tri.vw_contribuyentes where contribuyente like '%".$request['dat']."%'");
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

        $sql = DB::table('adm_tri.vw_contribuyentes')->where('contribuyente','like', '%'.strtoupper($request['dat']).'%')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        
        
        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_contrib;            
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_contrib),
                trim($Datos->id_persona),
                trim($Datos->nro_doc),
                trim(str_replace("-", "",$Datos->contribuyente)),
                trim($Datos->email),
                trim($Datos->dom_fis),               
            );
        }
        return response()->json($Lista);
        }
    }

    public function reporte_contribuyentes($sec,$mzna,$anio){
        //$sql = DB::select("select adm_tri.calcular_ivpp($an,$contri)");
        //dd($sec);
        //$anio = '2017';
        $flag = 1;
        $sql=DB::table('adm_tri.vw_contrib_predios_c')->where('sec',$sec)->where('mzna',$mzna)->where('ano_cta',$anio)->get();
        //$sql_pre=DB::table('adm_tri.vw_predi_urba')->where('id_contrib',$contri)->where('anio',$an)->get();
        $view =  \View::make('adm_tributaria.reportes.reporte_contribuyentes', compact('sql','anio','sec','mzna','flag'))->render();

        if(count($sql)>=1)
        {
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4','landscape');
            return $pdf->stream($sec."_".$mzna.".pdf");
        }
        else
        {   return 'No hay datos';}

    }
    public function reporte_contribuyentes_hab_urb($cod_hab_urb,$anio){
        //$sql = DB::select("select adm_tri.calcular_ivpp($an,$contri)");
        //dd($cod_hab_urb);
        //$anio = '2017';
        $sec='0';
        $mzna = '0';
        $flag = 2;

        $sql=DB::table('adm_tri.vw_contrib_hab_urb')->where('id_hab_urb',$cod_hab_urb)->where('anio',$anio)->get();
        //dd(count($sql));
        //$sql_pre=DB::table('adm_tri.vw_predi_urba')->where('id_contrib',$contri)->where('anio',$an)->get();


        if(count($sql)>0)
        {
            $view =  \View::make('adm_tributaria.reportes.reporte_contribuyentes', compact('sql','anio','sec','mzna','flag'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4','landscape');
            return $pdf->stream($sec."_".$mzna.".pdf");
        }
        else
        {   return 'No hay datos';}

        /*
        $flag = 2;
        $sql=DB::table('adm_tri.vw_contrib_hab_urb')->where('id_hab_urb',$cod_hab_urb)->get();
        $view =  \View::make('adm_tributaria.reportes.reporte_contribuyentes', compact('sql','flag'))->render();

        if(count($sql)>=1)
        {
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4','landscape');
            return $pdf->stream($cod_hab_urb.".pdf");
        }
        else
        {   return 'No hay datos';}*/

    }

    function reporte_contribuyentes_otro(){

        $sql=DB::table('reportes.vw_02_contri_predios')->orderBy('id_contrib')->orderBy('nro_doc_conyugue')->get();

        if(count($sql)>0)
        {
            $view =  \View::make('adm_tributaria.reportes.123_123',compact('sql'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4','landscape');
            return $pdf->stream("PRUEBA".".pdf");
        }
        else
        {
            return 'No hay datos';
        }


    }
    public function validar_del_contrib(Request $request)
    {
        $count = DB::table('adm_tri.vw_predi_urba')->where('id_contrib',$request['id'])->count();
        if($count==0)
        {
            return $count;
        }
        else
        {
            $poseedores=DB::table('adm_tri.vw_predi_urba')->select('cod_cat')->where('id_contrib',$request['id'])->get();
            $lista="";
            foreach ($poseedores as $contri)
            {
                $lista=$lista."<br>".$contri->cod_cat;
            }
            return $lista;
        }
    }
    public function destroy(Request $request)
    {   
        $contri=new Contribuyentes;
        $val=  $contri::where("id_contrib","=",$request['id'] )->first();
        if(count($val)>=1)
        {
            $persona=$val->id_pers;
            $val->delete();
            $count = DB::table('adm_tri.vw_contribuyentes')->where('id_pers',$persona)->count();
            if($count==0)
            {
                $per=new Personas;
                $valper=  $per::where("id_pers","=",$persona)->first();
                if(count($valper)>=1)
                {
                    $valper->delete();
                }
            }
            
        }
        return "destroy ".$request['id'];
    }

}
