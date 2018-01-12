@extends('layouts.app')
@section('content')

            <section id="widget-grid" class="">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -12px">
                        <div class="well well-sm well-light">
                            <h1 class="txt-color-green"><b>
                                    <h1 class="txt-color-green"><b>DESCARGA DE PREDIOS</b></h1>
                            <div class="row">
                                
                                
                                <div class="col-xs-3">
                                    <div class="input-group input-group-md" style="width: 100%">
                                        <span class="input-group-addon" style="width: 120px">DESDE &nbsp;<i class="fa fa-calendar"></i></span>
                                        <div>
                                        <input id="dlg_fec_desde" name="dlg_fec_desde" type="text"   class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 30px; width: 110%" placeholder="--/--/----" value="{{date('01/m/Y')}}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xs-3">
                                    <div class="input-group input-group-md" style="width: 100%">
                                        <span class="input-group-addon" style="width: 120px">HASTA &nbsp;<i class="fa fa-calendar"></i></span>
                                        <div>
                                        <input id="dlg_fec_hasta" name="dlg_fec_hasta" type="text"   class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 30px; width: 110%" placeholder="--/--/----" value="{{date('01/m/Y')}}">
                                        </div>
                                    </div>
                                </div>
                                
                                
                                
                                <div class="col-xs-6">
                                    <div class="text-right">
                                        @if( $permisos[0]->btn_new ==1 )
                                            <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="nuevo_dpredios();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="sin_permiso();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                            </button>
                                        @endif
                                        @if( $permisos[0]->btn_edit ==1 )
                                            <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="actualizar_tim();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                            </button>
                                        @else
                                            <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="sin_permiso();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                            </button>
                                        @endif
                                        @if( $permisos[0]->btn_del ==1 )
                                        <button  type="button" class="btn btn-labeled btn-danger" onclick="eliminar_tim();">
                                            <span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span>Eliminar
                                        </button>
                                        @else
                                            <button  type="button" class="btn btn-labeled btn-danger" onclick="sin_permiso();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span>Eliminar
                                            </button>
                                        @endif
                                        @if( $permisos[0]->btn_imp ==1 )
                                        <button type="button" class="btn btn-labeled bg-color-magenta txt-color-white">
                                            <span class="btn-label"><i class="glyphicon glyphicon-print"></i></span>Imprimir
                                        </button>
                                        @else
                                        <button type="button" class="btn btn-labeled bg-color-magenta txt-color-white" onclick="sin_permiso();">
                                            <span class="btn-label"><i class="glyphicon glyphicon-print"></i></span>Imprimir
                                        </button>
                                        @endif
                                      
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" id="current_id" value="0">
                        <table id="tabla_tim"></table>
                        <div id="pager_tabla_tim"></div>
                    </article>
                </div>
            </section>


</section>

@section('page-js-script')
<script type="text/javascript">

    $(document).ready(function () {
        $("#menu_registro_tributario").show();
        $("#li_descarga_predios").addClass('cr-active');
        anio = $("#select_anio").val(); 

        var pageWidth = $("#tabla_tim").parent().width() - 100;
        contrib_global=0;
        jQuery("#tabla_tim").jqGrid({
            url: 'obtener_usuarios?dat=0',
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            toolbarfilter: true,
            colNames: ['ID','FECHA','MOTIVO','AÑO'],
            rowNum: 20,sortname: 'id_tim', viewrecords: true, caption: 'DESCARGA DE PREDIOS', align: "center",
            colModel: [
                {name: 'id_tim', index: 'id_tim', align: 'center', hidden:true,width:(pageWidth*(20/100))},
                {name: 'documento_aprob', index: 'documento_aprob', align: 'center', width:(pageWidth*(80/100))}, 
                {name: 'tim', index: 'tim', align: 'center', width:(pageWidth*(50/100))},
                {name: 'anio', index: 'anio', align: 'center', hidden:true, width:(pageWidth*(20/100))},

            ],
            pager: '#pager_tabla_tim',
            rowList: [10, 20],
            gridComplete: function () {
                    var idarray = jQuery('#tabla_tim').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_tim').jqGrid('getDataIDs')[0];
                            $("#tabla_tim").setSelection(firstid);
                        }
                },
            onSelectRow: function (Id){
                $('#current_id').val($("#tabla_tim").getCell(Id, "id_tim"));

            },
            ondblClickRow: function (Id){
                $('#current_id').val($("#tabla_tim").getCell(Id, "id_tim"));
                actualizar_tim();}
        });

        
        
        $(window).on('resize.jqGrid', function () {
            $("#tabla_tim").jqGrid('setGridWidth', $("#content").width());
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
            rowNum: 12,sortname: 'id_contrib', viewrecords: true, caption: 'CONTRIBUYENTES', align: "center",
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

            },
            ondblClickRow: function (Id){
                $('#current_id').val($("#table_contribuyente").getCell(Id, "id_contrib"));
                actualizar_tim();}
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
                                        <input id="dlg_codigo" type="text" onkeypress="return soloDNI(event);"  placeholder="00000000" class="input-sm">
                                    </label>                      
                                </section>
                                <section class="col col-9" style="padding-left: 5px;padding-right:5px; ">
                                    <label class="label">Contribuyente:</label>
                                    <label class="input">
                                        <input type="hidden" id="dlg_hidden_contribuyente">
                                        <input id="dlg_contribuyente" type="text" placeholder="ejm. jose min 4 caracteres" class="input-sm text-uppercase">
                                    </label>
                                </section>
                                
                            </div>                            
                        </fieldset>
                    </div>
                </div>
                <div class="panel panel-success">
                    <div class="panel-heading bg-color-success">.:: Datos de Recibo ::.</div>
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
                                        <input id="dlg_fecha" type="text" placeholder="000000" value="{{date('d-m-Y')}}" class="input-sm" disabled="">
                                    </label>                        
                                </section>
                                                                                        
                            </div>                            
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




