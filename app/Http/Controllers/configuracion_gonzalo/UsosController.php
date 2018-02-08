<?php

namespace App\Http\Controllers\configuracion_gonzalo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\configuracion\UsosCatastro;

class UsosController extends Controller
{

    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_usos_catastrales' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        
        return view('configuracion_gonzalo/vw_usos_catastrales', compact('menu','permisos'));
    }

    public function create(Request $request){
        
        $UsosCatastro = new UsosCatastro;
        
            $UsosCatastro->codi_uso = $request['codi_uso'];
            $UsosCatastro->desc_uso = $request['desc_uso'];
            $UsosCatastro->save();

            return $UsosCatastro->id_uso;
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
       $Uso_catastro = DB::table('catastro.vw_uso_predio')->where('id_uso',$id)->get();
       return $Uso_catastro;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        $UsosCatastro = new UsosCatastro;
        $val=  $UsosCatastro::where("id_uso","=",$id )->first();
        if(count($val)>=1)
        {
            $val->codi_uso = $request['codi_uso'];
            $val->desc_uso = $request['desc_uso'];
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
        $UsosCatastro = new UsosCatastro;
        $val=  $UsosCatastro::where("id_uso","=",$request['id_uso'] )->first();
        if(count($val)>=1)
        {
            $val->delete();
        }
        return "destroy ".$request['id_uso'];
    }
    
    
    public function getUsosCatastrales(Request $request){
        header('Content-type: application/json');

        //$totalg = DB::select("select count(id_tributo) as total from presupuesto.vw_tributos_vladi_1");
        $totalg = DB::select("select count(id_uso) as total from catastro.vw_uso_predio");
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

        $sql = DB::table('catastro.vw_uso_predio')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;

        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_uso;
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_uso),
                trim($Datos->codi_uso),
                trim($Datos->desc_uso),
            );
        }

        return response()->json($Lista);

    }
    
}
