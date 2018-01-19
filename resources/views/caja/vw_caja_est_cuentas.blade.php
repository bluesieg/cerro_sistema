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
                                            <h2>ESTADO DE CUENTAS</h2>
                                    </header>
                                </div>
                            </section>
                            <div class="row">
                                <section class="col-lg-3" style="padding-right: 5px;">
                                    <div class="input-group input-group-md">
                                        <span class="input-group-addon">Año Desde:</span>
                                        <div class="icon-addon addon-md">
                                            <select onchange="selanio_est_cta();" id="vw_caja_ets_cta_anio_desde" class="form-control input-sm">
                                                @foreach ($anio1 as $anio1)
                                                <option value='{{$anio1->anio}}' >{{$anio1->anio}}</option>
                                                @endforeach
                                            </select><i></i>
                                        </div>
                                    </div>
                                </section>
                                <section class="col-lg-3" style="padding-left: 5px;">
                                    <div class="input-group input-group-md">
                                        <span class="input-group-addon">Año Hasta:</span>
                                        <div class="icon-addon addon-md">
                                            <select onchange="selanio_est_cta();" id="vw_caja_ets_cta_anio_hasta" class="form-control input-sm">
                                                @foreach ($anio as $anio2)
                                                <option value='{{$anio2->anio}}' >{{$anio2->anio}}</option>
                                                @endforeach
                                            </select><i></i>
                                        </div>
                                    </div>
                                </section>
                                
                                <section class="col lg-3" style="padding-right:175px;">
                                <div class="form-check">
                                        <label class="form-check-label">
                                            <input id="vw_foto" type="hidden" value="0">
                                            <input type="checkbox" class="form-check-input"  id="mostrar_foto" onclick="cambiar_estado();">
                                          MOSTRAR FOTO
                                        </label>
                            </div>
                                 </section>
                            </div>
                            <div class="row" style="margin-top: 7px">
                                <section class="col-lg-3" style="padding-right: 5px;">
                                    <div class="input-group input-group-md">
                                        <span class="input-group-addon">Codigo:</span>
                                        <div class="icon-addon addon-md">
                                            <input id="vw_caja_est_cta_id_contrib" type="hidden" value="0">
                                            <input id="vw_caja_est_cta_cod_contrib" type="text" class="form-control input-sm" >
                                        </div>
                                    </div>
                                </section>
                                <section class="col-lg-6" style="padding-left: 5px;">
                                    <div class="input-group input-group-md">
                                        <span class="input-group-addon">Contribuyente:</span>
                                        <div class="icon-addon addon-md">
                                            <input id="vw_caja_est_cta_contrib" class="form-control input-sm text-uppercase" type="text">
                                        </div>
                                    </div>
                                </section>
                                <section class="col-lg-3 text-align-left" style="padding-left: 5px;">
                                    @if( $permisos[0]->btn_imp ==1 )
                                        <button onclick="print_est_cta();" type="button" class="btn btn-labeled bg-color-magenta txt-color-white">
                                            <span class="btn-label"><i class="glyphicon glyphicon-print"></i></span>Imprimir
                                        </button>
                                    @else
                                        <button onclick="sin_permiso();" type="button" class="btn btn-labeled bg-color-magenta txt-color-white">
                                            <span class="btn-label"><i class="glyphicon glyphicon-print"></i></span>Imprimir
                                        </button>
                                    @endif
                                        <button onclick="print_est_cta_correo(0);" type="button" class="btn btn-labeled bg-color-blue txt-color-white">
                                            <span class="btn-label"><i class="glyphicon glyphicon-print"></i></span>Enviar Correo
                                        </button>
                                </section>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>                   
        </div>
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <table id="tabla_est_Cuenta"></table>
            <div id="pager_tabla_est_Cuenta"></div>
        </article>
    </div>
