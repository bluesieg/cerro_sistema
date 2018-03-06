<?php

namespace App\Http\Controllers\recaudacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\recaudacion\Beneficios_tributarios;

class Beneficios_TributariosController extends Controller
{

    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_beneficios_tributarios' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio = DB::select('SELECT anio FROM adm_tri.uit order by anio desc');
        return view('recaudacion/vw_beneficios_tributarios', compact('menu','permisos','anio'));
    }

    public function create(Request $request)
    {
        $beneficios_tributarios = new Beneficios_tributarios;

        $beneficios_tributarios->documento = $request['documento'];
        $beneficios_tributarios->descuento = $request['descuento'];
        $beneficios_tributarios->fecha_emision = $request['f_ini_vigencia'];
        $beneficios_tributarios->inicio_vigencia = $request['f_ini_vigencia'];
        $beneficios_tributarios->fin_vigencia = $request['f_fin_vigencia'];
        $beneficios_tributarios->tim = $request['tim'];
        $beneficios_tributarios->multas = $request['multas'];
        $beneficios_tributarios->save();
        return $beneficios_tributarios->id_bene_trib;
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
       $beneficios_tributarios = DB::table('recaudacion.vw_beneficios_tributarios')->where('id_bene_trib',$id)->get();
       return $beneficios_tributarios;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $beneficios_tributarios = new Beneficios_tributarios;
        $val=  $beneficios_tributarios::where("id_bene_trib","=",$id )->first();
        if(count($val)>=1)
        {
            $val->documento = $request['documento'];
            $val->descuento = $request['descuento'];
            $val->fecha_emision = $request['f_emision'];
            $val->inicio_vigencia = $request['f_ini_vigencia'];
            $val->fin_vigencia = $request['f_fin_vigencia'];
            $val->tim = $request['tim'];
            $val->multas = $request['multas'];
            $val->save();
        }
        return $id;
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
    public function destroy(Request $request)
    {
        $beneficios_tributarios = new Beneficios_tributarios;
        $val=  $beneficios_tributarios::where("id_bene_trib","=",$request['id_ben_trib'] )->first();
        if(count($val)>=1)
        {
            $val->delete();
        }
        return "destroy ".$request['id_ben_trib'];
    }
    
    public function getBeneficiosTributarios(Request $request){
        header('Content-type: application/json');

        $totalg = DB::select("select count(*) as total from recaudacion.vw_beneficios_tributarios");
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
        $start = ($limit * $page) - $limit; 
        if ($start < 0) {
            $start = 0;
        }

     

        $sql = DB::table('recaudacion.vw_beneficios_tributarios')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;

        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_bene_trib;
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_bene_trib),
                trim($Datos->documento),
                trim($Datos->descuento),
                trim($Datos->fecha_emision),
            );
        }

        return response()->json($Lista);

    }
    
}
