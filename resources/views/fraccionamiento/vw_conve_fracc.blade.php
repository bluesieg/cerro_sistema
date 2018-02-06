@extends('layouts.app')
@section('content')
<style>
#vw_em_rec_txt_detalle_total{
    background: #80B23E;
    color: white;
    border: 0px !important;
    font-size: 12px;
}
</style>
<section id="widget-grid" class="">    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -12px">
            <div class="well well-sm well-light">
                <h1 class="txt-color-green"><b>Fraccionamiento...</b></h1>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="col-xs-2 col-sm-12 col-md-12 col-lg-3">
                            <label class="select">Filtro Año:</label>
                            <select onchange="selecciona_anio();" id="vw_conve_fracc_cb_anio" class="input-sm">
                                @foreach ($anio as $anio1)
                                <option value='{{$anio1->anio}}' >{{$anio1->anio}}</option>
                                @endforeach
                            </select><i></i>                                
                        </div>  
                        <div class="text-right">
                            <button
                                @if($permisos[0]->btn_new==1) onclick="dialog_conve_fracc();" @else onclick="sin_permiso();" @endif
                                id="btn_vw_conve_fracc_Nuevo" type="button" class="btn btn-labeled bg-color-greenLight txt-color-white">
                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo Convenio de Fracc.
                            </button>
<!--                            <button id="btn_vw_conve_fracc_Editar" onclick="" type="button" class="btn btn-labeled bg-color-blue txt-color-white">
                                <span class="btn-label"><i class="glyphicon glyphicon-folder-close"></i></span>Editar
                            </button>                            
                            <button id="btn_vw_conve_fracc_Anular" onclick="" type="button" class="btn btn-labeled btn-danger">
                                <span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span>Anular
                            </button>-->
                        </div>
                    </div>
                </div> 
            </div>                   
        </div>
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <table id="table_Convenios"></table>
            <div id="pager_table_Convenios"></div>
        </article>
    </div>
</section>
@section('page-js-script')

<script type="text/javascript">    
    $(document).ready(function () {        
        $("#menu_fracc").show();
        $("#li_fraccionamiento").addClass('cr-active');
        anio = $("#vw_conve_fracc_cb_anio").val(); 
        jQuery("#table_Convenios").jqGrid({
            url: 'grid_Convenios?anio='+ anio,
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            colNames: ['Nro.Convenio', 'Año','id_contrib','Contribuyente', 'Fecha', 'Interes', 'N° Cuotas', 'Estado', 'Total'],
            rowNum: 20, sortname: 'id_conv', sortorder: 'desc', viewrecords: true, caption: 'Resumen Recibos', align: "center",
            colModel: [                
                {name: 'nro_convenio', index: 'nro_convenio',align: 'center',width: 80},
                {name: 'anio', index: 'anio',width: 80,align: 'center'},
                {name: 'id_contribuyente', index: 'id_contribuyente',hidden:true},
                {name: 'contribuyente', index: 'contribuyente',width: 200},
                {name: 'fec_reg', index: 'fec_reg', width: 80,align: 'center'},
                {name: 'interes', index: 'interes',align: 'center', width: 80},
                {name: 'nro_cuotas', index: 'nro_cuotas', width: 80,align: 'center'},
                {name: 'estado', index: 'estado', align: 'center', width: 80},
                {name: 'total_convenio', index: 'total_convenio', align: 'right', width: 80}                
            ],
            pager: '#pager_table_Convenios',
            rowList: [15, 25],
            gridComplete: function () {
                var rows = $("#table_Convenios").getDataIDs();
                if (rows.length > 0) {
                    var firstid = jQuery('#table_Convenios').jqGrid('getDataIDs')[0];
                    $("#table_Convenios").setSelection(firstid);
                }               
            },            
            ondblClickRow: function (Id) {}
        });
        jQuery("#table_contrib").jqGrid({
            url: 'obtiene_cotriname?dat=0',
            datatype: 'json', mtype: 'GET',
            height: '300px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_pers','codigo','DNI/RUC','contribuyente','email','dom_fiscal'],
            rowNum: 20, sortname: 'contribuyente', sortorder: 'asc', viewrecords: true, caption: 'Contribuyentes', align: "center",
            colModel: [
                {name: 'id_pers', index: 'id_pers', hidden: true},
                {name: 'id_per', index: 'id_per', align: 'center',width: 100},
                {name: 'nro_doc', index: 'nro_doc', align: 'center',width: 100},
                {name: 'contribuyente', index: 'contribuyente', align: 'left',width: 260},
                {name: 'dom_fis', index: 'dom_fis', hidden:true},
                {name: 'email', index: 'email', hidden: true},
            ],
            pager: '#pager_table_contrib',
            rowList: [13, 20],
            gridComplete: function () {
                    var idarray = jQuery('#table_contrib').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_contrib').jqGrid('getDataIDs')[0];
                            $("#table_contrib").setSelection(firstid);    
                        }
                    
                    
                    jQuery('#table_contrib').jqGrid('bindKeys', {"onEnter":function( rowid ){fn_bus_contrib_list(rowid);} } ); 
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){fn_bus_contrib_list(Id)}
        });
        $(window).on('resize.jqGrid', function () {
            $("#table_Convenios").jqGrid('setGridWidth', $("#content").width());
        });
        $("#vw_conve_fracc_fracc_n_cuo,#vw_conve_fracc_fracc_cod_conve").keypress(function (e) {
            if (e.which == 13) {
                realizar_table_fracc();
            }
        });
        var globalvalidador=0;
        $("#vw_conve_fracc_contrib").keypress(function (e) {
            if (e.which == 13) {
                if(globalvalidador==0){
                    fn_bus_contrib();
                    globalvalidador=1;
                }else{
                    globalvalidador=0;
                }                
            }
        });
    });    