</section>
@section('page-js-script')
<script type="text/javascript">
    $(document).ready(function () {
        $("#menu_ventanilla").show();
        $("#li_vent_est_cta").addClass('cr-active');
        jQuery("#tabla_est_Cuenta").jqGrid({
            url: 'caja_est_cta_contrib?id_pers=0&desde=0&hasta=0',
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            toolbarfilter: true,
            colNames: ['cod', 'id_pers', 'Periodo', 'Trimestre', 'Impuesto/Tributo', 'Cuota S/.', 'Abono S/.', 'Fecha', 'Total S/.'],
            rowNum: 20, sortname: 'descrip_tributo', sortorder: 'desc', viewrecords: true, caption: 'PREDIAL Y FORMATOS', align: "center",
            colModel: [
                {name: 'cod', index: 'cod', hidden: true},
                {name: 'id_pers', index: 'id_per', hidden: true},
                {name: 'ano_cta', index: 'ano_cta', align: 'center', width: 80},
                {name: 'trim', index: 'trim', align: 'center', width: 80},
                {name: 'descrip_tributo', index: 'descrip_tributo', align: 'left', width: 200},
                {name: 'cuota', index: 'cuota', align: 'center', width: 100},
                {name: 'abono', index: 'abono', align: 'center', width: 100},
                {name: 'fecha', index: 'fecha', align: 'center', width: 80},
                {name: 'total', index: 'total', align: 'right', width: 100}
            ],
            pager: '#pager_tabla_est_Cuenta',
            rowList: [13, 20],
            gridComplete: function () {
                var idarray = jQuery('#tabla_est_Cuenta').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_est_Cuenta').jqGrid('getDataIDs')[0];
                    $("#tabla_est_Cuenta").setSelection(firstid);
                }

            },
            onSelectRow: function (Id) {},
            ondblClickRow: function (Id) {}
        });
        $(window).on('resize.jqGrid', function () {
            $("#tabla_est_Cuenta").jqGrid('setGridWidth', $("#content").width());
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
                jQuery('#table_contrib').jqGrid('bindKeys', {"onEnter": function (rowid) { fn_bus_contrib_caja_est_cta(rowid); }});
            },
            onSelectRow: function (Id) {},
            ondblClickRow: function (Id) { fn_bus_contrib_caja_est_cta(Id);}
        });
        var globalvalidador = 0;
        $("#vw_caja_est_cta_contrib").keypress(function (e) {
            if (e.which == 13) {
                if (globalvalidador == 0) {
                    fn_bus_contrib();
                    globalvalidador = 1;
                } else {
                    globalvalidador = 0;
                }
            }
        });
    });
</script>
@stop
<script src="{{ asset('archivos_js/caja/estado_cuentas.js') }}"></script>
<div id="dlg_bus_contr" style="display: none;">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
        <table id="table_contrib"></table>
        <div id="pager_table_contrib"></div>
    </article>
</div>

<div id="dlg_enviar_correo" style="display: none;">
    <div class='cr_content col-xs-5 ' style="margin-bottom: 10px;">
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
    <form id="FormularioFiles" name="FormularioFiles" method="post" enctype="multipart/form-data" action="callpdf"  target="ifrafile">
        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" data-token="{{ csrf_token() }}"> 
            <input type="hidden" id="id_arch" name="id_arch" value="0"/>
        <input id="id_contrib_hidden" name="id_contrib_hidden" type="hidden" value="0"/>

                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px; ">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 100px">Persona &nbsp;<i class="fa fa-eye"></i></span>
                        <div>
                            <input id="dlg_persona" name="dlg_obs_exp" type="text"  class="form-control" style="height: 32px; width: 100%" maxlength="250">
        </div>
    </div>
</div>
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px; ">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 100px">Correo &nbsp;<i class="fa fa-map"></i></span>
                        <div>
                            <input id="dlg_correo" name="dlg_direcc" type="text"  class="form-control" style="height: 32px; width: 100%">
                        </div>
                    </div>
                </div>
    </form>            
                
            </div>
          
        </div>
    </div>
        <div id="dlg_sub_frame" class='cr_content col-xs-7 ' style="margin-bottom: 10px;">
            <iframe name="ifrafile" id="ifrafile" class="form-control col-xs-12"  style=" height: 400px; padding: 0px"></iframe>
        </div>
</div> 

@endsection