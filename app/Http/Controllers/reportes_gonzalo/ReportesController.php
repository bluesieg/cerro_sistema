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
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        $sectores =  DB::table('catastro.sectores')->orderBy('sector', 'asc')->where('id_sec', '>', 0)->get();
        $condicion = DB::select('select id_exo,desc_exon from adm_tri.exoneracion order by id_exo asc');
        $usos_predio_arb = DB::table('adm_tri.uso_predio_arbitrios')->orderBy('id_uso_arb', 'asc')->get();
        return view('reportes_gonzalo/vw_reportes', compact('menu','permisos','anio_tra','sectores','condicion','usos_predio_arb'));
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
    
    public function reportes_contribuyentes($anio,$min,$max,$num_reg)
    {

        $sql=DB::table('reportes.vw_pricos')->where('ano_cta',$anio)->where('ivpp','>',$min)->where('ivpp','<',$max)->limit($num_reg)->orderBy('ivpp', 'desc')->get();

        if(count($sql)>0)
        {
            $view =  \View::make('reportes_gonzalo.reportes.reporte_contribuyentes', compact('sql','anio','min','max'))->render();
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
        
        $sql=DB::table('adm_tri.vw_predi_usu')->where('anio',$anio)->where('id_sec',$sector)->where('id_mzna',$manzana)->orderBy('lote')->get();

        if(count($sql)>0)
        {
            $view =  \View::make('reportes_gonzalo.reportes.predios_prueba', compact('sql','anio','sector','manzana'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("Predios por Usuario".".pdf");
        }
        else
        {   return 'No hay datos';}
    }
    
    public function listado_contribuyentes($anio,$sector){
        
        if($anio != 0 && $sector == 0){
            
        \Excel::create('REPORTE DE CONTRIBUYENTES', function($excel) use ( $anio ) {
            
            $excel->sheet('CONTRIBUYENTES', function($sheet) use ( $anio ) {
                
                $sql = DB::select("select nro_doc, contribuyente, persona, dom_fis from adm_tri.vw_contrib_predios_c where ano_cta = '$anio'" );
                
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
        else
        {
        
        $sql=DB::table('adm_tri.vw_contrib_predios_c')->where('ano_cta',$anio)->where('id_sec',$sector)->get();
        
        }

        if(count($sql)>0)
        {
            $view =  \View::make('reportes_gonzalo.reportes.listado_contribuyentes', compact('sql','anio','sector'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("Lista Datos de Contribuyente".".pdf");
        }
        else
        {   return 'No hay datos';}

    }
    
    public function listado_contribuyentes_predios($anio,$sector)
    {
        if($anio != 0 && $sector == 0){
            
        \Excel::create('REPORTE DE LISTADO DE CONTRIBUYENTES Y PREDIOS', function($excel) use ( $anio ) {
            
            $excel->sheet('CONTRIBUYENTES', function($sheet) use ( $anio ) {
                
                $sql = DB::select("select id_persona,nro_doc_contri,contribuyente, (coalesce(cod_via, '') || ' ' || coalesce(nom_via, '') || ' ' || coalesce(nro_mun, '') || ' ' || coalesce(referencia, '')) as list_predio,are_terr,area_const from reportes.vw_02_contri_predios where anio = '$anio' order by contribuyente asc" );
                
                $data= json_decode( json_encode($sql), true);
                
                $sheet->fromArray($data);
                $sheet->row(1, array("CODIGO", "DNI/RUC", "NOMBRE O RAZON SOCIAL", "LISTADO DE PREDIOS", "AREA DE TERRENO CONSTRUIDA", "AREA DE TERRENO"))->freezeFirstRow();
                $sheet->setWidth(array(
                    'A'     =>  15,
                    'B'     =>  20,
                    'C'     =>  40,
                    'D'     =>  70,
                    'E'     =>  10,
                    'F'     =>  10
                ));
            });
        })->export('xls');
        
        }
        else
        {
        
        $sql=DB::table('reportes.vw_02_contri_predios')->where('anio',$anio)->where('id_sec',$sector)->orderBy('id_contrib')->get();
        
        }

        if(count($sql)>0)
        {
            $view =  \View::make('reportes_gonzalo.reportes.listado_contribuyentes_predios', compact('sql','anio','sector'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4','landscape');
            return $pdf->stream("Lista Contribuyentes y Predios".".pdf");
        }
        else
        {
            return 'No hay datos';
        }
    }
    
    public function reporte_contribuyentes_exonerados($anio,$sector,$condicion)
    {
        $sql = DB::table('reportes.vw_contribuyentes_condicion')->where('anio',$anio)->where('id_sect',$sector)->where('id_cond_exonerac',$condicion)->get();
        
        if($sql)
        {
            $view = \View::make('reportes_gonzalo.reportes.reporte_contribuyentes_exonerados', compact('sql'))->render();
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
        $sql = DB::table('reportes.vw_contribuyentes_condicion')->where('anio',$anio)->where('id_sect',$sector)->where(function($sql) {
            $sql->where('id_cond_exonerac', 4)
                ->orWhere('id_cond_exonerac', 5);
        })->get();

        if(count($sql)>0)
        {
            $view =  \View::make('reportes_gonzalo.reportes.reporte_cantidad_contribuyente', compact('sql'))->render();
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
        
        if(count($sql)>0)
        {
            $view =  \View::make('reportes_gonzalo.reportes.reporte_get_usuarios', compact('sql','total','fechainicio','fechafin'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4','landscape');
            return $pdf->stream("PRUEBA".".pdf");
        }
        else
        {
            return 'NO HAY RESULTADOS';
        }
    }
    
    public function reporte_contribuyentes_predios_zonas($anio,$sector)
    {
        
        if($anio != 0 && $sector == 0){
            
        \Excel::create('REPORTE DE CANTIDAD DE CONTRIBUYENTES Y PREDIOS POR ZONA', function($excel) use ( $anio ) {
            
            $excel->sheet('CONTRIBUYENTES', function($sheet) use ( $anio ) {
                
                $sql = DB::select("select nro_doc, contribuyente, cond_prop_descripc, dom_fis, (coalesce(cod_via, '') || ' ' || coalesce(nom_via, '') || ' ' || coalesce(nro_mun, '') || ' ' || coalesce(referencia, '')) as predio from adm_tri.vw_predi_urba where anio = '$anio'" );
                
                $data= json_decode( json_encode($sql), true);
                
                $sheet->fromArray($data);
                $sheet->row(1, array("DNI/RUC", "NOMBRE", "TIPO CONTRIBUYENTE", "DOMICILIO FISCAL", "LISTA DE PREDIOS"))->freezeFirstRow();
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
        else
        {
        
        $sql=DB::table('adm_tri.vw_predi_urba')->where('anio',$anio)->where('id_sec',$sector)->orderBy('id_contrib')->get();
        
        
        $nro_sectores = DB::select("select count(distinct id_contrib) as total from adm_tri.vw_predi_urba where id_sec = '$sector' ");
       
        $total = DB::select("select count(id_contrib) as total from adm_tri.vw_predi_urba where id_sec = '$sector' ");
        
        }
        
        if(count($sql)>0)
        {
            $view =  \View::make('reportes_gonzalo.reportes.reporte_contribuyentes_predios_zonas', compact('sql','anio','sector','nro_sectores','total'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4','landscape');
            return $pdf->stream("Reporte Contribuyentes y Predios por Zonas".".pdf");
        }
        else
        {
            return 'No hay datos';
        }
    }
    
    public function reporte_emision_predial($anio,$sector,$uso)
    {
        if($anio != 0 && $sector == 0 && $uso == 0){
            
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
        
        }elseif($anio != 0 && $sector != 0 && $uso == 0){
            \Excel::create('REPORTE DE EMISION PREDIAL POR USO', function($excel) use ( $anio, $sector ) {
            
            $excel->sheet('CONTRIBUYENTES', function($sheet) use ( $anio, $sector ) {
                
                $sql = DB::select("select pers_nro_doc, contribuyente, (coalesce(dir_pred, '') || ' ' || coalesce(referencia, '')) as domicilio, uso_arbitrio from reportes.vw_predios_tipo_uso_arb where anio = '$anio' and sec = '$sector' " );
                
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
        else
        {

        $sql = DB::table('reportes.vw_predios_tipo_uso_arb')->where('anio',$anio)->where('id_sec',$sector)->where('id_uso_arb',$uso)->get();
        
        $nombre_uso = DB::select("select uso_arbitrio from reportes.vw_predios_tipo_uso_arb where id_uso_arb = '$uso' ");
        
        $total = DB::select("select count(uso_arbitrio) as usos from reportes.vw_predios_tipo_uso_arb where id_uso_arb = '$uso' and id_sec='$sector'");
        
        }
        
        if(count($sql)>0)
        {
            $view =  \View::make('reportes_gonzalo.reportes.reporte_emision_predial', compact('sql','anio','sector','nombre_uso','total'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("Listado de Contribuyentes".".pdf");
        }
        else
        {
            return 'No hay datos';
        }
    }
    
    public function reporte_cant_cont_ded_mont_bas_imp($anio,$sector,$condicion)
    {
        if($anio != 0 && $sector == 0 && $condicion == 0){
            
        \Excel::create('Cantidad de contribuyentes por Condicion(Afecto, Inafecto, Exoneracion Parcial, Pensionista y Adulto mayor)', function($excel) use ( $anio ) {
            
            $excel->sheet('CONTRIBUYENTES', function($sheet) use ( $anio ) {
                
                $sql = DB::select("select pers_nro_doc, contribuyente, dom_fis, porctje, desc_exon, sec, base_impon from reportes.vw_por_tipo_exoneracion where anio = '$anio'" );
                
                $data= json_decode( json_encode($sql), true);
                
                $sheet->fromArray($data);
                $sheet->row(1, array("DNI", "NOMBRE", "DOMICILIO FISCAL", "DEDUCCION", "CONDICION", "SECTOR", "BASE IMPONIBLE"))->freezeFirstRow();
                $sheet->setWidth(array(
                    'A'     =>  15,
                    'B'     =>  50,
                    'C'     =>  100,
                    'D'     =>  30,
                    'E'     =>  30,
                    'F'     =>  20,
                    'G'     =>  20
                ));
            });
        })->export('xls');
        
        }
        else{
        
        $sql = DB::table('reportes.vw_por_tipo_exoneracion')->where('anio',$anio)->where('id_sec',$sector)->where('id_cond_exonerac',$condicion)->get();
        
        $nombre_condicion = DB::select("select desc_exon from reportes.vw_por_tipo_exoneracion where id_cond_exonerac = '$condicion' ");
        
        $total = DB::select("select count(desc_exon) as condiciones from reportes.vw_por_tipo_exoneracion where id_cond_exonerac = '$condicion' and id_sec = '$sector'");
        
        }
        
        if(count($sql)>0)
        {
            $view =  \View::make('reportes_gonzalo.reportes.reporte_cant_cont_ded_mont_bas_imp', compact('sql','anio','sector','nombre_condicion','total'))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view)->setPaper('a4');
            return $pdf->stream("Listado de Contribuyentes".".pdf");
        }
        else
        {
            return 'No hay datos';
        }
    }
}
