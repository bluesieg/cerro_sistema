<?php

namespace App\Http\Controllers\configuracion_gonzalo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\usuarios_web\Usuarios_web;

class UsuariosWebController extends Controller
{

    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_usuarios_web' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio = DB::select('SELECT anio FROM adm_tri.uit order by anio desc');
        return view('configuracion_gonzalo/vw_usuarios_web', compact('menu','permisos','anio'));
    }

    public function create(Request $request)
    {
        $data = new Usuarios_web;
        $data->usuario          = strtoupper($request['usuario']);
        $data->password         = bcrypt($request['password']);
        $data->id_contrib       = $request['id_contrib'];
        $data->nro_documento    = $request['nro_documento'];
        $data->contribuyente    = $request['contribuyente'];
        $data->cod_contribuyente= $request['cod_contribuyente'];
        $data->tipo_persona     = $request['tipo_persona'];
        $data->condicion        = $request['condicion'];
        $data->domicilio_fiscal = $request['domicilio_fiscal'];
        $data->save();
        return $data->id;
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
       $tim = DB::table('fraccionamiento.tim')->where('id_tim',$id)->get();
       return $tim;
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
    
    public function insertar_nuevo_usuario(Request $request){
       
        
    }
    
    function modificar_tim(Request $request) {
        $data = $request->all();
        unset($data['id_tim']);
        $update=DB::table('fraccionamiento.tim')->where('id_tim',$request['id_tim'])->update($data);
        if ($update){
            return response()->json([
                'msg' => 'si',
            ]);
        }else return false;
    }
    
    function eliminar_tim(Request $request){
        $delete = DB::table('fraccionamiento.tim')->where('id_tim', $request['id_tim'])->delete();

        if ($delete) {
            return response()->json([
                'msg' => 'si',
            ]);
        }
    }
    
    public function get_usuarios_web(Request $request){
        header('Content-type: application/json');

        $totalg = DB::select("select count(id) as total from web.usuarios_web ");
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

     

        $sql = DB::table('web.usuarios_web')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
        
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;

        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id;
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id),
                trim($Datos->usuario),
                trim($Datos->cod_contribuyente),
                trim($Datos->contribuyente),
            );
        }

        return response()->json($Lista);

    }
    
    
    
    
    
    
     public function get_contribuyente(Request $request) 
    {
        if($request['dat']=='0')
        {
            return 0;
        }
        else
        {
        header('Content-type: application/json');
        $totalg = DB::select("select count(id_contrib) as total from configuracion.vw_usuarios_web where contribuyente like '%".$request['dat']."%'");
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

        $sql = DB::table('configuracion.vw_usuarios_web')->where('contribuyente','like', '%'.strtoupper($request['dat']).'%')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
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
                trim($Datos->id_persona),
                trim($Datos->tipo_persona),
                trim($Datos->condicion),
                trim($Datos->domic_fiscal)
            );
        }
        return response()->json($Lista);
        }
    }
    
    function encripta_pass($c){        
        $tam=strlen($c)-1;
        
        $array = str_split($c);
        
        $ch=$array[0];
        $array[0]=$array[$tam];
        $array[$tam]=$ch;
        $n_p=array();
       
        $j=122;
        for($i=0;$i<=$tam;$i++){            
            $n_p[$i]=chr($j).$array[$i];
            $j--;
        }
        array_push($n_p,chr($j));
        
        $c = implode("", $n_p);
        return $c;
        
    }
    
}
