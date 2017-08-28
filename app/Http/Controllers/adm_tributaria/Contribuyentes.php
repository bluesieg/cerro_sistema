<?php

namespace App\Http\Controllers\adm_tributaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Contribuyentes extends Controller
{
    public function vw_contribuyentes() {
        $tip_contrib=DB::select('select * from adm_tri.tipo_contribuyente');
        return view('adm_tributaria/vw_contribuyentes',compact('tip_contrib'));
    }
    
    public function index() {
        header('Content-type: application/json');

        }
    public function get_cotrib_op(Request $request)  
    {
        if($request['dat']=='0'&&$request['sec']=='0'&&$request['manz'])
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
            if($request['sec']==0)
            {
                $totalg = DB::select('select count(id_pers) as total from adm_tri.vw_contribuyentes_vias where id_pers='.$request['dat']);
                $sql = DB::table('adm_tri.vw_contribuyentes_vias')->where('id_pers',$request['dat'])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
            }
            else
            {
                $totalg = DB::select("select count(*) as total from adm_tri.vw_contribuyentes_po where sec='".$request['sec']."' and mzna='".$request['manz']."'");
                $sql = DB::table('adm_tri.vw_contribuyentes_po')->where('sec',$request['sec'])->where('mzna',$request['manz'])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
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
                $Lista->rows[$Index]['id'] = $Datos->id_pers;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_pers),
                    trim($Datos->id_persona),
                    trim($Datos->tipo_doc),
                    trim($Datos->nro_doc),
                    trim(str_replace("-", "",$Datos->contribuyente)), 
                );
            }
            return response()->json($Lista);
        }
    }


    function llenar_form_contribuyentes(Request $request){
        
        $Consulta = DB::table('adm_tri.contribuyentes')->where('id_pers',$request['id_pers'])->get();
        $Lista=new \stdClass();
        foreach($Consulta as $Datos){                     
            $Lista->id_persona      =  trim($Datos->id_persona);
            $Lista->nro_doc         =  trim($Datos->nro_doc);
            $Lista->ape_pat         =  trim($Datos->ape_pat);
            $Lista->ape_mat         =  trim($Datos->ape_mat);
            $Lista->nombres         =  trim($Datos->nombres);
            $Lista->sexo            =  trim($Datos->sexo);
            $Lista->fnac            =  date('d-m-Y',strtotime($Datos->fnac));
            $Lista->tipo_persona    =  trim($Datos->tipo_persona);
            $Lista->tipo_doc        =  trim($Datos->tipo_doc);
            $Lista->raz_soc         =  trim($Datos->raz_soc);
            $Lista->tlfno_fijo      =  trim($Datos->tlfno_fijo);
            $Lista->tlfono_celular  =  trim($Datos->tlfono_celular);
            $Lista->email           =  trim($Datos->email);
            $Lista->dom_fiscal      =  trim($Datos->dom_fiscal);
            $Lista->est_civil       =  trim($Datos->est_civil);
            $Lista->nro_doc_conv    =  trim($Datos->nro_doc_conv);
            $Lista->conviviente     =  trim($Datos->conviviente);
            $Lista->id_prov         =  trim($Datos->id_prov);
            $Lista->id_dist         =  trim($Datos->id_dist);
            $Lista->nro_mun         =  trim($Datos->nro_mun);
            $Lista->dpto            =  trim($Datos->dpto);
            $Lista->manz            =  trim($Datos->manz);
            $Lista->lote            =  trim($Datos->lote);
            $Lista->activo          =  trim($Datos->activo);
            $Lista->id_dpto         =  trim($Datos->id_dpto);
            $Lista->id_cond_exonerac=  trim($Datos->id_cond_exonerac);
            $Lista->id_via          =  trim($Datos->id_via);
            $Lista->tip_doc_conv    =  trim($Datos->tip_doc_conv);            
        }        
        return response()->json($Lista);
    }
    
    public function insert_new_contribuyente(Request $request){
        header('Content-type: application/json');
        
        $data = $request->all();        
        $id_persona = $request['tipo_persona'].$request['tipo_doc'].$request['nro_doc'];
        $data['id_persona'] = $id_persona;
//        $data['dpto'] = '0';
        
        $insert=DB::table('adm_tri.contribuyentes')->insert($data);
        
        if ($insert) return response()->json($data);
        else return false;
    }
    
    function eliminar_contribuyente(Request $request){
//        $user = DB::table('adm_tri.contribuyentes')->select('usuario')->where('id_pers', '=', $request['id'])->get();
        $delete = DB::table('adm_tri.contribuyentes')->where('id_pers', $request['id'])->delete();

        if ($delete) {
            return response()->json([
                        'msg' => 'si',
            ]);
        }
    }
    
    function modificar_contribuyente(Request $request) {
        $data = $request->all(); 
        unset($data['id_pers']);
        $id_persona = $request['tipo_persona'].$request['tipo_doc'].$request['nro_doc'];
        $data['id_persona'] = $id_persona;
        $update=DB::table('adm_tri.contribuyentes')->where('id_pers',$request['id_pers'])->update($data);
        if ($update){  
            return response()->json([
                        'msg' => 'si',
            ]);
        }else return false;
    }
    
    function get_autocomplete_contrib(Request $request) {
        if($request['doc']==0)
        {
            $Consulta = DB::table('adm_tri.vw_contribuyentes')->where('id_persona', $request['cod'])->get();
        }
        else
        {
            $Consulta = DB::table('adm_tri.vw_contribuyentes')->where('nro_doc', $request['doc'])->get();
        }
        if (isset($Consulta[0]->id_pers)) {
            return response()->json([
                        'msg' => 'si',
                        'id_pers' => $Consulta[0]->id_pers,
                        'contribuyente' => $Consulta[0]->contribuyente,
                        'dom_fiscal' => $Consulta[0]->dom_fiscal,
                        'nro_doc' => $Consulta[0]->nro_doc,
                        'nro_doc_conv' => $Consulta[0]->nro_doc_conv,
                        'conviviente' => $Consulta[0]->conviviente,
            ]);
        } else {
            return response()->json([
                        'msg' => 'no',
            ]);
        }
    }
    function get_predios_contrib($id) {
        return DB::select("select id_pred,sec,mzna,lote,nom_via,nro_mun,mzna_dist,lote_dist,base_impon from adm_tri.vw_predi_urba where id_contrib=$id and anio='".date("Y")."'");

    }
}
