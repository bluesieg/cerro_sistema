@extends('layouts.app')
@section('content')

            <section id="widget-grid" class="">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -12px">
                        <div class="well well-sm well-light">
                            <h1 class="txt-color-green"><b>
                                    <h1 class="txt-color-green"><b>PRESCRIPCIONES</b></h1>
                            <div class="row">
                                
                                <div class="col-xs-3">
                                    <label>Año:</label>
                                    <label class="select">
                                        <select onchange="selecciona_anio_prescripcion();" id="select_anio" class="input-sm">
                                            @foreach ($anio as $anio_prescripcion)
                                                <option value='{{$anio_prescripcion->anio}}' >{{$anio_prescripcion->anio}}</option>
                                            @endforeach
                                        </select><i></i>
                                    </label>
                                </div>
                                
                                <div class="col-xs-9">
                                    <div class="text-right">
                                        @if( $permisos[0]->btn_new ==1 )
                                            <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="nueva_prescripcion();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>NUEVA PRESCRIPCION
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="sin_permiso();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>NUEVA PRESCRIPCION
                                            </button>
                                        @endif
                                        @if( $permisos[0]->btn_imp ==1 )
                                            <button onclick="reporte_prescripciones();" type="button" class="btn btn-labeled bg-color-magenta txt-color-white">
                                                <span class="btn-label"><i class="glyphicon glyphicon-print"></i></span>IMPRIMIR
                                            </button>
                                        @else
                                            <button onclick="sin_permiso();" type="button" class="btn btn-labeled bg-color-magenta txt-color-white">
                                                <span class="btn-label"><i class="glyphicon glyphicon-print"></i></span>IMPRIMIR
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <table id="tabla_prescripciones"></table>
                        <div id="pager_tabla_prescripciones">
                            <div style="float: right; font-weight: bold;">
                                Total S/. <input type="text" id="total_prescripciones" class="input-sm text-center" style="width: 110px; height: 25px;padding-right: 4px;" readonly="">
                            </div>
                        </div>
                    </article>
                </div>
            </section>


</section>

@section('page-js-script')
<script type="text/javascript">

    $(document).ready(function () {
        $("#menu_recaudacion").show();
        $("#li_prescripciones").addClass('cr-active');
        anio = $("#select_anio").val(); 

        var pageWidth = $("#tabla_prescripciones").parent().width() - 100;

        jQuery("#tabla_prescripciones").jqGrid({
            url:'get_prescripciones?anio='+anio,
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            toolbarfilter: true,
            colNames: ['ID','CONTRIBUYENTE','Nº RESOLUCION','FECHA','TOTAL'],
            rowNum: 20,sortname: 'id_presc', viewrecords: true, caption: 'PRESCRIPCIONES', align: "center",
            colModel: [
                {name: 'id_presc', index: 'id_presc', align: 'center',hidden:true, width:(pageWidth*(20/100))},
                {name: 'contribuyente', index: 'contribuyente', align: 'left', width:(pageWidth*(80/100))},
                {name: 'nro_resolucion', index: 'nro_resolucion', align: 'left', width:(pageWidth*(40/100))},
                {name: 'fecha_resolucion', index: 'fecha_resolucion', align: 'center', width:(pageWidth*(40/100))},
                {name: 'total', index: 'total', align: 'center', formatter: "integer", sorttype: "number",formatoptions:{decimalPlaces: 2}, width:(pageWidth*(20/100))}
            ],
            pager: '#pager_tabla_prescripciones',
            rowList: [10, 20],
            gridComplete: function () {
                    var idarray = jQuery('#tabla_prescripciones').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_prescripciones').jqGrid('getDataIDs')[0];
                            $("#tabla_prescripciones").setSelection(firstid);
                        }
                    var sum = jQuery("#tabla_prescripciones").getGridParam('userData').sum;
                    if(sum==undefined){
                        $("#total_prescripciones").val('000.00');
                    }else{
                        $("#total_prescripciones").val(formato_numero(sum,2,'.',','));
                    }
                }
        });

        $(window).on('resize.jqGrid', function () {
            $("#tabla_prescripciones").jqGrid('setGridWidth', $("#content").width());
        });
        
        var globalvalidador=0;
        $("#dlg_contribuyente").keypress(function (e) {
                    if (e.which == 13) {
                        if(globalvalidador==0)
                        {
                            tot_deuda=0;
                            $("#total_deuda").val('0.00');
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
                    jQuery('#table_contribuyente').jqGrid('bindKeys', {"onEnter": function (rowid) { fn_bus_deuda(rowid); }});
                },
                ondblClickRow: function (Id){fn_bus_deuda(Id)}
        });
        
        
        
        jQuery("#tabla_deuda").jqGrid({
        url: 'obtener_deudas?id_contrib='+0,
        datatype: 'json', mtype: 'GET',
        height: 200, width:780,
        toolbarfilter: true,
        colNames: ['ID', 'AÑO', 'IMPUESTO', 'DEUDA', ''],
        rowNum: 12, sortname: 'id_cta_cte', sortorder: 'asc', viewrecords: true, caption: 'DEUDA ACTUAL', align: "center",
        colModel: [
            {name: 'id_cta_cte', index: 'id_cta_cte',width: 20,align:'center', hidden:true},
            {name: 'anio_deu', index: 'anio_deu',width: 15,align:'center'},
            {name: 'tipo', index: 'tipo',width: 60, align:'left'},
            {name: 'deuda', index: 'deuda', width: 25, align:'center'},
            {name: 'check', index: 'check', width: 10, align:'center'}
        ],
        pager: '#pager_tabla_deuda',
        rowList: [10, 20],
        gridComplete: function () {
            var idarray = jQuery('#tabla_deuda').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_deuda').jqGrid('getDataIDs')[0];
                            $("#tabla_deuda").setSelection(firstid);
                        }
            var sum = jQuery("#tabla_deuda").getGridParam('userData').sum;
            $("#total_deuda").val('0.00');
                                                       
        }
    });  

    });
    
    