</script>
@stop
<div id="vw_conve_fracc" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <div class="panel panel-success">
                    <div class="panel-heading bg-color-success">.:: Datos del Contribuyente ::.</div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="row">                                
                                <section class="col col-2" style="padding-right: 5px;">
                                    <input type="hidden" id="vw_conve_fracc_id_pers">
                                    <label class="label">Cod Contrib:</label>
                                    <label class="input">
                                        <input id="vw_conve_fracc_cod_contrib" type="text" onkeypress="return soloDNI(event);"  placeholder="00000000" class="input-sm">
                                    </label>                      
                                </section>
                                <section class="col col-6" style="padding-left: 5px;padding-right:5px; ">
                                    <label class="label">Contribuyente:</label>
                                    <label class="input">                                        
                                        <input id="vw_conve_fracc_contrib" type="text" placeholder="ejm. jose min 4 caracteres" class="input-sm text-uppercase">
                                    </label>
                                </section>
                                <section class="col col-2" style="padding-left:5px">
                                    <label class="label">Año Desde:</label>                                   
                                    <label class="select">
                                        <select onchange="act_des_hast();" id="vw_conve_fracc_anio_desde" class="input-sm">                                       
                                        @foreach ($anio as $anio2)                                        
                                        <option value='{{$anio2->anio}}' >{{$anio2->anio}}</option>
                                        @endforeach                                    
                                    </select><i></i>                        
                                </section>
                                <section class="col col-2" style="padding-left:5px">
                                    <label class="label">Año Hasta:</label>                                   
                                    <label class="select">
                                        <select onchange="act_des_hast();" id="vw_conve_fracc_anio_hasta" class="input-sm">                                       
                                        @foreach ($anio as $anio3)                                        
                                        <option value='{{$anio3->anio}}' >{{$anio3->anio}}</option>
                                        @endforeach                                    
                                    </select><i></i>                        
                                </section>
                            </div>
                            <section>
                                <label class="label">Domicilio:</label>
                                    <label class="input">                                        
                                        <input id="vw_conve_fracc_domicilio" type="text" class="input-sm text-uppercase" disabled="">
                                    </label>
                            </section>
                        </fieldset>
                    </div>
                </div>                
                <div class="panel panel-success" style="border: 0px !important">                    
                    <div class="panel-body">    
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0px !important">
                            <table id="table_Deuda_Contrib_Arbitrios"></table>
                            <div id="pager_table_Deuda_Contrib_Arbitrios">
                                <div style="float: right; font-weight: bold;">
                                    Total S/. <input type="text" id="vw_conve_fracc_ttotal" class="input-xm text-right" style="width: 100px; height: 21px;padding-right: 4px;margin-bottom: -3px;" readonly="">
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>                   
        </div>        
    </div>
