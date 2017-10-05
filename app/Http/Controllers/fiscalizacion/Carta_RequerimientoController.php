<?php

namespace App\Http\Controllers\fiscalizacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\fiscalizacion\Carta_Requerimiento;
use App\Traits\DatesTranslator;
use App\Models\fiscalizacion\Puente_Carta_Predios;
use App\Models\fiscalizacion\Fisca_Enviados;
class Carta_RequerimientoController extends Controller
{
    use DatesTranslator;
    public function index()
    {
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        $fiscalizadores = DB::select('select * from fiscalizacion.vw_fiscalizadores where flg_act=1');
        return view('fiscalizacion/vw_carta_requerimiento',compact('anio_tra','fiscalizadores'));
    }

    public function create(Request $request)
    {
        if($request['otrotext']=="-"){
                 $otrotext="" ;
        }
        else{
            $otrotext=$request['otrotext'];
        }
        $carta=new Carta_Requerimiento;
        $carta->id_contrib=$request['contri'];
        $carta->soli_contra=$request['con'];
        $carta->soli_licen=$request['lic'];
        $carta->soli_dercl=$request['der'];
        $carta->soli_otro=$request['otro'];
        $carta->otro_text=$otrotext;
        $carta->fec_reg=date("d/m/Y");
        $carta->fec_fis=$request['fec'];
        $carta->hora_fis=$request['hor'];
        $carta->anio=date("Y");
        $carta->anio_fis=$request['anfis'];
        $carta->save();
        $this->puente_carta_predios_create($carta->id_car,$carta->id_contrib,$carta->anio_fis);
        return $carta->id_car;
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
        $carta=DB::table('fiscalizacion.vw_carta_requerimiento')->where('id_car',$id)->get();
        $carta[0]->fec_fis=$this->getCreatedAtAttribute($carta[0]->fec_fis)->format('l d \d\e F \d\e\l Y ');
        return $carta;
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
    public function get_cartas_req($an,$contrib,$ini,$fin,$num,Request $request)
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
                    $totalg = DB::select("select count(id_car) as total from fiscalizacion.vw_carta_requerimiento where fec_reg between '".$ini."' and '".$fin."'");
                    $sql = DB::table('fiscalizacion.vw_carta_requerimiento')->wherebetween("fec_reg",[$ini,$fin])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                    
                }
                else
                {
                    if($num==0)
                    {
                        $totalg = DB::select('select count(id_car) as total from fiscalizacion.vw_carta_requerimiento where anio='.$an);
                        $sql = DB::table('fiscalizacion.vw_carta_requerimiento')->where("anio",$an)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                    }
                    else
                    {
                        $totalg = DB::select("select count(id_car) as total from fiscalizacion.vw_carta_requerimiento where nro_car='".$num."' and anio=".$an);
                        $sql = DB::table('fiscalizacion.vw_carta_requerimiento')->where("anio",$an)->where("nro_car",$num)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                    }
                }
            }
            else
            {
              $totalg = DB::select('select count(id_car) as total from fiscalizacion.vw_carta_requerimiento where anio='.$an.' and id_contrib='.$contrib);
              $sql = DB::table('fiscalizacion.vw_carta_requerimiento')->where("anio",$an)->where("id_contrib",$contrib)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
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
                if($Datos->flg_est==0)
                {
                    $estado="Sin Hoja de Liq.";
                    $btnfis='<button class="btn btn-labeled bg-color-greenDark txt-color-white" type="button" onclick="datos_carta('.trim($Datos->id_car).')"><span class="btn-label"><i class="fa fa-edit"></i></span> Ing. Ficha</button>';
                    $btnhoj='<button class="btn btn-labeled bg-color-red txt-color-white" type="button" onclick="new_hoja('.trim($Datos->id_car).')"><span class="btn-label"><i class="fa fa-edit"></i></span> Crear Hoja Liq.</button>';
                }
                if($Datos->flg_est==1)
                {
                    $estado="Fizcalizado";
                    $btnfis='<button class="btn btn-labeled bg-color-redDark txt-color-white" type="button"><span class="btn-label"><i class="fa fa-file-text-o"></i></span> Fiscalizar</button>';
                    $btnhoj='<button class="btn btn-labeled bg-color-redDark txt-color-white" type="button"><span class="btn-label"><i class="fa fa-file-text-o"></i></span> Hoja de liq. creada</button>';
                }
                $Lista->rows[$Index]['id'] = $Datos->id_car;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_car),
                    trim($Datos->nro_car),
                    trim($Datos->contribuyente),
                    trim($this->getCreatedAtAttribute($Datos->fec_reg)->format('d/m/Y')),
                    trim($this->getCreatedAtAttribute($Datos->fec_fis)->format('d/m/Y'))." ".$Datos->hora_fis,
                    $estado,
                    '<button class="btn btn-labeled btn-warning" type="button" onclick="vercarta('.trim($Datos->id_car).')"><span class="btn-label"><i class="fa fa-file-text-o"></i></span> Ver</button>',
                    $btnfis,
                    $btnhoj
                );
            }
            return response()->json($Lista);
    }
    
    public function carta_repo($id)
    {
        $sql    =DB::table('fiscalizacion.vw_carta_requerimiento')->where('id_car',$id)->get()->first();
        if(count($sql)>=1)
        {
            $fiscalizadores=DB::table('fiscalizacion.vw_fisca_enviados')->where('id_car',$id)->get();
            $predios=DB::table('fiscalizacion.vw_puente_carta_predios')->where('id_car',$id)->get();
            $sql->fec_fis=$this->getCreatedAtAttribute($sql->fec_fis)->format('l d \d\e F \d\e\l Y ');
            $sql->fec_reg=$this->getCreatedAtAttribute($sql->fec_reg)->format('l d \d\e F \d\e\l Y ');
            $view =  \View::make('fiscalizacion.reportes.carta_req', compact('sql','fiscalizadores','predios'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("alcabala.pdf");
        }
    }
    
     public function get_predios_carta($car,Request $request)
    {
        if($car==0)
        {
            return 0;
        }
        else
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
            
            $totalg = DB::select("select count(id_car) as total from fiscalizacion.vw_puente_carta_predios where id_car=".$car);
            $sql = DB::table('fiscalizacion.vw_puente_carta_predios')->where("id_car",$car)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
            
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
                $dir=$Datos->nom_via;
                if($Datos->nro_mun!=""){
                    $dir=$dir." N° ".$Datos->nro_mun;
                }
                if($Datos->mzna_dist!=""){
                    $dir=$dir." Mzna ".$Datos->mzna_dist;
                }
                if($Datos->lote_dist!=""){
                    $dir=$dir." Lt ".$Datos->lote_dist;
                }
                if($Datos->zona!=""){
                    $dir=$dir." Zn ".$Datos->zona;
                }
                $fiscalizado='<a href="javascript:void(0);" class="btn btn-danger txt-color-white btn-circle"><i class="glyphicon glyphicon-remove"></i></a>';
                if($Datos->id_fic)
                {
                    $fiscalizado='<a href="javascript:void(0);" class="btn bg-color-green txt-color-white btn-circle"><i class="glyphicon glyphicon-ok"></i></a>';
                }
                $Lista->rows[$Index]['id'] = $Datos->id_pred_anio;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_pred_anio),
                    trim($Datos->id_puente),
                    trim($Datos->id_fic),
                    trim($Datos->tp),
                    trim($Datos->cod_cat),
                    $dir." ".$Datos->habilitacion,
                    trim($Datos->nro_fic),
                    '<button class="btn btn-labeled bg-color-blueDark txt-color-white" type="button" onclick="clickmodgrid('.trim($Datos->id_pred_anio).')"><span class="btn-label"><i class="fa fa-file-text-o"></i></span> Fiscalizar</button>',
                    $fiscalizado
                );
            }
            return response()->json($Lista);
        }
    }
     public function puente_carta_predios_create($carta,$contrib,$aniofis)
    {
        $prediosactuales=DB::table('adm_tri.vw_predi_urba')->select('id_pred')->where('id_contrib',$contrib)->where('anio',date("Y"))->where('pred_anio_activo',1)->where('pred_contrib_activo',1)->get();
        $info = array();
        foreach($prediosactuales as $actual)
        {
            $info[]=$actual->id_pred;
        }
        
        $predios=DB::table('adm_tri.vw_predi_urba')->where('id_contrib',$contrib)->where('anio',$aniofis)->wherein('id_pred',$info)->get();
        foreach($predios as $pre)
        {
            $puente=new Puente_Carta_Predios;
            $puente->id_pred_anio=$pre->id_pred_anio;
            $puente->id_car=$carta;
            $puente->save();
        }
    }

    public function fisca_enviados_create(Request $request)
    {
        $fisca=new Fisca_Enviados;
        $fisca->id_car=$request['car'];
        $fisca->id_user_fis=$request['fis'];
        $fisca->save();
        return $fisca->id_fis_env;
    }
}