</script>
@stop

<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/recaudacion/prescripciones.js') }}"></script>
<div id="dlg_nueva_prescripcion" style="display: none;">
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
                <input type="hidden" id="hidden_cta_cte" value="0">
                
                <div class="col-xs-12" style="padding: 0px; margin-bottom: 10px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 165px">CONTRIBUYENTE: &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <input id="dlg_hidden_contribuyente" type="hidden">
                            <input id="dlg_contribuyente" type="text"  class="form-control text-uppercase" style="height: 32px;" maxlength="100" onkeyup="javascript:this.value=this.value.toUpperCase();">
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0px !important; margin-bottom: 10px !important">
                        <div>
                        <fieldset>
                                                          
                                <section class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-right:5px !important;width: 60% !important">                                    
                                    <!--<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0px !important">-->
                                        <table id="tabla_deuda"></table>
                                        <div id="pager_tabla_deuda">
                                            <div style="float: right; font-weight: bold;">
                                                Total S/. <input type="text" id="total_deuda" class="input-sm text-center" style="width: 110px; height: 25px;padding-right: 4px;" readonly="">
                                            </div>
                                        </div>
                                    <!--</article>-->               
                                </section>                             
                            
                        </fieldset>
                        </div>
                </div>
                
                
                
                <div class="col-xs-12" style="padding: 0px; margin-bottom: 10px; ">
                    <div class="input-group input-group-md" style="width: 80%">
                        <span class="input-group-addon" style="width: 165px" style="height: 5px;">Nº RESOLUCION: &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <input id="dlg_nro_resolucion" type="text"  class="input-sm col-xs-7 text-center text-uppercase" style="height: 32px;" maxlength="100" onkeyup="javascript:this.value=this.value.toUpperCase();">
                        </div>
                        
                        <div class="col-xs-3">
                            <div class="input-group input-group-md" style="width: 100%">
                            <span class="input-group-addon" style="width: 120px">FECHA DE RESOLUCION &nbsp;<i class="fa fa-calendar"></i></span>
                            <input id="dlg_fecha_resolucion" name="dlg_fec_transferencia" type="text"   class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 32px" placeholder="--/--/----" value="{{date('01/m/Y')}}">
                            </div>
                        </div>
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




