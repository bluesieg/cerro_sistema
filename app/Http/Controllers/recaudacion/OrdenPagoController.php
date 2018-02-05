<?php

namespace App\Http\Controllers\recaudacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\orden_pago_master;
use App\Traits\DatesTranslator;
use Illuminate\Support\Facades\Auth;

class OrdenPagoController extends Controller
{
    use DatesTranslator;
    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_reca_op' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        $sectores = DB::select('select * from catastro.sectores where id_sec>0 order by sector');
        $manzanas = DB::select('select * from catastro.manzanas where id_mzna>0 and  id_sect=(select id_sec from catastro.sectores where id_sec>0 order by sector limit 1) ');
        return view('recaudacion/vw_orden_pago',compact('anio_tra','sectores','manzanas','menu','permisos'));
    }
    public function create(Request $request)
    {
        if($request['tip']=='1'||$request['tip']=='3')
        {
            return $this->create_by_user($request['per'],$request['an']);
        }
        if($request['tip']=='4')
        {
            $sql = DB::select("select id_contrib from adm_tri.vw_predi_urba where sec='".$request['sec']."' and mzna='".$request['man']."' and anio=".$request['an']." group by id_contrib order by id_contrib");
            $orden=0;
            foreach ($sql as $contri)
            {
                $valor=$this->create_by_user($contri->id_contrib,$request['an']);
                if($orden==0)
                {
                    $orden=$valor;
                }
            }
            return $orden."-".$valor;
        }
    }
    public function create_by_user($per,$anio)
    {
        $ivpp= DB::table('adm_tri.base_ivpp_op')->where('ano_cta',$anio)->where('id_pers',$per)->get()->first();
        $fisca= new orden_pago_master;
        $fisca->anio=$anio;
        $fisca->anio_reg=date("Y");
        $fisca->fec_reg=date("d/m/Y");
        $fisca->id_contrib=$per;
        $fisca->ivpp_afecto=$ivpp->ivpp_afecto;
        $fisca->ivpp=$ivpp->ivpp;
        $fisca->save();
        return $fisca->id_gen_fis;
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
    public function getOP($dat,$sec,$manz,$an,$ini,$fin,Request $request)
    {
        if($dat==0&&$sec==0&&$manz==0&&$ini==0&&$fin==0)
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
            if($sec==0)
            {
                if($dat==0)
                {
                    $totalg = DB::select("select count(id_gen_fis) as total from recaudacion.vw_genera_fisca where fec_reg between '".$ini."' and '".$fin."'");
                    $sql = DB::table('recaudacion.vw_genera_fisca')->wherebetween('fec_reg',[$ini,$fin])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                }
                else
                {
                $totalg = DB::select('select count(id_gen_fis) as total from recaudacion.vw_genera_fisca where id_per='.$dat.' and anio='.$an);
                    $sql = DB::table('recaudacion.vw_genera_fisca')->where('id_per',$dat)->where("anio",$an)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
                }
            }
            else
            {
                $totalg = DB::select("select count(id_gen_fis) as total from recaudacion.vw_genera_fisca where id_per in (select id_contrib from adm_tri.vw_predi_urba where sec='".$sec."' and mzna='".$manz."' order by 1 asc)");
                $sql = DB::select("select * from recaudacion.vw_genera_fisca where id_per in (select id_contrib from adm_tri.vw_predi_urba where sec='".$sec."' and mzna='".$manz."' order by 1 asc) order by $sidx $sord limit $limit offset $start");
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
                if($Datos->fec_notifica==null)
                {
                    $Datos->fec_notifica='<a href="javascript:void(0);" class="btn btn-danger txt-color-white btn-circle"><i class="glyphicon glyphicon-remove"></i></a>';
                    $envio="";
                }
                else
                {
                    $Datos->fec_notifica=trim($this->getCreatedAtAttribute($Datos->fec_notifica)->format('d/m/Y'));
                    $envio=$Datos->fec_notifica;
                }
                $Lista->rows[$Index]['id'] = $Datos->id_gen_fis;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_gen_fis),
                    trim($Datos->nro_fis),
                    trim($this->getCreatedAtAttribute($Datos->fec_reg)->format('d/m/Y')),
                    trim($Datos->anio),
                    trim($Datos->nro_doc),
                    trim($Datos->contribuyente),
                    '<button class="btn btn-labeled bg-color-blueDark txt-color-white" type="button" onclick="verop('.trim($Datos->id_gen_fis).')"><span class="btn-label"><i class="fa fa-file-text-o"></i></span> Ver OP</button>',
                    trim($Datos->fec_notifica),
                    '<button class="btn btn-labeled bg-color-blueDark txt-color-white" type="button" onclick="ing_fec_noti('.trim($Datos->id_gen_fis).','."'".trim($Datos->nro_fis)."' , '".$envio."'".')"><span class="btn-label"><i class="fa fa-file-text-o"></i></span> Ing Fecha Notificación</button>',

                );
            }
            return response()->json($Lista);
        }
    }
    public function getcontrbsec(Request $request)
    {
        if($request['sec']=='0'&&$request['man']=='0')
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
            
            $totalg = DB::select("select count(id_contrib) as total from adm_tri.vw_predi_urba where sec='".$request['sec']."' and mzna='".$request['man']."' group by id_contrib order by id_contrib");
            $sql = DB::select("select id_contrib,nro_doc, contribuyente from adm_tri.vw_predi_urba where sec='".$request['sec']."' and mzna='".$request['man']."' group by id_contrib,nro_doc, contribuyente order by id_contrib");
            
            

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
                $Lista->rows[$Index]['id'] = $Datos->id_contrib;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_contrib),
                    trim($Datos->nro_doc),
                    trim($Datos->contribuyente),
                    '<button type="button" class="btn btn-labeled bg-color-greenDark txt-color-white" onclick="generar_op(3,'.$Datos->id_contrib.')">
                    <span class="btn-label"><i class="fa fa-file-text-o"></i></span>Generar Nueva OP
                    </button>',
                );
            }
            return response()->json($Lista);
        }
    }
    public function get_trimestre_actual($datetime)
    {
        $mes = date("m",strtotime($datetime));
        $mes = is_null($mes) ? date('m') : $mes;
        $trim=floor(($mes-1) / 3)+1;
        return $trim;
    }

    public function reporte($tip,$id,$sec,$man) 
    {
        if($tip=='1'||$tip=='3')
        {
            $sql    =DB::table('recaudacion.vw_op_detalle')->where('id_gen_fis',$id)->get()->first();
            if(count($sql)>=1)
            {
                if($sql->anio<date("Y"))
                {
                    $sql->trimestre=4;
                }
                else
                {
                    $sql->trimestre=$this->get_trimestre_actual($sql->fec_reg);
                    if($sql->trimestre>1)
                    {
                        $sql->trimestre=$sql->trimestre-1;
                    }
                }
                $sql->fec_reg=$this->getCreatedAtAttribute($sql->fec_reg)->format('l d, F Y ');
                $UIT =DB::table('adm_tri.uit')->where('anio',$sql->anio)->get()->first();
                
            }
            $view =  \View::make('recaudacion.reportes.op', compact('sql','UIT'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("OP.pdf");
        }
        if($tip=='4')
        {
            $sql = DB::select("select * from recaudacion.vw_op_detalle where id_gen_fis between ". str_replace("-", " and ", $id));

            if(count($sql)>=1)
            {
                for($i=0;$i<count($sql);$i++)
                {
                    $sql[$i]->index=$i;
                    if($sql[$i]->anio<date("Y"))
                    {
                        $sql[$i]->trimestre=4;
                    }
                    else
                    {
                        $sql[$i]->trimestre=$this->get_trimestre_actual($sql[$i]->fec_reg);
                        if($sql[$i]->trimestre>1)
                        {
                            $sql[$i]->trimestre=$sql[$i]->trimestre-1;
                        }
                    }
                    $sql[$i]->fec_reg=$this->getCreatedAtAttribute($sql[$i]->fec_reg)->format('l d, F Y ');
                }
                $UIT =DB::table('adm_tri.uit')->where('anio',$sql[0]->anio)->get()->first();
            }
            $view =  \View::make('recaudacion.reportes.op_masivo', compact('sql','UIT'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("OP.pdf");
        }
        if($tip=='5')
        {
            $sql = DB::select("select * from recaudacion.vw_op_detalle where fec_reg between '".$sec."' and '".$man."'");

            if(count($sql)>=1)
            {
                for($i=0;$i<count($sql);$i++)
                {
                    $sql[$i]->index=$i;
                     if($sql[$i]->anio<date("Y"))
                    {
                        $sql[$i]->trimestre=4;
                    }
                    else
                    {
                        $sql[$i]->trimestre=$this->get_trimestre_actual($sql[$i]->fec_reg);
                        if($sql[$i]->trimestre>1)
                        {
                            $sql[$i]->trimestre=$sql[$i]->trimestre-1;
                        }
                    }
                    $sql[$i]->fec_reg=$this->getCreatedAtAttribute($sql[$i]->fec_reg)->format('l d, F Y ');
                }
                $UIT =DB::table('adm_tri.uit')->where('anio',$sql[0]->anio)->get()->first();
            }
            $view =  \View::make('recaudacion.reportes.op_masivo', compact('sql','UIT'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("OP.pdf");
        }
    }
    ////////////////////////Fecha de Notificacion OP
     public function notifica_op_index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_noti_op' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        $sectores = DB::select('select * from catastro.sectores where id_sec>0 order by sector');
        $manzanas = DB::select('select * from catastro.manzanas where id_mzna>0 and  id_sect=(select id_sec from catastro.sectores where id_sec>0 order by sector limit 1) ');
        return view('recaudacion/vw_notifica_op',compact('anio_tra','sectores','manzanas','menu','permisos'));
    }
    public function edit_op_fec(Request $request)
    {
        $op=new orden_pago_master;
        $val=  $op::where("id_gen_fis","=",$request['id'] )->first();
        if(count($val)>=1)
        {
            $val->fec_notifica=$request['fec'];
            $val->save();
        }
        return $request['id'];
    }
    //////////////////////////////Reportes OP///////////////////////////////////
    public function index_reportes_op()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_rep_op' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        return view('recaudacion/vw_reportes_op', compact('menu','permisos','anio_tra'));
    }
    public function ver_reporte_op($an,$tip) 
    {
        if($tip=='1')
        {
            $sql    =DB::table('recaudacion.vw_genera_fisca')->where('anio_reg',$an)->where('fec_notifica',"<>",null)->get();
            if(count($sql)>=1)
            {
                //$sql->fec_notifica=$this->getCreatedAtAttribute($sql->fec_notifica)->format('l d, F Y ');
                $view =  \View::make('recaudacion.reportes.rep_op_notificada', compact('sql'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4');
                return $pdf->stream("OP.pdf");
            }
            else
            {
                return "No Hay Datos";
            }
        }
        if($tip=='2')
        {
            $sql    =DB::table('recaudacion.vw_genera_fisca')->where('anio_reg',$an)->where('fec_notifica',"<>",null)->get();
            if(count($sql)>=1)
            {
                //$sql->fec_notifica=$this->getCreatedAtAttribute($sql->fec_notifica)->format('l d, F Y ');
                $view =  \View::make('recaudacion.reportes.rep_op_notificada', compact('sql'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4');
                return $pdf->stream("OP.pdf");
            }
            else
            {
                return "No Hay Datos";
            }
        }
        if($tip=='3')
        {
            $sql    =DB::table('recaudacion.vw_genera_fisca')->where('anio',$an)->where('fec_notifica',null)->get();
            if(count($sql)>=1)
            {
                $view =  \View::make('recaudacion.reportes.rep_op_sin_notificar', compact('sql'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4');
                return $pdf->stream("OP.pdf");
            }
            else
            {
                return "No Hay Datos";
            }
        }
        if($tip=='4')
        {
            $sql    =DB::table('recaudacion.vw_genera_fisca')->where('anio',$an)->get();
            if(count($sql)>=1)
            {
                $view =  \View::make('recaudacion.reportes.rep_op_emitidas', compact('sql'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4');
                return $pdf->stream("OP.pdf");
            }
            else
            {
                return "No Hay Datos";
            }
        }
        
    }
}
