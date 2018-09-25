@extends('layouts.app')
@section('content')
<section id="widget-grid" class="">    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -12px">
            <div class="well well-sm well-light">
                <h1 class="txt-color-green"><b>:: MANTENIMIENTO CAJAS ::</b></h1>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="text-right">
                            
                            @if( $permisos[0]->btn_new ==1 )
                                <button onclick="nueva_caja();" type="button" class="btn btn-labeled bg-color-greenLight txt-color-white">
                                    <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                </button>
                            @else
                                <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="sin_permiso()">
                                    <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                </button>
                            @endif
                            @if( $permisos[0]->btn_edit ==1 )
                                <button onclick="modificar_caja();" type="button" class="btn btn-labeled bg-color-blue txt-color-white">
                                    <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                </button>
                            @else
                                <button onclick="sin_permiso();" type="button" class="btn btn-labeled bg-color-blue txt-color-white">
                                    <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                </button>
                            @endif
                        </div>                        
                    </div>
                </div> 
            </div>
            <div class="well well-sm well-light" style="margin-top:-20px;">                
                <div class="row">
                    <div class="col-xs-12">                        
                        <div class="row">
                            <section id="content_2" class="col-lg-12">
                                <table id="table_cajas"></table>
                                <div id="p_table_cajas"></div>
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
    $("#menu_tesoreria").show();
    $("#li_config_cajas").addClass('cr-active');
    jQuery("#table_cajas").jqGrid({
        url: 'cajas/0?grid=cajas',
        datatype: 'json', mtype: 'GET',
        height: 'auto', autowidth: true,
        toolbarfilter: true,
        colNames: ['ID','SERIE', 'DESCRIPCION', 'DIRECCION CAJA'],
        rowNum: 15, sortname: 'id_caj', sortorder: 'asc', viewrecords: true, caption: 'MANTENIMIENTO CAJAS', align: "center",
        colModel: [
            {name: 'id_caj', index: 'id_caj', align: 'center', width: 50,hidden:true},
            {name: 'serie', index: 'serie', align: 'center', width: 50},
            {name: 'descrip_caja', index: 'descrip_caja', align: 'left', width: 100},
            {name: 'direc_caja', index: 'direc_caja', align: 'left', width: 100}
        ],
        pager: '#p_table_cajas',
        rowList: [15, 20],
        gridComplete: function () {
            var idarray = jQuery('#table_cajas').jqGrid('getDataIDs');
            if (idarray.length > 0) {
                var firstid = jQuery('#table_cajas').jqGrid('getDataIDs')[0];
                $("#table_cajas").setSelection(firstid);
            }
        },
        onSelectRow: function (Id) {},
        ondblClickRow: function (Id) {
            perms = {!! json_encode($permisos[0]->btn_edit) !!};
            if(perms==1){
                modificar_caja();
            }else sin_permiso();            
        }
    });
    $(window).on('resize.jqGrid', function () {
        $("#table_cajas").jqGrid('setGridWidth', $("#content_2").width());
    });
});
</script>
@stop
<script src="{{ asset('archivos_js/configuracion/cajas.js') }}"></script>

<div id="dlg_nueva_caja" style="display: none;">
    <div class='cr_content col-xs-12 ' style="margin-bottom: 10px;">
    <div class="col-xs-12 cr-body" >
        <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px; margin-top: 0px;">

            <section>
                <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                    <header>
                            <span class="widget-icon"> <i class="fa fa-info"></i> </span>
                            <h2>LLenado de Información::..</h2>
                    </header>
                </div>
            </section>

            <div class="col-xs-12" style="padding: 0px; margin-bottom: 10px; ">
                <div class="input-group input-group-md" style="width: 100%">
                    <span class="input-group-addon" style="width: 165px">DESCRIPCION &nbsp;<i class="fa fa-hashtag"></i></span>
                    <div>
                        <input id="dlg_descripcion" type="text"  class="form-control text-uppercase" style="height: 32px;" maxlength="50">
                    </div>
                </div>
            </div>

            <div class="col-xs-12" style="padding: 0px; margin-bottom: 10px;">
                <div class="input-group input-group-md" style="width: 100%">
                    <span class="input-group-addon" style="width: 165px">DIRECCION &nbsp;<i class="fa fa-hashtag"></i></span>
                    <div>
                        <input id="dlg_direccion" type="text"  class="form-control text-uppercase" style="height: 32px;" maxlength="50">
                    </div>
                </div>
            </div>
            
            <div class="col-xs-12" style="padding: 0px; margin-bottom: 10px;">
                <div class="input-group input-group-md" style="width: 100%">
                    <span class="input-group-addon" style="width: 165px">SERIE &nbsp;<i class="fa fa-hashtag"></i></span>
                    <div>
                        <input id="dlg_serie" type="text"  class="form-control" style="height: 32px;" maxlength="3" onkeypress="return soloNumeroTab(event);">
                    </div>
                </div>
            </div>   
            
        </div>
    </div>
</div>

</div>
@endsection
