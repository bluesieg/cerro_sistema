@extends('layouts.app')
@section('content')
<section id="widget-grid" class="">    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -12px">
            <div class="well well-sm well-light">                
                <div class="row">
                    <div class="col-xs-12">                        
                        <div class="text-right">                            
                            <section>
                                <div class="jarviswidget jarviswidget-color-white" style="margin-bottom: 15px;"  >
                                    <header style="background: #01a858 !important;color: white" >
                                            <span class="widget-icon"> <i class="fa fa-align-justify"></i> </span>
                                            <h2>COMPROMISOS DE PAGO</h2>
                                    </header>
                                </div>
                                <div class="input-group input-group-md col-lg-6">
                                    <span class="input-group-addon">Contribuyente<i class="icon-append fa fa-male"></i></span>
                                    <div class="icon-addon addon-md">
                                        <input type="hidden" id="vw_compromiso_idcontrib">
                                        <input id="vw_compromiso_contrib" class="form-control text-uppercase" type="text">
                                    </div>
                                    <span class="input-group-btn">
                                        <button onclick="bus_contrib_compromiso();" class="btn btn-primary" type="button" title="BUSCAR">
                                            <i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;Buscar
                                        </button>
                                    </span>                                        
                                </div>    
                            </section>                           
                        </div>
                    </div>
                </div> 
            </div> 
            <div class="well well-sm well-light" style="margin-top:-20px;">                
                <div class="row">
                    <div class="col-xs-12">                        
                        <div class="row">
                            <section id="content_1" class="col col-lg-2" style="padding-right:5px;width: 20%"> 
                                <table id="tabla_expedientes"></table>
                                <div id="p_tabla_expedientes"></div>                     
                            </section>                                
                            <section id="content_2" class="col col-lg-10" style="padding-left:5px;width: 80%">
                                <table id="t_compromisos_pago"></table>
                                <div id="p_t_compromisos_pago"></div>
                            </section>                            
                        </div>                                                
                    </div>
                </div> 
            </div>
        </div>
    </div>
