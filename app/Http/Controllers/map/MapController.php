<?php

namespace App\Http\Controllers\map;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MapController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        return view('cartografia/cartografia_predios', compact('sectores','anio_tra','menu'));
    }

    function get_manzanas(Request $request){
  
        $where="";
        if($request['sector']!='0')
        {
           $where="where id_sect=".$request['sector']; 
        }
        $mznas = DB::select("SELECT json_build_object(
                            'type',     'FeatureCollection',
                            'features', json_agg(feature)
                        )
                        FROM (
                          SELECT json_build_object(
                            'type',       'Feature',
                            'id_mzna',         id_mzna,
                            'geometry',   ST_AsGeoJSON(ST_Transform (geom, 4326))::json,
                            'properties', json_build_object(
                                'codi_mzna', codi_mzna
                             )
                          ) AS feature
                          FROM (SELECT * FROM catastro.manzanas $where) row) features;");

        return response()->json($mznas);
    }
    
    function get_limites(){

        $limites =  DB::select("SELECT json_build_object(
                            'type',     'FeatureCollection',
                            'features', json_agg(feature)
                        )
                        FROM (
                          SELECT json_build_object(
                            'type',       'Feature',
                            'geometry',   ST_AsGeoJSON(ST_Transform (geom, 4326))::json,
                            'properties', json_build_object(
                               'gid', gid
                             )
                          ) AS feature
                          FROM (SELECT * FROM catastro.limites) row) features;");

        return response()->json($limites);

        /*
        $limites =>select("SELECT json_build_object(
                            'type',     'FeatureCollection',
                            'features', json_agg(feature)
                        )
                        FROM (
                          SELECT json_build_object(
                            'type',       'Feature',
                            'id',         gid,
                            'geometry',   ST_AsGeoJSON(ST_Transform (geom, 4326))::json,
                            'properties', json_build_object(
                               'gid', gid,
                                'layer', layer,
                                'doctype', doctype
                             )
                          ) AS feature
                          FROM (SELECT * FROM mdcc_2017.limites_distritales) row) features;");

        return response()->json($limites);*/
    }
    function get_sectores(){

        $sectores = DB::select("SELECT json_build_object(
                            'type',     'FeatureCollection',
                            'features', json_agg(feature)
                        )
                        FROM (
                          SELECT json_build_object(
                            'type',       'Feature',
                            'id_sec',         id_sec,
                            'geometry',   ST_AsGeoJSON(ST_Transform (geom, 4326))::json,
                            'properties', json_build_object(
                                'sector', sector
                             )
                          ) AS feature
                          FROM (SELECT * FROM catastro.sectores) row) features;");

        return response()->json($sectores);

        /*
        $sectores = DB::connection('mapa')->select("SELECT json_build_object(
                            'type',     'FeatureCollection',
                            'features', json_agg(feature)
                        )
                        FROM (
                          SELECT json_build_object(
                            'type',       'Feature',
                            'id',         gid,
                            'geometry',   ST_AsGeoJSON(ST_Transform (geom, 4326))::json,
                            'properties', json_build_object(
                               'gid', gid,
                                'entity', entity,
                                'codigo', codigo,
                                'sector', sector
                             )
                          ) AS feature
                          FROM (SELECT * FROM mdcc_2017.sectores_cat) row) features;");

        return response()->json($sectores);*/
    }

    function get_lotes_x_sector(Request $req){


        $lotes = DB::select("SELECT json_build_object(
                            'type',     'FeatureCollection',
                            'features', json_agg(feature)
                        )
                        FROM (
                          SELECT json_build_object(
                            'type',       'Feature',
                            'id_lote',         id_lote,
                            'geometry',   ST_AsGeoJSON(ST_Transform (geom, 4326))::json,
                            'properties', json_build_object(
                               'id_lote', id_lote,
                                'id_mzna', id_mzna,
                                'codi_lote', codi_lote,
                                'id_hab_urb', id_hab_urb,
                                'id_sect',id_sect,
                                'codi_mzna',codi_mzna,
                                'sector',sector
                             )
                          ) AS feature
                          FROM (select l.id_lote, l.id_mzna,m.codi_mzna,s.sector, l.codi_lote, l.id_hab_urb, l.geom, m.id_sect from  catastro.lotes l
                                join catastro.manzanas m on m.id_mzna = l.id_mzna
                                join catastro.sectores s on s.id_sec=m.id_sect where id_hab_urb = '".$req->codigo."') row) features ;");

        return response()->json($lotes);
        /*
        $lotes = DB::select("SELECT json_build_object(
                            'type',     'FeatureCollection',
                            'features', json_agg(feature)
                        )
                        FROM (
                          SELECT json_build_object(
                            'type',       'Feature',
                            'id',         gid,
                            'geometry',   ST_AsGeoJSON(ST_Transform (geom, 4326))::json,
                            'properties', json_build_object(
                               'gid', gid,
                                'cod_mza', cod_mza,
                                'mz_urb', mz_urb,
                                'cod_sect', cod_sect,
                                'nom_lote',nom_lote,
                                'cod_habi',cod_habi,
                                'habilit',habilit,
                                'sec_mzna',sec_mzna,
                                'cod_lote',cod_lote
                             )
                          ) AS feature
                          FROM (SELECT * FROM mdcc_2017.lotes where cod_sect = '".$req->codigo."') row) features;");

        return response()->json($lotes);*/
    }


    function get_centro_sector(Request $reques){
        //dd($reques->codigo);
        /*
        $centro_sector = DB::select("SELECT ST_X(ST_Centroid(ST_Transform (geom, 4326))) lat,ST_Y(ST_Centroid(ST_Transform (geom, 4326))) lon  from mdcc_2017.sectores_cat where codigo = '" . $reques->codigo . "'");
        return response()->json($centro_sector);*/
        $centro_sector = DB::select("SELECT ST_X(ST_Centroid(ST_Transform (geom, 4326))) lat,ST_Y(ST_Centroid(ST_Transform (geom, 4326))) lon  from catastro.sectores where id_sec = '" . $reques->codigo . "'");
        return response()->json($centro_sector);

    }

    function mznas_x_sector(Request $req){
        $mznas=DB::select("SELECT id_mzna, codi_mzna FROM catastro.manzanas where id_sect = '". $req->codigo."';");

        return view("principal/fpart/vw_select_mznas", compact('mznas'));

        /*
        $mznas=DB::select("SELECT gid, mz_cat FROM mdcc_2017.manzanas where sector_cat = '". $req->codigo."';");
        return view("principal/fpart/vw_select_mznas", compact('mznas'));*/
        //return view('catastro/vw_part_dlg_new_memoria_descriptiva', compact('mznas'));
    }

    function get_hab_urb($id){
        $where="";
        if($id>0)
        {
            $where='where id_hab_urb='.$id;
        }
       
        $sectores = DB::select("SELECT json_build_object(
                            'type',     'FeatureCollection',
                            'features', json_agg(feature)
                        )
                        FROM (
                          SELECT json_build_object(
                            'type',       'Feature',
                            'id_hab_urb',         id_hab_urb,
                            'geometry',   ST_AsGeoJSON(ST_Transform (geom, 4326))::json,
                            'properties', json_build_object(
                              'codi_hab_urba',codi_hab_urba,
                              'nomb_hab_urba',nomb_hab_urba
                 
                             )
                          ) AS feature
                          FROM (SELECT * FROM catastro.hab_urb ".$where.") row) features;");

        return response()->json($sectores);
    }

    function geogetmznas_x_sector(Request $req){

        $mznas = DB::select("SELECT json_build_object(
                            'type',     'FeatureCollection',
                            'features', json_agg(feature)
                        )
                        FROM (
                          SELECT json_build_object(
                            'type',       'Feature',
                            'id_mzna',         id_mzna,
                            'geometry',   ST_AsGeoJSON(ST_Transform (geom, 4326))::json,
                            'properties', json_build_object(
                               'id_mzna', id_mzna,
                                'id_sect', id_sect,
                                'codi_mzna', codi_mzna
                             )
                          ) AS feature
                          FROM (SELECT * FROM catastro.manzanas where id_sect = '".$req->codigo."') row) features;");

        return response()->json($mznas);
        /*
        $mznas = DB::connection('mapa')->select("SELECT json_build_object(
                            'type',     'FeatureCollection',
                            'features', json_agg(feature)
                        )
                        FROM (
                          SELECT json_build_object(
                            'type',       'Feature',
                            'id',         gid,
                            'geometry',   ST_AsGeoJSON(ST_Transform (geom, 4326))::json,
                            'properties', json_build_object(
                               'gid', gid,
                                'mz_cat', mz_cat,
                                'mz_urb', mz_urb,
                                'sector_cat', sector_cat,
                                'aprobacion', aprobacion,
                                'cod_hab',cod_hab,
                                'nombre', nombre,
                                'jurisdicci', jurisdicci
                             )
                          ) AS feature
                          FROM (SELECT * FROM mdcc_2017.manzanas where sector_cat = '".$req->codigo."') row) features;");
*/
        return response()->json($mznas);
    }

    function get_predios_rentas(Request $req){

        $predios = DB::select("SELECT json_build_object(
                            'type',     'FeatureCollection',
                            'features', json_agg(feature)
                        )
                        FROM (
                          SELECT json_build_object(
                            'type',       'Feature',
                            'id_lote',         id_lote,
                            'geometry',   ST_AsGeoJSON(ST_Transform (geom, 4326))::json,
                            'properties', json_build_object(
                               'id_lote', id_lote,
                                'id_mzna', id_mzna,
                                'codi_lote', codi_lote,
                                'id_hab_urb', id_hab_urb,
                                'id_sect',id_sect,
                                'codi_mzna',codi_mzna,
                                'sector',sector
                             )
                          ) AS feature
                          FROM (SELECT * from catastro.vw_lotes_by_habiltacion where id_hab_urb = '".$req->codigo."' and anio='".$req->anio."' and flg_act=1) row) features;");

        return response()->json($predios);

        /*
        $predios = DB::select("SELECT json_build_object(
                            'type',     'FeatureCollection',
                            'features', json_agg(feature)
                        )
                        FROM (
                          SELECT json_build_object(
                            'type',       'Feature',
                            'id',         gid,
                            'geometry',   ST_AsGeoJSON(ST_Transform (geom, 4326))::json,
                            'properties', json_build_object(
                               'gid', gid,
                                'cod_mza', cod_mza,
                                'mz_urb', mz_urb,
                                'cod_sect', cod_sect,
                                'nom_lote',nom_lote,
                                'cod_habi',cod_habi,
                                'habilit',habilit,
                                'sec_mzna',sec_mzna,
                                'cod_lote',cod_lote
                             )
                          ) AS feature
                          FROM (SELECT lotes.gid, lotes.layer, lotes.cod_mza, lotes.mz_urb, lotes.cod_sect, lotes.nom_lote, lotes.cod_habi, lotes.habilit,
                                   lotes.sec_mzna, lotes.cod_lote, lotes.geom FROM mdcc_2017.lotes lotes
                                    join
 (Select * from  dblink(
'dbname=catastro_rentas2 host=172.25.8.18 user=mdcc password=123456',
'SELECT sec, mzna, lote FROM adm_tri.predios')
 AS tb1(sec1 CHARACTER VARYING,mzna1 CHARACTER VARYING,lote1 CHARACTER VARYING)) as tb1
                                    on tb1.sec1 = lotes.cod_sect and tb1.mzna1 = lotes.cod_mza and tb1.lote1 = lotes.cod_lote where cod_sect = '".$req->codigo ."' ) row) features;");
        return response()->json($predios);*/
    }
    
    
    function get_agencias(){

        $agencias = DB::select("SELECT json_build_object(
                            'type',     'FeatureCollection',
                            'features', json_agg(feature)
                        )
                        FROM (
                          SELECT json_build_object(
                            'type',       'Feature',
                            'id ',         id ,
                            'geometry',   ST_AsGeoJSON(ST_Transform (geom, 4326))::json,
                            'properties', json_build_object(
                                'gid',gid,
                                'agencia', agencia
                             )
                          ) AS feature
                          FROM (SELECT * FROM catastro.agencia_mun) row) features;");

        return response()->json($agencias);

      
    }
    
    function get_agencias_polygono(){

        $agencias = DB::select("SELECT json_build_object(
                            'type',     'FeatureCollection',
                            'features', json_agg(feature)
                        )
                        FROM (
                          SELECT json_build_object(
                            'type',       'Feature',
                            'geometry',   ST_AsGeoJSON(ST_Transform (geom, 4326))::json,
                            'properties', json_build_object(
                                'gid',gid,
                                'layer', layer,
                                'text', text
                             )
                          ) AS feature
                          FROM (SELECT * FROM catastro.juridicc_agenc) row) features;");

        return response()->json($agencias);

      
    }
    
    
    function get_camaras(){

        $camaras = DB::select("SELECT json_build_object(
                            'type',     'FeatureCollection',
                            'features', json_agg(feature)
                        )
                        FROM (
                          SELECT json_build_object(
                            'type',       
                            'Feature',
                            'geometry',   ST_AsGeoJSON(ST_Transform (geom, 4326))::json,
                            'properties', json_build_object(
                                'layer',layer,
                                'gid',id
                             )
                          ) AS feature
                          FROM (SELECT * FROM catastro.camaras) row) features;");

        return response()->json($camaras);

      
    }
    
    function get_vias(){
        $vias = DB::select("SELECT json_build_object(
                            'type',     'FeatureCollection',
                            'features', json_agg(feature)
                        )
                        FROM (
                          SELECT json_build_object(
                            'type',       'Feature',
                            'geometry',   ST_AsGeoJSON(ST_Transform (geom, 4326))::json,
                            'properties', json_build_object(
                                'gid',gid,
                                'cod_via', cod_via,
                                'result', result
                             )
                          ) AS feature
                          FROM (SELECT * FROM catastro.vias_geom) row) features;");

        return response()->json($vias);
    }
    
    function get_z_urbana()
    {

        $agencias = DB::select("SELECT json_build_object(
                            'type',     'FeatureCollection',
                            'features', json_agg(feature)
                        )
                        FROM (
                          SELECT json_build_object(
                            'type',       'Feature',
                            'geometry',   ST_AsGeoJSON(ST_Transform (geom, 4326))::json,
                            'properties', json_build_object(
                                'gid',gid,
                                'zona', zona
                             )
                          ) AS feature
                          FROM (SELECT * FROM catastro.zon_terr where zona='ZONA URBANA') row) features;");

        return response()->json($agencias);

      
    }
    function get_z_agricola()
    {

        $agencias = DB::select("SELECT json_build_object(
                            'type',     'FeatureCollection',
                            'features', json_agg(feature)
                        )
                        FROM (
                          SELECT json_build_object(
                            'type',       'Feature',
                            'geometry',   ST_AsGeoJSON(ST_Transform (geom, 4326))::json,
                            'properties', json_build_object(
                                'gid',gid,
                                'zona', zona
                             )
                          ) AS feature
                          FROM (SELECT * FROM catastro.zon_terr where zona='ZONA AGRICOLA') row) features;");

        return response()->json($agencias);
    }
    function get_z_eriaza()
    {

        $agencias = DB::select("SELECT json_build_object(
                            'type',     'FeatureCollection',
                            'features', json_agg(feature)
                        )
                        FROM (
                          SELECT json_build_object(
                            'type',       'Feature',
                            'geometry',   ST_AsGeoJSON(ST_Transform (geom, 4326))::json,
                            'properties', json_build_object(
                                'gid',gid,
                                'zona', zona
                             )
                          ) AS feature
                          FROM (SELECT * FROM catastro.zon_terr where zona='ZONA ERIAZA') row) features;");

        return response()->json($agencias);
    }
    function get_aportes()
    {

        $agencias = DB::select("SELECT json_build_object(
                            'type',     'FeatureCollection',
                            'features', json_agg(feature)
                        )
                        FROM (
                          SELECT json_build_object(
                            'type',       'Feature',
                            'geometry',   ST_AsGeoJSON(ST_Transform (geom, 4326))::json,
                            'properties', json_build_object(
                                'gid',gid,
                                'layer', layer,
                                'ocupacion', ocupacion
                             )
                          ) AS feature
                          FROM (SELECT * FROM catastro.aporte) row) features;");

        return response()->json($agencias);
    }
}
