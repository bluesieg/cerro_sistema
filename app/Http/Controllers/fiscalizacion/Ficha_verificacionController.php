<?php

namespace App\Http\Controllers\fiscalizacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\fiscalizacion\Ficha_Verificacion;
use App\Traits\DatesTranslator;
use Illuminate\Support\Facades\Auth;

class Ficha_verificacionController extends Controller
{
    use DatesTranslator;
    public function index()
    {
        $permisos = DB::select("SELECT * from permisos.vw_permisos where id_sistema='li_ficha_ver' and id_usu=".Auth::user()->id);
        $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
        if(count($permisos)==0)
        {
            return view('errors/sin_permiso',compact('menu','permisos'));
        }
        $anio_tra = DB::select('select anio from adm_tri.uit order by anio desc');
        $condicion = DB::select('select * from adm_tri.cond_prop order by id_cond ');
        $ecc = DB::select('select * from adm_tri.ecc order by id_ecc ');
        $tpre = DB::select('select * from adm_tri.tip_predio order by id_tip_p ');
        $fadq = DB::select('select * from adm_tri.form_adq order by id_for ');
        $pisclasi = DB::select('select * from adm_tri.clas_predio where id_cla_pre>0 order by id_cla_pre');
        $pismat = DB::select('select * from adm_tri.mep order by id_mep');
        $pisecs = DB::select('select * from adm_tri.ecs order by id_ecs');
        $gpoterr = DB::select('select * from adm_tri.gpo_tierras where id_gpo>1 order by id_gpo');
        return view('fiscalizacion/vw_ficha_verificacion',compact('anio_tra','condicion','ecc','tpre','fadq','pisclasi','pismat','pisecs','menu','permisos','gpoterr'));
    }

    public function create(Request $request)
    {
        $nro_hojas=DB::table('fiscalizacion.vw_hoja_liquidacion')->where('id_car',$request['carta'])->get();
        if(count($nro_hojas)>0)
        {
            return 0;
        }
        $anulado=DB::table('fiscalizacion.vw_carta_requerimiento')->where('id_car',$request['carta'])->get();
        if($anulado[0]->flg_anu>0)
        {
            return -1;
        }
        
        $ficha=new Ficha_Verificacion;
        $ficha->nro_fic=$request['nro'];
        $ficha->id_puente=$request['puente'];
        $ficha->fec_reg=date("d/m/Y");
        $ficha->observaciones=$request['obs'];
        $ficha->id_cond_prop=$request['cprop'];
        $ficha->nro_condominios=$request['condos'];
        $ficha->id_est_const=$request['ecc'];
        $ficha->id_tip_pred=$request['tp'];
        $ficha->arancel=$request['arcancel'];
        
        if($request['tip_pre_u_r']=="RUS")
        {
            $ficha->tip_pre_u_r=2;
            $ficha->hectareas=$request['hectareas'];
            $ficha->id_gpo_tierra=$request['gr_tierra'];
            $ficha->id_cat_gpo_tierra=$request['cat_tierra'];
            $ficha->val_ter=($request['hectareas'])*$request['arcancel'];
        }
        else
        {
            $ficha->tip_pre_u_r=1;
            $ficha->are_terr=$request['ater'];
            $ficha->are_com_terr=$request['acomun'];
            $ficha->val_ter=($request['ater']+$request['acomun'])*$request['arcancel'];
        }
        
        $ficha->save();
        return $ficha->id_fic;
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $prediovw= DB::table('fiscalizacion.vw_ficha_verificacion')->where('id_fic',$id)->get();
        if(trim($prediovw[0]->tp)=="RUS")
        {
            $prediovw[0]->foto=0;
        }
        else
        {
            $prediovw[0]->foto= $this->getfoto($prediovw[0]->sec,$prediovw[0]->mzna,$prediovw[0]->lote);
        }
        return $prediovw;
    }

    public function edit($id,Request $request)
    {
        $nro_hojas=DB::table('fiscalizacion.vw_hoja_liquidacion')->where('id_car',$request['carta'])->get();
        if(count($nro_hojas)>0)
        {
            return 0;
        }
        $anulado=DB::table('fiscalizacion.vw_carta_requerimiento')->where('id_car',$request['carta'])->get();
        if($anulado[0]->flg_anu>0)
        {
            return -1;
        }
        $ficha=new Ficha_Verificacion;
        $val=  $ficha::where("id_fic","=",$id )->first();
        if(count($val)>=1)
        {
            $val->nro_fic=$request['nro'];
            $val->observaciones=$request['obs'];
            $val->id_cond_prop=$request['cprop'];
            $val->nro_condominios=$request['condos'];
            $val->id_est_const=$request['ecc'];
            $val->id_tip_pred=$request['tp'];
            $val->arancel=$request['arcancel'];
            
            if($request['tip_pre_u_r']=="RUS")
            {
                $val->hectareas=$request['hectareas'];
                $val->id_gpo_tierra=$request['gr_tierra'];
                $val->id_cat_gpo_tierra=$request['cat_tierra'];
                $val->val_ter=($request['hectareas'])*$request['arcancel'];
            }
            else
            {
                $val->are_terr=$request['ater'];
                $val->are_com_terr=$request['acomun'];
                $ficha->val_ter=($request['ater']+$request['acomun'])*$request['arcancel'];
            }
            $val->save();
        }
        return $id;
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
    public function getfoto($sec,$mzna,$lote)
    {
        $foto = DB::connection('fotos')->select("select encode(foto,'base64') as foto from sect_".$sec." where id_lote='".$sec.$mzna.$lote."' limit 1");
        if(count($foto)>=1){
           return $foto[0]->foto;
        }
        else{
            return 0; 
        }
    }
}
