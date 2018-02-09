<?php

namespace App\Http\Controllers\alcabala;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\alcabala\deducciones;
use Illuminate\Support\Facades\DB;
use App\Models\alcabala\tasas;
use App\Models\alcabala\Alcabala;
use App\Models\alcabala\Tipo_Contrato;
use App\Models\alcabala\Doc_Transf;
use App\Models\alcabala\Transferencias_Inafectas;
use App\Traits\DatesTranslator;
use Illuminate\Support\Facades\Auth;
class AlcabalaController extends Controller
{
    use DatesTranslator;
    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_alcabala' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        $contrato = DB::select('select * from alcabala.tipo_contrato order by id_tip_cto');
        $transferencia = DB::select('select * from alcabala.doc_transf order by id_doc_transf');
        $inafecto = DB::select('select * from alcabala.transferencias_inafectas order by id_trans_inaf');
        $UIT =DB::table('adm_tri.uit')->where('anio',date("Y"))->get()->first();
        $deduc =DB::table('alcabala.deducciones')->where('flg_act',1)->get()->first();
        $tasa =DB::table('alcabala.tasas')->where('flg_act',1    )->get()->first();
        return view('alcabala/vw_alcabala', compact('anio_tra','contrato','transferencia','UIT','deduc','tasa','inafecto','menu','permisos'));
    }

    public function create(Request $request)
    {
        $alcabala= new Alcabala;
        $alcabala->id_pred_anio=$request["pred"];
        $alcabala->id_adqui=$request["adqui"];
        $alcabala->adqui_rep_leg=$request["adqui_rl"];
        $alcabala->id_trans=$request["trans"];
        $alcabala->trans_rep_leg=$request["trans_rl"];
        $alcabala->fecha_reg=date("d/m/Y");
        $alcabala->hora_reg = date("H:i");
        $alcabala->id_doc_cont=$request["contra"];
        $alcabala->id_doc_tranf=$request["doctrans"];
        $alcabala->fec_doc_tranf=$request["fectrans"];
        $alcabala->nom_notaria= strtoupper($request["notaria"]);
        $alcabala->id_dec=DB::table('alcabala.deducciones')->where('flg_act',1)->get()->first()->id_dec;
        $alcabala->id_tas=DB::table('alcabala.tasas')->where('flg_act',1)->get()->first()->id_tas;
        $alcabala->base_impon_autoavaluo=$request["bimpo"];
        $alcabala->porcen_adqui=$request["poradq"];
        $alcabala->valor_transferencia=$request["vtrans"];
        $alcabala->id_tip_camb=$request["id_tip_camb"];
        $alcabala->tip_camb=$request["tip_camb"];
        $alcabala->base_impon_afecta=$request["bafecta"];
        $alcabala->id_trans_inafec=$request["inafec"];
        if($request["inafec"]==0)
        {
            $alcabala->impuesto_tot=$request["imp_tot"];
        }
        else
        {
            $alcabala->impuesto_tot=0;
        }
        $alcabala->anio=date("Y");
        $alcabala->pk_uit=DB::table('adm_tri.uit')->where('anio',date("Y"))->get()->first()->pk_uit;

        
        $alcabala->save();
        return $alcabala->id_alcab;
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
    public function mantenimiento()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_alcala_conf' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        return view('alcabala/vw_mantenimiento',compact('menu','permisos'));
    }
    public function manten_docs()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_alca_manten_doc' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        return view('alcabala/vw_manten_doc',compact('menu','permisos'));
    }
    
    public function get_alcabala($an,$id,$tip,$num,$ini,$fin,Request $request)
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
            if($id=="0")
            {
                if($tip==0)
                {
                    $totalg = DB::select('select count(id_alcab) as total from alcabala.vw_alcabala where anio='.$an);
                    $sql = DB::table('alcabala.vw_alcabala')->where("anio",$an)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                }
                if($tip==3)
                {
                    $totalg = DB::select("select count(id_alcab) as total from alcabala.vw_alcabala where nro_alcab='".$num."' AND anio=".$an);
                    $sql = DB::table('alcabala.vw_alcabala')->where("nro_alcab",$num)->where("anio",$an)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                }
                if($tip==4)
                {
                    $totalg = DB::select("select count(id_alcab) as total from alcabala.vw_alcabala where fecha_reg between '".$ini."' AND '".$fin."'");
                    $sql = DB::table('alcabala.vw_alcabala')->whereBetween("fecha_reg",[$ini,$fin])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                }
            }
            else
            {
                if($tip==1)
                {
                    $totalg = DB::select('select count(id_alcab) as total from alcabala.vw_alcabala where id_adqui='.$id);
                    $sql = DB::table('alcabala.vw_alcabala')->where("id_adqui",$id)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                }
                if($tip==2)
                {
                    $totalg = DB::select('select count(id_alcab) as total from alcabala.vw_alcabala where id_trans='.$id);
                    $sql = DB::table('alcabala.vw_alcabala')->where("id_trans",$id)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                }
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
                $dir=$Datos->sec."-".$Datos->mzna."-".$Datos->lote."-".$Datos->nom_via;
                if($Datos->nro_mun!=""){
                    $dir=$dir." Nro ".$Datos->nro_mun;
                }
                if($Datos->mzna_dist!=""){
                    $dir=$dir." Mzna ".$Datos->mzna_dist;
                }
                if($Datos->lote_dist!=""){
                    $dir=$dir." Lt ".$Datos->lote_dist;
                }
                if($Datos->estado==0)
                {
                    $Datos->estado="Sin Pago";
                }
                if($Datos->estado==1)
                {
                    $Datos->estado="Pagado";
                }
                $Lista->rows[$Index]['id'] = $Datos->id_alcab;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_alcab),
                    trim($Datos->nro_alcab),
                    trim($Datos->nom_adqui),
                    trim($Datos->nom_trans),
                    $dir,
                    trim($this->getCreatedAtAttribute($Datos->fecha_reg)->format('d/m/Y')),
                    trim($Datos->estado),
                    '<button class="btn btn-labeled bg-color-blueDark txt-color-white" type="button" onclick="veralcab('.trim($Datos->id_alcab).')"><span class="btn-label"><i class="fa fa-file-text-o"></i></span> Ver</button>',
                );
            }
            return response()->json($Lista);
    }
    /////////////////deducciones
    public function ded_create(Request $request)
    {
        $this->actualiza_deduccion();
        $dedu= new deducciones;
        $dedu->nro_uit=$request["uit"];
        $dedu->fec_ini=$request["ini"];
        $dedu->act_ley=$request["ley"];
        $dedu->flg_act=1;
        $dedu->save();
        return $dedu->id_dec;
    }
    public function actualiza_deduccion()
    {
        DB::select("Update alcabala.deducciones set flg_act=0, fec_fin='".date("d/m/Y")."' where flg_act=1");
    }
    public function get_deduc(Request $request)
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
            $totalg = DB::select("select count(id_dec) as total from alcabala.deducciones");
            $sql = DB::select("select * from alcabala.deducciones order by flg_act desc");
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
                $Lista->rows[$Index]['id'] = $Datos->id_dec;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_dec),
                    trim($Datos->nro_uit),
                    $this->getCreatedAtAttribute(trim($Datos->fec_ini))->format('d/m/Y'),
                    trim($Datos->fec_fin),
                    trim($Datos->act_ley),
                    trim($Datos->flg_act),
                );
            }
            return response()->json($Lista);
    }
    
    //////////////////////////////tasas
    public function tas_create(Request $request)
    {
        $this->actualiza_tasas();
        $tasa= new tasas;
        $tasa->por_tas=$request["por"];
        $tasa->fec_ini=$request["ini"];
        $tasa->act_ley=$request["ley"];
        $tasa->flg_act=1;
        $tasa->save();
        return $tasa->id_tas;
    }
    public function actualiza_tasas()
    {
        DB::select("Update alcabala.tasas set flg_act=0, fec_fin='".date("d/m/Y")."' where flg_act=1");
    }
    public function get_tasas(Request $request)
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
            $totalg = DB::select("select count(id_tas) as total from alcabala.tasas");
            $sql = DB::select("select * from alcabala.tasas order by flg_act desc");
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
                $Lista->rows[$Index]['id'] = $Datos->id_tas;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_tas),
                    trim($Datos->por_tas)." %",
                    $this->getCreatedAtAttribute(trim($Datos->fec_ini))->format('d/m/Y'),
                    trim($Datos->fec_fin),
                    trim($Datos->act_ley),
                    trim($Datos->flg_act),
                );
            }
            return response()->json($Lista);
    }
    ////////////////////////////// naturaleza del contrato
    public function contra_create(Request $request)
    {
        $contra= new Tipo_Contrato;
        $contra->descrip_cto=$request["des"];
        $contra->save();
        return $contra->id_tip_cto;
    }
    public function get_contra(Request $request)
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
            $totalg = DB::select("select count(id_tip_cto) as total from alcabala.tipo_contrato");
            $sql = DB::table('alcabala.tipo_contrato')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
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
                $Lista->rows[$Index]['id'] = $Datos->id_tip_cto;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_tip_cto),
                    trim($Datos->descrip_cto),
                );
            }
            return response()->json($Lista);
    }
    ////////////////////////////// documento de transferencia
    public function transfer_create(Request $request)
    {
        $trans= new Doc_Transf;
        $trans->descrip_doc_transf=$request["des"];
        $trans->save();
        return $trans->id_doc_transf;
    }
    public function get_transfe(Request $request)
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
            $totalg = DB::select("select count(id_doc_transf) as total from alcabala.doc_transf");
            $sql = DB::table('alcabala.doc_transf')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
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
                $Lista->rows[$Index]['id'] = $Datos->id_doc_transf;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_doc_transf),
                    trim($Datos->descrip_doc_transf),
                );
            }
            return response()->json($Lista);
    }
    
    ////////////////////////////// tranferencia inafecta
    public function inafecto_create(Request $request)
    {
        $trans= new Transferencias_Inafectas;
        $trans->descrip_trans_inaf=$request["des"];
        $trans->save();
        return $trans->id_trans_inaf;
    }
    public function get_inafecto(Request $request)
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
            $totalg = DB::select("select count(id_trans_inaf) as total from alcabala.transferencias_inafectas");
            $sql = DB::table('alcabala.transferencias_inafectas')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
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
                $Lista->rows[$Index]['id'] = $Datos->id_trans_inaf;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_trans_inaf),
                    trim($Datos->descrip_trans_inaf),
                );
            }
            return response()->json($Lista);
    }
    
    public function reporte($id) 
    {
       
        $sql    =DB::table('alcabala.vw_alcabala')->where('id_alcab',$id)->get()->first();
        if(count($sql)>=1)
        {
            $dir=$sql->sec."-".$sql->mzna."-".$sql->lote."-".$sql->nomb_hab_urba." ".$sql->nom_via;
            if($sql->nro_mun!=""){
                $dir=$dir." -N° ".$sql->nro_mun;
            }
            if($sql->mzna_dist!=""){
                $dir=$dir." -Mzna ".$sql->mzna_dist;
            }
            if($sql->lote_dist!=""){
                $dir=$dir." -Lt ".$sql->lote_dist;
            }
            $moneda="S/.";
            $afecto=$sql->base_impon_autoavaluo*$sql->porcen_adqui/100;
            if($afecto<($sql->valor_transferencia*$sql->tip_camb))
            {
                $afecto=$sql->valor_transferencia*$sql->tip_camb;
            }
            $sql->fec_doc_tranf=$this->getCreatedAtAttribute($sql->fec_doc_tranf)->format('d/m/Y');
            if($sql->id_tip_camb==2){$moneda="$";}
            $view =  \View::make('alcabala.reportes.alcab', compact('sql','dir','moneda','afecto'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("alcabala.pdf");
        }
        
    }
}
