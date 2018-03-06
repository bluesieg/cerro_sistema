@extends('layouts.app')
@section('content')
<section id="widget-grid" class="">    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -12px">
            <div class="well well-sm well-light">
                <h1 class="txt-color-green"><b>Sub - Genérica...</b></h1>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="text-right">
                            <div class="col-xs-2 col-sm-12 col-md-12 col-lg-6 text-left">
                                <label>Filtro Año:</label>
                                <select id="vw_subgen_anio"  class="input-sm" onchange="selecciona_anio();">
                                    @foreach ($anio as $anio)
                                    <option value='{{$anio->anio}}' >{{$anio->anio}}</option>
                                    @endforeach
                                </select><i></i>
                            </div>
                            @if( $permisos[0]->btn_new ==1 )
                                <button onclick="dlg_subgenerica();" type="button" class="btn btn-labeled bg-color-greenLight txt-color-white">
                                    <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                </button>
                            @else
                                <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="sin_permiso()">
                                    <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                </button>
                            @endif
                            @if( $permisos[0]->btn_edit ==1 )
                                <button onclick="up_dlg_subgenerica();" type="button" class="btn btn-labeled bg-color-blue txt-color-white">
                                    <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                </button>
                            @else
                                <button onclick="sin_permiso();" type="button" class="btn btn-labeled bg-color-blue txt-color-white">
                                    <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                </button>
                            @endif
                            @if( $permisos[0]->btn_del ==1 )
                                <button onclick="del_subgen();" id="btn_vw_contribuyentes_Eliminar" type="button" class="btn btn-labeled btn-danger">
                                    <span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span>Eliminar
                                </button>
                            @else
                                <button onclick="sin_permiso();" id="btn_vw_contribuyentes_Eliminar" type="button" class="btn btn-labeled btn-danger">
                                <span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span>Eliminar
                            </button>
                            @endif                            
                        </div>                        
                    </div>
                </div> 
            </div>
            <div class="well well-sm well-light" style="margin-top:-20px;">                
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">                        
                        <div class="row">
                            <section id="con2" class="col-lg-6">
                                <table id="table_Generica"></table>
                                <div id="p_table_Generica"></div>
                            </section>
                            <section id="con3" class="col-lg-6">
                                <table id="table_SubGenerica"></table>
                                <div id="p_table_SubGenerica"></div>
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
$(document).ready(function () {
    $("#menu_presupuesto").show();
    $("#li_pres_subgen").addClass('cr-active');
    MensajeDialogLoadAjax('content','Cargando');
    jQuery("#table_Generica").jqGrid({
        url: 'get_generica?anio='+$("#vw_subgen_anio").val(),
        datatype: 'json', mtype: 'GET',
        height: 'auto', autowidth: true,
        toolbarfilter: true,
        colNames: ['ID','Codigo', 'Descripción - Genérica'],
        rowNum: 15, sortname: 'id_gener', sortorder: 'asc', viewrecords: true, caption: 'Generica', align: "center",
        colModel: [
            {name: 'id_gener', index: 'id_gener', align: 'center', width: 50, hidden:true},
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
        onSelectRow: function (Id) { fn_actualizar_grilla('table_SubGenerica','get_subgenerica?anio='+$("#vw_subgen_anio").val()+'&id_gener='+Id); }
    });
    
    
    setTimeout(function(){ 
        id_gener = $('#table_Generica').jqGrid ('getGridParam', 'selrow');    
        jQuery("#table_SubGenerica").jqGrid({
            url: 'get_subgenerica?anio='+$("#vw_subgen_anio").val()+'&id_gener='+id_gener,
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
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
                }
            },
            onSelectRow: function (Id) {},
            ondblClickRow: function (Id) { 
                perms = {!! json_encode($permisos[0]->btn_edit) !!};
                if(perms==1){
                    up_dlg_subgenerica();
                }else sin_permiso();
            }
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
<script src="{{ asset('archivos_js/presupuesto/subgenerica.js') }}"></script>
<div id="dlg_subgen" style="display: none;">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">                
                <div class="panel panel-success" style="border: 0px !important">                    
                    <div class="panel-body">
                        <fieldset>                            
                            <section>
                                <label class="label">Código:</label>
                                <label class="input">
                                    <input id="subgen_cod" onkeypress="return soloDNI(event);" type="text" placeholder="0" class="input-sm" style="width:80px;">
                                </label>                        
                            </section>
                            <section>
                                <label class="label">Descripción:</label>
                                <label class="input">
                                    <input id="subgen_desc" type="text" placeholder="Descripción" class="input-sm text-uppercase">
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

