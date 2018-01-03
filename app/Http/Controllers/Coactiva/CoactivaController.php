<?php

namespace App\Http\Controllers\Coactiva;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Traits\DatesTranslator;
use App\Models\coactiva\coactiva_master;
use App\Models\coactiva\coactiva_documentos;
use Illuminate\Support\Facades\Auth;

class CoactivaController extends Controller
{
    use DatesTranslator;
    function letra(){        
        $monto= '1000.00';        
        $le = $this->num_letras($monto);
        echo $le;
    }
    public function gest_exped(){
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_gesion_exped' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        return view('coactiva.vw_ges_exped',compact('menu','permisos','anio_tra'));
    }   
    public function recep_doc() {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_recep_doc' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        return view('coactiva.vw_recep_doc',compact('menu','permisos'));
    }
    function emision_apertura_resolucion(){
        $plantilla=DB::table('coactiva.plantillas')->where('id_plant',1)->value('contenido');
        return view('coactiva.vw_emision_rec',compact('plantilla'));
    }
    function editar_resol(Request $request){
        $id_doc=$request['id_doc'];$id_coa_mtr=$request['id_coa_mtr'];
        $resolucion=DB::select('select * from coactiva.vw_documentos_edit where id_doc='.$id_doc);
        $fch_recep=DB::table('coactiva.vw_documentos_edit')->where('id_coa_mtr',$id_coa_mtr)->where('id_tip_doc',1)->value('fch_recep');
        
        $plantilla = $this->rec_res_eje_coa_plantilla($id_doc, $id_coa_mtr);
        return view('coactiva.editor_resolucion_aper',compact('plantilla','id_doc'));
    }
    
    function rec_res_eje_coa_plantilla($id_doc,$id_coa_mtr){
        $resolucion=DB::select('select * from coactiva.vw_documentos_edit where id_doc='.$id_doc);
        $fch_recep=DB::table('coactiva.vw_documentos_edit')->where('id_coa_mtr',$id_coa_mtr)->where('id_tip_doc',1)->value('fch_recep');
        
        $doc_ini=DB::table('coactiva.vw_coactiva_mtr')->where('id_coa_mtr',$id_coa_mtr)->value('doc_ini');
        if($doc_ini=='1'){
            $nro_rd_op=DB::table('fiscalizacion.vw_resolucion_determinacion')->where('id_coa_mtr',$id_coa_mtr)->get();
        }else if($doc_ini=='2'){
            $nro_rd_op=DB::table('recaudacion.vw_genera_fisca')->select('nro_fis as nro_rd','anio')->where('id_coa_mtr',$id_coa_mtr)->get();
        }
        
        $resol=new \stdClass();
        foreach ($resolucion as $Index => $Datos) {
            $resol->nro_exped=$Datos->nro_exped;
            $resol->id_doc=$Datos->id_doc;
            $resol->id_contrib=$Datos->id_contrib;
            $resol->contribuyente= str_replace('-','',$Datos->contribuyente);
            $resol->nro_resol=$Datos->nro_resol;
            $resol->fch_recep=mb_strtolower($this->getCreatedAtAttribute($fch_recep)->format('l, d \d\e F \d\e\l Y'));
            $resol->anio_resol=$Datos->anio_resol;
            $resol->monto=$Datos->monto;
            $resol->monto_letra=$this->num_letras(number_format($Datos->monto,2));
            $resol->periodos=$Datos->periodos;
            $resol->nro_rd=$nro_rd_op[0]->nro_rd ?? '...............';
            $resol->anio_rd=$nro_rd_op[0]->anio ?? '........';
            $resol->fch_emi=$Datos->fch_emi;
            $resol->dom_fis=$Datos->dom_fis;
            $resol->ubi_pred=$Datos->ubi_pred;
            $resol->doc_ini=$Datos->doc_ini;
            $resol->fch_emi_l=mb_strtolower($this->getCreatedAtAttribute($Datos->fch_emi)->format('l, d \d\e F \d\e\l Y'));            
        }
        $plantilla=DB::table('coactiva.vw_documentos_edit')->where('id_doc',$id_doc)->value('texto');
        $plantilla = str_replace('{@fch_recep@}',$resol->fch_recep,$plantilla);
        $plantilla = str_replace('{@fch_emi@}',$resol->fch_emi_l,$plantilla);
        $plantilla = str_replace('{@contribuyente@}',$resol->contribuyente,$plantilla);
        $plantilla = str_replace('{@nro_resol@}',$resol->nro_resol,$plantilla);
        $plantilla = str_replace('{@anio_resol@}',$resol->anio_resol,$plantilla);
        $plantilla = str_replace('{@nro_rd@}',$resol->nro_rd,$plantilla);
        $plantilla = str_replace('{@anio_rd@}',$resol->anio_rd,$plantilla);
        $plantilla = str_replace('{@periodos@}',$resol->periodos,$plantilla);
        $plantilla = str_replace('{@monto@}', number_format($resol->monto,2,'.',','),$plantilla);
        $plantilla = str_replace('{@monto_letra@}',$resol->monto_letra,$plantilla);
        $plantilla = str_replace('{@ubi_pred@}',$resol->ubi_pred,$plantilla);
        $plantilla = str_replace('{@doc_ini@}',$resol->doc_ini,$plantilla);
        $plantilla = str_replace('{@nro_exped@}',$resol->nro_exped,$plantilla);
        $plantilla = str_replace('{@dia@}',$this->getCreatedAtAttribute(date('d-m-Y'))->format('l, d \d\e F \d\e\l Y'),$plantilla);
        return $plantilla;
    }
    
