<?php

namespace App\Http\Controllers\ordenanzas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ordenanzas\ordenanzas;
use App\Models\ordenanzas\orde_predial;

class ordenanza_Controller extends Controller
{

    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_mod_orde' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        return view('ordenanzas/vw_ordenanza', compact('anio_tra','menu','permisos'));
    }

    public function create(Request $request)
    {
        if($request['tipo']=='ordenanza')
        {
            return $this->create_ordenanza($request);
        }
        if($request['tipo']=='predial')
        {
            return $this->create_predial($request);
        }
        
    }
    public function create_ordenanza(Request $request)
    {
        $ordenanzas=new ordenanzas;
        $ordenanzas->refe_orde = strtoupper($request['refe']);
        $ordenanzas->fec_reg = date('d/m/Y');
        $ordenanzas->fec_ini = $request['fec_ini'];
        $ordenanzas->fec_fin = $request['fec_fin'];
        $ordenanzas->glosa = $request['glosa'];
        $ordenanzas->id_usu = Auth::user()->id;
        $ordenanzas->save();
        return $ordenanzas->id_orde;
    }
    public function create_predial(Request $request)
    {
        $predial=new orde_predial;
        $predial->id_orde = $request['id_orde'];
        $predial->anio_ini = $request['anio_ini'];
        $predial->anio_fin = $request['anio_fin'];
        $predial->porcent_desc_ip_nat = $request['porcent_desc_ip_nat'];
        $predial->pocent_desc_ip_jur = $request['pocent_desc_ip_jur'];
        $predial->monto_multa_nat = $request['monto_multa_nat'];
        $predial->monto_multa_jur = $request['monto_multa_jur'];
        $predial->pocent_desc_im_nat = date('pocent_desc_im_nat');
        $predial->porcent_desc_im_jur = $request['porcent_desc_im_jur'];
        $predial->fec_reg =  date('d/m/Y');
        $predial->id_usu = Auth::user()->id;
        $predial->save();
        return $predial->id_orde_pred;
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
}
