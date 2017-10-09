@extends('layouts.app')
@section('content')
<section id="widget-grid" class="">    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -12px">
            <div class="well well-sm well-light">
                <div class="row">                    
                    <section class="col col-lg-6">
                        <div class="col-xs-12">               
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <section style="padding-right: 10px">
                                        <div class="well well-sm well-light" style="margin-top:10px;padding:0px">
                                        <table id="table_Generica"></table>
                                        <div id="p_table_Generica"></div>
                                        </div>
                                    </section>
                                    <section style="padding-right: 10px">
                                        <div class="well well-sm well-light" style="margin-top:-5px;padding:0px;" >          
                                        <table id="table_SubGenerica"></table>
                                        <div id="p_table_SubGenerica"></div>
                                         </div> 
                                    </section>
                                </div>
                            </div> 
                        </div>
                    </section>
                    <section class="col col-lg-6">
                        <h1 class="txt-color-green"><b>Sub Genérica Detalle...</b></h1>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="text-right">                                    
                                    <label>Filtro Año:</label>
                                    <select id="vw_subgen_anio"  class="input-sm">
                                        @foreach ($anio as $anio)
                                        <option value='{{$anio->anio}}' >{{$anio->anio}}</option>
                                        @endforeach
                                    </select><i></i>                                    
                                    <button onclick="dlg_subgen_detalle();" id="btn_vw_contribuyentes_Nuevo" type="button" class="btn btn-labeled bg-color-greenLight txt-color-white">
                                        <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                    </button>
                                    <button onclick="up_dlg_subgen_detalle();" id="btn_vw_contribuyentes_Editar" type="button" class="btn btn-labeled bg-color-blue txt-color-white">
                                        <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                    </button>
                                    <button onclick="del_subgen();" id="btn_vw_contribuyentes_Eliminar" type="button" class="btn btn-labeled btn-danger">
                                        <span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span>Eliminar
                                    </button>
                                </div>                        
                            </div>
                        </div>
                        <hr style="border: 1px solid #DDD;margin: 10px -10px">            
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding:5px; margin-top: 10px">
                                <section style="padding-right:5px;">
                                    <div class="well well-sm well-light" style="padding:0px">
                                    <table id="table_SubGen_Detalle"></table>
                                    <div id="p_table_SubGen_Detalle"></div>
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
    $("#menu_presupuesto").show();
    $("#li_pres_subgendeta").addClass('cr-active');
    MensajeDialogLoadAjax('content','Cargando');
    jQuery("#table_Generica").jqGrid({
        url: 'get_generica?anio='+$("#vw_subgen_anio").val(),
        datatype: 'json', mtype: 'GET',
        height: 180, autowidth: true,        
        colNames: ['Codigo', 'Descripción - Genérica'],
        rowNum: 15, sortname: 'id_gener', sortorder: 'asc', viewrecords: true, caption: 'Generica', align: "center",
        colModel: [            
            {name: 'cod_generica', index: 'cod_generica', align: 'center', width: 50},
            {name: 'descr_gen', index: 'descr_gen', align: 'left', width: 300}            
        ],
        pager: '#p_table_Generica',
        rowList: [15, 20],
        gridComplete: function () {
            var idarray = jQuery('#table_Generica').jqGrid('getDataIDs');
            if (idarray.length > 0) {
                var firstid = jQuery('#table_Generica').jqGrid('getDataIDs')[0];
                $("#table_Generica").setSelection(firstid);
            }
        },
        onSelectRow: function (Id) { fn_actualizar_grilla('table_SubGenerica','get_subgenerica?anio='+$("#vw_subgen_anio").val()+'&id_gener='+Id); },               
    });
    
    setTimeout(function(){ 
        id_gener = $('#table_Generica').jqGrid ('getGridParam', 'selrow');    
        jQuery("#table_SubGenerica").jqGrid({
            url: 'get_subgenerica?anio='+$("#vw_subgen_anio").val()+'&id_gener='+id_gener,
            datatype: 'json', mtype: 'GET',
            height: 180, autowidth: true,
            toolbarfilter: true,
            colNames: ['Codigo', 'Descripción - Sub Genérica'],
            rowNum: 15, sortname: 'id_sub_gen', sortorder: 'asc', viewrecords: true, caption: 'Sub-Generica', align: "center",
            colModel: [            
                {name: 'cod_sub_generica', index: 'cod_generica', align: 'center', width: 50},
                {name: 'desc_sub_gen', index: 'descr_gen', align: 'left', width: 300}            
            ],
            pager: '#p_table_SubGenerica',
            rowList: [15, 20],
            gridComplete: function () {
                var idarray = jQuery('#table_SubGenerica').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                    var firstid = jQuery('#table_SubGenerica').jqGrid('getDataIDs')[0];
                    $("#table_SubGenerica").setSelection(firstid);
                }else{
                    $('#table_SubGen_Detalle').jqGrid('clearGridData');
                }
            },
            onSelectRow: function (Id) { fn_actualizar_grilla('table_SubGen_Detalle','get_subgenerica_detalle?anio='+$("#vw_subgen_anio").val()+'&id_sub_gen='+Id);},
            ondblClickRow: function (Id) {}
        });
        MensajeDialogLoadAjaxFinish('content');
    }, 500);
    setTimeout(function(){ 
        id_sub_gen = $('#table_SubGenerica').jqGrid ('getGridParam', 'selrow');    
        jQuery("#table_SubGen_Detalle").jqGrid({
            url: 'get_subgenerica_detalle?anio='+$("#vw_subgen_anio").val()+'&id_sub_gen='+id_sub_gen,
            datatype: 'json', mtype: 'GET',
            height: 350, autowidth: true,
            toolbarfilter: true,
            colNames: ['cod_subgendetalle','Codigo', 'Descripción - Sub Genérica Detalle'],
            rowNum: 15, sortname: 'id_sub_gen_det', sortorder: 'asc', viewrecords: true, caption: 'Sub Generica Detalle', align: "center",
            colModel: [     
                {name: 'cod_subgendeta', index: 'cod_subgendeta',hidden:true},
                {name: 'cod', index: 'cod', align: 'center', width: 50},
                {name: 'desc', index: 'desc', align: 'left', width: 300}            
            ],
            pager: '#p_table_SubGen_Detalle',
            rowList: [15, 20],
            gridComplete: function () {
                var idarray = jQuery('#table_SubGen_Detalle').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                    var firstid = jQuery('#table_SubGen_Detalle').jqGrid('getDataIDs')[0];
                    $("#table_SubGen_Detalle").setSelection(firstid);
                }
            },
            onSelectRow: function (Id) {},
            ondblClickRow: function (Id) {up_dlg_subgen_detalle();}
        });
        MensajeDialogLoadAjaxFinish('content');
    }, 1000);    
    $(window).on('resize.jqGrid', function () {
        $("#table_Generica").jqGrid('setGridWidth', $("#con2").width());
        $("#table_SubGenerica").jqGrid('setGridWidth', $("#con3").width());
    });
});
</script>
@stop
<script src="{{ asset('archivos_js/presupuesto/sub_gen_detalle.js') }}"></script>
<div id="dlg_subgen_detalle" style="display: none;">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">                
                <div class="panel panel-success" style="border: 0px !important">                    
                    <div class="panel-body">
                        <fieldset>                            
                            <section>
                                <label class="label">Código:</label>
                                <label class="input">
                                    <input id="subgendetalle_cod" onkeypress="return soloDNI(event);" type="text" placeholder="0" class="input-sm" style="width:80px;">
                                </label>                        
                            </section>
                            <section>
                                <label class="label">Descripción:</label>
                                <label class="input">
                                    <input id="subgendetalle_desc" type="text" placeholder="Descripción" class="input-sm text-uppercase">
                                </label>                      
                            </section>
                        </fieldset>
                    </div>
                </div>               
            </div>
        </div>
    </div>
</div>
@endsection

