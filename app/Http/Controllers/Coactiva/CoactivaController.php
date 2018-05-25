<?php

namespace App\Http\Controllers\Coactiva;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Traits\DatesTranslator;
use App\Models\coactiva\coactiva_master;
use App\Models\coactiva\coactiva_documentos;
use Illuminate\Support\Facades\Auth;
use App\Models\fiscalizacion\Resolucion_Determinacion;
use App\Models\orden_pago_master;

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
        $valores = DB::select('SELECT * from coactiva.valores ');
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        return view('coactiva.vw_recep_doc',compact('menu','permisos','valores'));
    }
    function emision_apertura_resolucion(){
        $plantilla=DB::table('coactiva.plantillas')->where('id_plant',1)->value('contenido');
        return view('coactiva.vw_emision_rec',compact('plantilla'));
    }
    function editar_resol(Request $request){
        $id_doc=$request['id_doc'];$id_coa_mtr=$request['id_coa_mtr'];
        $resolucion=DB::select('select * from coactiva.vw_documentos_edit where id_doc='.$id_doc);
        //$fch_recep=DB::table('coactiva.vw_documentos_edit')->where('id_coa_mtr',$id_coa_mtr)->where('id_tip_doc',1)->value('fch_recep');
        
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
        
        $totalg = DB::select("select count(id_coa_mtr) as total from coactiva.avance___ where estado=2 and anio=".$request['anio']); 
        
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
        
        $sql = DB::table('coactiva.avance___')->where('estado',2)->where('anio',$request['anio'])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;

        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_coa_mtr;            
            $Lista->rows[$Index]['cell'] = array(                
                str_replace('0','',trim($Datos->nro_procedimiento)),
                trim($Datos->nro_exped.'-'.$Datos->anio),
                trim($Datos->contribuyente),
                trim($Datos->desc_mat),
                $Datos->total_deuda,
                $Datos->pagado,
                $Datos->saldo,
                "<button class='btn btn-labeled bg-color-red txt-color-white' type='button' onclick='documentos(".$Datos->id_coa_mtr.")'><span class='btn-label'><i class='fa fa-file-pdf-o'></i></span>Ver</button>"

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
            else if($Datos->id_tip_doc=='1'){
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
         if($request['tip_doc']>3)
         {
             return 0;
         }
        $tip_doc=$request['tip_doc'];
        $tip_bus=$request['tip_bus'];
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $start = ($limit * $page) - $limit; // do not put $limit*($page - 1)  
        if ($start < 0) {
            $start = 0;
        }
        
        if($request['tip_doc']==1)
        {
            $totalg = DB::select("select count(id_rd) as total from fiscalizacion.vw_resolucion_determinacion where env_coactivo=1"); 
            $sql = DB::table('fiscalizacion.vw_resolucion_determinacion')->where('env_coactivo',1)->orderBy('id_rd', $sord)->limit($limit)->offset($start)->get();
        }
        if($request['tip_doc']==2)
        {
            $totalg = DB::select("select count(id_gen_fis) as total from recaudacion.vw_genera_fisca where env_coactivo=1"); 
            $sql = DB::table('recaudacion.vw_genera_fisca')->where('env_coactivo',1)->orderBy('id_gen_fis', $sord)->limit($limit)->offset($start)->get();
        }
        if($request['tip_doc']==3)
        {
            $totalg = DB::select("select count(id_multa_reg) as total from fiscalizacion.vw_multas_registradas where env_coactivo=1"); 
            $sql = DB::table('fiscalizacion.vw_multas_registradas')->where('env_coactivo',1)->orderBy('id_multa_reg', $sord)->limit($limit)->offset($start)->get();
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
                    $Datos->ivpp_verif,
                    "<input type='checkbox' name='chk_recib_doc' value='".$Datos->id_rd."'>"
                );
            }
        }else if($tip_doc=='3'){
            foreach ($sql as $Index => $Datos) {
                $Lista->rows[$Index]['id'] = $Datos->id_multa_reg;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_contrib),
                    trim($Datos->nro_multa),
                    date('d-m-Y', strtotime($Datos->fec_reg)),
                    trim($Datos->hora_env),
                    trim($Datos->anio_reg),
                    trim($Datos->pers_nro_doc),
                    str_replace('-','',trim($Datos->contribuyente)),
                    '',
                    $Datos->total_multa,
                    "<input type='checkbox' name='chk_recib_doc' value='".$Datos->id_multa_reg."'>"
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
        $documento=DB::select('select * from coactiva.coactiva_documentos where id_doc='.$id_doc);
        if($documento[0]->id_tip_doc=='9')
        {
            return $this->verdocumentoapersonamiento($id_doc,$id_coa_mtr);
        }
        else if($documento[0]->id_tip_doc=='1'){
                return $this->verdocumentoresini($id_doc,$id_coa_mtr);//inicio
        }
        else
        {
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
        
        
    }
    }
    function verdocumentoapersonamiento($id_doc,$id_coa_mtr)
    {
            $doc=DB::select('select * from coactiva.vw_apersonamiento_reporte where id_doc='.$id_doc);
            //$nro_resol=$doc[0]->nro_resol;
            $i=1;
            foreach($doc as $datos)
            {
                $datos->nro= $i++;
                $datos->fch_larga=$this->getCreatedAtAttribute($datos->fch_pago)->format('l d \d\e F \d\e\l Y ');
                $datos->contribuyente= str_replace('-','',$datos->contribuyente);
            }
            
            $plantilla_acta= $doc[0]->texto;
            
            $plantilla_acta = str_replace('{@fecha@}',$this->getCreatedAtAttribute(date("d-m-Y"))->format('l d \d\e F \d\e\l Y '),$plantilla_acta);
            $plantilla_acta = str_replace('{@hora@}', date('H:i A'),$plantilla_acta);
            $plantilla_acta = str_replace('{@contribuyente@}',$doc[0]->contribuyente,$plantilla_acta);
            $plantilla_acta = str_replace('{@dni@}',$doc[0]->pers_nro_doc,$plantilla_acta);
            $plantilla_acta = str_replace('{@doc_ini@}',$doc[0]->desc_val,$plantilla_acta);
            if($doc[0]->doc_ini=='1'){
                $documento_inicial=DB::table('fiscalizacion.resolucion_determinacion')->where('id_rd',$doc[0]->id_doc_ini)->get();
                $plantilla_acta = str_replace('{@nro_rd@}',$documento_inicial[0]->nro_rd,$plantilla_acta);
                $plantilla_acta = str_replace('{@anio_rd@}',$documento_inicial[0]->anio,$plantilla_acta);
            }else if($doc[0]->doc_ini=='2'){
                $documento_inicial=DB::table('recaudacion.orden_pago_master')->where('id_gen_fis',$doc[0]->id_doc_ini)->get();
                $plantilla_acta = str_replace('{@nro_rd@}',$documento_inicial[0]->nro_fis,$plantilla_acta);
                $plantilla_acta = str_replace('{@anio_rd@}',$documento_inicial[0]->anio,$plantilla_acta);
            }
            else if($doc[0]->doc_ini=='3'){
                $documento_inicial=DB::table('fiscalizacion.multas_registradas')->where('id_multa_reg',$doc[0]->id_doc_ini)->get();
                $plantilla_acta = str_replace('{@nro_rd@}',$documento_inicial[0]->nro_multa,$plantilla_acta);
                $plantilla_acta = str_replace('{@anio_rd@}',$documento_inicial[0]->anio_reg,$plantilla_acta);
            }
            
            $plantilla_acta = str_replace('{@nro_resol@}', $doc[0]->nro_resol,$plantilla_acta);
            $plantilla_acta = str_replace('{@anio_resol@}',$doc[0]->anio_resol,$plantilla_acta);
            $plantilla_acta = str_replace('{@cuotas@}',count($doc),$plantilla_acta);
          
            $view = \View::make('coactiva.reportes.vw_acta_aper',compact('doc','plantilla_acta'))->render();
            $pdf = \App::make('dompdf.wrapper');            
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream('Apersonamiento.pdf');
            
    }
    function verdocumentoresini($id_doc,$id_coa_mtr)
    {
        $resol=DB::select('select * from coactiva.coactiva_documentos where id_doc='.$id_doc);
        $view = \View::make('coactiva.reportes.rec_apertura',compact('resol'))->render();
        $pdf = \App::make('dompdf.wrapper');            
        $pdf->loadHTML($view)->setPaper('a4');
        return $pdf->stream('Resolucion_inicio.pdf');
            
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
                $porcentaje = explode('*', $request['porcentaje']);
                $count=count($fechas);
                $i=0;
                for($i==0;$i<=$count-1;$i++){
                    $array_data = array();
                    $array_data['id_doc']=$data->id_doc;
                    $array_data['nro_cuo']=$i+1;
                    $array_data['fch_pago']=$fechas[$i];
                    $array_data['monto']=str_replace(',','',$montos[$i]);
                    $array_data['porcentaje']=$porcentaje[$i];
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
        $anio= $request['anio'];
        if($materia==1){$materia='TRIBUTARIA';}else{$materia='NO TRIBUTARIA';}
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        
        $totalg = DB::select("select count(id_coa_mtr) as total from coactiva.vw_all_exped where desc_mat='".$materia."' AND contribuyente like '%".$buscar."%' and id_doc_ini>0 and estado=1 and anio=".$anio); 
        
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
        
        $sql = DB::table('coactiva.vw_all_exped')->where([['desc_mat',$materia],['contribuyente', 'like', '%'.$buscar.'%']])->where('id_doc_ini','>',0)->where('anio',$anio)->where('estado',1)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                
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
                trim($Datos->nombre),
                trim($Datos->doc_ini),
                trim($Datos->monto),
                "<button class='btn btn-labeled bg-color-red txt-color-white' type='button' onclick='devolver_valor(".$Datos->id_coa_mtr.")'><span class='btn-label'><i class='fa fa-file-pdf-o'></i></span>Devolver</button>",
                "<button class='btn btn-labeled bg-color-green txt-color-white' type='button' onclick='aceptar_valor(".$Datos->id_coa_mtr.")'><span class='btn-label'><i class='fa fa-check'></i></span>Aceptar / Iniciar</button>"
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
    function create_coa_documentos($id_coa_mtr,$contenido){
        $data = new coactiva_documentos();
        $data->id_coa_mtr = $id_coa_mtr;
        $data->id_tip_doc = 1;        
        $data->fch_emi = date('Y-m-d');
        $data->fch_recep = date('Y-m-d');        
        $data->anio = date('Y');        
        $data->texto = utf8_decode($contenido);        
        $data->save();
        return $data->texto;
    }
    
    function devolver_valor(Request $request){
        $id_coa_mtr = $request['id_coa_mtr'];
        
        $coactiva=new coactiva_master;
        $val=  $coactiva::where("id_coa_mtr","=",$id_coa_mtr )->first();
        if(count($val)>=1)
        {
            
            if($val->doc_ini==1)
            {
                $this->devolver_rd($val->id_doc_ini);
            }
            if($val->doc_ini==2)
            {
                $this->devolver_op($val->id_doc_ini);
            }
            $val->estado = 0;
            $val->save();
        }
        return response()->json(['msg'=>'si']);        
    }
    public function devolver_rd($id_rd)
    {
        $rd=new Resolucion_Determinacion;
        $rd_vw=DB::select("select * from fiscalizacion.vw_resolucion_determinacion where id_rd =".$id_rd);
        $val=  $rd::where("id_rd","=",$id_rd)->first();
        if(count($val)>=1)
        {
            $val->env_coactivo=0;
            $val->save();
            $recpred = DB::select('select * from presupuesto.vw_impuesto_predial where anio='.$val->anio); 
            DB::table('adm_tri.cta_cte')->where('id_pers','=',$rd_vw[0]->id_contrib)->where('id_tribu','=',$recpred[0]->id_tributo)->where('ano_cta',$val->anio)
                    ->update(['id_coa_mtr'=>null,'trim1_estado'=>1,'trim2_estado'=>1,'trim3_estado'=>1,'trim4_estado'=>1]);
        }

    }
    public function devolver_op($id_op)
    {
        $op=new orden_pago_master;
        $op_vw=DB::select("select * from recaudacion.vw_genera_fisca where id_gen_fis =".$id_op);
        $val=  $op::where("id_gen_fis","=",$id_op)->first();
        if(count($val)>=1)
        {
            $val->env_coactivo=0;
            $val->save();
            $recpred = DB::select('select * from presupuesto.vw_impuesto_predial where anio='.$val->anio); 
            DB::table('adm_tri.cta_cte')->where('id_pers','=',$op_vw[0]->id_per)->where('id_tribu','=',$recpred[0]->id_tributo)->where('ano_cta',$val->anio)
                    ->update(['id_coa_mtr'=>null,'trim1_estado'=>1,'trim2_estado'=>1,'trim3_estado'=>1,'trim4_estado'=>1]);
        }
    }
    
    public function aceptar_valor(Request $request) {
        $id_coa_mtr = $request['id_coa_mtr'];
        $costas = $request['costas'];
        $contenido = $request['contenido'];
        
        $coactiva=new coactiva_master;
        $val=  $coactiva::where("id_coa_mtr","=",$id_coa_mtr )->first();
        if(count($val)>=1)
        {
            $val->estado = 2;
            $val->costas = $costas;
            $val->save();
            return $this->create_coa_documentos($id_coa_mtr,$contenido);
        }
        
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
    
    
    function grid_apersonamiento(Request $request){
        $buscar=$request['id_contrib'];
        $anio= $request['anio'];
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        
        $totalg = DB::select("select count(id_aper) as total from coactiva.vw_apersonamiento_reporte where id_contrib='".$buscar."'  and anio=".$anio); 
        
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
        
        $sql = DB::table('coactiva.vw_apersonamiento_reporte')->where('id_contrib',$buscar)->where('anio',$anio)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;

        foreach ($sql as $Index => $Datos) {
            $check="";
            if($Datos->estado==0)
            {
                $check="<input type='checkbox' name='chk_coactivo' value='".$Datos->monto."' id_aper='".$Datos->id_aper."' id_tributo='".$Datos->id_tributo."' onchange='calc_tot_coactivo(this.value,this,".$Datos->id_aper.")'>";
            }
            else
            {
               $check="Pagado";
            }
            $Lista->rows[$Index]['id'] = $Datos->id_aper;            
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->nro_cuo),
                trim($Datos->nro_resol).'-'.$Datos->anio,
                str_replace('-','',trim($Datos->contribuyente)),
                $this->getCreatedAtAttribute($Datos->fch_pago)->format('l d \d\e F \d\e\l Y '),
                number_format($Datos->monto,2,".",","),
                $check
            );
        }
        return response()->json($Lista); 
    }
}
