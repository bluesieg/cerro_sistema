@extends('layouts.app')
@section('content')
<style>
    .ui-jqgrid .ui-jqgrid-titlebar{ 
        padding: 0px 10px;
    }
</style>
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
                                        <div class="well well-sm well-light" style="margin-top:-15px;padding:0px;" >          
                                        <table id="table_SubGenerica"></table>
                                        <div id="p_table_SubGenerica"></div>
                                         </div> 
                                    </section>
                                    <section style="padding-right: 10px">
                                        <div class="well well-sm well-light" style="margin-top:-15px;padding:0px;" >          
                                        <table id="table_SubGen_Detalle"></table>
                                        <div id="p_table_SubGen_Detalle"></div>
                                         </div> 
                                    </section>
                                </div>
                            </div> 
                        </div>
                    </section>
                    <section class="col col-lg-6">
                        <h1 class="txt-color-green"><b>Especifica Detalle...</b></h1>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="text-right">                                    
                                    <label>Filtro Año:</label>
                                    <select id="vw_esp_det_anio"  class="input-sm" onchange="selecciona_anio();">
                                        @foreach ($anio as $anio)
                                        <option value='{{$anio->anio}}' >{{$anio->anio}}</option>
                                        @endforeach
                                    </select><i></i>
                                    @if( $permisos[0]->btn_new ==1 )
                                        <button onclick="dlg_esp_detalle();" type="button" class="btn btn-labeled bg-color-greenLight txt-color-white">
                                            <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                        </button>
                                    @else
                                        <button onclick="sin_permiso();" type="button" class="btn btn-labeled bg-color-greenLight txt-color-white">
                                            <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                        </button>
                                    @endif
                                    @if( $permisos[0]->btn_edit ==1 )
                                        <button onclick="up_dlg_esp_detalle();" type="button" class="btn btn-labeled bg-color-blue txt-color-white">
                                            <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                        </button>
                                    @else
                                        <button onclick="sin_permiso();" type="button" class="btn btn-labeled bg-color-blue txt-color-white">
                                            <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                        </button>
                                    @endif
                                    @if( $permisos[0]->btn_del ==1 )
                                        <button onclick="del_esp_detalle();" type="button" class="btn btn-labeled btn-danger">
                                            <span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span>Eliminar
                                        </button>
                                    @else
                                        <button onclick="sin_permiso();" type="button" class="btn btn-labeled btn-danger">
                                            <span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span>Eliminar
                                        </button>
                                    @endif
                                </div>                        
                            </div>
                        </div>
                        <hr style="border: 1px solid #DDD;margin: 10px -10px">            
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding:5px; margin-top: 10px">
                                <section style="padding-right:5px;">
                                    <div class="well well-sm well-light" style="padding:0px">
                                    <table id="table_Especifica"></table>
                                    <div id="p_table_Especifica"></div>
                                    </div> 
                                </section>
                                <section style="padding-right:5px;">
                                    <div class="well well-sm well-light" style="padding:0px; margin-top: -10px">
                                    <table id="table_Esp_Detalle"></table>
                                    <div id="p_table_Esp_Detalle"></div>
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
    $("#li_pres_especideta").addClass('cr-active');
    MensajeDialogLoadAjax('content','Cargando');
    jQuery("#table_Generica").jqGrid({
        url: 'get_generica?anio='+$("#vw_esp_det_anio").val(),
        datatype: 'json', mtype: 'GET',
        height: 100, autowidth: true,        
        colNames: ['ID','Codigo', 'Descripción - Genérica'],
        rowNum: 15, sortname: 'id_gener', sortorder: 'asc', viewrecords: true, caption: 'Generica', align: "center",
        colModel: [
            {name: 'id_gener', index: 'id_gener', align: 'center', width: 50,hidden:true},
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
        onSelectRow: function (Id) { fn_actualizar_grilla('table_SubGenerica','get_subgenerica?anio='+$("#vw_esp_det_anio").val()+'&id_gener='+Id); },               
    });
    
    setTimeout(function(){ 
        id_gener = $('#table_Generica').jqGrid ('getGridParam', 'selrow');
        if(!id_gener){ id_gener=0; }
        jQuery("#table_SubGenerica").jqGrid({
            url: 'get_subgenerica?anio='+$("#vw_esp_det_anio").val()+'&id_gener='+id_gener,
            datatype: 'json', mtype: 'GET',
            height: 100, autowidth: true,
            toolbarfilter: true,
            colNames: ['Codigo', 'Descripción - Sub Genérica'],
            rowNum: 15, sortname: 'id_sub_gen', sortorder: 'asc', viewrecords: true, caption: 'Sub-Generica', align: "center",
            colModel: [            
                {name: 'cod_sub_generica', index: 'cod_sub_generica', align: 'center', width: 50},
                {name: 'desc_sub_gen', index: 'desc_sub_gen', align: 'left', width: 300}            
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
            onSelectRow: function (Id) { fn_actualizar_grilla('table_SubGen_Detalle','get_subgenerica_detalle?anio='+$("#vw_esp_det_anio").val()+'&id_sub_gen='+Id);},            
        });        
    }, 500);
    setTimeout(function(){ 
        id_sub_gen = $('#table_SubGenerica').jqGrid ('getGridParam', 'selrow');
        if(!id_sub_gen){ id_sub_gen=0; }
        jQuery("#table_SubGen_Detalle").jqGrid({
            url: 'get_subgenerica_detalle?anio='+$("#vw_esp_det_anio").val()+'&id_sub_gen='+id_sub_gen,
            datatype: 'json', mtype: 'GET',
            height: 100, autowidth: true,
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
                }else{
                    $('#table_Especifica').jqGrid('clearGridData');                    
                }
            },
            onSelectRow: function (Id) {fn_actualizar_grilla('table_Especifica','get_especifica?anio='+$("#vw_esp_det_anio").val()+'&id_sub_gen_det='+Id);}            
        });        
    }, 1000);
    setTimeout(function(){ 
        id_sub_gen_det = $('#table_SubGen_Detalle').jqGrid ('getGridParam', 'selrow');
        if(!id_sub_gen_det){ id_sub_gen_det=0; }
        jQuery("#table_Especifica").jqGrid({
            url: 'get_especifica?anio='+$("#vw_esp_det_anio").val()+'&id_sub_gen_det='+id_sub_gen_det,
            datatype: 'json', mtype: 'GET',
            height: 100, autowidth: true,
            toolbarfilter: true,
            colNames: ['cod_especifica','Codigo', 'Descripción - Especifica'],
            rowNum: 15, sortname: 'id_especif', sortorder: 'asc', viewrecords: true, caption: 'Especifica', align: "center",
            colModel: [     
                {name: 'cod_especifica', index: 'cod_especifica',hidden:true},
                {name: 'cod', index: 'cod', align: 'center', width: 50},
                {name: 'desc', index: 'desc', align: 'left', width: 300}            
            ],
            pager: '#p_table_Especifica',
            rowList: [15, 20],
            gridComplete: function () {
                var idarray = jQuery('#table_Especifica').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                    var firstid = jQuery('#table_Especifica').jqGrid('getDataIDs')[0];
                    $("#table_Especifica").setSelection(firstid);
                }else{
                    $('#table_Esp_Detalle').jqGrid('clearGridData');                    
                }
            },
            onSelectRow: function (Id){fn_actualizar_grilla('table_Esp_Detalle','get_esp_detalle?anio='+$("#vw_esp_det_anio").val()+'&id_espec='+Id);}            
        });        
    }, 1500);
    setTimeout(function(){ 
        id_espec = $('#table_Especifica').jqGrid ('getGridParam', 'selrow');
        if(!id_espec){ id_espec=0; }
        jQuery("#table_Esp_Detalle").jqGrid({
            url: 'get_esp_detalle?anio='+$("#vw_esp_det_anio").val()+'&id_espec='+id_espec,
            datatype: 'json', mtype: 'GET',
            height: 180, autowidth: true,
            toolbarfilter: true,
            colNames: ['cod_esp_det','Codigo', 'Descripción - Especifica'],
            rowNum: 15, sortname: 'id_espec_det', sortorder: 'asc', viewrecords: true, caption: 'Especifica Detalle', align: "center",
            colModel: [     
                {name: 'cod_esp_det', index: 'cod_esp_det',hidden:true},
                {name: 'cod', index: 'cod', align: 'center', width: 50},
                {name: 'desc', index: 'desc', align: 'left', width: 300}            
            ],
            pager: '#p_table_Esp_Detalle',
            rowList: [15, 20],
            gridComplete: function () {
                var idarray = jQuery('#table_Esp_Detalle').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                    var firstid = jQuery('#table_Esp_Detalle').jqGrid('getDataIDs')[0];
                    $("#table_Esp_Detalle").setSelection(firstid);
                }
            },            
            ondblClickRow: function (Id) {
                perms = {!! json_encode($permisos[0]->btn_edit) !!};
                if(perms==1){
                    up_dlg_esp_detalle();
                }else sin_permiso();
            }
        });
        MensajeDialogLoadAjaxFinish('content');
    }, 2500);
    $(window).on('resize.jqGrid', function () {
        $("#table_Generica").jqGrid('setGridWidth', $("#con2").width());
        $("#table_SubGenerica").jqGrid('setGridWidth', $("#con3").width());
    });
});
</script>
@stop
<script src="{{ asset('archivos_js/presupuesto/esp_detalle.js') }}"></script>
<div id="dlg_esp_detalle" style="display: none;">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">                
                <div class="panel panel-success" style="border: 0px !important">                    
                    <div class="panel-body">
                        <fieldset>                            
                            <section>
                                <label class="label">Código:</label>
                                <label class="input">
                                    <input id="esp_det_cod" onkeypress="return soloDNI(event);" type="text" placeholder="0" class="input-sm" style="width:80px;">
                                </label>                        
                            </section>
                            <section>
                                <label class="label">Descripción:</label>
                                <label class="input">
                                    <input id="esp_det_desc" type="text" placeholder="Descripción" class="input-sm text-uppercase">
                                </label>                      
                            </section>
                            <section>
                                <label class="label">Cod. Patrimonial Debe:</label>
                                <label class="input">
                                    <input id="inp_cod_pat_debe" type="text" placeholder="Ingrese código patrimonial DEBE" class="input-sm " onkeypress="return soloNumeroTab(event);">
                                </label>                      
                            </section>
                            <section>
                                <label class="label">Cod. Patrimonial Haber:</label>
                                <label class="input">
                                    <input id="inp_cod_pat_haber" type="text" placeholder="Ingrese código patrimonial HABER" class="input-sm" onkeypress="return soloNumeroTab(event);">
                                </label>                      
                            </section>
                            <section>
                                <label class="label">Fuente de Financiamiento:</label>
                                <label class="input">
                                   <select id='sel_id_fte' class="form-control" style="height: 32px;" >
                                        @foreach ($fte_financiamiento as $fte)
                                        <option value='{{$fte->id_fte_fto}}' >{{$fte->descripcion}}</option>
                                        @endforeach
                                   </select>
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

