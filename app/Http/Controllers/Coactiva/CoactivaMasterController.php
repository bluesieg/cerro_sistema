<?php

namespace App\Http\Controllers\Coactiva;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Traits\DatesTranslator;
use App\Models\coactiva\coactiva_master;
use App\Models\fiscalizacion\Resolucion_Determinacion;
use App\Models\orden_pago_master;
use App\Models\fiscalizacion\multas_registradas;

class CoactivaMasterController extends Controller
{
    use DatesTranslator;
    public function index(){}

    public function create($id_contrib,$id_doc_ini,$monto,$anio,$doc_ini,$oficina,$id_tributo){        
        $data = new coactiva_master();
        $data->id_contrib = $id_contrib;
        $data->fch_recep = date('Y-m-d');
        $data->hora_recep = date('h:i A');
        $data->estado = 1;
        $data->anio = date('Y');
        $data->doc_ini=$doc_ini;
        $data->monto=$monto;
        $data->materia=1;
        $data->id_doc_ini=$id_doc_ini;
        $data->id_oficina=$oficina;
        $data->id_tributo=$id_tributo;
        $sql = $data->save();
        $recpred = DB::select('select * from presupuesto.vw_impuesto_predial where anio='.$anio); 
        if($sql){
            DB::table('adm_tri.cta_cte')->where('id_pers','=',$id_contrib)->where('id_tribu','=',$recpred[0]->id_tributo)->where('ano_cta',$anio)
                    ->update(['id_coa_mtr'=>$data->id_coa_mtr,'trim1_estado'=>2,'trim2_estado'=>2,'trim3_estado'=>2,'trim4_estado'=>2]);
            return $data->id_coa_mtr;
        }
    }

    public function store(Request $request){}

    public function show($id){}

    public function edit($id) {}

    public function update(Request $request, $id){}

    public function destroy($id){}
    
    function resep_documentos_op(Request $request){
        $array = explode('-', $request['id_gen_fis']);
        $count=count($array);
        $i=0;
        $op=new orden_pago_master;
        for($i==0;$i<=$count-1;$i++){  
            $op_vw=DB::select("select * from recaudacion.vw_genera_fisca where id_gen_fis =".$array[$i]);
            $val=  $op::where("id_gen_fis","=",$array[$i])->first();
            if(count($val)>=1)
            {
                $id_tributo=DB::select("select * from presupuesto.vw_impuesto_predial where anio =".$val->anio_reg);
                $val->env_coactivo=2;
                $val->save();
                
                $this->create($op_vw[0]->id_per,$val->id_gen_fis,$op_vw[0]->monto,$val->anio,2,35,$id_tributo[0]->id_tributo);
            }
        }  
        return response()->json(['msg'=>'si']);
    }
    function resep_documentos_rd(Request $request){
        $array = explode('-', $request['id_rd']);
        $count=count($array);
        $i=0;
        $rd=new Resolucion_Determinacion;
        for($i==0;$i<=$count-1;$i++){  
            $rd_vw=DB::select("select * from fiscalizacion.vw_resolucion_determinacion where id_rd =".$array[$i]);
            $val=  $rd::where("id_rd","=",$array[$i])->first();
            if(count($val)>=1)
            {
                $id_tributo=DB::select("select * from presupuesto.vw_impuesto_predial where anio =".$val->anio_fis);
                $val->env_coactivo=2;
                $val->save();
                $sql = $this->create($rd_vw[0]->id_contrib,$val->id_rd,$rd_vw[0]->ivpp_verif,$val->anio,1,36,$id_tributo[0]->id_tributo);
            }
        }        
        return response()->json(['msg'=>'si']);
    }
    function resep_documentos_multa(Request $request){
        $array = explode('-', $request['id_multa_reg']);
        $count=count($array);
        $i=0;
        $multa=new multas_registradas;
        for($i==0;$i<=$count-1;$i++){  
            $val=  $multa::where("id_multa_reg","=",$array[$i])->first();
            if(count($val)>=1)
            {
                $val->env_coactivo=2;
                $val->save();
                $sql = $this->create($val->id_contrib,$val->id_multa_reg,$val->total_multa,$val->anio_reg,3,36);
            }

        }        
        return response()->json(['msg'=>'si']);
    }
}