</div>
<div id="vw_conve_fracc_fraccionar" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <div class="panel panel-success">
                    <div class="panel-heading bg-color-success">.:: Datos del Convenio ::.</div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="row">
                                <section class="col col-3" style="padding-right: 5px;">                                    
                                    <label class="label">Fecha:</label>
                                    <label class="input">
                                        <input id="vw_conve_fracc_fracc_fecha" value="<?php date_default_timezone_set('America/Lima');echo date('d-m-Y') ?>" type="text" class="input-sm" disabled="">
                                    </label>                      
                                </section>
                                <section class="col col-3" style="padding-right: 5px;padding-left: 5px;">                                    
                                    <label class="label">Total:</label>
                                    <label class="input">
                                        <input id="vw_conve_fracc_fracc_tot" type="text" class="input-sm" disabled="">
                                    </label>                      
                                </section>
                                <section class="col col-2" style="padding-left: 5px;padding-right:5px;">
                                    <label class="label">Tif:</label>
                                    <label class="input">                                        
                                        <input id="vw_conve_fracc_fracc_tif" value="{{$cfracc[0]->tif}}" type="text" class="input-sm" disabled="">
                                    </label>
                                </section>
                                <section class="col col-1" style="padding-right:5px;padding-left:5px; ">                                    
                                    <label class="label">%:</label>
                                    <label class="input">
                                        <input type="hidden" id="vw_conve_fracc_fracc_porc_cuo_ini_min">
                                        <input type="hidden" id="vw_conve_fracc_fracc_limit_cuo" value="{{$cfracc[0]->limit_cuotas}}">
                                        <input id="vw_conve_fracc_fracc_porc_cuo_ini" onkeypress="return soloNumeroTab(event);" type="text" onblur="calc_inicial(this.value);" class="input-sm"> 
                                    </label>                      
                                </section>
                                <section class="col col-3" style="padding-left:5px;">
                                    <label>Tipo Fraccionamiento:</label>
                                    <label class="select">
                                        <select id="vw_conve_fracc_fracc_tip_fracc" onchange="sel_tip_fracc(this.options[this.selectedIndex].innerHTML);" class="input-sm">
                                            @foreach ($tip_f as $tip_f)
                                            <option value='{{$tip_f->id_tip_f}}' >{{$tip_f->tipo}} - {{round($tip_f->porcent)}}%</option>
                                            @endforeach
                                        </select><i></i></label>                                    
                                </section>                                
                            </div>
                            <div class="row">                               
                                <section class="col col-3" style="padding-right:5px;">                                    
                                    <label class="label">Inicial:</label>
                                    <label class="input">                                        
                                        <input id="vw_conve_fracc_fracc_inicial" onkeypress="return soloNumeroTab(event);" onblur="calc_deuda(this.value);" type="text" class="input-sm"> 
                                    </label>                      
                                </section>
                                <section class="col col-1" style="padding-right:5px;padding-left:5px;">
                                    <label class="label">Cuotas:</label>
                                    <label class="input">                                        
                                        <input id="vw_conve_fracc_fracc_n_cuo" onkeypress="return soloDNI(event);" onblur="calc_deuda();" type="text" class="input-sm">
                                    </label>                       
                                </section>                                                                
                                <section class="col col-2" style="padding-right:5px;padding-left:5px;">
                                    <label class="label">Deuta Total:</label>
                                    <label class="input">                                        
                                        <input id="vw_conve_fracc_fracc_deuda" type="text" class="input-sm" disabled="">
                                    </label>                       
                                </section>                                
                                <section class="col col-3" style="padding-left:5px">
                                      <label class="label">&nbsp;</label>
                                        <a onclick="realizar_table_fracc();" class="btn btn-primary btn-sm">Ver Fraccionamiento</a>                    
                                </section>
                            </div>
                            <section>
                                <label class="label">Glosa:</label>
                                <label class="input">                                        
                                    <input id="vw_conve_fracc_fracc_glosa" type="text" class="input-sm text-uppercase" maxlength="300">
                                </label>
                            </section>
                        </fieldset>
                    </div>
                </div>
                
                <div class="panel panel-success" style="border: 0px !important;height: 325px; overflow-y: scroll">
                    <div class="panel-heading bg-color-success">.:: Vista Fraccionamiento ::.</div>
                    <div class="panel-body">    
                        <div style="border: 1px solid #DDD; margin-bottom: 6px;">
                            <table id="t_dina_conve_fracc" class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th width="2%" align="center">N</th>
                                        <th width="20%" style="text-align: center">SALDO</th>
                                        <th width="20%" style="text-align: center">AMORTIZACION</th>
                                        <th width="20%" style="text-align: center">INTERES</th>
                                        <th width="20%" style="text-align: center">CUOTA MENSUAL</th>
                                        <th width="20%" style="text-align: center">Fecha Vence</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>

                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th width="2%" align="center"></th>
                                        <th width="20%" style="text-align: right">Totales S/.</th>
                                        <th width="20%" style="border-top: 2px solid #017E42;">
                                            <label class='input'><input id="vw_con_fracc_tot_amor" type="text" value="000.00" class="input-xs text-align-right" disabled=""></label>
                                        </th>
                                        <th width="20%" style="border-top: 2px solid #017E42;">
                                            <label class='input'><input id="vw_con_fracc_tot_inter" type="text" value="000.00" class="input-xs text-align-right" disabled=""></label>
                                        </th>
                                        <th width="20%" style="border-top: 2px solid #017E42;">
                                            <label class='input'><input id="vw_con_fracc_tot_cc" type="text" value="000.00" class="input-xs text-align-right" disabled=""></label>
                                        </th>
                                        <th width="20%" align="center"></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>                   
        </div>        
    </div>
</div>
<div id="dlg_bus_contr" style="display: none;">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
        <table id="table_contrib"></table>
        <div id="pager_table_contrib"></div>
    </article>
</div>
<script src="{{ asset('archivos_js/fraccionamiento/convenio.js') }}"></script>
@endsection


