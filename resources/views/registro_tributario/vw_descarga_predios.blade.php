@extends('layouts.app')
@section('content')

            <section id="widget-grid" class="">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -12px">
                        <div class="well well-sm well-light">
                            <h1 class="txt-color-green"><b>
                                    <h1 class="txt-color-green"><b>BAJA DE PREDIOS</b></h1>
                            <div class="row">
                                
                                
                                <div class="col-xs-3">
                                    <div class="input-group input-group-md" style="width: 100%">
                                        <span class="input-group-addon" style="width: 120px">DESDE &nbsp;<i class="fa fa-calendar"></i></span>
                                        <div>
                                            <input id="dlg_fec_desde" onchange="selecciona_fecha();" name="dlg_fec_desde" type="text"   class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 30px; width: 110%" placeholder="--/--/----" value="{{date('01/m/Y')}}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xs-3">
                                    <div class="input-group input-group-md" style="width: 100%">
                                        <span class="input-group-addon" style="width: 120px">HASTA &nbsp;<i class="fa fa-calendar"></i></span>
                                        <div>
                                            <input id="dlg_fec_hasta" onchange="selecciona_fecha();" name="dlg_fec_hasta" type="text"   class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 30px; width: 110%" placeholder="--/--/----" value="{{date('15/m/Y')}}">
                                        </div>
                                    </div>
                                </div>
                                
                                
                                
                                <div class="col-xs-6">
                                    <div class="text-right">
                                        @if( $permisos[0]->btn_new ==1 )
                                            <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="nuevo_dpredios();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>GENERER BAJA
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="sin_permiso();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>GENERAR BAJA
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" id="current_id" value="0">
                        <table id="tabla_descarga_predios"></table>
                        <div id="pager_tabla_descarga_predios"></div>
                    </article>
                </div>
            </section>


</section>

@section('page-js-script')
<script type="text/javascript">

    $(document).ready(function () {
        $("#menu_admtri").show();
        $("#li_descarga_predios").addClass('cr-active');
        
        fecha_desde = $("#dlg_fec_desde").val(); 
        fecha_hasta = $("#dlg_fec_hasta").val(); 

        var pageWidth = $("#tabla_descarga_predios").parent().width() - 100;
        contrib_global=0;
        
        jQuery("#tabla_descarga_predios").jqGrid({
            url: 'obtener_descarga_predios?fecha_desde='+fecha_desde +'&fecha_hasta='+fecha_hasta,
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            toolbarfilter: true,
            colNames: ['ID','FECHA','MOTIVO','DOCUMENTACION'],
            rowNum: 20,sortname: 'id_trans', viewrecords: true, caption: 'DESCARGA DE PREDIOS', align: "center",
            colModel: [
                {name: 'id_trans', index: 'id_trans', align: 'center', hidden:true,width:(pageWidth*(20/100))},
                {name: 'fch_transf', index: 'fch_transf', align: 'center', width:(pageWidth*(40/100))}, 
                {name: 'desc_motivo', index: 'desc_motivo', align: 'center', width:(pageWidth*(90/100))},
                {name: 'ver', index: 'ver', align: 'center', width: (pageWidth*(50/100))},
            ],
            pager: '#pager_tabla_descarga_predios',
            rowList: [10, 20],
            gridComplete: function () {
                    var idarray = jQuery('#tabla_descarga_predios').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_descarga_predios').jqGrid('getDataIDs')[0];
                            $("#tabla_descarga_predios").setSelection(firstid);
                        }
                },
            onSelectRow: function (Id){
                $('#current_id').val($("#tabla_descarga_predios").getCell(Id, "id_trans"));
            }
        });

        
        
        $(window).on('resize.jqGrid', function () {
            $("#tabla_descarga_predios").jqGrid('setGridWidth', $("#content").width());
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
                    jQuery('#table_contribuyente').jqGrid('bindKeys', {"onEnter": function (rowid) { fn_bus_contrib_predio(rowid); }});
                },
            onSelectRow: function (Id){
                $('#current_id').val($("#table_contribuyente").getCell(Id, "id_contrib"));

            }
        });
        
        
        jQuery("#tabla").jqGrid({
        url: 'obtener_predios?id_contrib='+0+'&anio='+0,
        datatype: 'json', mtype: 'GET',
        height: 200, width: 950,
        toolbarfilter: true,
        colNames: ['ID_PRED', 'CODIGO VIA', 'SECTOR', 'MANZANA', 'LOTE', 'REFERENCIA'],
        rowNum: 12, sortname: 'id_contrib', sortorder: 'asc', viewrecords: true, caption: 'PREDIOS CONTRIBUYENTE', align: "center",
        colModel: [
            {name: 'id_pred_contri', index: 'id_pred_contri',width: 20,align:'center', hidden:true},
            {name: 'cod_via', index: 'cod_via',width: 60,align:'center'},
            {name: 'sector', index: 'sector',width: 50, align:'center'},
            {name: 'mzna', index: 'mzna', width: 50},
            {name: 'lote', index: 'lote', width: 60, align:'center'},
            {name: 'referencia', index: 'referencia', align: 'center', width: 50}
        ],
        pager: '#pager_tabla',
        rowList: [10, 20],
        gridComplete: function () {
            var idarray = jQuery('#tabla').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla').jqGrid('getDataIDs')[0];
                            $("#tabla").setSelection(firstid);
                        }            
   
        },            
        onSelectRow: function (Id){
                $('#current_id_tabla').val($("#tabla").getCell(Id, "id_pred_contri"));

        }
    });  
        
         

    });

