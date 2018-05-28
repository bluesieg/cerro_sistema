@extends('layouts.app')
@section('content')
<section id="widget-grid" class="">    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -12px">
            <div class="well well-sm well-light">
                <h1 class="txt-color-green"><b>Procedimientos...</b></h1>
                <div class="row">
                    
                        <div class="col-xs-2">
                            <label>Filtro Año:</label>
                            <label class="select">
                                <select id="vw_procedim_anio"  class="input-sm" onchange="seleccionar_anio();">
                                    @foreach ($anio as $anio)
                                    <option value='{{$anio->anio}}' >{{$anio->anio}}</option>
                                    @endforeach
                                </select><i></i>
                            </label>
                        </div>
                     
                        <div class="col-xs-6"> 
                            <input id="dlg_buscar_procedimiento" type="text"  class="form-control" style="height: 36px;font-size: 0.9em;width: 102% !important" placeholder="ESCRIBIR EL NOMBRE DEL PROCEDIMIENTO">
                        </div>
                    
                        <div class="col-xs-4">
                            <div class="text-right">    
                                <button 
                                    @if($permisos[0]->btn_new==1) onclick="dlg_procedimiento();"  @else onclick="sin_permiso();" @endif
                                     type="button" class="btn btn-labeled bg-color-greenLight txt-color-white">
                                    <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                </button>
                                <button 
                                    @if($permisos[0]->btn_edit==1) onclick="up_dlg_procedimiento();"  @else onclick="sin_permiso();" @endif
                                     type="button" class="btn btn-labeled bg-color-blue txt-color-white">
                                    <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                </button>
                                <button 
                                    @if($permisos[0]->btn_del==1) onclick="del_procedimiento();"  @else onclick="sin_permiso();" @endif
                                     type="button" class="btn btn-labeled btn-danger">
                                    <span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span>Eliminar
                                </button>
                            </div> 
                        </div>                      
                    
                </div> 
            </div>
            <div class="well well-sm well-light" style="margin-top:-20px;">                
                <div class="row">
                    <div class="col-xs-12">                        
                        <div class="row">
                            <section id="content_2" class="col-lg-12">
                                <table id="table_Procedimiento"></table>
                                <div id="p_table_Procedimiento"></div>
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
    $("#li_pres_proced").addClass('cr-active');
    seleccionar_anio();
    anio = $("#vw_procedim_anio").val();
    
    jQuery("#table_Procedimiento").jqGrid({
        url: 'get_procedimientos?anio=' + anio,
        datatype: 'json', mtype: 'GET',
        height: 'auto', autowidth: true,
        toolbarfilter: true,
        colNames: ['Codigo', 'Descripción - Procedimiento','id_esp_det','desc','id_ofi','nombre'],
        rowNum: 15, sortname: 'id_proced', sortorder: 'asc', viewrecords: true, caption: 'Procedimientos', align: "center",
        colModel: [            
            {name: 'cod_proc', index: 'cod_proc', align: 'center', width: 50},
            {name: 'desc_proc', index: 'desc_proc', align: 'left', width: 300},
            {name: 'id_espec_det', index: 'id_espec_det', hidden:true},
            {name: 'desc_espec_detalle', index: 'desc_espec_detalle', hidden:true},
            {name: 'id_ofic', index: 'id_ofic', hidden:true},
            {name: 'nombre', index: 'nombre', hidden:true},
        ],
        pager: '#p_table_Procedimiento',
        rowList: [15, 20],
        gridComplete: function () {
            var idarray = jQuery('#table_Procedimiento').jqGrid('getDataIDs');
            if (idarray.length > 0) {
                var firstid = jQuery('#table_Procedimiento').jqGrid('getDataIDs')[0];
                $("#table_Procedimiento").setSelection(firstid);
            }
        },
        ondblClickRow: function (Id) {
            perms = {!! json_encode($permisos[0]->btn_edit) !!};
            if(perms==1){
                up_dlg_procedimiento();
            }else sin_permiso();
        }
    });
    $(window).on('resize.jqGrid', function () {
        $("#table_Procedimiento").jqGrid('setGridWidth', $("#content_2").width());
    });
});

    $("#dlg_buscar_procedimiento").keypress(function (e) {
        if (e.which == 13) {

               fn_buscar_procedimiento();

        }
    });
</script>
@stop
<script src="{{ asset('archivos_js/presupuesto/procedimientos.js') }}"></script>
<div id="dlg_proced" style="display: none;">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">                
                <div class="panel panel-success" style="border: 0px !important">                    
                    <div class="panel-body cr-body">
                        <fieldset>
                            <section>
                                <label class="label">Específica Detalle:</label>
                                <label class="input">
                                    <input type="hidden" id="hiddenproced_esp_det">
                                    <input id="proced_esp_det" type="text" placeholder="Específica Detalle" class="input-sm text-uppercase">
                                </label>                        
                            </section>
                            <section>
                                <label class="label">Oficina:</label>
                                <label class="input">
                                    <input type="hidden" id="hiddenproced_ofi">
                                    <input id="proced_ofi" type="text" placeholder="Escriba el nombre de de la oficina" class="input-sm text-uppercase">
                                </label>                        
                            </section>
<!--                            <section>
                                <label class="label">Código:</label>
                                <label class="input">
                                    <input id="proced_cod" onkeypress="return soloDNI(event);" type="text" placeholder="0" class="input-sm" style="width:80px;">
                                </label>                        
                            </section>-->
                            <section>
                                <label class="label">Descripción:</label>
                                <label class="input textarea">
                                    <!--<input id="proced_desc" type="textarea" placeholder="Descripción" class="input-sm text-uppercase">-->
                                    <textarea rows="12" id="proced_desc" placeholder="Descripción" class="input-sm text-uppercase"></textarea>
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
