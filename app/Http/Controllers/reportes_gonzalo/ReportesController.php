<?php

namespace App\Http\Controllers\reportes_gonzalo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class ReportesController extends Controller
{

    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_rep_gonza' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        //$condicion = DB::table('adm_tri.exoneracion')->get();
                    $institucion = DB::select('SELECT * FROM maysa.institucion');

        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        $sectores =  DB::table('catastro.sectores')->orderBy('sector', 'asc')->where('id_sec', '>', 0)->get();
        $hab_urb =  DB::table('catastro.hab_urb')->orderBy('nomb_hab_urba', 'asc')->get();
        $condicion = DB::select('select id_exo,desc_exon from adm_tri.exoneracion order by id_exo asc');
        $adulto_pensionista = DB::select("select id_exo,desc_exon from adm_tri.exoneracion where desc_exon  ilike '%PENSIONISTA%' OR desc_exon  ilike '%ADULTO MAYOR%'");
        $exonerados = DB::select("select id_exo,desc_exon from adm_tri.exoneracion where desc_exon  ilike '%Exone%'");
        $inafecto = DB::select("select id_exo,desc_exon from adm_tri.exoneracion where desc_exon  ilike '%Inafec%'");
        $estado_frac = DB::select('select id_estado,desc_estado from fraccionamiento.convenio_estado order by desc_estado asc');
        $usos_predio_arb = DB::table('adm_tri.uso_predio_arbitrios')->orderBy('id_uso_arb', 'asc')->get();
        $agencias = DB::select('select id_caj,descrip_caja from tesoreria.cajas order by descrip_caja desc');
        return view('reportes_gonzalo/vw_reportes', compact('menu','permisos','anio_tra','sectores','hab_urb','condicion','usos_predio_arb','agencias','estado_frac','adulto_pensionista','exonerados','inafecto','institucion'));
    }
    public function index_supervisores()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_rep_sup' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        //$condicion = DB::table('adm_tri.exoneracion')->get();
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        $hab_urb =  DB::table('catastro.hab_urb')->orderBy('nomb_hab_urba', 'asc')->get();
        $sectores =  DB::table('catastro.sectores')->orderBy('sector', 'asc')->where('id_sec', '>', 0)->get();
        $condicion = DB::select('select id_exo,desc_exon from adm_tri.exoneracion');
        return view('reportes_gonzalo/vw_reportes_supervisor', compact('menu','permisos','anio_tra','sectores','condicion'));
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    
   
    public function listado_contribuyentes_predios_det($anio,$hab_urb1)
    {          


        if($anio != 0 && $hab_urb1 != 0){
            $predios = DB::table('adm_tri.vw_predi_urba')->select('id_persona','nro_doc','contribuyente','dom_fis as domicilio','descripcion','tp','desc_uso','are_com_terr','are_terr','anio','base_impon_afecto as autovaluo','nro_doc_conv','conviviente')->where('anio',$anio)->where('id_hab_urb',$hab_urb1)->orderby('contribuyente')->get();
            if(count($predios)>0){
                set_time_limit(0);
                ini_set('memory_limit', '1G');
                \Excel::create('REPORTE DE CONTRIBUYENTES', function($excel) use ( $anio, $hab_urb1,$predios ) {
                $predios = DB::table('adm_tri.vw_predi_urba')->select('id_persona','nro_doc','contribuyente','dom_fis as domicilio','descripcion','tp','desc_uso','are_com_terr','are_terr','anio','base_impon_afecto as autovaluo','id_pred_anio','nro_doc_conv','conviviente')->where('anio',$anio)->where('id_hab_urb',$hab_urb1)->orderby('contribuyente')->get();
                 $num= 1;
                 $row = array(
                            array('N°','CÓDIGO','DNI/RUC','CONTRIBUYENTE','DIRECCIÓN','DNI','CONYUGUE','ESTADO','TIPO','USO','ÁREA COMÚN','AREA TERRENO','AUTOVALUO','N° PISO','CLASIFICACIÓN','MATERIAL','EST. CONSERVACIÓN','CATEGORIAS','ÁREA CONSTYRUCCIÓN','ÁREA COMÚN')
                        );

                        foreach($predios as $predio){
                            $pisos = DB::table('reportes.vw_pisos')->where('id_pred_anio',$predio->id_pred_anio)->get();
                            $row[] = array(
                                $num++,
                                $predio->id_persona,
                                $predio->nro_doc,
                                $predio->contribuyente,
                                $predio->domicilio,
                                $predio->nro_doc_conv,
                                $predio->conviviente,
                                $predio->descripcion,
                                $predio->tp,
                                $predio->desc_uso,
                                $predio->are_com_terr,
                                $predio->are_terr,
                                $predio->autovaluo);
                                foreach ($pisos as $piso)
                                {
                                    $row[] = array(
                                        " "," "," "," "," "," "," "," "," "," "," "," "," ",
                                        $piso->cod_piso,
                                        $piso->clas,
                                        $piso->mep,
                                        $piso->esc,
                                        $piso->categorias,
                                        $piso->area_const,
                                        $piso->val_areas_com
                                    );
                                }
                        }
                $excel->sheet('CONTRIBUYENTES', function($sheet) use($row) {
                    $sheet->fromArray($row, null, 'A1', false, false);
                    $sheet->setWidth(array(
                                    'A'     =>  3,
                                    'B'     =>  15,
                                    'C'     =>  15,
                                    'D'     =>  40,
                                    'E'     =>  60,
                                    'F'     =>  10,
                                    'G'     =>  40,
                                    'H'     =>  20,
                                    'I'     =>  7,
                                    'J'     =>  20,
                                    'K'     =>  5,
                                    'L'     =>  10,
                                    'M'     =>  10,
                                    'N'     =>  5,
                                    'O'     =>  5,
                                    'P'     =>  5,
                                    'Q'     =>  5,
                                    'R'     =>  10,
                                    'S'     =>  7,
                                    'T'     =>  7
                                ));
                });
            })->export('xls'); 
            
            }
            else
            {
                return 'No hay datos';
            }
        
        }

    }
    
    
    //reportes gerenciales
     public function ver_reportes_gerenciales($tip,Request $request)
    {
        if($tip=='1')
        {
            return $this->rep_por_partida($request);
        }
         if($tip=='2')
        {
            return $this->rep_por_tributo($request);
        }
         if($tip=='3')
        {
            return $this->rep_por_tributo($request);
        }
       
    }
    
    public function reportes_contribuyentes($anio,$min,$max,$num_reg)
    {
        $institucion = DB::select('SELECT * FROM maysa.institucion');
        if($max == 0){
            
        $sql=DB::table('reportes.vw_pricos')->where('ano_cta',$anio)->where('ivpp','>',$min)->limit($num_reg)->orderBy('ivpp', 'desc')->get();
            
        }else{
            
        $sql=DB::table('reportes.vw_pricos')->where('ano_cta',$anio)->where('ivpp','>',$min)->where('ivpp','<',$max)->limit($num_reg)->orderBy('ivpp', 'desc')->get();
        
        }
        $usuario = DB::select('SELECT * from public.usuarios where id='.Auth::user()->id);
        $fecha = (date('d/m/Y H:i:s'));
        if(count($sql)>0)
        { 
            set_time_limit(0);
            ini_set('memory_limit', '2G');
            $view =  \View::make('reportes_gonzalo.reportes.reporte_contribuyentes', compact('sql','anio','min','max','usuario','fecha','institucion'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("Listado de Contribuyentes".".pdf");
        }
        else
        {
            return 'No hay datos';
        }
    }
    
    public function reportes($anio,$sector,$manzana)
    {
         $institucion = DB::select('SELECT * FROM maysa.institucion');
        $sql=DB::table('adm_tri.vw_predi_usu')->where('anio',$anio)->where('id_sec',$sector)->where('id_mzna',$manzana)->orderBy('lote')->get();

        if(count($sql)>0)
        {
            set_time_limit(0);
            ini_set('memory_limit', '2G');
            $view =  \View::make('reportes_gonzalo.reportes.predios_prueba', compact('sql','anio','sector','manzana','institucion'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("Predios por Usuario".".pdf");
        }
        else
        {   return 'No hay datos';}
    }
    
    public function listado_contribuyentes($tip,$anio,$hab_urb){
        if($tip==1){
                if($anio != 0 && $hab_urb == 0){
                 set_time_limit(0);
                 ini_set('memory_limit', '1G');
             \Excel::create('REPORTE DE CONTRIBUYENTES', function($excel) use ( $anio ) {

                 $excel->sheet('CONTRIBUYENTES', function($sheet) use ( $anio ) {

                     $sql = DB::select("select nro_doc, contribuyente, persona, dom_fis from adm_tri.vw_contrib_predios_c where ano_cta = '$anio' order by contribuyente" );

                     $data= json_decode( json_encode($sql), true);

                     $sheet->fromArray($data);
                     $sheet->row(1, array("DNI/RUC", "CONTRIBUYENTE", "TIPO PERSONA", "DOMICILIO"))->freezeFirstRow();
                     $sheet->setWidth(array(
                         'A'     =>  15,
                         'B'     =>  50,
                         'C'     =>  30,
                         'D'     =>  70
                     ));
                 });
             })->export('xls'); 
            } 
        }
        if($tip==0){
            $sql=DB::table('adm_tri.vw_contrib_predios_c')->where('ano_cta',$anio)->where('id_hab_urb',$hab_urb)->orderBy('contribuyente')->get();
            $usuario = DB::select('SELECT * from public.usuarios where id='.Auth::user()->id);
        $fecha = (date('d/m/Y H:i:s'));
        $institucion = DB::select('SELECT * FROM maysa.institucion');
        if(count($sql)>0)
        {
            set_time_limit(0);
            ini_set('memory_limit', '2G');
            $view =  \View::make('reportes_gonzalo.reportes.listado_contribuyentes', compact('sql','anio','hab_urb','usuario','fecha','institucion'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("Lista Datos de Contribuyente".".pdf");
        }
        else
        {   return 'No hay datos';}
        }
    }
    
    public function listado_contribuyentes_predios($tip,$anio,$hab_urb1)
    {
        if($tip==1){
            if($anio != 0 && $hab_urb1 == 0){
                set_time_limit(0);
                ini_set('memory_limit', '1G');
            \Excel::create('REPORTE DE LISTADO DE CONTRIBUYENTES Y PREDIOS', function($excel) use ( $anio ) {

                $excel->sheet('CONTRIBUYENTES', function($sheet) use ( $anio ) {

                    $sql = DB::select("select id_persona,nro_doc_contri,contribuyente, (coalesce(cod_via, '') || ' ' || coalesce(nom_via, '') || ' ' || coalesce(nro_mun, '') || ' ' || coalesce(referencia, '')) as list_predio,mzna,lote_cat,are_terr,area_const from reportes.vw_02_contri_predios where anio = '$anio' order by contribuyente asc" );

                    $data= json_decode( json_encode($sql), true);

                    $sheet->fromArray($data);
                    $sheet->row(1, array("CODIGO", "DNI/RUC", "NOMBRE O RAZON SOCIAL", "LISTADO DE PREDIOS","MZNA","LOTE", "AREA DE TERRENO CONSTRUIDA", "AREA DE TERRENO"))->freezeFirstRow();
                    $sheet->setWidth(array(
                        'A'     =>  15,
                        'B'     =>  20,
                        'C'     =>  40,
                        'D'     =>  70,
                        'E'     =>  10,
                        'F'     =>  10,
                        'G'     =>  10,
                        'H'     =>  10
                    ));
                });
                })->export('xls');
            }
        }
        if($tip==0){
            $sql=DB::table('reportes.vw_02_contri_predios')->where('anio',$anio)->where('id_hab_urb',$hab_urb1)->orderBy('contribuyente')->get();
            $usuario = DB::select('SELECT * from public.usuarios where id='.Auth::user()->id);
            $fecha = (date('d/m/Y H:i:s'));
            $institucion = DB::select('SELECT * FROM maysa.institucion');
            if(count($sql)>0)
            {
                set_time_limit(0);
                ini_set('memory_limit', '2G');
                $view =  \View::make('reportes_gonzalo.reportes.listado_contribuyentes_predios', compact('sql','anio','hab_urb','usuario','fecha','institucion'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4','landscape');
                return $pdf->stream("Lista Contribuyentes y Predios".".pdf");
            }
            else
            {
                return 'No hay datos';
            }
        }
    }
    
    
    public function reporte_contribuyentes_exonerados($anio,$sector,$condicion)
    {
        $sql = DB::table('reportes.vw_contribuyentes_condicion')->where('anio',$anio)->where('id_sect',$sector)->where('id_cond_exonerac',$condicion)->get();
        $institucion = DB::select('SELECT * FROM maysa.institucion');
        if($sql)
        {
            set_time_limit(0);
            ini_set('memory_limit', '2G');
            $view = \View::make('reportes_gonzalo.reportes.reporte_contribuyentes_exonerados', compact('sql','institucion'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4','landscape');
            return $pdf->stream("PRUEBA".".pdf");
        }
        else
        {
            return 'No hay datos';
        }
    }
    
    public function reporte_cantidad_contribuyentes($anio,$sector)
    {
                $institucion = DB::select('SELECT * FROM maysa.institucion');
        $sql = DB::table('reportes.vw_contribuyentes_condicion')->where('anio',$anio)->where('id_sect',$sector)->where(function($sql) {
            $sql->where('id_cond_exonerac', 4)
                ->orWhere('id_cond_exonerac', 5);
        })->get();

        if(count($sql)>0)
        {
            set_time_limit(0);
            ini_set('memory_limit', '2G');
            $view =  \View::make('reportes_gonzalo.reportes.reporte_cantidad_contribuyente', compact('sql','institucion'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4','landscape');
            return $pdf->stream("PRUEBA".".pdf");
        }
        else
        {
            return 'No hay datos';
        }
    }
    
    //BUSQUEDA DE USUARIOS 
    public function get_usuarios(Request $request) 
    {
        if($request['dat']=='0')
        {
            return 0;
        }
        else
        {
        header('Content-type: application/json');
        $totalg = DB::select("select count(id) as total from public.usuarios where ape_nom like '%".$request['dat']."%'");
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

        $sql = DB::table('public.usuarios')->where('ape_nom','like', '%'.strtoupper($request['dat']).'%')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        
        
        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id;            
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id),
                trim($Datos->dni),
                trim($Datos->ape_nom),
                trim($Datos->usuario)
            );
        }
        return response()->json($Lista);
        }
    }
    
    public function reporte_usuarios($id,Request $request)
    {
        $fechainicio = $request['ini'];
        $fechafin = $request['fin'];
        $sql=DB::table('adm_tri.vw_predi_usu')->where('id_usu',$id)->whereBetween('fec_reg', [$fechainicio, $fechafin])->orderBy('fec_reg','asc')->get();
        
        $total = DB::select("select count(id_usu) as usuario from adm_tri.vw_predi_usu where id_usu = '$id' and fec_reg BETWEEN '$fechainicio' AND '$fechafin' group by nom_usu");
         $institucion = DB::select('SELECT * FROM maysa.institucion');
        if(count($sql)>0)
        {
            set_time_limit(0);
            ini_set('memory_limit', '2G');
            $view =  \View::make('reportes_gonzalo.reportes.reporte_get_usuarios', compact('sql','total','fechainicio','fechafin','institucion'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4','landscape');
            return $pdf->stream("PRUEBA".".pdf");
        }
        else
        {
            return 'NO HAY RESULTADOS';
        }
    }
    
    public function reporte_contribuyentes_predios_zonas($tip,$anio,$hab_urb)
    {    
        if($tip==1)
        {
              if($anio != 0 && $hab_urb == 0){
                set_time_limit(0);
                ini_set('memory_limit', '1G');
            \Excel::create('REPORTE DE CANTIDAD DE CONTRIBUYENTES Y PREDIOS POR ZONA', function($excel) use ( $anio ) {

                $excel->sheet('CONTRIBUYENTES', function($sheet) use ( $anio ) {

                    $sql = DB::select("select nro_doc, contribuyente, cond_prop_descripc, dom_fis,(coalesce(cod_via, '') || ' ' || coalesce(nom_via, '') || ' ' || coalesce(nro_mun, '') || ' ' || coalesce(referencia, '')) as predio, mzna,lote from adm_tri.vw_predi_urba where anio = '$anio'" );

                    $data= json_decode( json_encode($sql), true);

                    $sheet->fromArray($data);
                    $sheet->row(1, array("DNI/RUC", "NOMBRE", "TIPO CONTRIBUYENTE", "DOMICILIO FISCAL", "LISTA DE PREDIOS","MZNA","LOTE"))->freezeFirstRow();
                    $sheet->setWidth(array(
                        'A'     =>  15,
                        'B'     =>  50,
                        'C'     =>  25,
                        'D'     =>  70,
                        'E'     =>  70
                    ));
                });
            })->export('xls');

            }
        }
        if($tip==0)
        {
              $sql=DB::table('adm_tri.vw_predi_urba')->where('anio',$anio)->where('id_hab_urb',$hab_urb)->orderBy('contribuyente')->get();
              $nro_zonas = DB::select("select count(distinct id_contrib) as total from adm_tri.vw_predi_urba where id_hab_urb = '$hab_urb' ");
              $total = DB::select("select count(id_contrib) as total from adm_tri.vw_predi_urba where id_hab_urb = '$hab_urb' ");
              $usuario = DB::select('SELECT * from public.usuarios where id='.Auth::user()->id);
              $fecha = (date('d/m/Y H:i:s'));
               $institucion = DB::select('SELECT * FROM maysa.institucion');
              if(count($sql)>0)
              {
                  set_time_limit(0);
                  ini_set('memory_limit', '2G');
                  $view =  \View::make('reportes_gonzalo.reportes.reporte_contribuyentes_predios_zonas', compact('sql','anio','hab_urb','nro_zonas','total','usuario','fecha','institucion'))->render();
                  $pdf = \App::make('dompdf.wrapper');
                  $pdf->loadHTML($view)->setPaper('a4','landscape');
                  return $pdf->stream("Reporte Contribuyentes y Predios por Zonas".".pdf");
              }
              else
              {
                  return 'No hay datos';
              }
        }
               
    }
    
    public function reporte_emision_predial($tip,$anio,$hab_urb,$uso)
    {
        if($tip==1){
                if($anio != 0 && $hab_urb == 0 && $uso == 0)
            {
                set_time_limit(0);
                ini_set('memory_limit', '1G');
            \Excel::create('REPORTE DE EMISION PREDIAL POR USO', function($excel) use ( $anio ) {

                $excel->sheet('CONTRIBUYENTES', function($sheet) use ( $anio ) {

                    $sql = DB::select("select pers_nro_doc, contribuyente, (coalesce(dir_pred, '') || ' ' || coalesce(referencia, '')) as domicilio, uso_arbitrio from reportes.vw_predios_tipo_uso_arb where anio = '$anio'" );

                    $data= json_decode( json_encode($sql), true);

                    $sheet->fromArray($data);
                    $sheet->row(1, array("DNI/RUC", "CONTRIBUYENTE", "DOMICILIO", "USO"))->freezeFirstRow();
                    $sheet->setWidth(array(
                        'A'     =>  15,
                        'B'     =>  50,
                        'C'     =>  120,
                        'D'     =>  20
                    ));
                });
            })->export('xls');

            }
            elseif($anio != 0 && $hab_urb == 0 && $uso != 0){
                set_time_limit(0);
                ini_set('memory_limit', '1G');
                \Excel::create('REPORTE DE EMISION PREDIAL POR USO', function($excel) use ( $anio, $hab_urb ) {

                $excel->sheet('CONTRIBUYENTES', function($sheet) use ( $anio, $hab_urb ) {

                    $sql = DB::select("select pers_nro_doc, contribuyente, (coalesce(dir_pred, '') || ' ' || coalesce(referencia, '')) as domicilio, uso_arbitrio from reportes.vw_predios_tipo_uso_arb where anio = '$anio' and id_uso_arb = '$uso' " );

                    $data= json_decode( json_encode($sql), true);

                    $sheet->fromArray($data);
                    $sheet->row(1, array("DNI/RUC", "CONTRIBUYENTE", "DOMICILIO", "USO"))->freezeFirstRow();
                    $sheet->setWidth(array(
                        'A'     =>  15,
                        'B'     =>  50,
                        'C'     =>  120,
                        'D'     =>  20
                    ));
                });
            })->export('xls');
            }
        
        }
        if($tip==0){
          $usuario = DB::select('SELECT * from public.usuarios where id='.Auth::user()->id);
          $fecha = (date('d/m/Y H:i:s'));  
           $institucion = DB::select('SELECT * FROM maysa.institucion');
         if($anio != 0 && $hab_urb != 0 && $uso != 0)
         {
            $sql = DB::table('reportes.vw_predios_tipo_uso_arb')->where('anio',$anio)->where('id_hab_urb',$hab_urb)->where('id_uso_arb',$uso)->get();
            //$nombre_uso = DB::select("select uso_arbitrio from reportes.vw_predios_tipo_uso_arb where id_uso_arb = '$uso' ");
            $total = DB::select("select count(uso_arbitrio) as usos from reportes.vw_predios_tipo_uso_arb where id_uso_arb = '$uso' and id_hab_urb='$hab_urb'");
         }
         else
         {
             $sql = DB::table('reportes.vw_predios_tipo_uso_arb')->where('anio',$anio)->where('id_hab_urb',$hab_urb)->get();
            $total = DB::select("select count(uso_arbitrio) as usos from reportes.vw_predios_tipo_uso_arb where id_hab_urb='$hab_urb'");
           
          }
           if(count($sql)>0)
          {
              set_time_limit(0);
              ini_set('memory_limit', '2G');
              $view =  \View::make('reportes_gonzalo.reportes.reporte_emision_predial', compact('sql','anio','hab_urb','total','usuario','fecha','uso','institucion'))->render();
              $pdf = \App::make('dompdf.wrapper');
              $pdf->loadHTML($view)->setPaper('a4');
              return $pdf->stream("Listado de Contribuyentes".".pdf");
              
          }
          else
              {   return 'No hay datos';}
        
        }
          
    }
    
    public function reporte_deduccion_50UIT($tip,$anio,$hab_urb,$condicion)
    {
        if($tip==1){
                if($anio != 0 && $hab_urb == 0 && $condicion == 0){
                set_time_limit(0);
                ini_set('memory_limit', '1G');
            \Excel::create('Reporte de cantidad de contribuyentes con deducción de 50 UIT y monto de la Base Imponible - Pensionista y Adulto mayor)', function($excel) use ( $anio ) {

                $excel->sheet('CONTRIBUYENTES', function($sheet) use ( $anio ) {

                    $sql = DB::select("select pers_nro_doc, contribuyente, dom_fis, porctje, desc_exon,nomb_hab_urba, sec,mzna,lote, base_impon,base_impon_afecto from reportes.vw_por_tipo_exoneracion where  anio = '$anio' and desc_exon  ilike '%PENSIONISTA%' OR desc_exon  ilike '%ADULTO MAYOR%' order by contribuyente asc" );

                    $data= json_decode( json_encode($sql), true);

                    $sheet->fromArray($data);
                    $sheet->row(1, array("DNI", "NOMBRE", "DOMICILIO FISCAL", "DEDUCCION", "CONDICION", "HAB. URBANA", "SECTOR","MZNA","LOTE", "BASE IMPONIBLE","BASE IMPONIBLE AFECTO"))->freezeFirstRow();
                    $sheet->setWidth(array(
                        'A'     =>  15,
                        'B'     =>  50,
                        'C'     =>  100,
                        'D'     =>  30,
                        'E'     =>  30,
                        'F'     =>  100,
                        'G'     =>  20,
                        'H'     =>  20,
                        'I'     =>  20,
                        'J'     =>  20,
                        'K'     =>  20
                    ));
                });

            })->export('xls');

            }          
            elseif($anio != 0 && $hab_urb == 0 && $condicion != 0){
                set_time_limit(0);
                ini_set('memory_limit', '1G');
                \Excel::create('Cantidad de contribuyentes por Condicion(Afecto, Inafecto, Exoneracion Parcial, Pensionista y Adulto mayor)', function($excel) use ( $anio, $condicion ) {

                $excel->sheet('CONTRIBUYENTES', function($sheet) use ( $anio, $condicion ) {

                    $sql = DB::select("select pers_nro_doc, contribuyente, dom_fis, porctje, desc_exon,nomb_hab_urba, sec,mzna,lote, base_impon,base_impon_afecto from reportes.vw_por_tipo_exoneracion where anio = '$anio' and id_cond_exonerac = '$condicion' order by contribuyente ");

                    $data= json_decode( json_encode($sql), true);

                    $sheet->fromArray($data);
                     $sheet->row(1, array("DNI", "NOMBRE", "DOMICILIO FISCAL", "DEDUCCION", "CONDICION", "HAB. URBANA", "SECTOR","MZNA","LOTE", "BASE IMPONIBLE","BASE IMPONIBLE AFECTO"))->freezeFirstRow();
                    $sheet->setWidth(array(
                        'A'     =>  15,
                        'B'     =>  50,
                        'C'     =>  100,
                        'D'     =>  30,
                        'E'     =>  30,
                        'F'     =>  100,
                        'G'     =>  20,
                        'H'     =>  20,
                        'I'     =>  20,
                        'J'     =>  20,
                        'K'     =>  20
                    ));
                });
            })->export('xls');
            }
        }
        if($tip==0){
             $institucion = DB::select('SELECT * FROM maysa.institucion');
            $usuario = DB::select('SELECT * from public.usuarios where id='.Auth::user()->id);
            $fecha = (date('d/m/Y H:i:s'));
            if($anio != 0 && $hab_urb != 0 && $condicion != 0){
            $sql = DB::table('reportes.vw_por_tipo_exoneracion')->where('anio',$anio)->where('id_hab_urb',$hab_urb)->where('id_cond_exonerac',$condicion)->get();
            $nombre_condicion = DB::select("select desc_exon from reportes.vw_por_tipo_exoneracion where id_cond_exonerac = '$condicion' ");
            $total = DB::select("select count(*) as condiciones from reportes.vw_por_tipo_exoneracion where id_cond_exonerac = '$condicion' and id_hab_urb = '$hab_urb'");
             
            }
            else{
            $sql = DB::table('reportes.vw_por_tipo_exoneracion')->where('anio',$anio)->where('id_hab_urb',$hab_urb)->whereIn('desc_exon', array('Adulto Mayor', 'Pensionista'))->get();
            $nombre_condicion = DB::select("select id_exo,desc_exon from adm_tri.exoneracion where desc_exon  ilike '%PENSIONISTA%' OR desc_exon  ilike '%ADULTO MAYOR%' ");
            $total = DB::select("select count(*) as condiciones from reportes.vw_por_tipo_exoneracion where  id_hab_urb = '$hab_urb'");
             
            }
            
            if(count($sql)>0)
            {
                set_time_limit(0);
                ini_set('memory_limit', '2G');
                $view =  \View::make('reportes_gonzalo.reportes.reporte_cant_cont_ded_mont_bas_imp', compact('sql','anio','hab_urb','nombre_condicion','total','usuario','fecha','institucion'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4');
                return $pdf->stream("Listado de Contribuyentes".".pdf");
            }
            else
            {   return 'No hay datos';  }
        
        }

    }
    public function reporte_exonerados($tip,$anio,$hab_urb,$condicion)
    {
        if($tip==1){
                if($anio != 0 && $hab_urb == 0 && $condicion == 0){
                set_time_limit(0);
                ini_set('memory_limit', '1G');
            \Excel::create('Reporte de cantidad de contribuyentes con deducción de 50 UIT y monto de la Base Imponible - Pensionista y Adulto mayor)', function($excel) use ( $anio ) {

                $excel->sheet('CONTRIBUYENTES', function($sheet) use ( $anio ) {

                    $sql = DB::select("select pers_nro_doc, contribuyente, dom_fis, porctje, desc_exon,nomb_hab_urba, sec,mzna,lote, base_impon from reportes.vw_por_tipo_exoneracion where  anio = '$anio' and desc_exon  ilike '%PENSIONISTA%' OR desc_exon  ilike '%ADULTO MAYOR%' order by contribuyente asc" );

                    $data= json_decode( json_encode($sql), true);

                    $sheet->fromArray($data);
                    $sheet->row(1, array("DNI", "NOMBRE", "DOMICILIO FISCAL", "DEDUCCION", "CONDICION", "HAB. URBANA", "SECTOR","MZNA","LOTE", "BASE IMPONIBLE"))->freezeFirstRow();
                    $sheet->setWidth(array(
                        'A'     =>  15,
                        'B'     =>  50,
                        'C'     =>  100,
                        'D'     =>  30,
                        'E'     =>  30,
                        'F'     =>  100,
                        'G'     =>  20,
                        'H'     =>  20,
                        'I'     =>  20,
                        'J'     =>  20
                    ));
                });

            })->export('xls');

            }          
            elseif($anio != 0 && $hab_urb == 0 && $condicion != 0){
                set_time_limit(0);
                ini_set('memory_limit', '1G');
                \Excel::create('Cantidad de contribuyentes por Condicion(Afecto, Inafecto, Exoneracion Parcial, Pensionista y Adulto mayor)', function($excel) use ( $anio, $condicion ) {

                $excel->sheet('CONTRIBUYENTES', function($sheet) use ( $anio, $condicion ) {

                    $sql = DB::select("select pers_nro_doc, contribuyente, dom_fis, porctje, desc_exon,nomb_hab_urba, sec,mzna,lote, base_impon from reportes.vw_por_tipo_exoneracion where anio = '$anio' and id_cond_exonerac = '$condicion' order by contribuyente ");

                    $data= json_decode( json_encode($sql), true);

                    $sheet->fromArray($data);
                    $sheet->row(1, array("DNI", "NOMBRE", "DOMICILIO FISCAL", "DEDUCCION", "CONDICION", "HAB. URBANA", "SECTOR","MZNA","LOTE", "BASE IMPONIBLE"))->freezeFirstRow();
                    $sheet->setWidth(array(
                        'A'     =>  15,
                        'B'     =>  50,
                        'C'     =>  100,
                        'D'     =>  30,
                        'E'     =>  30,
                        'F'     =>  100,
                        'G'     =>  20,
                        'H'     =>  20,
                        'I'     =>  20,
                        'J'     =>  20
                    ));
                });
            })->export('xls');
            }
        }
        if($tip==0){
             $institucion = DB::select('SELECT * FROM maysa.institucion');
            $usuario = DB::select('SELECT * from public.usuarios where id='.Auth::user()->id);
            $fecha = (date('d/m/Y H:i:s'));
            if($anio != 0 && $hab_urb != 0 && $condicion != 0){
            $sql = DB::table('reportes.vw_por_tipo_exoneracion')->where('anio',$anio)->where('id_hab_urb',$hab_urb)->where('id_cond_exonerac',$condicion)->get();
            $nombre_condicion = DB::select("select desc_exon from reportes.vw_por_tipo_exoneracion where id_cond_exonerac = '$condicion' ");
            $total = DB::select("select count(*) as condiciones from reportes.vw_por_tipo_exoneracion where id_cond_exonerac = '$condicion' and id_hab_urb = '$hab_urb'");
             
            }
            else{
            $sql = DB::table('reportes.vw_por_tipo_exoneracion')->where('anio',$anio)->where('id_hab_urb',$hab_urb)->whereIn('desc_exon', array('Adulto Mayor', 'Pensionista'))->get();
            $nombre_condicion = DB::select("select id_exo,desc_exon from adm_tri.exoneracion where desc_exon  ilike '%PENSIONISTA%' OR desc_exon  ilike '%ADULTO MAYOR%' ");
            $total = DB::select("select count(*) as condiciones from reportes.vw_por_tipo_exoneracion where  id_hab_urb = '$hab_urb'");
             
            }
            
            if(count($sql)>0)
            {
                set_time_limit(0);
                ini_set('memory_limit', '2G');
                $view =  \View::make('reportes_gonzalo.reportes.reporte_cant_cont_ded_mont_bas_imp', compact('sql','anio','hab_urb','nombre_condicion','total','usuario','fecha','institucion'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4');
                return $pdf->stream("Listado de Contribuyentes".".pdf");
            }
            else
            {   return 'No hay datos';  }
        
        }

    }
     public function rep_por_zona($anio,$id)
    {
        $sql=DB::table('')->where('',$anio) ->where('', $id)->orderBy('','asc')->get();
         $institucion = DB::select('SELECT * FROM maysa.institucion');
       
        
        if(count($sql)>0)
        {
            set_time_limit(0);
            ini_set('memory_limit', '2G');
            $view =  \View::make('reportes_gonzalo.reportes.rep_por_zona', compact('sql','institucion'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("PRUEBA".".pdf");
        }
        else
        {
            return 'NO HAY RESULTADOS';
        }
        
    }
    public function rep_corriente($anio)
    {
        //$anio= $request['anio'];
       // $sql=DB::table('presupuesto.vw_por_tributo')->where('id_tributo',$id_tributo) ->whereBetween('fecha', [$fechainicio, $fechafin])->orderBy('fecha','asc')->get();
        $sql = DB::select(" select * from presupuesto.vw_imp_pre_corr_nocorr where periodo='$anio' order by periodo desc" );
        $sql1 = DB::select("select sum(sum) from presupuesto.vw_imp_pre_corr_nocorr where periodo<'$anio' " );
        $usuario = DB::select('SELECT * from public.usuarios where id='.Auth::user()->id);
        $fecha = (date('d/m/Y H:i:s'));
         $institucion = DB::select('SELECT * FROM maysa.institucion');
        if(count($sql)>0)
        {
            set_time_limit(0);
            ini_set('memory_limit', '2G');
            $view =  \View::make('reportes_gonzalo.reportes.rep_corriente', compact('sql','sql1','usuario','fecha','institucion'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("PRUEBA".".pdf");
        }
        else
        {
            return 'NO HAY RESULTADOS';
        }
        
    }
     public function rep_fraccionamiento($anio, $estado)
    {
        if($anio != 0 && $estado == 0)
        {
             $sql=DB::table('fraccionamiento.vw_convenios')->where('anio',$anio)->orderBy('contribuyente','asc')->get();
        $total = DB::select("select count(est_actual) as estados from fraccionamiento.vw_convenios where estado = '$estado' and anio = '$anio'");
        $usuario = DB::select('SELECT * from public.usuarios where id='.Auth::user()->id);
        $fecha = (date('d/m/Y H:i:s'));
         $institucion = DB::select('SELECT * FROM maysa.institucion');
        if(count($sql)>0)
        {
            set_time_limit(0);
            ini_set('memory_limit', '2G');
            $view =  \View::make('reportes_gonzalo.reportes.reporte_fraccionamiento', compact('sql','usuario','fecha','total','estado','institucion'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("PRUEBA".".pdf");
        }
        else
        {
            return 'NO HAY RESULTADOS';
        }
        }
        else {
             $institucion = DB::select('SELECT * FROM maysa.institucion');
        $sql=DB::table('fraccionamiento.vw_convenios')->where('estado',$estado)->where('anio',$anio)->orderBy('contribuyente','asc')->get();
        $total = DB::select("select count(est_actual) as estados from fraccionamiento.vw_convenios where estado = '$estado' and anio = '$anio'");
        $usuario = DB::select('SELECT * from public.usuarios where id='.Auth::user()->id);
        $fecha = (date('d/m/Y H:i:s'));
        if(count($sql)>0)
        {
            set_time_limit(0);
            ini_set('memory_limit', '2G');
            $view =  \View::make('reportes_gonzalo.reportes.reporte_fraccionamiento', compact('sql','usuario','fecha','total','estado','institucion'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("PRUEBA".".pdf");
        }
        else
        {
            return 'NO HAY RESULTADOS';
        }
        }
        
        
    }
   
    
    public function reporte_bi_afecto_exonerado($tip,$anio,$condicion)
    {
        if($tip==1){
                if($anio != 0 && $condicion != 0){
                set_time_limit(0);
                ini_set('memory_limit', '1G');
                \Excel::create('Reporte del Monto de la Base Imponible Afecto y Exonerado', function($excel) use ( $anio, $condicion ) {

                $excel->sheet('CONTRIBUYENTES', function($sheet) use ( $anio, $condicion ) {

                    $sql = DB::select("select nro_doc, contribuyente, domic_fiscal,base_imponible, impuesto from reportes.vw_report_06_and_08 where anio = '$anio' and id_cond_exonerac = '$condicion' order by contribuyente asc" );

                    $data= json_decode( json_encode($sql), true);

                    $sheet->fromArray($data);
                    $sheet->row(1, array("DNI", "CONTRIBUYENTE", "DOMICILIO FISCAL","BASE IMPONIBLE","IMPUESTO"))->freezeFirstRow();
                    $sheet->setWidth(array(
                        'A'     =>  20,
                        'B'     =>  80,
                        'C'     =>  100,
                        'D'     =>  15,
                        'E'     =>  15
                    ));
                });

            })->export('xls');

            }elseif($anio != 0 && $condicion == 0){
                set_time_limit(0);
                ini_set('memory_limit', '1G');
                \Excel::create('Reporte del Monto de la Base Imponible Afecto y Exonerado', function($excel) use ( $anio ) {

                $excel->sheet('CONTRIBUYENTES', function($sheet) use ( $anio ) {

                    $sql = DB::select("select nro_doc, contribuyente, domic_fiscal,base_imponible, impuesto from reportes.vw_report_06_and_08 where anio = '$anio' order by contribuyente asc" );

                    $data= json_decode( json_encode($sql), true);

                    $sheet->fromArray($data);
                    $sheet->row(1, array("DNI", "CONTRIBUYENTE", "DOMICILIO FISCAL","BASE IMPONIBLE","IMPUESTO"))->freezeFirstRow();
                    $sheet->setWidth(array(
                        'A'     =>  20,
                        'B'     =>  80,
                        'C'     =>  100,
                        'D'     =>  15,
                        'E'     =>  15
                    ));
                });

            })->export('xls');

            }          
        }
        if($tip==0){
             $institucion = DB::select('SELECT * FROM maysa.institucion');
            $usuario = DB::select('SELECT * from public.usuarios where id='.Auth::user()->id);
            $fecha = (date('d/m/Y H:i:s'));
            
            if($anio != 0 && $condicion != 0){
            $sql = DB::select("select SUM(base_imponible) as base_imponible, SUM(impuesto) as impuesto from reportes.vw_report_06_and_08 where id_cond_exonerac = '$condicion' and anio = '$anio'");
            $base_imponible = DB::select("select SUM(base_imponible) as base_imponible from reportes.vw_report_06_and_08 where id_cond_exonerac = '$condicion' and anio = '$anio'");
            $impuesto = DB::select("select SUM(impuesto) as impuesto from reportes.vw_report_06_and_08 where id_cond_exonerac = '$condicion' and anio = '$anio'");
            $nombre_condicion = DB::select("select desc_exon from reportes.vw_report_06_and_08 where id_cond_exonerac = '$condicion' ");
            
             
            }else{
            $sql = DB::select("select SUM(base_imponible) as base_imponible, SUM(impuesto) as impuesto from reportes.vw_report_06_and_08 where id_cond_exonerac = '$condicion' and anio = '$anio'");
            $base_imponible = DB::select("select SUM(base_imponible) as base_imponible from reportes.vw_report_06_and_08 where anio = '$anio'");
            $impuesto = DB::select("select SUM(impuesto) as impuesto from reportes.vw_report_06_and_08 where anio = '$anio'");
            $nombre_condicion1 = 'TODOS';
            $nombre_condicion = DB::select("select desc_exon from reportes.vw_report_06_and_08 where id_cond_exonerac = '$condicion' ");
            }

            if(count($sql)>0)
            {
                set_time_limit(0);
                ini_set('memory_limit', '2G');
                $view =  \View::make('reportes_gonzalo.reportes.reporte_bi_afecto_exonerado', compact('sql','base_imponible','impuesto','nombre_condicion','nombre_condicion1','anio','usuario','fecha','institucion'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4');
                return $pdf->stream("Reporte del Monto de la Base Imponible Afecto y Exonerado".".pdf");
            }
            else
            {   return 'No hay datos';  }
        
        }

    }
    
    public function reporte_ep_afecto_exonerado($tip,$anio,$condicion)
    {
        if($tip==1){
                if($anio != 0 && $condicion != 0){
                set_time_limit(0);
                ini_set('memory_limit', '1G');
                \Excel::create('Reporte Número de Contribuyentes de la emision predial Afecto y Exonerado', function($excel) use ( $anio, $condicion ) {

                $excel->sheet('CONTRIBUYENTES', function($sheet) use ( $anio, $condicion ) {

                    $sql = DB::select("select nro_doc, contribuyente, domic_fiscal,base_imponible, impuesto from reportes.vw_report_06_and_08 where anio = '$anio' and id_cond_exonerac = '$condicion' order by contribuyente asc" );

                    $data= json_decode( json_encode($sql), true);

                    $sheet->fromArray($data);
                    $sheet->row(1, array("DNI", "CONTRIBUYENTE", "DOMICILIO FISCAL","BASE IMPONIBLE","IMPUESTO"))->freezeFirstRow();
                    $sheet->setWidth(array(
                        'A'     =>  20,
                        'B'     =>  80,
                        'C'     =>  100,
                        'D'     =>  15,
                        'E'     =>  15
                    ));
                });

            })->export('xls');

            }elseif($anio != 0 && $condicion == 0){
                set_time_limit(0);
                ini_set('memory_limit', '1G');
                \Excel::create('Reporte Número de Contribuyentes de la emision predial Afecto y Exonerado', function($excel) use ( $anio ) {

                $excel->sheet('CONTRIBUYENTES', function($sheet) use ( $anio ) {

                    $sql = DB::select("select nro_doc, contribuyente, domic_fiscal,base_imponible, impuesto from reportes.vw_report_06_and_08 where anio = '$anio' order by contribuyente asc" );

                    $data= json_decode( json_encode($sql), true);

                    $sheet->fromArray($data);
                    $sheet->row(1, array("DNI", "CONTRIBUYENTE", "DOMICILIO FISCAL","BASE IMPONIBLE","IMPUESTO"))->freezeFirstRow();
                    $sheet->setWidth(array(
                        'A'     =>  20,
                        'B'     =>  80,
                        'C'     =>  100,
                        'D'     =>  15,
                        'E'     =>  15
                    ));
                });

            })->export('xls');

            }          
        }
        if($tip==0){
            $usuario = DB::select('SELECT * from public.usuarios where id='.Auth::user()->id);
            $fecha = (date('d/m/Y H:i:s'));
             $institucion = DB::select('SELECT * FROM maysa.institucion');
            if($anio != 0 && $condicion != 0){
            $sql = DB::select("select SUM(base_imponible) as base_imponible, SUM(impuesto) as impuesto from reportes.vw_report_06_and_08 where id_cond_exonerac = '$condicion' and anio = '$anio'");
            $base_imponible = DB::select("select SUM(base_imponible) as base_imponible from reportes.vw_report_06_and_08 where id_cond_exonerac = '$condicion' and anio = '$anio'");
            $impuesto = DB::select("select SUM(impuesto) as impuesto from reportes.vw_report_06_and_08 where id_cond_exonerac = '$condicion' and anio = '$anio'");
            $nombre_condicion = DB::select("select desc_exon from reportes.vw_report_06_and_08 where id_cond_exonerac = '$condicion' ");
            
             
            }else{
            $sql = DB::select("select SUM(base_imponible) as base_imponible, SUM(impuesto) as impuesto from reportes.vw_report_06_and_08 where id_cond_exonerac = '$condicion' and anio = '$anio'");
            $base_imponible = DB::select("select SUM(base_imponible) as base_imponible from reportes.vw_report_06_and_08 where anio = '$anio'");
            $impuesto = DB::select("select SUM(impuesto) as impuesto from reportes.vw_report_06_and_08 where anio = '$anio'");
            $nombre_condicion1 = 'TODOS';
            $nombre_condicion = DB::select("select desc_exon from reportes.vw_report_06_and_08 where id_cond_exonerac = '$condicion' ");
            }

            if(count($sql)>0)
            {
                set_time_limit(0);
                ini_set('memory_limit', '2G');
                $view =  \View::make('reportes_gonzalo.reportes.reporte_ep_afecto_exonerado', compact('sql','base_imponible','impuesto','nombre_condicion','nombre_condicion1','anio','usuario','fecha','institucion'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4');
                return $pdf->stream("Reporte Número de Contribuyentes de la emision predial Afecto y Exonerado".".pdf");
            }
            else
            {   return 'No hay datos';  }
        
        }
        if($tip==2){
                if($anio != 0 && $condicion != 0){
                set_time_limit(0);
                ini_set('memory_limit', '1G');
                \Excel::create('Reporte Número de Contribuyentes de la emision predial Afecto y Exonerado', function($excel) use ( $anio, $condicion ) {

                $excel->sheet('CONTRIBUYENTES', function($sheet) use ( $anio, $condicion ) {

                    $sql = DB::select("select nro_doc, contribuyente, domic_fiscal,base_imponible, impuesto from reportes.vw_report_06_and_08 where anio = '$anio' and id_cond_exonerac = '$condicion' order by contribuyente asc" );

                    $data= json_decode( json_encode($sql), true);

                    $sheet->fromArray($data);
                    $sheet->row(1, array("DNI", "CONTRIBUYENTE", "DOMICILIO FISCAL","BASE IMPONIBLE","IMPUESTO"))->freezeFirstRow();
                    $sheet->setWidth(array(
                        'A'     =>  20,
                        'B'     =>  80,
                        'C'     =>  100,
                        'D'     =>  15,
                        'E'     =>  15
                    ));
                });

            })->export('xls');

            }elseif($anio != 0 && $condicion == 0){
                set_time_limit(0);
                ini_set('memory_limit', '1G');
                \Excel::create('Reporte Número de Contribuyentes de la emision predial Afecto y Exonerado', function($excel) use ( $anio ) {

                $excel->sheet('CONTRIBUYENTES', function($sheet) use ( $anio ) {

                    $sql = DB::select("select nro_doc, contribuyente, domic_fiscal,base_imponible, impuesto from reportes.vw_report_06_and_08 where anio = '$anio' order by contribuyente asc" );

                    $data= json_decode( json_encode($sql), true);

                    $sheet->fromArray($data);
                    $sheet->row(1, array("DNI", "CONTRIBUYENTE", "DOMICILIO FISCAL","BASE IMPONIBLE","IMPUESTO"))->freezeFirstRow();
                    $sheet->setWidth(array(
                        'A'     =>  20,
                        'B'     =>  80,
                        'C'     =>  100,
                        'D'     =>  15,
                        'E'     =>  15
                    ));
                });

            })->export('xls');

            }          
        }
        if($tip==3){
            $usuario = DB::select('SELECT * from public.usuarios where id='.Auth::user()->id);
            $fecha = (date('d/m/Y H:i:s'));
             $institucion = DB::select('SELECT * FROM maysa.institucion');
            if($anio != 0 && $condicion != 0){
            $sql = DB::select("select SUM(base_imponible) as base_imponible, SUM(impuesto) as impuesto from reportes.vw_report_06_and_08 where id_cond_exonerac = '$condicion' and anio = '$anio'");
            $base_imponible = DB::select("select SUM(base_imponible) as base_imponible from reportes.vw_report_06_and_08 where id_cond_exonerac = '$condicion' and anio = '$anio'");
            $impuesto = DB::select("select SUM(impuesto) as impuesto from reportes.vw_report_06_and_08 where id_cond_exonerac = '$condicion' and anio = '$anio'");
            $nombre_condicion = DB::select("select desc_exon from reportes.vw_report_06_and_08 where id_cond_exonerac = '$condicion' ");
            
             
            }else{
            $sql = DB::select("select SUM(base_imponible) as base_imponible, SUM(impuesto) as impuesto from reportes.vw_report_06_and_08 where id_cond_exonerac = '$condicion' and anio = '$anio'");
            $base_imponible = DB::select("select SUM(base_imponible) as base_imponible from reportes.vw_report_06_and_08 where anio = '$anio'");
            $impuesto = DB::select("select SUM(impuesto) as impuesto from reportes.vw_report_06_and_08 where anio = '$anio'");
            $nombre_condicion1 = 'TODOS';
            $nombre_condicion = DB::select("select desc_exon from reportes.vw_report_06_and_08 where id_cond_exonerac = '$condicion' ");
            }

            if(count($sql)>0)
            {
                set_time_limit(0);
                ini_set('memory_limit', '2G');
                $view =  \View::make('reportes_gonzalo.reportes.reporte_monto_ep_afecto_exonerado', compact('sql','base_imponible','impuesto','nombre_condicion','nombre_condicion1','anio','usuario','fecha','institucion'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4');
                return $pdf->stream("Reporte Número de Contribuyentes de la emision predial Afecto y Exonerado".".pdf");
            }
            else
            {   return 'No hay datos';  }
        
        }

    }
    
    public function reporte_morosidad_arbitrios($tip,$anio,$hab_urb)
    {
       $usuario = DB::select('SELECT * from public.usuarios where id='.Auth::user()->id);
       $fecha = (date('d/m/Y H:i:s'));
        $institucion = DB::select('SELECT * FROM maysa.institucion');
        if($tip==1){
            if($anio != 0 && $hab_urb == 0){  
            $sql = DB::table('reportes.vw_reporte_15')->where('anio',$anio)->orderBy('tot_pagar','desc')->get();       
            if(count($sql)>0)
            {
                set_time_limit(0);
                ini_set('memory_limit', '2G');
                $view =  \View::make('reportes_gonzalo.reportes.reporte_morosidad_arbitrios', compact('sql','anio','hab_urb','usuario','fecha'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4');
                return $pdf->stream("Listado de Contribuyentes".".pdf");
            }
            else
            {   return 'No hay datos';  }
        
            }
        }
        if($tip==0){
            
            if($anio != 0 && $hab_urb != 0){
            $sql = DB::table('reportes.vw_reporte_15')->where('anio',$anio)->where('id_hab_urb',$hab_urb)->orderBy('tot_pagar','desc')->get();             
            if(count($sql)>0)
            {
                set_time_limit(0);
                ini_set('memory_limit', '2G');
                $view =  \View::make('reportes_gonzalo.reportes.reporte_morosidad_arbitrios', compact('sql','anio','hab_urb','usuario','fecha','institucion'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4');
                return $pdf->stream("Listado de Contribuyentes".".pdf");
            }
            else
            {   return 'No hay datos';  }
        
            }
        }
    }
    public function reporte_recaudacion_arbitrios($tip,$anio,$hab_urb)
    {
       $usuario = DB::select('SELECT * from public.usuarios where id='.Auth::user()->id);
       $fecha = (date('d/m/Y H:i:s'));
        $institucion = DB::select('SELECT * FROM maysa.institucion');
        if($tip==1){
            if($anio != 0 && $hab_urb == 0){  
            $sql = DB::table('reportes.vw_reporte_15')->where('anio',$anio)->orderBy('tot_pagar','desc')->get();       
            if(count($sql)>0)
            {
                set_time_limit(0);
                ini_set('memory_limit', '2G');
                $view =  \View::make('reportes_gonzalo.reportes.reporte_morosidad_arbitrios', compact('sql','anio','hab_urb','usuario','fecha','institucion'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4');
                return $pdf->stream("Listado de Contribuyentes".".pdf");
            }
            else
            {   return 'No hay datos';  }
        
            }
        }
        if($tip==0){
            
            if($anio != 0 && $hab_urb != 0){
            $sql = DB::table('reportes.vw_reporte_15')->where('anio',$anio)->where('id_hab_urb',$hab_urb)->orderBy('tot_pagar','desc')->get();             
            if(count($sql)>0)
            {
                set_time_limit(0);
                ini_set('memory_limit', '2G');
                $view =  \View::make('reportes_gonzalo.reportes.reporte_recaudacionss_arbitrios', compact('sql','anio','hab_urb','usuario','fecha','institucion'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4');
                return $pdf->stream("Listado de Contribuyentes".".pdf");
            }
            else
            {   return 'No hay datos';  }
        
            }
        }
    }
    
    
    public function reporte_monto_trans_a_coactivo($anio,$doc)
    {
       $usuario = DB::select('SELECT * from public.usuarios where id='.Auth::user()->id);
       $fecha = (date('d/m/Y H:i:s'));
        $institucion = DB::select('SELECT * FROM maysa.institucion');
        if($anio != 0 && $doc==1){
            $sql = DB::table('recaudacion.vw_op_detalle')->where('anio',$anio)->orderBy('nro_fis','desc')->get();       
            if(count($sql)>0)
            {
                set_time_limit(0);
                ini_set('memory_limit', '2G');
                $view =  \View::make('reportes_gonzalo.reportes.reporte_monto_trans_a_coactivo', compact('sql','anio','usuario','fecha','institucion'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4');
                return $pdf->stream("Listado de Contribuyentes".".pdf");
            }
            else
            {   return 'No hay datos';  }
        
            
        }
        if($anio != 0 && $doc==2){
            $sql = DB::table('fiscalizacion.vw_resolucion_determinacion')->where('anio',$anio)->orderBy('nro_rd','desc')->get();       
            if(count($sql)>0)
            {
                set_time_limit(0);
                ini_set('memory_limit', '2G');
                $view =  \View::make('reportes_gonzalo.reportes.reporte_monto_trans_a_coactivo_rd', compact('sql','anio','usuario','fecha','institucion'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4');
                return $pdf->stream("Listado de Contribuyentes".".pdf");
            }
            else
            {   return 'No hay datos';  }
        
            
        }
       
    }
    public function reporte_monto_cuentas_imp($hab_urb,$anio)
    { 
            $sql = DB::table('reportes.vw_reporte_25')->where('id_hab_urb',$hab_urb)->where('ano_cta',$anio)->orderBy('contribuyente')->get();
            $usuario = DB::select('SELECT * from public.usuarios where id='.Auth::user()->id);
            $fecha = (date('d/m/Y H:i:s'));
            $institucion = DB::select('SELECT * FROM maysa.institucion');
            if(count($sql)>0)
            {
                set_time_limit(0);
                ini_set('memory_limit', '2G');
                $view =  \View::make('reportes_gonzalo.reportes.reporte_cuentas_imp', compact('sql','usuario','fecha','institucion','anio'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4','landscape');
                return $pdf->stream("Lista Contribuyentes y Predios".".pdf");
            }
            else
            {
                return 'No hay datos';
            }     
    }
     public function reporte_monto_cuentas_arb($hab_urb,$anio)
    { 
            $sql = DB::table('reportes.vw_reporte_26')->where('id_hab_urb',$hab_urb)->where('anio',$anio)->orderBy('contribuyente')->get();
            $usuario = DB::select('SELECT * from public.usuarios where id='.Auth::user()->id);
            $fecha = (date('d/m/Y H:i:s'));
            $institucion = DB::select('SELECT * FROM maysa.institucion');
            if(count($sql)>0)
            {
                set_time_limit(0);
                ini_set('memory_limit', '2G');
                $view =  \View::make('reportes_gonzalo.reportes.reporte_cuentas_arb', compact('sql','usuario','fecha','institucion','anio'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4','landscape');
                return $pdf->stream("Lista Recaudado,Pagado y Saldos Arbitrios".".pdf");
            }
            else
            {
                return 'No hay datos';
            }     
    }
    function autocompletar_haburb() {
        $Consulta = DB::table('catastro.hab_urb')->get();
        $todo = array();
        foreach ($Consulta as $Datos) {
            $Lista = new \stdClass();
            $Lista->value = $Datos->id_hab_urb;
            //$Lista->label = trim($Datos->codi_hab_urba);
            $Lista->label = trim($Datos->nomb_hab_urba);
            array_push($todo, $Lista);
        }
        return response()->json($todo);
    }
    
     
    public function reporte_constancia( Request $request)
    {
//        $sql = DB::table('soft_const_posesion.vw_expedientes')->get();
            $sql = DB::table('adm_tri.vw_instalaciones')->get();
        $institucion = DB::select('SELECT * FROM maysa.institucion');
            if($sql)
            {
                set_time_limit(0);
                ini_set('memory_limit', '2G');
                $view = \View::make('reportes_gonzalo.reportes.reporte_cajas1', compact('sql','institucion'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4');
                return $pdf->stream("PRUEBA".".pdf");
            }
            else
            {
                return 'No hay datos';
            }
    }
    
    
    
    
    public function reporte_cajas( Request $request)
    {
        
        $fechainicio = $request['ini'];
        $fechafin = $request['fin'];
        $id_agencia = $request['id_agen'];
        $institucion = DB::select('SELECT * FROM maysa.institucion');
        $usuario = DB::select('SELECT * from public.usuarios where id='.Auth::user()->id);
        if($id_agencia == 0 ){
            $sql = DB::table("tesoreria.vw_caja_mov")->select("descrip_caja",DB::raw('SUM(total) as total'))->whereBetween('fecha', [$fechainicio, $fechafin])->groupBy('descrip_caja')->orderBy('descrip_caja','asc')->get();
            if($sql)
            {
                set_time_limit(0);
                ini_set('memory_limit', '2G');
                $view = \View::make('reportes_gonzalo.reportes.reporte_cajas0', compact('sql','institucion','agencia','usuario'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4');
                return $pdf->stream("PRUEBA".".pdf");
            }
            else
            {
                return 'No hay datos';
            }
        
        }
        
        else{
            $sql = DB::select(" SELECT descrip_caja,fecha,sum(total) FROM tesoreria.vw_caja_mov where id_caja ='$id_agencia' and fecha between '$fechainicio' and '$fechafin' GROUP BY descrip_caja,  fecha" );
            $sql1 = DB::select(" SELECT descrip_caja,sum(total)FROM tesoreria.vw_caja_mov where id_caja ='$id_agencia'  and fecha between '$fechainicio' and '$fechafin' GROUP BY descrip_caja " );

            if($sql)
            {
                set_time_limit(0);
                ini_set('memory_limit', '2G');
                $view = \View::make('reportes_gonzalo.reportes.reporte_cajas', compact('sql','sql1','institucion','id_agencia'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4');
                return $pdf->stream("PRUEBA".".pdf");
            }
            else
            {
                return 'No hay datos';
            }
       } 
        
    }
}