</script>
@stop

<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/registro_tributario/dpredios.js') }}"></script>


<div id="dlg_nuevo_dpredios" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <div class="panel panel-success">
                    <div class="panel-heading bg-color-success">.:: Datos del Contribuyente ::.</div>
                    <div class="panel-body cr-body">
                        <fieldset>
                            <div class="row">                                
                                <section class="col col-3" style="padding-right: 5px;">
                                    <input type="hidden" id="dlg_id_contribuyente">
                                    <label class="label">Cod Contrib:</label>
                                    <label class="input">
                                        <input id="dlg_codigo" type="text" onkeypress="return soloDNI(event);"  placeholder="00000000" class="input-sm" disabled="">
                                    </label>                      
                                </section>
                                <section class="col col-6" style="padding-left: 5px;padding-right:5px; ">
                                    <label class="label">Contribuyente:</label>
                                    <label class="input">
                                        <input type="hidden" id="dlg_hidden_contribuyente">
                                        <input id="dlg_contribuyente" type="text" placeholder="ejm. jose min 4 caracteres" class="input-sm text-uppercase">
                                    </label>
                                </section>
                                
                                <section class="col col-3" >
                                    <label class="label">Año:</label>                                   
                                    <label class="select">
                                        <select id="dlg_anio" class="form-control input-sm" onchange="obtener_predios_anio();">
                                                @foreach ($anio as $anio_dpredios)
                                                <option value='{{$anio_dpredios->anio}}' >{{$anio_dpredios->anio}}</option>
                                                @endforeach
                                            </select><i></i>                       
                                </section>
                                
                            </div>                            
                        </fieldset>
                    </div>
                </div>
                <div class="panel panel-success">
                    <div class="panel-heading bg-color-success">.:: Datos de Predio ::.</div>
                    <div class="panel-body">                        
                        <fieldset>
                            
                            <section>                                
                                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
                                    <input type="hidden" id="current_id_tabla" value="0">
                                    <table id="tabla"></table>
                                    <div id="pager_tabla">                                        
                                    </div>
                                </article>
                            </section>
                            <div class="row">
                                
                                <section class="col col-6" >
                                    <label class="label">Motivo:</label>                                   
                                    <label class="select">
                                        <select id="dlg_motivos" class="form-control input-sm">
                                                @foreach ($motivos as $motivo)
                                                <option value='{{$motivo->id_motivo}}' >{{$motivo->motivo}}</option>
                                                @endforeach
                                            </select><i></i>                       
                                </section>
                                <section class="col col-6">                                   
                                    <label class="label">Fecha:</label>
                                    <label class="input">
                                        <input placeholder="dd/mm/aaaa" id="dlg_fecha" class="form-control datepicker" data-dateformat='dd/mm/yy' value="{{date('d/m/Y')}}" maxlength="10">
                                    </label>                        
                                </section>
                                                                                        
                            </div>                            
                            <section>
                                <label class="label">Comprador:</label>
                                <label class="input">
                                    <input id="dlg_comprador" type="text" placeholder="Nombre Completo del comprador" class="input-sm text-uppercase" maxlength="150">                                  
                                </label>                                       
                            </section>
                            
                            <section>
                                <label class="label">Glosa:</label>
                                <label class="textarea">
                                    <textarea id="dlg_glosa" rows="2" placeholder="descripcion de recibo" class="input-sm text-uppercase"></textarea>                                    
                                </label>                                       
                            </section>
                            
                        </fieldset>
                    </div>
                </div>
            </div>                   
        </div>        
    </div>
</div>

<div id="dlg_bus_contribuyente" style="display: none;">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
        <table id="table_contribuyente"></table>
        <div id="pager_table_contribuyente"></div>
    </article>
</div> 
@endsection