</section>
@section('page-js-script')
<script type="text/javascript">
    $("#menu_coactiva").show();
    $("#li_compromiso_pago").addClass('cr-active');
    $(document).ready(function () {
        jQuery("#tabla_expedientes").jqGrid({
            url: 'get_exped?id_contrib=0',
            datatype: 'json', mtype: 'GET',
            height: 329, autowidth: true,
            toolbarfilter: true,
            colNames: ['Nro', 'Expediente', 'monto', 'estado','Materia'],
            rowNum: 20, sortname: 'nro_procedimiento', sortorder: 'asc', viewrecords: true, caption: 'Expedientes', align: "center",
            colModel: [
                {name: 'nro_procedimiento', index: 'nro_procedimiento', align: 'center', width: 30},
                {name: 'nro_exped', index: 'nro_exped', align: 'center', width: 90},
                {name: 'monto', index: 'monto', hidden: true},
                {name: 'estado', index: 'estado', hidden: true},
                {name: 'materia', index: 'materia', align: 'center', width: 70}
            ],
            rowList: [13, 20],
            gridComplete: function () {},
            onSelectRow: function (Id) {},
            ondblClickRow: function (Id) {
                ver_compromisos_pago(Id);
            }
        });
        jQuery("#t_compromisos_pago").jqGrid({
            url: 'get_compromisopago?id_coa_mtr=0',
            datatype: 'json', mtype: 'GET',
            height: 329, autowidth: true,
            toolbarfilter: true,
            colNames: ['N°','Fecha de Pago', '% A Pagar - Monto Total', 'cod_estado','Estado','Dias Atrazo','Editar'],
            rowNum: 20, sortname: 'nro_cuo', sortorder: 'asc', viewrecords: true, caption: 'Compromisos de Pago', align: "center",
            colModel: [
                {name: 'nro', index: 'nro', align: 'center', width: 20},
                {name: 'fch_pago', index: 'fch_pago',align: 'center', width: 80},
                {name: 'monto', index: 'monto', align: 'center', width: 100},
                {name: 'estado', index: 'estado', hidden:true},
                {name: 'desc_est', index: 'desc_est', align: 'center', width: 70},   
                {name: 'retraso', index: 'retraso', align: 'center', width: 70},
                {name: 'editar', index: 'editar', align: 'center', width: 70} 
            ],
            rowList: [13, 20],
            pager: '#p_t_compromisos_pago',
            gridComplete: function () {
                var idarray = jQuery('#all_tabla_expedientes').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                    var firstid = jQuery('#all_tabla_expedientes').jqGrid('getDataIDs')[0];
                    $("#all_tabla_expedientes").setSelection(firstid);
                }
            },
            onSelectRow: function (Id) {},
            ondblClickRow: function (Id) {}
        });     
        jQuery("#table_contrib").jqGrid({
            url: 'obtiene_cotriname?dat=0',
            datatype: 'json', mtype: 'GET',
            height: '300px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_pers', 'codigo', 'DNI/RUC', 'contribuyente'],
            rowNum: 20, sortname: 'contribuyente', sortorder: 'asc', viewrecords: true, caption: 'Contribuyentes', align: "center",
            colModel: [
                {name: 'id_pers', index: 'id_pers', hidden: true},
                {name: 'id_per', index: 'id_per', align: 'center', width: 100},
                {name: 'nro_doc', index: 'nro_doc', align: 'center', width: 100},
                {name: 'contribuyente', index: 'contribuyente', align: 'left', width: 260}
            ],
            pager: '#pager_table_contrib',
            rowList: [13, 20],
            gridComplete: function () {
                var idarray = jQuery('#table_contrib').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                    var firstid = jQuery('#table_contrib').jqGrid('getDataIDs')[0];
                    $("#table_contrib").setSelection(firstid);
                }
            },
            onSelectRow: function (Id) {},
            ondblClickRow: function (Id) {
                fn_bus_contrib_select_compromiso(Id);
            }
        });
        $(window).on('resize.jqGrid', function () {
            $("#tabla_expedientes").jqGrid('setGridWidth', $("#content_1").width());
            $("#t_compromisos_pago").jqGrid('setGridWidth', $("#content_2").width());
        });
        
        
        var globalvalidador = 0;
        $("#vw_compromiso_contrib").keypress(function (e) {
            if (e.which == 13) {
                if (globalvalidador == 0) {
                    bus_contrib_compromiso();
                    globalvalidador = 1;
                } else {
                    globalvalidador = 0;
                }
            }
        });        
    });
</script>
@stop
<script src="{{ asset('archivos_js/coactiva/compromiso_pago.js') }}"></script>
<div id="dlg_new_valor" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">                
                <div class="panel panel-success" style="border: 0px !important">
                    <div class="panel-heading bg-color-success" >.:: Documentos Adjuntos ::.</div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="row">
                                <section class="col col-6"> 
                                    <label class="label">Materia:</label>
                                    <label class="input">
                                        <input type="hidden" id="hiddendlg_new_val_txt_mat">
                                        <input id="dlg_new_val_txt_mat" type="text" class="input-sm text-uppercase" disabled="">
                                    </label>                      
                                </section>
                            </div>
                            <section> 
                                <label class="label">Especificar Valor:</label>
                                <label class="input">
                                    <input id="dlg_new_val_txt_valor" type="text" placeholder="Ejm: RESOLUCION DE MULTA" class="input-sm text-uppercase">
                                </label>                      
                            </section>
                            <section> 
                                <label class="label">Abreviatura:</label>
                                <label class="input">
                                    <input id="dlg_new_val_txt_abrev" type="text" placeholder="Ejm: OP, RD" class="input-sm text-uppercase">
                                </label>                      
                            </section>
                        </fieldset>
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
@endsection




