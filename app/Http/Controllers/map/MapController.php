<?php

namespace App\Http\Controllers\map;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MapController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
//       if (Auth::check())
//        {
//            return view('home');
//        }
//        else return view('auth/login');

        //$lotes = DB::select('SELECT ST_AsGeoJSON(geometria) from catastro.lotes;');
        $sectores = DB::connection('mapa')->select('SELECT gid, entity, codigo, sector FROM mdcc_2017.sectores_cat order by codigo asc;');

        return view('cartografia/cartografia_predios', compact('sectores'));
    }

    function get_manzanas(){
        //$lotes = DB::select('select ST_AsGeoJSON(geom) geometry from espacial.manzanas');
/*
        $manzanas = DB::select(" SELECT json_build_object(
                'type',       'Feature',
                'id',         gid,
                'properties', json_build_object(
                   'gid', gid,
                    'cod_sect', cod_sect,
                    'cod_mza', cod_mza,
                    'mza_urb', mza_urb
                 ),
                 'geometry',   ST_AsGeoJSON(geom)::json
                  ) features
                  FROM espacial.manzanas limit 10;");*/


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
                          FROM (SELECT * FROM mdcc_2017.manzanas) row) features;");

        return response()->json($mznas);
    }
    function get_limites(){
        $limites = DB::connection('mapa')->select("SELECT json_build_object(
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

        return response()->json($limites);
    }
    function get_sectores(){

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

        return response()->json($sectores);
    }

    function get_lotes_x_sector(Request $req){

        $lotes = DB::connection('mapa')->select("SELECT json_build_object(
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

        return response()->json($lotes);
    }


    function get_centro_sector(Request $reques){
        //dd($reques->codigo);
        $centro_sector = DB::connection('mapa')->select("SELECT ST_X(ST_Centroid(ST_Transform (geom, 4326))) lat,ST_Y(ST_Centroid(ST_Transform (geom, 4326))) lon  from mdcc_2017.sectores_cat where codigo = '" . $reques->codigo . "'");
        return response()->json($centro_sector);
    }

    function mznas_x_sector(Request $req){
        $mznas=DB::connection('mapa')->select("SELECT gid, mz_cat FROM mdcc_2017.manzanas where sector_cat = '". $req->codigo."';");

        return view("principal/fpart/vw_select_mznas", compact('mznas'));
        //return view('catastro/vw_part_dlg_new_memoria_descriptiva', compact('mznas'));
    }

    function geogetmznas_x_sector(Request $req){
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

        return response()->json($mznas);
    }

    function get_predios_rentas(Request $req){
        $predios = DB::connection('mapa')->select("SELECT json_build_object(
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
        return response()->json($predios);
    }

}
