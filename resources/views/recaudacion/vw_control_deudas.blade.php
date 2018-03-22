@extends('layouts.app')
@section('content')
<section id="widget-grid" class="">    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -12px">
            <div class="well well-sm well-light">
                <div class="row">                    
                    
                    <section class="col col-lg-12">
                        <center><h1 class="txt-color-green"><b>CONTROL DE DEUDAS</b></h1></center>
                        <div class="row">
                          
                            <div class="col-xs-2" style="padding-left: 35px;">
                                <div class="input-group input-group-md">
                                    <span class="input-group-addon">Año <i class="fa fa-cogs"></i></span>
                                    <div class="icon-addon addon-md">
                                        <select id='dlg_anio' class="form-control col-lg-8" style="height: 32px;" onchange="selecciona_anio();">
                                        @foreach ($anio as $anio)
                                        <option value='{{$anio->anio}}' >{{$anio->anio}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                          
                                <div class="col-xs-4" style="padding-left: 80px;">
                                    <div class="input-group input-group-md">
                                        <span class="input-group-addon">Codigo. &nbsp;<i class="fa fa-hashtag"></i></span>
                                        <div class=""  >
                                            <input id="dlg_codigo" type="text"  class="form-control input-sm text-uppercase" style="height: 32px; " placeholder="Codigo Contribuyente" disabled="" >
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="col-xs-5" style="padding-left:80px;">
                                    <div class="input-group input-group-md">
                                        <span class="input-group-addon">Contribuyente. &nbsp;<i class="fa fa-male"></i></span>
                                        <div>
                                            <input type="hidden" id="dlg_hidden_contribuyente">
                                            <input id="dlg_contribuyente" type="text"  class="form-control input-sm text-uppercase" style="height: 32px;font-size: 0.9em;width: 102% !important" autofocus="focus" placeholder="NOMBRE CONTRIBUYENTE">
                                        </div>
                                    </div>
                                </div>
                        </div>
                        
                        <hr style="border: 1px solid #DDD;margin: 10px -10px"> 
                        
                        <ul id="tabs1" class="nav nav-tabs bordered">
                            <li class="active">
                                <a href="#s1" data-toggle="tab" aria-expanded="true">
                                    VER PREDIAL
                                    <i class="fa fa-lg fa-fw fa-cog fa-spin"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#s2" data-toggle="tab" aria-expanded="false">
                                    VER ARBITRIOS
                                    <i class="fa fa-lg fa-fw fa-cog fa-spin"></i>
                                </a>
                            </li>
                        </ul>
                        
                    <div id="myTabContent1" class="tab-content padding-1"> 
                        
                      <div id="s1" class="tab-pane fade active in">
                        <section class="col col-lg-12">
                        <div class="col-xs-12">               
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <section style="padding-right: 10px">
                                        <div class="well well-sm well-light" style="margin-top:10px;padding:0px">
                                        <table id="table_deuda_actual"></table>
                                        <div id="p_table_deuda_actual">
                                            <div style="float: right; font-weight: bold;">
                                                Total S/. <input type="text" id="vw_est_cta_total" class="input-sm text-center" style="width: 143px; height: 25px;padding-right: 4px;" readonly="">
                                            </div>
                                        </div>
                                        </div>
                                    </section>
                                    <section style="padding-right: 10px">
                                        <div class="well well-sm well-light" style="margin-top:-5px;padding:0px;" >          
                                        <table id="table_detalle_deuda"></table>
                                        <div id="p_table_detalle_deuda"></div>
                                         </div> 
                                    </section>
                                </div>
                            </div>
                            
                            <div class="row">
                                
                                <div class="col-xs-4" style="padding-left: 80px;">
                                    <div class="input-group input-group-md">
                                        <span class="input-group-addon">Monto. &nbsp;<i class="fa fa-hashtag"></i></span>
                                        <div class=""  >
                                            <input id="dlg_monto" type="text" placeholder="INGRESE MONTO" class="form-control input-sm text-uppercase text-center" style="height: 32px;" onkeypress="return soloNumeroTab(event);">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xs-1">
                                    
                                    <div class="text-right">
                                        @if( $permisos[0]->btn_new ==1 )
                                            <button type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="compensacion_predial();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>EJECUTAR
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="sin_permiso();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>EJECUTAR
                                            </button>
                                        @endif
                                    </div>
                            </div>
                            </div>    
                            
                            
                        </div>
                        </section>
                        
                      </div>
                        
                        <div id="s2" class="tab-pane fade" style="height: auto">
                        
                        <section class="col col-lg-12">
                        <div class="col-xs-12">               
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <section style="padding-right: 10px">
                                        <div class="well well-sm well-light" style="margin-top:10px;padding:0px">
                                        <table id="table_predios"></table>
                                        <div id="p_table_predios"></div>
                                        </div>
                                    </section>
                                </div>
                            </div> 
                        </div>
                        </section>
                        
                        <section class="col col-lg-6"> 
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <section>
                                    <div class="well well-sm well-light" style="padding:0px">
                                    <table id="table_concepto"></table>
                                    <div id="p_table_concepto"></div>
                                    </div> 
                                </section>    
                            </div>
                        </div> 
                        </section>
                            
                        <section class="col col-lg-6">              
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <section style="padding-right: 30px">
                                        <div class="well well-sm well-light" style="padding:0px">
                                        <table id="table_meses"></table>
                                        <div id="p_table_meses"></div>
                                        </div>
                                    </section>
                                </div>
                            </div> 
                        </section>
                            
                        </div> 
                        </div> 
      
                    </section>
                </div>
            </div>            
        </div>       
    </div>
</section>
@section('page-js-script')

<script type="text/javascript">
$(document).ready(function () {
    $("#menu_recaudacion").show();
    $("#li_control_deudas").addClass('cr-active');
    
    anio = $("#dlg_anio").val();
    
    MensajeDialogLoadAjax('content','Cargando');
    jQuery("#table_deuda_actual").jqGrid({
        url: 'get_est_cta_cte?id_contrib='+0+'&anio='+0,
        datatype: 'json', mtype: 'GET',
        height: 100, autowidth: true,        
        colNames: ['ID','AÑO', 'DESCRIPCION','DEUDA','REAJUSTE','T.I.M','DEUDA TOTAL','PAGADO','SALDO'],
        rowNum: 15, sortname: 'id_cta_cte', sortorder: 'asc', viewrecords: true, caption: 'DEUDA ACTUAL', align: "center",
        colModel: [
            {name: 'id_cta_cte', index: 'id_cta_cte', align: 'center', width: 50,hidden:true},
            {name: 'ano_cta', index: 'ano_cta', align: 'center', width: 50},
            {name: 'descrip_tributo', index: 'descrip_tributo', align: 'left', width: 90},
            {name: 'deuda_actual', index: 'deuda_actual', align: 'center', width: 50},
            {name: 'reajuste', index: 'reajuste', align: 'center', width: 50},
            {name: 'interes', index: 'interes', align: 'center', width: 50},
            {name: 'tot_deuda', index: 'tot_deuda', align: 'center', width: 50},
            {name: 'pagado', index: 'pagado', align: 'center', width: 50},
            {name: 'saldo', index: 'saldo', align: 'center', width: 50}          
        ],
        pager: '#p_table_deuda_actual',
        rowList: [15, 20],
        gridComplete: function () {
            var idarray = jQuery('#table_deuda_actual').jqGrid('getDataIDs');
            if (idarray.length > 0) {
                var firstid = jQuery('#table_deuda_actual').jqGrid('getDataIDs')[0];
                $("#table_deuda_actual").setSelection(firstid);
            }
            var sum = jQuery("#table_deuda_actual").getGridParam('userData').sum_total;
                if(sum==undefined){
                    $("#vw_est_cta_total").val('000.00');
                }else{
                    $("#vw_est_cta_total").val(formato_numero(sum,2,'.',','));
                }
        },
        onSelectRow: function (Id) {       
             fn_actualizar_grilla('table_detalle_deuda','get_detalle_deuda?anio='+anio+'&id_cta_cte='+Id); 
        } 
        
    });
    
    var globalvalidador=0;
        $("#dlg_contribuyente").keypress(function (e) {
                    if (e.which == 13) {
                        if(globalvalidador==0)
                        {
                            fn_bus_contrib();
                            globalvalidador=1;
                        }
                        else
                        {
                            globalvalidador=0;
                        }
                    }
        });
    contrib_global=0;    
    jQuery("#table_contribuyente").jqGrid({
            url: 'obtener_contribuyentes?dat=0',
            datatype: 'json', mtype: 'GET',
            height: 300, width: 480,
            toolbarfilter: true,
            colNames: ['ID','DNI','PERSONA'],
            rowNum: 12,sortname: 'contribuyente', viewrecords: true, caption: 'CONTRIBUYENTES', align: "center",
            colModel: [
                {name: 'id_contrib', index: 'id_contrib', align: 'center', hidden:true,width:20},
                {name: 'pers_nro_doc', index: 'pers_nro_doc', align: 'center', width:10}, 
                {name: 'contribuyente', index: 'contribuyente', align: 'center', width:30},

            ],
            pager: '#pager_table_contribuyente',
            rowList: [10, 20],
            gridComplete: function () {
                    var idarray = jQuery('#table_contribuyente').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_contribuyente').jqGrid('getDataIDs')[0];
                            $("#table_contribuyente").setSelection(firstid);
                        }
                        
                    if(contrib_global==0)
                    {   contrib_global=1;
                    jQuery('#table_contribuyente').jqGrid('bindKeys', {"onEnter": function (rowid) { fn_bus_est_cta_cte(rowid); }});
                    }
                },
            onSelectRow: function (Id){
                $('#current_id').val($("#table_contribuyente").getCell(Id, "id_contrib"));

            },
            ondblClickRow: function (Id){fn_bus_est_cta_cte(Id)}
        });
    
    setTimeout(function(){ 
        id_cta_cte = $('#table_deuda_actual').jqGrid ('getGridParam', 'selrow');
        
        jQuery("#table_detalle_deuda").jqGrid({
            url: 'get_detalle_deuda?anio='+0+'&id_cta_cte='+0,
            datatype: 'json', mtype: 'GET',
            height: 100, autowidth: true,
            toolbarfilter: true,
            colNames: ['ID','DESCRIPCION','SALDO','TRIM I','ABO I','TRIM II','ABO II','TRIM III','ABO III','TRIM IV','ABO IV'],
            rowNum: 15, sortname: 'id_cta_cte', sortorder: 'asc', viewrecords: true, caption: 'DETALLE DEUDA', align: "center",
            colModel: [            
                {name: 'id_cta_cte', index: 'id_cta_cte', align: 'center', width: 50,hidden:true},
                {name: 'descrip_tributo', index: 'descrip_tributo', align: 'left', width: 120},
                {name: 'saldo', index: 'saldo', align: 'center', width: 50},
                {name: 'trim1', index: 'trim1', align: 'center', width: 50},
                {name: 'abo1', index: 'abo1', align: 'center', width: 50},
                {name: 'trim2', index: 'trim2', align: 'center', width: 50},
                {name: 'abo2', index: 'abo2', align: 'center', width: 50},
                {name: 'trim3', index: 'trim3', align: 'center', width: 50},
                {name: 'abo3', index: 'abo3', align: 'center', width: 50},
                {name: 'trim4', index: 'trim4', align: 'center', width: 50},
                {name: 'abo4', index: 'abo4', align: 'center', width: 50}
            ],
            pager: '#p_table_detalle_deuda',
            rowList: [15, 20],
            gridComplete: function () {
                var idarray = jQuery('#table_detalle_deuda').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                    var firstid = jQuery('#table_detalle_deuda').jqGrid('getDataIDs')[0];
                    $("#table_detalle_deuda").setSelection(firstid);
                }
            }
        });
         MensajeDialogLoadAjaxFinish('content');
    }, 500);
    
   //ARBITRIOS
   jQuery("#table_predios").jqGrid({
        url: 'get_predios_arbitrios?id_contrib='+0+'&anio='+0,
        datatype: 'json', mtype: 'GET',
        height: 100, width: 1180,        
        colNames: ['ID','AÑO', 'COD-CATASTRAL','DIRECCION','DEUDA','INTERES','DEUDA TOTAL','PAGADO','SALDO'],
        rowNum: 15, sortname: 'id_arb', sortorder: 'asc', viewrecords: true, caption: 'PREDIOS', align: "center",
        colModel: [
            {name: 'id_arb', index: 'id_arb', align: 'center', width: 50,hidden:true},
            {name: 'anio', index: 'anio', align: 'center', width: 30},
            {name: 'cod_cat', index: 'cod_cat', align: 'center', width: 60},
            {name: 'direccion', index: 'direccion', align: 'left', width: 200},
            {name: 'deuda_normal', index: 'deuda_normal', align: 'center', width: 25},
            {name: 'intereses', index: 'intereses', align: 'center', width: 25},
            {name: 'tot_deuda', index: 'tot_deuda', align: 'center', width: 42},
            {name: 'pagado', index: 'pagado', align: 'center', width: 25},
            {name: 'saldo', index: 'saldo', align: 'center', width: 20}          
        ],
        pager: '#p_table_predios',
        rowList: [15, 20],
        gridComplete: function () {
            var idarray = jQuery('#table_predios').jqGrid('getDataIDs');
            if (idarray.length > 0) {
                var firstid = jQuery('#table_predios').jqGrid('getDataIDs')[0];
                $("#table_predios").setSelection(firstid);
            }
        },
        onSelectRow: function (Id) { fn_actualizar_grilla('table_concepto','get_predios_arbitrios_concepto?id_arb='+Id); }
    });
    
    setTimeout(function(){ 
        id_arb = $('#table_predios').jqGrid ('getGridParam', 'selrow');
        
        jQuery("#table_concepto").jqGrid({
            url: 'get_predios_arbitrios_concepto?id_arb='+0,
            datatype: 'json', mtype: 'GET',
            height: 110, width: 600,
            toolbarfilter: true,
            colNames: ['ID','DESCRIPCION','DEUDA','INTERES','DEUDA TOTAL','PAGADO','SALDO'],
            rowNum: 15, sortname: 'id_cta_arb', sortorder: 'asc', viewrecords: true, caption: 'CONCEPTO', align: "center",
            colModel: [            
                {name: 'id_cta_arb', index: 'id_cta_arb', align: 'center', width: 50,hidden:true},
                {name: 'descripcion', index: 'descripcion', align: 'left', width: 115},
                {name: 'deuda', index: 'deuda', align: 'center', width: 40},
                {name: 'interes', index: 'interes', align: 'center', width: 35},
                {name: 'tot_deuda', index: 'tot_deuda', align: 'center', width: 60},
                {name: 'pagado', index: 'pagado', align: 'center', width: 45},
                {name: 'saldo', index: 'saldo', align: 'center', width: 45}
            ],
            pager: '#p_table_concepto',
            rowList: [15, 20],
            gridComplete: function () {
                var idarray = jQuery('#table_concepto').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                    var firstid = jQuery('#table_concepto').jqGrid('getDataIDs')[0];
                    $("#table_concepto").setSelection(firstid);
                }
            },
            onSelectRow: function (Id) { fn_actualizar_grilla('table_meses','get_meses_arbitrios?id_cta_arb='+Id); }
        });
    }, 500);
   
   
   
    setTimeout(function(){ 
        jQuery("#table_meses").jqGrid({
            url: 'get_meses_arbitrios?id_cta_arb='+0,
            datatype: 'json', mtype: 'GET',
            height: 110, width: 600,
            toolbarfilter: true,
            colNames: ['ID','MES','T. PAGAR','INTERES','TOTAL','PAGADO','SALDO'],
            rowNum: 15, sortname: 'id_arb', sortorder: 'asc', viewrecords: true, caption: 'MESES', align: "center",
            colModel: [            
                {name: 'id_arb', index: 'id_cta_arb', align: 'center', width: 50,hidden:true},
                {name: 'mes', index: 'mes', align: 'left', width: 115},
                {name: 'deuda', index: 'deuda', align: 'center', width: 42},
                {name: 'interes', index: 'interes', align: 'center', width: 38},
                {name: 'tot_deuda', index: 'tot_deuda', align: 'center', width: 30},
                {name: 'pagado', index: 'pagado', align: 'center', width: 38},
                {name: 'saldo', index: 'saldo', align: 'center', width: 32}  
            ],
            pager: '#p_table_meses',
            rowList: [15, 20],
            gridComplete: function () {
                var idarray = jQuery('#table_meses').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                    var firstid = jQuery('#table_meses').jqGrid('getDataIDs')[0];
                    $("#table_meses").setSelection(firstid);
                }
            }
        });
    }, 500);
    
});
</script>
@stop
<script src="{{ asset('archivos_js/recaudacion/control_deudas.js') }}"></script>

<div id="dlg_bus_contribuyente" style="display: none;">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
        <table id="table_contribuyente"></table>
        <div id="pager_table_contribuyente"></div>
    </article>
</div> 
@endsection

