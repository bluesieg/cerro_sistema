<?php

namespace App\Http\Controllers\tesoreria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Recibos_Detalle;

class Recibos_DetalleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        date_default_timezone_set('America/Lima');
        $rec_det = new Recibos_Detalle(); 
        $rec_det->id_rec_master=$request->id_rec_master;
        
        if($request->periodo != null){
            $rec_det->periodo=$request->periodo;
        }else{
            $rec_det->periodo=date('Y');
        }
        
        $rec_det->id_ofi=0;
        $rec_det->id_trib=$request->id_trib;
        $rec_det->monto=$request->monto;
        $rec_det->cant=$request->cant;
        $rec_det->p_unit=$request->p_unit;
        $rec_det->save();
        
        return $rec_det->id_rec_det;
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
}
