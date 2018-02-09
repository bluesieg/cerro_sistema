<?php

namespace App\Http\Controllers\fiscalizacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\fiscalizacion\Hoja_Liquidacion;
use Illuminate\Support\Facades\Auth;
use App\Traits\DatesTranslator;


class Hoja_liquidacionController extends Controller
{
    use DatesTranslator;
    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_hoja_liq' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        return view('fiscalizacion/vw_hoja_liquidacion',compact('anio_tra','menu','permisos'));
    }

    public function create(Request $request)
    {
        $anulado=DB::table('fiscalizacion.vw_carta_requerimiento')->where('id_car',$request['car'])->get();
        if($anulado[0]->flg_anu>0)
        {
            return -1;
        }
        $totpre= DB::select('select count(id_puente) as total from fiscalizacion.puente_carta_predios where id_car='.$request['car']);
        $totfisca= DB::select('select count(id_fic) as total from fiscalizacion.vw_ficha_verificacion where id_car='.$request['car']);
        if($totpre[0]->total>$totfisca[0]->total)
        {
            return 0;
        }
        else
        {
            $hoja=new Hoja_Liquidacion;
            $hoja->id_car=$request['car'];
            $hoja->dias_fisca=$request['insitu'];
            $hoja->dia_plazo=$request['plazo'];
            $hoja->fec_reg=date("d/m/Y");
            $hoja->anio=date("Y");
            $hoja->id_usuario = Auth::user()->id;
            $hoja->save();
            DB::select("select fiscalizacion.calc_ivpp_fisc(".$hoja->id_car.")");
            return $hoja->id_hoja_liq;
        }
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
    public function get_hojas_liq($an,$contrib,$ini,$fin,$num,Request $request)
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
                    $totalg = DB::select("select count(id_hoja_liq) as total from fiscalizacion.vw_hoja_liquidacion  where fec_reg between '".$ini."' and '".$fin."'");
                    $sql = DB::table('fiscalizacion.vw_hoja_liquidacion')->wherebetween("fec_reg",[$ini,$fin])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                    
                }
                else
                {
                    if($num==0)
                    {
                        $totalg = DB::select('select count(id_hoja_liq) as total from fiscalizacion.vw_hoja_liquidacion where anio='.$an);
                        $sql = DB::table('fiscalizacion.vw_hoja_liquidacion')->where("anio",$an)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                    }
                    else
                    {
                        $totalg = DB::select("select count(id_hoja_liq) as total from fiscalizacion.vw_hoja_liquidacion where nro_hoja='".$num."' and anio=".$an);
                        $sql = DB::table('fiscalizacion.vw_hoja_liquidacion')->where("anio",$an)->where("nro_hoja",$num)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                    }
                }
            }
            else
            {
              $totalg = DB::select('select count(id_hoja_liq) as total from fiscalizacion.vw_hoja_liquidacion where anio='.$an.' and id_contrib='.$contrib);
              $sql = DB::table('fiscalizacion.vw_hoja_liquidacion')->where("anio",$an)->where("id_contrib",$contrib)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
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
                    $estado="Sin R.D";
                    $btnrd='<button class="btn btn-labeled bg-color-red txt-color-white" type="button" onclick="valida_rd('.trim($Datos->id_hoja_liq).')"><span class="btn-label"><i class="fa fa-edit"></i></span> Generar R.D</button>';
                }
                if($Datos->flg_est==1)
                {
                    $estado=$Datos->nro_rd;
                    $btnrd='<button class="btn btn-labeled bg-color-redDark txt-color-white" type="button"><span class="btn-label"><i class="fa fa-file-text-o"></i></span> R.D. creada</button>';
                }
                if($Datos->fecha_notificacion==null)
                {
                    $btnnotificacion='<button class="btn btn-labeled bg-color-red txt-color-white" type="button" onclick="ponerfechanoti('."'".trim($Datos->nro_hoja)."'".');"><span class="btn-label"><i class="fa fa-edit"></i></span> Ing. Fecha Notificación</button>';
                    $btnrd='Hoja sin Notificar';
                    $diastrans=0;
                }
                else
                {
                    $btnnotificacion=$this->getCreatedAtAttribute($Datos->fecha_notificacion)->format('d/m/Y');
                    $diastrans=$this->dias_transcurridos($Datos->fecha_notificacion,date("Y-m-d"));
                }
                $Lista->rows[$Index]['id'] = $Datos->id_hoja_liq;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_hoja_liq),
                    trim($Datos->nro_hoja),
                    trim($Datos->contribuyente),
                    trim($Datos->nro_car),
                    trim($this->getCreatedAtAttribute($Datos->fec_reg)->format('d/m/Y')),
                    $btnnotificacion,
                    $Datos->dia_plazo,
                    $diastrans,
                    '<button class="btn btn-labeled btn-warning" type="button" onclick="verhoja('.trim($Datos->id_hoja_liq).')"><span class="btn-label"><i class="fa fa-file-text-o"></i></span> Ver</button>',
                    $estado,
                    $btnrd
                );
            }
            return response()->json($Lista);
    }
    public function hoja_repo($id)
    {
        $sql    =DB::table('fiscalizacion.vw_hoja_liquidacion')->where('id_hoja_liq',$id)->get()->first();
        if(count($sql)>=1)
        {
            $fichas    =DB::table('fiscalizacion.vw_ficha_verificacion')->where('id_car',$sql->id_car)->get();
            $sql->fec_reg=$this->getCreatedAtAttribute($sql->fec_reg)->format('l d \d\e F \d\e\l Y ');
            $sql->fec_carta=$this->getCreatedAtAttribute($sql->fec_carta)->format('l d \d\e F \d\e\l Y ');
            $view =  \View::make('fiscalizacion.reportes.hoja_liq', compact('sql','fichas'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("hoja_liquidacion.pdf");
        }
    }
    public function edit_hoja_fec(Request $request)
    {
        $hoja=new Hoja_Liquidacion;
        $val=  $hoja::where("id_hoja_liq","=",$request['id'] )->first();
        if(count($val)>=1)
        {
            $val->fecha_notificacion=$request['fec'];
            $val->save();
        }
        return $request['id'];
    }
}
