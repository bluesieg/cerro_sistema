<?php

namespace App\Http\Controllers\fiscalizacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\fiscalizacion\multas;
use App\Models\fiscalizacion\multas_registradas;
use App\Traits\DatesTranslator;
use App\Models\fiscalizacion\multas_detalle;

class MultasController extends Controller
{
    use DatesTranslator;
    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_fis_multa' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        return view('fiscalizacion/vw_multas',compact('anio_tra','menu','permisos'));
  
    }

    public function create(Request $request)
    {
        if($request['tip']=="1"){
           return $this->create_multa($request); ;
        }
        if($request['tip']=="2"){
           return $this->create_multa_registrada($request);
        }
        if($request['tip']=="3"){
           return $this->create_multa_detalle($request);
        }
    }
    public function create_multa(Request $request)
    {
        $multa=new multas;
        $multa->des_multa=strtoupper($request['des']);
        $multa->cos_multa=$request['costo'];
        $multa->id_usuario = Auth::user()->id;
        $multa->save();
        return $multa->id_multa;
    }
    public function create_multa_registrada(Request $request)
    {
        $multa=new multas_registradas;
        $multa->id_contrib=strtoupper($request['contrib']);
        $multa->anio_reg=date("Y");
        $multa->id_usuario = Auth::user()->id;
        $multa->fec_reg = date("d/m/Y");
        $multa->glosa_multa = $request['glosa'];
        $multa->fundamentos = utf8_decode($request['fundamentos']);
        $multa->save();
        return $multa->id_multa_reg;
    }
    public function create_multa_detalle(Request $request)
    {
        $datos = explode("-", $request['id_an']);
        $costo=DB::select('select cos_multa from fiscalizacion.multas where id_multa='.trim($datos[0]));
        $multa=new multas_detalle;
        $multa->id_multa=trim($datos[0]);
        $multa->anio=trim($datos[1]);
        $multa->id_multa_reg = $request['mul_reg'];
        $multa->cos_multa = $costo[0]->cos_multa;
        $multa->save();
        return $multa->id_multa_det;
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
    public function get_multa($an,$contrib,$ini,$fin,$num,Request $request)
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
                    $totalg = DB::select("select count(id_multa_reg) as total from fiscalizacion.vw_multas_registradas where fec_reg between '".$ini."' and '".$fin."'");
                    $sql = DB::table('fiscalizacion.vw_multas_registradas')->wherebetween("fec_reg",[$ini,$fin])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                    
                }
                else
                {
                    if($num==0)
                    {
                        $totalg = DB::select('select count(id_multa_reg) as total from fiscalizacion.vw_multas_registradas where anio_reg='.$an);
                        $sql = DB::table('fiscalizacion.vw_multas_registradas')->where("anio_reg",$an)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                    }
                    else
                    {
                        $totalg = DB::select("select count(id_multa_reg) as total from fiscalizacion.vw_multas_registradas where nro_multa='".$num."' and anio_reg=".$an);
                        $sql = DB::table('fiscalizacion.vw_multas_registradas')->where("anio_reg",$an)->where("nro_multa",$num)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                    }
                }
            }
            else
            {
              $totalg = DB::select('select count(id_multa_reg) as total from fiscalizacion.vw_multas_registradas where anio_reg='.$an.' and id_contrib='.$contrib);
              $sql = DB::table('fiscalizacion.vw_multas_registradas')->where("anio_reg",$an)->where("id_contrib",$contrib)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
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
               
                if($Datos->fec_notificacion==null)
                {
                    $btnnotificacion='<button class="btn btn-labeled bg-color-red txt-color-white" type="button" onclick="ponerfechanoti('."'".trim($Datos->nro_multa)."'".');"><span class="btn-label"><i class="fa fa-edit"></i></span> Ing. Fecha Notificación</button>';
                    $btnhoj='Carta sin Notificar';
                    $diastrans=0;
                }
                else
                {
                    $btnnotificacion=$this->getCreatedAtAttribute($Datos->fec_notificacion)->format('d/m/Y');
                    $diastrans=$this->dias_transcurridos($Datos->fec_notificacion,date("Y-m-d"));
                }
                $Lista->rows[$Index]['id'] = $Datos->id_multa_reg;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_multa_reg),
                    trim($Datos->nro_multa),
                    trim($Datos->contribuyente),
                    trim($this->getCreatedAtAttribute($Datos->fec_reg)->format('d/m/Y')),
                    $btnnotificacion,
                    $diastrans,
                    '<button class="btn btn-labeled btn-warning" type="button" onclick="vermulta('.trim($Datos->id_multa_reg).')"><span class="btn-label"><i class="fa fa-file-text-o"></i></span> Ver</button>',
                );
            }
            return response()->json($Lista);
    }
    public function get_multa_criterio($texto)
    {
        if($texto=='0')
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
            if($texto=='1')
            {

                $totalg = DB::select("select count(id_multa) as total from fiscalizacion.multas");
                $sql = DB::table('fiscalizacion.multas')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
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
               
               
                $Lista->rows[$Index]['id'] = $Datos->id_multa;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_multa),
                    trim($Datos->des_multa),
                    trim($Datos->cos_multa)
                );
            }
            return response()->json($Lista);
        }
    }
    public function edit_multa_fec(Request $request)
    {
        $multa=new multas_registradas;
        $val=  $multa::where("id_multa_reg","=",$request['id'] )->first();
        if(count($val)>=1)
        {
            $val->fec_notificacion=$request['fec'];
            $val->save();
        }
        return $request['id'];
    }
    public function multa_repo($id)
    {
        $sql    =DB::table('fiscalizacion.vw_multas_registradas')->where('id_multa_reg',$id)->get()->first();
        if(count($sql)>=1)
        {
            $sql_detalle=DB::table('fiscalizacion.vw_multas_detalle')->where('id_multa_reg',$id)->get();
            $sql->fec_reg=$this->getCreatedAtAttribute($sql->fec_reg)->format('l d \d\e F \d\e\l Y ');
            $view =  \View::make('fiscalizacion.reportes.multa', compact('sql','fiscalizadores','predios','sql_detalle'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("alcabala.pdf");
        }
    }
    
}