    function editar_acta_aper(Request $request){
        $id_doc=$request['id_doc'];$id_coa_mtr=$request['id_coa_mtr'];
        $plantilla_acta= $this->rec_acta_aper_plantilla($id_doc, $id_coa_mtr);
        return view('coactiva.editor_acta_aper',compact('plantilla_acta','id_doc'));
    }
    function rec_acta_aper_plantilla($id_doc,$id_coa_mtr){
        $doc=DB::select('select * from coactiva.vw_documentos_edit where id_doc='.$id_doc);
        $nro_resol=DB::table('coactiva.vw_documentos_edit')->where('id_coa_mtr',$id_coa_mtr)->where('id_tip_doc',2)->value('nro_resol');
        
        $doc_ini=DB::table('coactiva.vw_coactiva_mtr')->where('id_coa_mtr',$id_coa_mtr)->value('doc_ini');
        if($doc_ini=='1'){
            $nro_rd_op=DB::table('fiscalizacion.vw_resolucion_determinacion')->where('id_coa_mtr',$id_coa_mtr)->get();
        }else if($doc_ini=='2'){
            $nro_rd_op=DB::table('recaudacion.vw_genera_fisca')->select('nro_fis as nro_rd','anio')->where('id_coa_mtr',$id_coa_mtr)->get();
        }        
        
        $cuotas=DB::table('coactiva.vw_documentos_edit')->where('id_doc',$id_doc)->value('fch_cuo_acta');
        $cuotas = explode('*',$cuotas);
        $resol=new \stdClass();
            foreach ($doc as $Index => $Datos){                
                $resol->id_contrib=$Datos->id_contrib;
                $resol->nro_resol=$nro_resol;
                $resol->nro_exped=$Datos->nro_exped;
                $resol->nro_doc=$Datos->nro_doc;
                $resol->anio_resol=$Datos->anio_resol;
                $resol->doc_ini=$Datos->doc_ini;
                $resol->nro_rd=$nro_rd_op[0]->nro_rd ?? '...............';
                $resol->anio_rd=$nro_rd_op[0]->anio ?? '........';                
                $resol->fch_cuo_acta=$Datos->fch_cuo_acta;
                $resol->contribuyente= str_replace('-','',$Datos->contribuyente);                
//                $resol->monto= $Datos->monto_acta;
//                $resol->monto_letra=$Datos->monto_acta;
                $resol->fch_larga=mb_strtolower($this->getCreatedAtAttribute(date('d-m-Y'))->format('l, d \d\e F \d\e\l Y'));
            }
        $plantilla=DB::table('coactiva.vw_documentos_edit')->where('id_doc',$id_doc)->value('texto');
        $plantilla = str_replace('{@fecha@}',$resol->fch_larga,$plantilla);
        $plantilla = str_replace('{@hora@}', date('H:i A'),$plantilla);
        $plantilla = str_replace('{@contribuyente@}',$resol->contribuyente,$plantilla);
        $plantilla = str_replace('{@dni@}',$resol->nro_doc,$plantilla);
        $plantilla = str_replace('{@doc_ini@}',$resol->doc_ini,$plantilla);
        $plantilla = str_replace('{@nro_rd@}',$resol->nro_rd,$plantilla);
        $plantilla = str_replace('{@anio_rd@}',$resol->anio_rd,$plantilla);
        $plantilla = str_replace('{@nro_resol@}', $resol->nro_resol,$plantilla);
        $plantilla = str_replace('{@anio_resol@}',$resol->anio_resol,$plantilla);
//        $plantilla = str_replace('{@monto@}',$resol->monto,$plantilla);
//        $plantilla = str_replace('{@monto_letra@}',$resol->monto_letra,$plantilla);
        $plantilla = str_replace('{@cuotas@}',count($cuotas),$plantilla);
        return $plantilla;
    }
    
