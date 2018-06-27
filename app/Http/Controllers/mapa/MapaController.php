<?php

namespace App\Http\Controllers\mapa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MapaController extends Controller
{

    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_map_cris' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $sectores = DB::select('SELECT  id_sec, sector FROM catastro.sectores order by sector asc;');
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        return view('cartografia/mapa_cris', compact('sectores','anio_tra','menu','permisos'));
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
                               'gid', gid,
                               'area_km2', area_km2,
                               'perimetro', perimetro,
                               'poblacion', poblacion,
                               'lim_norte', lim_norte,
                               'lim_sur', lim_sur,
                               'lim_este', lim_este,
                               'lim_oeste', lim_oeste,
                               'creacion', creacion
                             )
                          ) AS feature
                          FROM (SELECT * FROM catastro.limites) row) features;");

        return response()->json($limites);

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
    public function get_lotes_x_hab_urb(Request $req){


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
    }
}