    function get_expedientes(Request $request){
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        
        $totalg = DB::select("select count(id_coa_mtr) as total from coactiva.vw_coactiva_mtr where id_contrib=".$request['id_contrib']); 
        
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
        
        $sql = DB::table('coactiva.vw_coactiva_mtr')->where('id_contrib',$request['id_contrib'])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;

        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_coa_mtr;            
            $Lista->rows[$Index]['cell'] = array(                
                str_replace('0','',trim($Datos->nro_procedimiento)),
                trim($Datos->nro_exped.'-'.$Datos->anio),
                $Datos->monto,
                $Datos->estado,
                trim($Datos->desc_mat)
            );
        }
        return response()->json($Lista);
    }
    function get_docum_expediente(Request $request){
        $id_coa_mtr=$request['id_coa_mtr'];
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        
        $totalg = DB::select("select count(id_doc) as total from coactiva.vw_documentos where id_coa_mtr=".$id_coa_mtr); 
        
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
        
        $sql = DB::table('coactiva.vw_documentos')->where('id_coa_mtr',$id_coa_mtr)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        $cc=0;
        $ver = "";
        $edit= "";
        $fch_recep="";
        foreach ($sql as $Index => $Datos) {
            $cc++;            
            if($Datos->fch_recep){
                $fch_recep=date('d-m-Y', strtotime($Datos->fch_recep));
            }else{
                $fch_recep="";
            }
            if($Datos->id_tip_doc=='2'){
                $ver = "<button class='btn btn-labeled bg-color-red txt-color-white' type='button' onclick='ver_doc(".$Datos->id_doc.",".$Datos->id_coa_mtr.")'><span class='btn-label'><i class='fa fa-file-pdf-o'></i></span>Ver</button>";
                $edit= "<button class='btn btn-labeled bg-color-green txt-color-white' type='button' onclick='editar_doc(".$Datos->id_doc.",".$Datos->id_coa_mtr.")'><span class='btn-label'><i class='fa fa-pencil'></i></span>Editar</button>";
            }
            else if($Datos->id_tip_doc=='3'){
                $ver = "<button class='btn btn-labeled bg-color-red txt-color-white' type='button' onclick='ver_doc(".$Datos->id_doc.",".$Datos->id_coa_mtr.")'><span class='btn-label'><i class='fa fa-file-pdf-o'></i></span>Ver</button>";
                $edit= "<button class='btn btn-labeled bg-color-green txt-color-white' type='button' onclick='editar_doc(".$Datos->id_doc.",".$Datos->id_coa_mtr.")'><span class='btn-label'><i class='fa fa-pencil'></i></span>Editar</button>";
            }
            else if($Datos->id_tip_doc=='6'){
                $ver = "<button class='btn btn-labeled bg-color-red txt-color-white' type='button' onclick='ver_doc(".$Datos->id_doc.",".$Datos->id_coa_mtr.")'><span class='btn-label'><i class='fa fa-file-pdf-o'></i></span>Ver</button>";
                $edit= "<button class='btn btn-labeled bg-color-green txt-color-white' type='button' onclick='editar_notificacion(".$Datos->id_doc.",".$Datos->id_coa_mtr.",\"".trim($Datos->texto)."\")'><span class='btn-label'><i class='fa fa-pencil'></i></span>Editar</button>";
                if($Datos->fch_recep==null){
                    $fch_recep= "<button class='btn btn-labeled bg-color-orange txt-color-white' title='Agregar Fecha de Recepción' type='button' onclick='fecha_resep_notif(".$Datos->id_doc.")'><span class='btn-label'><i class='fa fa-calendar'></i></span>Fecha</button>";
                }else{$fch_recep=date('d-m-Y', strtotime($Datos->fch_recep));}                
            }
            else if($Datos->id_tip_doc=='7'){
                $ver = "<button class='btn btn-labeled bg-color-red txt-color-white' type='button' onclick='ver_doc(".$Datos->id_doc.",".$Datos->id_coa_mtr.")'><span class='btn-label'><i class='fa fa-file-pdf-o'></i></span>Ver</button>";
                $edit= "";
            }
            else if($Datos->id_tip_doc=='9'){
                $ver = "<button class='btn btn-labeled bg-color-red txt-color-white' type='button' onclick='ver_doc(".$Datos->id_doc.",".$Datos->id_coa_mtr.")'><span class='btn-label'><i class='fa fa-file-pdf-o'></i></span>Ver</button>";
                $edit= "<button class='btn btn-labeled bg-color-green txt-color-white' type='button' onclick='editar_acta(".$Datos->id_doc.",".$Datos->id_coa_mtr.")'><span class='btn-label'><i class='fa fa-pencil'></i></span>Editar</button>";
            }
            else if($Datos->id_tip_doc=='10'){
                $ver = "<button class='btn btn-labeled bg-color-red txt-color-white' type='button' onclick='ver_doc(".$Datos->id_doc.",".$Datos->id_coa_mtr.")'><span class='btn-label'><i class='fa fa-file-pdf-o'></i></span>Ver</button>";
                $edit= "<button class='btn btn-labeled bg-color-green txt-color-white' type='button' onclick='editar_doc(".$Datos->id_doc.",".$Datos->id_coa_mtr.")'><span class='btn-label'><i class='fa fa-pencil'></i></span>Editar</button>";
            }
            
            $Lista->rows[$Index]['id'] = $Datos->id_doc;            
            $Lista->rows[$Index]['cell'] = array(
                $cc,
                date('d-m-Y', strtotime($Datos->fch_emi)),
                trim($Datos->tip_gestion),
                trim($Datos->nro_resol),
                $fch_recep,
                $ver,
                $edit                
            );
        }
        return response()->json($Lista);
    }

    function fch_recep_notif(Request $request){
        $id_doc=$request['id_doc'];
        $fch_recep=$request['fch_recep'];
        DB::table('coactiva.coactiva_documentos')->where('id_doc',$id_doc)->update(['fch_recep'=>$fch_recep]);
    }

    public function destroy($id){}
    
    function get_doc(Request $request){
                
        $tip_doc=$request['tip_doc'];
        $tip_bus=$request['tip_bus'];
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        
        if($tip_doc=='2'){
            if($tip_bus=='1'){
                $desde= str_replace('/','-',$request['desde']);
                $hasta=str_replace('/','-',$request['hasta']);
                $totalg = DB::select("select count(id_per) as total from recaudacion.vw_genera_fisca where env_op=2 and verif_env=0 and fch_env between '".$desde."' and '".$hasta."' ");            
            }else if($tip_bus=='2'){
                $del=str_pad($request['del'], 7, "0", STR_PAD_LEFT);
                $al=str_pad($request['al'], 7, "0", STR_PAD_LEFT);
                $totalg = DB::select("selecta count(id_per) as total from recaudacion.vw_genera_fisca where env_op=2 and verif_env=0 and nro_fis between '".$del."' and '".$al."' ");            
            }
        }else if($tip_doc=='1'){
            if($tip_bus=='1'){
                $desde= str_replace('/','-',$request['desde']);
                $hasta=str_replace('/','-',$request['hasta']);
                $totalg = DB::select("select count(id_rd) as total from fiscalizacion.vw_resolucion_determinacion where env_rd=2 and verif_env=0 and fch_env between '".$desde."' and '".$hasta."' ");            
            }else if($tip_bus=='2'){
                $del=str_pad($request['del'], 7, "0", STR_PAD_LEFT);
                $al=str_pad($request['al'], 7, "0", STR_PAD_LEFT);
                $totalg = DB::select("select count(id_rd) as total from fiscalizacion.vw_resolucion_determinacion where env_rd=2 and verif_env=0 and nro_rd between '".$del."' and '".$al."' ");            
            }
        }else{
            $totalg = DB::select("select count(id_per) as total from recaudacion.vw_genera_fisca where env_op=0 and verif_env=0");            
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
        $start = ($limit * $page) - $limit; // do not put $limit*($page - 1)  
        if ($start < 0) {
            $start = 0;
        }
        
        if($tip_doc=='2'){
            if($tip_bus=='1'){
                $sql = DB::table('recaudacion.vw_genera_fisca')->where('env_op',2)->where('verif_env',0)->whereBetween('fch_env',[$desde,$hasta])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
            }else if($tip_bus=='2'){
                $sql = DB::table('recaudacion.vw_genera_fisca')->where('env_op',2)->where('verif_env',0)->whereBetween('nro_fis',[$del,$al])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
            }
        }else if($tip_doc=='1'){
            if($tip_bus=='1'){
                $sql = DB::table('fiscalizacion.vw_resolucion_determinacion')->where('env_rd',2)->where('verif_env',0)->whereBetween('fch_env',[$desde,$hasta])->orderBy('id_rd', $sord)->limit($limit)->offset($start)->get();
            }else if($tip_bus=='2'){
                $sql = DB::table('fiscalizacion.vw_resolucion_determinacion')->where('env_rd',2)->where('verif_env',0)->whereBetween('nro_rd',[$del,$al])->orderBy('id_rd', $sord)->limit($limit)->offset($start)->get();
            }
        }else{
            $sql = DB::table('recaudacion.vw_genera_fisca')->where('env_op',0)->where('verif_env',0)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        }
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        
        if($tip_doc=='2'){
            foreach ($sql as $Index => $Datos) {
                $Lista->rows[$Index]['id'] = $Datos->id_gen_fis;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_per),
                    trim($Datos->nro_fis),
                    date('d-m-Y', strtotime($Datos->fec_reg)),
                    trim($Datos->hora_env),
                    trim($Datos->anio),
                    trim($Datos->nro_doc),
                    str_replace('-','',trim($Datos->contribuyente)),
                    trim($Datos->estado),
                    trim($Datos->verif_env),
                    $Datos->monto,
                    "<input type='checkbox' name='chk_recib_doc' value='".$Datos->id_gen_fis."'>"
                );
            }
        }else if($tip_doc=='1'){
            foreach ($sql as $Index => $Datos) {
                $Lista->rows[$Index]['id'] = $Datos->id_rd;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_contrib),
                    trim($Datos->nro_rd),
                    date('d-m-Y', strtotime($Datos->fec_reg)),
                    trim($Datos->hora_env),
                    trim($Datos->anio),
                    trim($Datos->pers_nro_doc),
                    str_replace('-','',trim($Datos->contribuyente)),
                    trim($Datos->estado),
                    trim($Datos->verif_env),
                    $Datos->ivpp_verif,
                    "<input type='checkbox' name='chk_recib_doc' value='".$Datos->id_rd."'>"
                );
            }
        }
        
        
        return response()->json($Lista); 
    }
    
    function get_doc_recibidos(){
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        
        $totalg = DB::select("select count(id_per) as total from recaudacion.vw_genera_fisca where env_op=2 and verif_env=1 and estado=0"); 
        
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
        
        $sql = DB::table('recaudacion.vw_genera_fisca')->where('env_op',2)->where('verif_env',1)->where('estado',0)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;

        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_gen_fis;            
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_gen_fis),
                trim($Datos->id_per),
                trim($Datos->nro_fis),
                date('d-m-Y', strtotime($Datos->fec_reg)),
                trim($Datos->hora_env),
                trim($Datos->anio),
                trim($Datos->nro_doc),
                str_replace('-','',trim($Datos->contribuyente)),
                trim($Datos->estado),
                trim($Datos->verif_env),
                $Datos->monto                
            );
        }
        return response()->json($Lista); 
    }
    
    function resep_documentos(Request $request){
        $array = explode('-', $request['id_gen_fis']);
        $count=count($array);
        $i=0;
        for($i==0;$i<=$count-1;$i++){            
            DB::table('recaudacion.orden_pago_master')->where('id_gen_fis',$array[$i])
                        ->update(['verif_env'=>1,'fch_recep'=>date('d-m-Y'),'hora_recep'=>date('h:i A')]);
        }
        return response()->json(['msg'=>'si']);
    }
    
    function rec_cierre_plantilla($id_doc,$id_coa_mtr){
        $resolucion=DB::select('select * from coactiva.vw_documentos_edit where id_doc='.$id_doc);
        $fch_recep=DB::table('coactiva.vw_documentos_edit')->where('id_coa_mtr',$id_coa_mtr)->where('id_tip_doc',1)->value('fch_recep');
        
        $doc_ini=DB::table('coactiva.vw_coactiva_mtr')->where('id_coa_mtr',$id_coa_mtr)->value('doc_ini');
        if($doc_ini=='1'){
            $nro_rd_op=DB::table('fiscalizacion.vw_resolucion_determinacion')->where('id_coa_mtr',$id_coa_mtr)->get();
        }else if($doc_ini=='2'){
            $nro_rd_op=DB::table('recaudacion.vw_genera_fisca')->select('nro_fis as nro_rd','anio')->where('id_coa_mtr',$id_coa_mtr)->get();
        }
        $fch_ini_res_eje_coa = DB::table('coactiva.vw_documentos_edit')->where('id_coa_mtr',$id_coa_mtr)->where('id_tip_doc',2)->value('fch_emi');
        
        $resol=new \stdClass();
        foreach ($resolucion as $Index => $Datos) {
            $resol->nro_exped=$Datos->nro_exped;
            $resol->id_doc=$Datos->id_doc;
            $resol->id_contrib=$Datos->id_contrib;
            $resol->contribuyente= str_replace('-','',$Datos->contribuyente);
            $resol->nro_resol=$Datos->nro_resol;
            $resol->fch_recep=mb_strtolower($this->getCreatedAtAttribute($fch_recep)->format('l, d \d\e F \d\e\l Y'));
            $resol->anio_resol=$Datos->anio_resol;
            $resol->monto=$Datos->monto;
            $resol->periodos=$Datos->periodos;
            $resol->nro_rd=$nro_rd_op[0]->nro_rd ?? '...............';
            $resol->anio_rd=$nro_rd_op[0]->anio ?? '........';
            $resol->fch_ini_rec=date('d-m-Y', strtotime($fch_ini_res_eje_coa));
            $resol->fch_emi=$Datos->fch_emi;
            $resol->dom_fis=$Datos->dom_fis;
            $resol->ubi_pred=$Datos->ubi_pred;
            $resol->doc_ini=$Datos->doc_ini;
            $resol->monto=$Datos->monto;
            $resol->monto_letra=$this->num_letras($Datos->monto);
            $resol->fch_emi_l=mb_strtolower($this->getCreatedAtAttribute($Datos->fch_emi)->format('l, d \d\e F \d\e\l Y'));            
        }
        $plantilla=DB::table('coactiva.vw_documentos_edit')->where('id_doc',$id_doc)->value('texto');
        $plantilla = str_replace('{@fch_recep@}',$resol->fch_recep,$plantilla);
        $plantilla = str_replace('{@fch_emi@}',$resol->fch_emi_l,$plantilla);
        $plantilla = str_replace('{@contribuyente@}',$resol->contribuyente,$plantilla);
        $plantilla = str_replace('{@nro_resol@}',$resol->nro_resol,$plantilla);
        $plantilla = str_replace('{@anio_resol@}',$resol->anio_resol,$plantilla);
        $plantilla = str_replace('{@nro_rd@}',$resol->nro_rd,$plantilla);
        $plantilla = str_replace('{@anio_rd@}',$resol->anio_rd,$plantilla);
        $plantilla = str_replace('{@periodos@}',$resol->periodos,$plantilla);
        $plantilla = str_replace('{@monto@}', number_format($resol->monto,2,'.',','),$plantilla);
        $plantilla = str_replace('{@monto_letra@}',$resol->monto_letra,$plantilla);
        $plantilla = str_replace('{@ubi_pred@}',$resol->ubi_pred,$plantilla);
        $plantilla = str_replace('{@doc_ini@}',$resol->doc_ini,$plantilla);
        $plantilla = str_replace('{@nro_exped@}',$resol->nro_exped,$plantilla);
        $plantilla = str_replace('{@fch_ini_rec@}',$this->getCreatedAtAttribute($resol->fch_ini_rec)->format('l, d \d\e F \d\e\l Y'),$plantilla);
        $plantilla = str_replace('{@dia@}',$this->getCreatedAtAttribute(date('d-m-Y'))->format('l, d \d\e F \d\e\l Y'),$plantilla);
        return $plantilla;
    }
    function open_document($id_doc,$id_coa_mtr){
        $documento=DB::select('select * from coactiva.vw_documentos_edit where id_doc='.$id_doc);
        $fch_recep=DB::table('coactiva.vw_documentos_edit')->where('id_coa_mtr',$id_coa_mtr)->where('id_tip_doc',1)->value('fch_recep');
        if($documento[0]->id_tip_doc=='2'){            
            $resol=new \stdClass();
            foreach ($documento as $Index => $Datos) {
                $resol->nro_exped=$Datos->nro_exped;
                $resol->id_doc=$Datos->id_doc;
                $resol->id_contrib=$Datos->id_contrib;
                $resol->contribuyente= str_replace('-','',$Datos->contribuyente);
                $resol->nro_resol=$Datos->nro_resol;
                $resol->fch_recep=mb_strtolower($this->getCreatedAtAttribute($fch_recep)->format('l, d \d\e F \d\e\l Y'));
                $resol->anio_resol=$Datos->anio_resol;
                $resol->monto=$Datos->monto;
                $resol->periodos=$Datos->periodos;
                $resol->nro_rd=$Datos->nro_rd;
                $resol->fch_emi=$Datos->fch_emi;
                $resol->fch_emi_l=mb_strtolower($this->getCreatedAtAttribute($Datos->fch_emi)->format('l, d \d\e F \d\e\l Y'));
                $resol->dom_fis=$Datos->dom_fis;
                $resol->ubi_pred=$Datos->ubi_pred;
                $resol->doc_ini=$Datos->doc_ini;
                $resol->desc_mat=$Datos->desc_mat;
            }
            
            
            $plantilla = $this->rec_res_eje_coa_plantilla($id_doc, $id_coa_mtr);
//            dd($plantilla);
            $view = \View::make('coactiva.reportes.rec_apertura',compact('plantilla','resol'))->render();
            $pdf = \App::make('dompdf.wrapper');            
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream();
        }
        else if($documento[0]->id_tip_doc=='3' || $documento[0]->id_tip_doc=='10')//CIERRE RPROCEDIMIENTO COACTIVO
        {   
            $resol=new \stdClass();
            foreach ($documento as $Index => $Datos) {
                $resol->nro_exped=$Datos->nro_exped;
                $resol->id_doc=$Datos->id_doc;
                $resol->id_contrib=$Datos->id_contrib;
                $resol->contribuyente= str_replace('-','',$Datos->contribuyente);
                $resol->nro_resol=$Datos->nro_resol;
                $resol->fch_recep=mb_strtolower($this->getCreatedAtAttribute($fch_recep)->format('l, d \d\e F \d\e\l Y'));
                $resol->anio_resol=$Datos->anio_resol;
                $resol->nro_rd=$Datos->nro_rd;
                $resol->fch_emi=$Datos->fch_emi;
                $resol->fch_emi_l=mb_strtolower($this->getCreatedAtAttribute($Datos->fch_emi)->format('l, d \d\e F \d\e\l Y'));
                $resol->dom_fis=$Datos->dom_fis;
                $resol->ubi_pred=$Datos->ubi_pred;
                $resol->doc_ini=$Datos->doc_ini;
                $resol->desc_mat=$Datos->desc_mat;
            }
            $doc_ini=DB::table('coactiva.vw_coactiva_mtr')->where('id_coa_mtr',$id_coa_mtr)->value('doc_ini');
            if($doc_ini=='1'){
                $nro_rd_op=DB::table('fiscalizacion.vw_resolucion_determinacion')->where('id_coa_mtr',$id_coa_mtr)->get();
            }else if($doc_ini=='2'){
                $nro_rd_op=DB::table('recaudacion.vw_genera_fisca')->select('nro_fis as nro_rd','anio')->where('id_coa_mtr',$id_coa_mtr)->get();
            }
            $plantilla = $this->rec_cierre_plantilla($id_doc, $id_coa_mtr);
            $view = \View::make('coactiva.reportes.rec_cierre',compact('plantilla','resol'))->render();
            $pdf = \App::make('dompdf.wrapper');            
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream();
        }
        else if($documento[0]->id_tip_doc=='6'){
            $documento=DB::select('select * from coactiva.vw_documentos_edit where id_doc='.$id_doc);
            $view = \View::make('coactiva.reportes.c_notificacion',compact('documento'))->render();
            $pdf = \App::make('dompdf.wrapper');            
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream();
        }
        else if($documento[0]->id_tip_doc=='7'){//REQUERIMIENTO DE PAGO
            $doc=DB::select('select * from coactiva.vw_documentos_edit where id_doc='.$id_doc);
            $nro_resol=DB::table('coactiva.vw_documentos_edit')->where('id_coa_mtr',$id_coa_mtr)->where('id_tip_doc',2)->value('nro_resol');
            $doc_ini=DB::table('coactiva.vw_coactiva_mtr')->where('id_coa_mtr',$id_coa_mtr)->value('doc_ini');
            if($doc_ini=='1'){
                $nro_rd_op=DB::table('fiscalizacion.vw_resolucion_determinacion')->where('id_coa_mtr',$id_coa_mtr)->get();
            }else if($doc_ini=='2'){
                $nro_rd_op=DB::table('recaudacion.vw_genera_fisca')->select('nro_fis as nro_rd','anio')->where('id_coa_mtr',$id_coa_mtr)->get();
            } 
            $resol=new \stdClass();
            foreach ($doc as $Index => $Datos) {
                $resol->id_doc=$Datos->id_doc;
                $resol->id_contrib=$Datos->id_contrib;
                $resol->contribuyente= str_replace('-','',$Datos->contribuyente);
                $resol->nro_resol=$nro_resol;
                $resol->nro_exped=$Datos->nro_exped;
                $resol->nro_rd=$nro_rd_op[0]->nro_rd ?? '...............';
                $resol->anio_rd=$nro_rd_op[0]->anio ?? '........';
                $resol->anio_resol=$Datos->anio_resol;
                $resol->monto= number_format($Datos->monto,2,'.',',');
                $resol->monto_letra=$this->num_letras(number_format($Datos->monto,2));
                $resol->periodos=$Datos->periodos;
                $resol->dom_fis=$Datos->dom_fis;
                $resol->ubi_pred=$Datos->ubi_pred;
                $resol->doc_ini=$Datos->doc_ini;
                $resol->fch_larga=mb_strtolower($this->getCreatedAtAttribute(date('d-m-Y'))->format('l, d \d\e F \d\e\l Y'));
            }            
            $view = \View::make('coactiva.reportes.req_pago',compact('resol'))->render();
            $pdf = \App::make('dompdf.wrapper');            
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream();
        }
        else if($documento[0]->id_tip_doc=='9'){//ACTA DE APERSONAMIENTO
            $doc=DB::select('select * from coactiva.vw_documentos_edit where id_doc='.$id_doc);
            $nro_resol=DB::table('coactiva.vw_documentos_edit')->where('id_coa_mtr',$id_coa_mtr)->where('id_tip_doc',2)->value('nro_resol');
//            $nro_rd_op=DB::table('fiscalizacion.vw_resolucion_determinacion')->where('id_coa_mtr',$id_coa_mtr)->get();
            $cuotas=DB::table('coactiva.vw_documentos_edit')->where('id_doc',$id_doc)->value('fch_cuo_acta');
            $porcentaje=DB::table('coactiva.vw_documentos_edit')->where('id_doc',$id_doc)->value('monto_acta');
//            $cuo = array();
            $cuo = explode('*',$cuotas);
            $porc = explode('*',$porcentaje);
            $cuotas = array();
            for($i=0;$i<=count($cuo)-1;$i++){
                $Lista = array();
                $Lista['nro'] = $i+1;
                $Lista['fch'] = $cuo[$i];
                $Lista['fch_larga'] = mb_strtolower($this->getCreatedAtAttribute($cuo[$i])->format('d \d\e F \d\e\l Y')).' - '.$porc[$i].'%';
                array_push($cuotas, $Lista);
            }
            $plantilla_acta= $this->rec_acta_aper_plantilla($id_doc, $id_coa_mtr);
            $resol=new \stdClass();
            foreach ($doc as $Index => $Datos){
                $resol->nro_exped=$Datos->nro_exped;
                $resol->nro_doc=$Datos->nro_doc;
                $resol->anio_resol=$Datos->anio_resol;
                $resol->contribuyente= str_replace('-','',$Datos->contribuyente);
            }
            $view = \View::make('coactiva.reportes.vw_acta_aper',compact('resol','plantilla_acta','cuotas'))->render();
            $pdf = \App::make('dompdf.wrapper');            
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream();
        }
    }
    
    function update_documento(Request $request){
        $contenido = $request['contenido'];
        $id_doc = $request['id_doc'];
       
        $update = DB::table('coactiva.coactiva_documentos')->where('id_doc',$id_doc)
                        ->update(['texto'=>$contenido]);
        if($update){return response()->json(['msg'=>'si']);}
    }
    
    function notif_up_texto(Request $request){
        $texto = $request['texto'];
        $id_doc = $request['id_doc'];
        $update = DB::table('coactiva.coactiva_documentos')->where('id_doc',$id_doc)
                        ->update(['texto'=>$texto]);
        if($update){return response()->json(['msg'=>'si']);}
    }
    
    function grid_all_resolucionessssss(){
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        
        $totalg = DB::select("select count(id_resol) as total from coactiva.vw_resol_apertura"); 
        
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
        
        $sql = DB::table('coactiva.vw_resol_apertura')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;

        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_resol;            
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_resol),
                trim($Datos->fch_resol),                
                trim($Datos->anio_resol),
                trim($Datos->nro_resol),
                str_replace('-','',trim($Datos->contribuyente)),                
                "<button class='btn btn-labeled bg-color-green txt-color-white' type='button' onclick='ver_resol(".$Datos->id_resol.")'><span class='btn-label'><i class='fa fa-file-text-o'></i></span> Ver REC</button>",
                trim($Datos->texto),
                "<button class='btn btn-labeled bg-color-orange txt-color-white' type='button' onclick='editor_resolucion(".$Datos->id_resol.")'><span class='btn-label'><i class='fa fa-pencil'></i></span> Editar REC</button>",
                "<button class='btn btn-labeled bg-color-red txt-color-white' type='button' onclick='print_notif(".$Datos->id_resol.")'><span class='btn-label'><i class='fa fa-file-pdf-o'></i></span>Notificación</button>"                
            );
        }
        return response()->json($Lista); 
    }
    
    function imp_cons_notif($id_resol){
        $resolucion=DB::select('select * from coactiva.vw_resol_apertura where id_resol='.$id_resol);
        $view = \View::make('coactiva.reportes.c_notificacion',compact('resolucion'))->render();
        $pdf = \App::make('dompdf.wrapper');            
        $pdf->loadHTML($view)->setPaper('a4');
        return $pdf->stream();
    }
    
    function add_documento(Request $request){
        $adjuntar = $request['adjuntar'];
        
        $data = new coactiva_documentos();
        $data->id_coa_mtr = $request['id_coa_mtr'];
        $data->id_tip_doc = $request['id_tip_doc'];
        $data->fch_emi = date('Y-m-d');
        $data->anio = date('Y');
        $data->periodos = date('Y');
        $data->fch_cuo_acta = $request['fechas_cuotas'] ?? null;
        $data->monto_acta = $request['monto'] ?? null;        
        $insert = $data->save();

        if($insert){
            if($request['id_tip_doc']=='9'){//acta de apersonamiento
                DB::select("update coactiva.coactiva_documentos set texto=(select texto from coactiva.tip_doc where id_tip=9) where id_coa_mtr=".$request['id_coa_mtr']." and id_tip_doc=9");
                $fechas = explode('*', $request['fechas_cuotas']);
                $montos = explode('*', $request['monto']);
                $count=count($fechas);
                $i=0;
                for($i==0;$i<=$count-1;$i++){
                    $array_data = array();
                    $array_data['id_doc']=$data->id_doc;
                    $array_data['nro_cuo']=$i+1;
                    $array_data['fch_pago']=$fechas[$i];
                    $array_data['monto']=$montos[$i];
                    $array_data['estado']=0;
                    DB::table('coactiva.apersonamiento_cuotas')->insert($array_data);
                }
            }else if($request['id_tip_doc']=='10'){                
                DB::table('coactiva.coactiva_master')->where('id_coa_mtr',$data->id_coa_mtr)->update(['estado' => 0]);
                $adjuntar=1;
            }else if($request['id_tip_doc']=='3'){
                DB::table('coactiva.coactiva_master')->where('id_coa_mtr',$data->id_coa_mtr)->update(['estado' => 2]);
            }
            
            
            if($adjuntar==1){
                $data = new coactiva_documentos();//adjuntar constancia de notificacion
                $data->id_coa_mtr = $request['id_coa_mtr'];
                $data->id_tip_doc = 6;
                $data->fch_emi = date('Y-m-d');
                $data->anio = date('Y');
                $data->save();
            }            
            return response()->json(['msg'=>'si']);
        }
    }
    
    function get_all_expedientes(Request $request){
        $buscar=$request['contrib'];
        $materia= $request['materia'];
        if($materia==1){$materia='TRIBUTARIA';}else{$materia='NO TRIBUTARIA';}
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        
        $totalg = DB::select("select count(id_coa_mtr) as total from coactiva.vw_all_exped where desc_mat='".$materia."' AND contribuyente like '%".$buscar."%'"); 
        
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
        
        $sql = DB::table('coactiva.vw_all_exped')->where([['desc_mat',$materia],['contribuyente', 'like', '%'.$buscar.'%']])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;

        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_coa_mtr;            
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->nro_exped).'-'.$Datos->anio,
                $Datos->id_contrib,
                str_replace('-','',trim($Datos->contribuyente)),
                trim($Datos->desc_mat),
                trim($Datos->ult_gestion),
                trim($Datos->monto),
                $Datos->estado,
                $Datos->id_val
            );
        }
        return response()->json($Lista); 
    }
    
    function create_coa_master(Request $request){
        $id_contrib=$request['id_contrib'];
        $monto=$request['monto'];
        $data = new coactiva_master();
        $data->id_contrib = $id_contrib;
        $data->fch_ini = date('Y-m-d');        
        $data->estado = 1;
        $data->anio = date('Y');
        $data->doc_ini= $request['doc_ini'];
        $data->monto=$monto;
        $data->materia=0;
        $sql = $data->save();
        if($sql){
            $this->create_coa_documentos($data->id_coa_mtr);
            return $data->id_coa_mtr;
        }
    }
    function create_coa_documentos($id_coa_mtr){
        $data = new coactiva_documentos();
        $data->id_coa_mtr = $id_coa_mtr;
        $data->id_tip_doc = 1;        
        $data->fch_emi = date('Y-m-d');
        $data->fch_recep = date('Y-m-d');        
        $data->anio = date('Y');        
        $data->save();
        return $data->id_doc;
    }
    
    function devolver_valor(Request $request){
        $id_coa_mtr = $request['id_coa_mtr'];
        $id_contrib = $request['id_contrib'];
        $id_val = $request['id_val'];
        $anio_tra=$request['ano_tra'];
        
        if($id_val=='1' || $id_val=='2'){
            DB::table('adm_tri.cta_cte')->where([['id_pers',$id_contrib],['id_tribu',103],['id_coa_mtr',$id_coa_mtr],['ano_cta',$anio_tra]])->update([
                'trim1_estado'=>'1',
                'trim2_estado'=>'1',
                'trim3_estado'=>'1',
                'trim4_estado'=>'1',
                'id_coa_mtr'=>null
            ]);
        }
        DB::table('coactiva.coactiva_master')->where('id_coa_mtr',$id_coa_mtr)->update(['estado'=>'3']);
        
        DB::table('recaudacion.orden_pago_master')->where([['id_coa_mtr',$id_coa_mtr],['id_contrib',$id_contrib]])->update([
            'env_op'=>1,'verif_env'=>0,'fch_env'=>null,'hora_env'=>null,'fch_recep'=>null,'hora_recep'=>null,'id_coa_mtr'=>null
        ]);

        return response()->json(['msg'=>'si']);        
    }
    function eliminar_documento(Request $request){
        $id_doc = $request['id_doc'];
        $sql=DB::table('coactiva.coactiva_documentos')->where('id_doc',$id_doc)->delete();
        if($sql){
            return response()->json(['msg'=>'si']);
        }
    }
    function activar_exped(Request $request){
        $id_coa_mtr = $request['id_coa_mtr'];
        $id_contrib = $request['id_contrib'];
        $id_val = $request['id_val'];
        
        if($id_val=='1' || $id_val=='2'){
            DB::table('adm_tri.cta_cte')->where([['id_pers',$id_contrib],['id_tribu',103],['id_coa_mtr',$id_coa_mtr]])->update([
                'trim1_estado'=>'2',
                'trim2_estado'=>'2',
                'trim3_estado'=>'2',
                'trim4_estado'=>'2'
            ]);
        }
        DB::table('coactiva.coactiva_master')->where('id_coa_mtr',$id_coa_mtr)->update(['estado'=>'1']);

        return response()->json(['msg'=>'si']);        
    }
    
    function cta_cte(Request $request){
        $id_contrib = $request['id_contrib'];
//        $ano_cta = $request['ano_cta'];
//        $totalg = DB::select("select count(id_cta_cte) as total from adm_tri.vw_cta_cte2 where id_contrib='".$id_contrib."' and ano_cta='".$ano_cta."'");
        $totalg = DB::select("select count(id_cta_cte) as total from adm_tri.vw_cta_cte2 where id_contrib=".$id_contrib." and id_tribu=103");
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

        $sql = DB::table('adm_tri.vw_cta_cte2')->where('id_contrib',$id_contrib)->where('id_tribu',103)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        
        foreach ($sql as $Index => $Datos) {            
            $Lista->rows[$Index]['id'] = $Datos->id_cta_cte;
            $Lista->rows[$Index]['cell'] = array(                
                trim($Datos->descrip_tributo),                                
                trim($Datos->trim1_est),                
                trim($Datos->trim2_est),
                trim($Datos->trim3_est),
                trim($Datos->trim4_est)                                
            );
        }        
        return response()->json($Lista);
    }
    
    function habilitar_pago_cta_cte(Request $request){
        $id_coa_mtr = $request['id_coa_mtr'];
        $id_contrib = $request['id_contrib'];
        $trim_checks = $request['trim_checks'];
        
        $trim = explode('*', $trim_checks);
        
        $count=count($trim);
        $i=0;
        for($i==0;$i<=$count-1;$i++){            
            DB::table('adm_tri.cta_cte')->where('id_pers',$id_contrib)->where('id_coa_mtr',$id_coa_mtr)
                        ->update(['trim'.$trim[$i].'_estado'=>10]);
        }
        return response()->json(['msg'=>'si']);
    }
}
