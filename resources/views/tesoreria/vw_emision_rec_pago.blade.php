@extends('layouts.app')
@section('content')
<style>
    #vw_em_rec_txt_detalle_total{
        background: #80B23E;
        color: white;
        border: 0px !important;
        font-size: 12px;
    }
</style>
<section id="widget-grid" class="">    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -12px">
            <div class="well well-sm well-light">
                <h1 class="txt-color-green"><b>Emision de Recibos de Pago...</b></h1>
                <div class="row">
                    <div class="col-xs-12">                        
                        <div class="text-right">
                            <div class="col-xs-2 col-sm-12 col-md-12 col-lg-3">
                                <label>Fecha:</label>
                                <label class="input">
                                    <input id="vw_emision_reg_pag_fil_fecha" type="text" data-mask="99/99/9999" data-mask-placeholder=".." value="<?php date_default_timezone_set('America/Lima');echo date('d-m-Y') ?>">
                                </label>
                            </div>
                            <button onclick="" style="display: none;" id="btn_vw_valores_arancelarios_Buscar" type="button" class="btn btn-labeled bg-color-blue txt-color-white">
                                <span class="btn-label"><i class="fa fa-search"></i></span>Buscar
                            </button>
                            @if( $permisos[0]->btn_edit ==1 )
                                <button onclick="dialog_emi_rec_pag_arbitrios();" type="button" class="btn btn-labeled bg-color-orange txt-color-white">
                                    <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Arbitrios
                                </button>
                            @else
                                <button onclick="sin_permiso();" type="button" class="btn btn-labeled bg-color-orange txt-color-white">
                                    <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Arbitrios
                                </button>
                            @endif
                            
                            <button 
                                @if($permisos[0]->btn_edit==1) onclick="dialog_emi_rec_pag_imp_predial();" @else onclick="sin_permiso();" @endif
                                type="button" class="btn btn-labeled bg-color-greenLight txt-color-white">
                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Impuesto Predial
                            </button>
                            
                            <button @if($permisos[0]->btn_edit==1) onclick="dialog_emi_rec_pag_fracc();" @else onclick="sin_permiso();" @endif
                                    type="button" class="btn btn-labeled bg-color-blue txt-color-white">
                                <span class="btn-label"><i class="glyphicon glyphicon-folder-close"></i></span>Fraccionamiento
                            </button>
                            <button @if($permisos[0]->btn_edit==1) onclick="dialog_emi_rec_pag_varios();" @else onclick="sin_permiso();" @endif
                                    type="button" class="btn btn-labeled bg-color-magenta txt-color-white">
                                <span class="btn-label"><i class="glyphicon glyphicon-tasks"></i></span>Varios
                            </button>
                            <button @if($permisos[0]->btn_edit==1) onclick="dialog_pago_coactivo();" @else onclick="sin_permiso();" @endif
                                    type="button" class="btn btn-labeled bg-color-blue txt-color-white">
                                <span class="btn-label"><i class="glyphicon glyphicon-tasks"></i></span>Pago Coactivo
                            </button>
                        </div>
                    </div>
                </div> 
            </div>                   
        </div>
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <table id="table_Resumen_Recibos"></table>
            <div id="pager_table_Resumen_Recibos">
                <div style="float: right; font-weight: bold;">
                    Total S/. <input type="text" id="vw_emision_rec_pago_total_global" class="input-sm text-right" style="width: 143px; height: 21px;padding-right: 4px;" readonly="">
                </div>                    
            </div>
        </article>
    </div>
</section>
@section('page-js-script')

<script type="text/javascript">
    sumTotal = 0;
    $(document).ready(function () {
        $("#menu_ventanilla").show();
        $("#li_tesoreria_emi_rec_pag").addClass('cr-active');
        jQuery("#table_Resumen_Recibos").jqGrid({
            url: 'grid_Resumen_recibos?fecha=' + $("#vw_emision_reg_pag_fil_fecha").val(),
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            colNames: ['ID', 'id_contrib', 'N°. Recibo', 'Fecha', 'Descripcion del Pago', 'Estado', 'Caja', 'Hora Pago', 'Total'],
            rowNum: 20, sortname: 'id_rec_mtr', sortorder: 'desc', viewrecords: true, caption: 'Resumen Recibos', align: "center",
            colModel: [
                {name: 'id_rec_mtr', index: 'id_rec_mtr', width: 40, align: 'center'},
                {name: 'id_contrib', index: 'id_contrib', hidden: true},
                {name: 'nro_recibo_mtr', index: 'nro_recibo_mtr', hidden: true},
                {name: 'fecha', index: 'fecha', align: 'center', width: 60},
                {name: 'glosa', index: 'glosa', width: 230},
                {name: 'estad_recibo', index: 'estad_recibo', width: 60},
                {name: 'descrip_caja', index: 'descrip_caja', width: 130},
                {name: 'hora_pago', index: 'hora_pago', align: 'center', width: 50},
                {name: 'total', index: 'total', align: 'right', width: 80, sorttype: 'number', formatter: 'number', formatoptions: {decimalPlaces: 3}}
            ],
            pager: '#pager_table_Resumen_Recibos',
            rowList: [15, 25],
            gridComplete: function () {
                var rows = $("#table_Resumen_Recibos").getDataIDs();
                if (rows.length > 0) {
                    var firstid = jQuery('#table_Resumen_Recibos').jqGrid('getDataIDs')[0];
                    $("#table_Resumen_Recibos").setSelection(firstid);
                }
                var sum = jQuery("#table_Resumen_Recibos").getGridParam('userData').sum_total;
                if (sum == undefined) {
                    $("#vw_emision_rec_pago_total_global").val('0000.00');
                } else {
                    $("#vw_emision_rec_pago_total_global").val(formato_numero(sum, 2, '.', ','));
                }
            },
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
                fn_bus_contrib_list(Id);
                fn_bus_contrib_list_fracc(Id);
            }
        });
        jQuery("#table_cta_cte2").jqGrid({
            url: 'get_grid_cta_cte2?id_contrib=0&ano_cta=0',
            datatype: 'json', mtype: 'GET',
            height: '120px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_tribu', 'Descripción', 'Total a Pagar', 'Saldo S/.', 'Trim I', 'Trim II', 'Trim III', 'Trim IV','con','coa','Todo'],
            rowNum: 5, sortname: 'descrip_tributo', sortorder: 'asc', viewrecords: true, align: "center",
            colModel: [
                {name: 'id_tribu', index: 'id_tribu', hidden:true},
                {name: 'descrip_tributo', index: 'descrip_tributo', width: 297},
                {name: 'ivpp', index: 'ivpp', align: 'center', width: 110},
                {name: 'saldo', index: 'saldo', align: 'center', width: 110},
                {name: 'abo1_cta', index: 'abo1_cta', align: 'right', width: 100},
                {name: 'abo2_cta', index: 'abo2_cta', align: 'right', width: 100},
                {name: 'abo3_cta', index: 'abo3_cta', align: 'right', width: 100},
                {name: 'abo4_cta', index: 'abo4_cta', align: 'right', width: 100},
                {name: 'conv', index: 'conv', hidden: true},
                {name: 'coa', index: 'coa', hidden: true},
                {name: 'total', index: 'total', align: 'right', width: 100},
            ],
            pager: '#pager_table_cta_cte2',
            rowList: [5, 10],
            gridComplete: function () {
                $("#vw_emi_rec_imp_pred_glosa").val("");
                var predial = ($("#vw_emi_rec_imp_pre_contrib_anio option:selected").attr("predial"));
                var formatos = ($("#vw_emi_rec_imp_pre_contrib_anio option:selected").attr("formatos"));
                var pre_x_trim = 0;
                var idarray = jQuery('#table_cta_cte2').jqGrid('getDataIDs');

                for (var i = 0; i < idarray.length; i++) {
                    var deuda = $("#table_cta_cte2").getCell(idarray[i], 'saldo');
                    if(deuda>0 && idarray[i] == predial)
                    {
                        $("#table_cta_cte2").jqGrid("setCell", idarray[i], 'total',
                                        "<input type='checkbox' name='chk_total' value='" + deuda + "' pago='"+idarray[i]+"' onchange='marcar_todos_predial(this.value,this,"+idarray[i]+")'>"+deuda);
                    }
                    var conv = $("#table_cta_cte2").getCell(idarray[i], 'conv');
                    
                    if(conv!='' && idarray[i] == predial){
                        $("#table_cta_cte2").closest(".ui-jqgrid").block({
                            message:"<div style='font-size:1.5em;text-align:center;font-weight: bold'>Esta Cuenta esta en Fraccionamiento...</div>",
                            theme: true,
                            themedCSS:{ width: "40%", left: "30%", border: "3px solid #a00" }
                        });
                    }
                    for (var a = 1; a <= 4; a++) {
                        var val = $("#table_cta_cte2").getCell(idarray[i], 'abo' + a + '_cta');
                        if (parseFloat(val) > 0 && idarray[i] == predial) {
                            $("#table_cta_cte2").jqGrid("setCell", idarray[i], 'abo' + a + '_cta',
                                    "<input type='checkbox' name='chk_trim' value='" + a + "' id='chk_calc_pag_" + a + "' cantidad='"+val+"' onchange='calc_tot_a_pagar_predial(" + a + ",this,"+val+")'>"+val, {'text-align': 'center'});
                        }
                        if (val == '0.000' && idarray[i] == formatos) {
                            $("#table_cta_cte2").jqGrid("setCell", idarray[i], 'abo' + a + '_cta',
                                    "<input type='checkbox' name='chk_trim_form' value='" + a + "' id='chk_calc_form_imp_" + a + "' cantidad='"+val+"' onchange='calc_tot_a_pagar_predial(" + a + ",this,"+val+")' disabled='disabled' checked>", {'text-align': 'center'});
                        }
                    }
                    pre_x_trim = parseFloat($("#table_cta_cte2").getCell(idarray[i], 'ivpp'));
                }

                pre_x_trim = formato_numero((pre_x_trim / 4), 2, '.', ',');
                $("#vw_emis_re_pag_pre_x_trim").val(pre_x_trim);
                form = $("#table_cta_cte2").getCell(formatos, 'saldo') || '0.00';
                $("#vw_emision_rec_pago_imp_pred_total_trimestre").val(form);
                if (idarray.length > 0) {
                    var firstid = jQuery('#table_cta_cte2').jqGrid('getDataIDs')[1];
                    $("#table_cta_cte2").setSelection(firstid);
                }
            },
            onSelectRow: function (Id) {
           
            },
            ondblClickRow: function (Id) {}
        });
        $(window).on('resize.jqGrid', function () {
            $("#table_Resumen_Recibos").jqGrid('setGridWidth', $("#content").width());
        });
        $("#vw_emision_reg_pag_fil_fecha").keypress(function (e) {
            if (e.which == 13) {
                fn_actualizar_grilla('table_Resumen_Recibos', 'grid_Resumen_recibos?fecha=' + $("#vw_emision_reg_pag_fil_fecha").val());
            }
        });
        $("#vw_emi_rec_txt_tributo").keypress(function (e) {
            if (e.which == 13 && !e.shiftKey) {
                event.preventDefault();
                //autocomplete_tributo('vw_emi_rec_txt_tributo', 'vw_emi_rec_txt_valor');
            }
        });
        $("#vw_emi_rec_txt_nrecibo").keypress(function (e) {
            if (e.which == 13 && !e.shiftKey) {
                event.preventDefault();
                traer_glosa();
                validacion_alcabala();
                
            }
        });
        $("#vw_emi_rec_txt_cantidad").keypress(function (e) {
            if (e.which == 13 && !e.shiftKey) {
                if ($("#hiddenvw_emi_rec_txt_tributo").val() == '') {
                    mostraralertasconfoco('Ingrese un Tributo', '#hiddenvw_emi_rec_txt_tributo');
                    return false;
                } else
                    detalle_recibo();
            }
        });
        $("#vw_emi_rec_txt_nro_doc").keypress(function (e) {
            if (e.which == 13 && !e.shiftKey) {
                consultar_persona();
            }
        });
        var globalvalidador = 0;
        $("#vw_emi_rec_imp_pre_contrib").keypress(function (e) {
            if (e.which == 13) {
                if (globalvalidador == 0) {
                    fn_bus_contrib_predial("vw_emi_rec_imp_pre_contrib");
                    globalvalidador = 1;
                } else {
                    globalvalidador = 0;
                }
            }
        });
        
        $("#vw_emi_rec_arbitrios_contrib").keypress(function (e) {
            if (e.which == 13) {
                if (globalvalidador == 0) {
                    fn_bus_contrib_predial("vw_emi_rec_arbitrios_contrib");
                    globalvalidador = 1;
                } else {
                    globalvalidador = 0;
                }
            }
        });
        $("#inp_coactivo_contrib").keypress(function (e) {
            if (e.which == 13) {
                if (globalvalidador == 0) {
                    fn_bus_contrib_predial("inp_coactivo_contrib");
                    globalvalidador = 1;
                } else {
                    globalvalidador = 0;
                }
            }
        });
        var globalvalidador_3 = 0;
        $("#vw_emi_rec_fracc_contrib").keypress(function (e) {
            if (e.which == 13) {
                if (globalvalidador_3 == 0) {
                    tot_mes=0;
                    $("#t_fracc_pago_mes_total").val('0.00');
                    fn_bus_contrib_fracc();
                    globalvalidador_3 = 1;
                } else {
                    globalvalidador_3 = 0;
                }
            }
        });
        $("#vw_emi_rec_fracc_cod_contrib").keypress(function (e) {
            if (e.which == 13) {
                traer_contrib_cod("vw_emi_rec_fracc_contrib",$("#vw_emi_rec_fracc_cod_contrib").val());                
            }
        });
    });

    window.addEventListener('load', function () {
        setInterval(function () {
            document.getElementById('vw_emi_rec_imp_pred_hora_act').value = (new Date()).toTimeString().substr(0, 8);
        }, 1000);
    });
</script>
@stop
<div id="vw_emision_rec_pag_varios" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <div class="panel panel-success">
                    <div class="panel-heading bg-color-success">.:: Datos de la Persona ::.</div>
                    <div class="panel-body cr-body">
                        <fieldset>
                            <div class="row">
                                <section class="col col-2" style="padding-right:5px">
                                    <label class="label">Tipo Documento:</label>                                   
                                    <label class="select">
                                        <select onchange="emi_rec_select_tipo_recibo(this.value);" id="vw_emi_rec_txt_selec_tip_doc" class="input-sm">                                       
                                            @foreach ($tip_doc as $tip_doc1)                                        
                                            <option value='{{$tip_doc1->tip_doc}}' >{{$tip_doc1->tipo_documento}}</option>
                                            @endforeach                                        
                                        </select><i></i>                        
                                </section>
                                <section class="col col-2" style="padding-right:5px; padding-left: 5px;">
                                    <input type="hidden" id="vw_emi_rec_txt_id_pers">
                                    <label class="label">Nro. Documento:</label>
                                    <label class="input">
                                        <input id="vw_emi_rec_txt_nro_doc" type="text" onkeypress="return soloDNI(event);"  placeholder="00000000" class="input-sm">
                                    </label>                      
                                </section>
                                <section class="col col-8" style="padding-left: 5px;">
                                    <label class="label">Nombres y Apellidos / Razon Social:</label>
                                    <label class="input">
                                        <input id="vw_emi_rec_txt_nombres_raz_soc" type="text" class="input-sm" disabled="">
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
                                <label class="label">Tributo:</label>
                                <label class="textarea">
                                    <textarea id="vw_emi_rec_txt_tributo" type="text" rows="5" placeholder="Tributo" class="input-sm text-uppercase"></textarea>
                                </label>                      
                            </section>
                            <section>
                                <label class="label">Glosa:</label>
                                <label class="textarea">
                                    <textarea id="vw_emi_rec_txt_glosa" rows="2" placeholder="descripcion de recibo" class="input-sm text-uppercase"></textarea>                                    
                                </label>                      
                            </section>
                            <section>
                                <label class="label">Dirección:</label>
                                <label class="input">
                                    <input id="vw_emi_rec_txt_direc" rows="2" placeholder="Dirección de la persona" class="input-sm text-uppercase"></input>                                    
                                </label>                      
                            </section>
                            <div class="row">
                                <section class="col col-3" style="padding-right: 5px">                                    
                                    <label class="label">Cod. Tributo:</label>
                                    <label class="input">
                                        <input id="hiddenvw_emi_rec_txt_tributo" type="text" placeholder="000000" class="input-sm" disabled="">
                                    </label>                        
                                </section>
                                <section class="col col-2" style="padding-left: 5px;padding-right: 5px">
                                    <input type="hidden">
                                    <label class="label">Cantidad:</label>
                                    <label class="input">
                                        <input id="vw_emi_rec_txt_cantidad" onkeypress="return soloDNI(event);" type="text" placeholder="00" class="input-sm">
                                    </label>                        
                                </section>
                                <section class="col col-2" style="padding-left: 5px;padding-right: 5px">
                                    <label class="label">Valor S/.:</label>
                                    <label class="input">
                                        <input id="vw_emi_rec_txt_valor" type="text" placeholder="000.00" class="input-sm" disabled="">
                                    </label>                      
                                </section>
                                <section class="col col-2" style="padding-left: 5px;padding-right: 5px">
                                    <label class="label">Nº Recibo.:</label>
                                    <label class="input">
                                        <input id="vw_emi_rec_txt_nrecibo" type="text" placeholder="0" class="input-sm" onkeypress="return soloNumeroTab(event);" disabled="">
                                    </label>                      
                                </section>
                                <section class="col col-3 text-center" style="padding-left: 5px">
                                    <label class="label">&nbsp;</label>
                                    <button onclick="detalle_recibo();" class="btn btn-primary btn-sm" id="btn_agregar_insertar">Agregar / Insertar</button>                    
                                </section>                                
                            </div> 
                            <section>
                                <div class="panel panel-success" style="border: 0px !important; margin-top: 8px;">
                                    <div class="panel-heading bg-color-primary">.:: Detalle de Recibo ::.</div>
                                    <div class="panel-body">

                                        <div style="border: 1px solid #DDD; margin-bottom: 6px;">
                                            <table id="t_dina_det_recibo" class="table table-bordered table-sm">
                                                <thead>
                                                    <tr>
                                                        <th width="1%" align="center">N</th>
                                                        <th width="79%">Tributo</th>
                                                        <th width="15%" align="right">Costo</th>
                                                        <th width="5%" align="center">Elim.</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>

                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th width="1%" align="center"></th>
                                                        <th width="79%" style="text-align: right">Total S/.</th>
                                                        <th width="15%" style="border-top: 2px solid #017E42;">
                                                            <label class='input'><input id="vw_em_rec_txt_detalle_total" type="text" value="000.000" class="input-xs text-align-right" disabled=""></label>
                                                        </th>
                                                        <th width="5%" align="center"></th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </fieldset>
                    </div>
                </div>
            </div>                   
        </div>        
    </div>
</div>
<div id="vw_emision_rec_pag_imp_predial" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <div class="panel panel-success">
                    <div class="panel-heading bg-color-success">.:: Datos del Contribuyente ::.</div>
                    <div class="panel-body cr-body">
                        <fieldset>
                            <div class="row">                                
                                <section class="col col-2" style="padding-right: 5px;">
                                    <input type="hidden" id="vw_emi_rec_txt_id_pers">
                                    <label class="label">Cod Contrib:</label>
                                    <label class="input">
                                        <input id="vw_emi_rec_imp_pre_contrib_cod" type="text" onkeypress="return soloDNI(event);"  placeholder="00000000" class="input-sm">
                                    </label>                      
                                </section>
                                <section class="col col-8" style="padding-left: 5px;padding-right:5px; ">
                                    <label class="label">Contribuyente:</label>
                                    <label class="input">
                                        <input type="hidden" id="vw_emi_rec_imp_pre_contrib_hidden">
                                        <input id="vw_emi_rec_imp_pre_contrib" type="text" placeholder="ejm. jose min 4 caracteres" class="input-sm text-uppercase">
                                    </label>
                                </section>
                                <section class="col col-2" style="padding-left:5px">
                                    <label class="label">Año:</label>                                   
                                    <label class="select">
                                        <select onchange="filter_anio(this.value);" id="vw_emi_rec_imp_pre_contrib_anio" class="input-sm">                                       
                                            @foreach ($anio as $anio1)                                        
                                            <option value='{{$anio1->anio}}' predial = '{{$anio1->impuesto_predial}}' formatos = '{{$anio1->formatos}}' >{{$anio1->anio}}</option>
                                            @endforeach                                    
                                        </select><i></i>                        
                                </section>
                            </div>                            
                        </fieldset>
                    </div>
                </div>
                <div class="panel panel-success">
                    <div class="panel-heading bg-color-success">.:: Datos de Recibo ::.</div>
                    <div class="panel-body">                        
                        <fieldset>
                            <div class="row">
                                <section class="col col-3" style="padding-right: 5px">                                    
                                    <label class="label">Fecha:</label>
                                    <label class="input">
                                        <input id="vw_emi_rec_imp_pred_fech_act" type="text" placeholder="000000" value="{{date('d-m-Y')}}" class="input-sm" disabled="">
                                    </label>                        
                                </section>
                                <section class="col col-3" style="padding-left: 5px;padding-right: 5px">
                                    <input type="hidden">
                                    <label class="label">Hora:</label>
                                    <label class="input">
                                        <input id="vw_emi_rec_imp_pred_hora_act"  type="text" value="{{date('h:i A')}}" class="input-sm" disabled="">
                                    </label>                        
                                </section>                                
                                <section class="col col-2" style="padding-left:5px">
                                    <label class="label">&nbsp;</label>
                                    <a onclick="calcular_tot_a_pagar();" class="btn btn-primary btn-sm">Total a Pagar</a>
                                </section>                             
                            </div>                            
                            <section>
                                <label class="label">Glosa:</label>
                                <label class="textarea">
                                    <textarea id="vw_emi_rec_imp_pred_glosa" rows="2" placeholder="descripcion de recibo" class="input-sm text-uppercase"></textarea>                                    
                                </label>
                                 <input id="vw_detalle_trimestres"  type="hidden" >
                            </section>
                            
                            <input type="hidden" id="vw_emi_rec_imp_pred_t1" value="0">
                            <input type="hidden" id="vw_emi_rec_imp_pred_t2" value="0">
                            <input type="hidden" id="vw_emi_rec_imp_pred_t3" value="0">
                            <input type="hidden" id="vw_emi_rec_imp_pred_t4" value="0">
                                                                
                                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
                                    <table id="table_cta_cte2"></table>
                                    <div id="pager_table_cta_cte2">
                                        <div class="col-xs-6">
                                            <div class="col-xs-12" style="padding: 0px;">
                                                <div class="input-group input-group-md">
                                                    <span class="input-group-addon" style="width: 100px; background-color: white">Interes S/. &nbsp;</span>
                                                    <div class="">
                                                        <input id="input_interes_tot" type="text"  class="form-control" style="height: 32px; text-align: right; padding-right: 5px;width: 120px;" disabled="" value="0.00">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-3" style="z-index: 99999999">
                                            <div class="input-group input-group-md" style="background-color: white;margin-top: 34px;">
                                                <span class="input-group-addon" style="width: 100px; background-color: white">Activar Pago a Cuenta &nbsp;</span>
                                                <div class="input-group-addon"  style=" background-color: white; ">
                                                    <input id="check_pago_cuenta" type="checkbox" style="margin-top: 5px;" onchange="validar_pago_cuenta()">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-3" style="padding: 0px;">
                                            
                                            <div class="col-xs-12" style="padding: 0px;">
                                                <div class="input-group input-group-md">
                                                    <span class="input-group-addon" style="width: 100px; background-color: white">Trimestre S/. &nbsp;</span>
                                                    <div class=""  >
                                                        <input id="vw_emis_re_pag_pre_x_trim" type="text"  class="form-control" style="height: 32px; text-align: right; padding-right: 5px;width: 120px;" disabled="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12" style="padding: 0px;">
                                                <div class="input-group input-group-md">
                                                    <span class="input-group-addon" style="width: 100px;background-color: white">Total S/. &nbsp;</span>
                                                    <div class=""  >
                                                        <input id="vw_emision_rec_pago_imp_pred_total_trimestre" type="text"  class="form-control" style="height: 32px; text-align: right; padding-right: 5px;width: 120px;" disabled="" onkeypress="return soloNumeroTab(event);">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </article>
                            </section>
                        </fieldset>
                    </div>
                </div>
            </div>                   
        </div>        
    </div>
</div>
<div id="vw_emision_rec_pag_arbitrios" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <div class="panel panel-success">
                    <div class="panel-heading bg-color-success">.:: Datos del Contribuyente ::.</div>
                    <div class="panel-body cr-body">
                        <fieldset>
                            <div class="row">                                
                                <section class="col col-2" style="padding-right: 5px;">
                                    
                                    <label class="label">Cod Contrib:</label>
                                    <label class="input">
                                        <input id="vw_emi_rec_arbitrios_contrib_cod" type="text" onkeypress="return soloDNI(event);"  placeholder="00000000" class="input-sm">
                                    </label>                      
                                </section>
                                <section class="col col-8" style="padding-left: 5px;padding-right:5px; ">
                                    <label class="label">Contribuyente:</label>
                                    <label class="input">
                                        <input type="hidden" id="vw_emi_rec_arbitrios_contrib_hidden" value="0"> 
                                        <input id="vw_emi_rec_arbitrios_contrib" type="text" placeholder="ejm. jose min 4 caracteres" class="input-sm text-uppercase">
                                    </label>
                                </section>
                                <section class="col col-2" style="padding-left:5px">
                                    <label class="label">Año:</label>                                   
                                    <label class="select">
                                        <select onchange="selanio_arbi_pred(this.value);" id="vw_emi_rec_arbitrios_contrib_anio" class="input-sm">                                       
                                            @foreach ($anio as $anio2)                                        
                                            <option value='{{$anio2->anio}}' >{{$anio2->anio}}</option>
                                            @endforeach                                    
                                        </select><i></i>                        
                                </section>
                            </div>                            
                        </fieldset>
                    </div>
                </div>
                <div class="panel panel-success" style="border: 0px !important">                    
                    <div class="panel-body">    
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0px !important">
                            <table id="table_Predios_Arbitrios"></table>
                            <div id="pager_table_Predios_Arbitrios"></div>
                        </article>
                    </div>
                </div>
                <div class="panel panel-success" style="border: 0px !important">                    
                    <div class="panel-body">    
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0px !important">
                            <table id="table_cta_Arbitrios"></table>
                            <div id="pager_table_cta_Arbitrios">
                                <div style="float: right; font-weight: bold;">
                                    Total S/. <input type="text" id="vw_emision_rec_Arbitrios_tot" class="input-xm text-right" style="width: 100px; height: 21px;padding-right: 4px;margin-bottom: -3px;" readonly="" value="0">
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>                   
        </div>        
    </div>
</div>
<div id="vw_emision_rec_pag_fracc" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <div class="panel panel-success">
                    <div class="panel-heading bg-color-success">.:: Datos del Contribuyente ::.</div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="row">                                
                                <section class="col col-2" style="padding-right: 5px;">
                                    <!--<input type="hidden" id="vw_emi_rec_fracc_id_pers">-->
                                    <label class="label">Cod Contrib:</label>
                                    <label class="input">
                                        <input id="vw_emi_rec_fracc_cod_contrib" type="text" onkeypress="return soloDNI(event);"  placeholder="00000000" class="input-sm">
                                    </label>                      
                                </section>
                                <section class="col col-8" style="padding-left: 5px;">
                                    <label class="label">Contribuyente:</label>
                                    <label class="input">
                                        <input type="hidden" id="vw_emi_rec_fracc_contrib_hidden">
                                        <input id="vw_emi_rec_fracc_contrib" type="text" placeholder="ejm. jose min 4 caracteres" class="input-sm text-uppercase">
                                    </label>
                                </section>                                
                            </div>                            
                        </fieldset>
                    </div>
                </div>
                <div class="panel panel-success" style="">
                    <div class="panel-heading bg-color-success">.:: Datos del Convenio de Fraccionamiento ::.</div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="row">                                
                                <section class="col col-6" style="padding-right:5px;width: 60%">                                    
                                    <!--<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0px !important">-->
                                        <table id="table_fracc_de_contrib"></table>
                                        <div id="pag_table_fracc_de_contrib"></div>
                                    <!--</article>-->               
                                </section>
                                <section class="col col-6" style="padding-left:5px;width: 40%">                                    
                                    <!--<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0px !important">-->
                                        <table id="t_fracc_crono_contrib"></table>
                                        <div id="pag_t_fracc_crono_contrib">
                                            <div style="float: right; font-weight: bold;">
                                                Total S/. <input type="text" id="t_fracc_pago_mes_total" class="input-sm text-right" style="width: 110px; height: 8px;padding-right: 4px;" readonly="">
                                            </div>
                                        </div>
                                    <!--</article>-->
                                </section>                                
                            </div>                            
                        </fieldset>
                    </div>
                </div>

            </div>                   
        </div>        
    </div>
</div>

<div id="dialog_Personas" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">                
                <div class="panel panel-success" style="border: 0px !important;">
<!--                    <div class="panel-heading bg-color-success">.:: Datos del Contribuyente ::.</div>-->
                    <div class="panel-body">
                        <fieldset>
                            <div class="row">
                                <section class="col col-6" style="padding-right:5px;">
                                    <label class="label">Tipo Documento:</label>                                   
                                    <label class="select">
                                        <select id="cb_tip_doc_3" onchange="filtro_tipo_doc_pers(this.value);" class="input-sm">
                                        @foreach ($tip_doc as $tip_doc2)
                                        <option value='{{$tip_doc2->tip_doc}}' >{{trim($tip_doc2->tipo_documento)}}</option>
                                        @endforeach                                          
                                        </select><i></i> </label>                                                       
                                </section>
                                <section class="col col-6" style="padding-left:5px;">
                                    <label class="label">Nro. Documento:</label>
                                    <label class="input">
                                        <input id="pers_nro_doc" type="text" onkeypress="return soloDNI(event);" maxlength="8" placeholder="00000000" class="input-sm">
                                    </label>                                                                                            
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-3" style="padding-right:5px;">
                                    <label class="label">Ape.Paterno:</label>
                                    <label class="input">
                                        <input id="pers_pat" type="text" maxlength="50" class="input-sm text-uppercase">
                                    </label>                                    
                                </section>
                                <section class="col col-3" style="padding-left:5px;padding-right:5px;">
                                    <label class="label">Ape.Materno:</label>
                                    <label class="input">
                                        <input id="pers_mat" type="text" maxlength="50" class="input-sm text-uppercase">
                                    </label>                                                                     
                                </section>
                                <section class="col col-6" style="padding-left:5px;">
                                    <label class="label">Nombres:</label>
                                    <label class="input">
                                        <input id="pers_nombres" type="text" maxlength="100" class="input-sm text-uppercase">
                                    </label>                                                                     
                                </section> 
                            </div>                            
                            <div class="row">                            
                                <section class="col col-9" style="padding-left:12px;">
                                    <label class="label">Razon Social:</label>
                                    <label class="input">
                                        <input id="pers_raz_soc" name="pers_raz_soc" type="text" class="input-sm text-uppercase">
                                    </label>
                                </section>
                                <section class="col col-3" style="padding-left:5px;">
                                <label class="label">Entidad Reciproca:</label>
                                <label><input type="checkbox" name="entidad_reciproca" id="entidad_reciproca" style="width:22px;height:22px" disabled=""></label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-6" style="padding-right:5px;">
                                    <label class="label">Sexo:</label>                                   
                                    <label class="select">
                                        <select id="pers_sexo" class="input-sm text-uppercase">
                                            <option value="-">Seleccionar</option>
                                            <option value="1">Masculino</option>
                                            <option value="0">Femenino</option>        
                                        </select><i></i> </label>                                     
                                </section>
                                <section class="col col-6" style="padding-left:5px;">
                                    <label class="label">Fecha Nac.:</label>
                                    <label class="input">
                                        <input id="pers_fnac" type="text" data-mask="99/99/9999" data-mask-placeholder="-" placeholder="dia/mes/año" class="input-sm">
                                    </label>                                                                                                          
                                </section>                                
                            </div>
<!--                            <section>
                                <label class="label">Domicilio Fiscal:</label>
                                <label class="input">
                                    <input id="pers_dom_fis" type="text" class="input-sm text-uppercase">
                                </label>                                
                            </section>-->
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
<div id="dlg_pago_coactivo" style="display: none;">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <div class="panel panel-success">
                    <div class="panel-heading bg-color-success">.:: Datos del Contribuyente ::.</div>
                    <div class="panel-body cr-body">
                        <fieldset>
                            <div class="row">                                
                                <section class="col col-8" style="padding-left: 20px;padding-right:5px; ">
                                    <label class="label">Contribuyente:</label>
                                    <label class="input">
                                        <input type="hidden" id="inp_coactivo_contrib_hidden" value="0"> 
                                        <input id="inp_coactivo_contrib" type="text" placeholder="ejm. jose min 4 caracteres" class="input-sm text-uppercase">
                                    </label>
                                </section>
                                <section class="col col-2" style="padding-left:5px">
                                    <label class="label">Año:</label>                                   
                                    <label class="select">
                                        <select onchange="selanio_arbi_pred(this.value);" id="inp_coactivo_contrib_anio" class="input-sm">                                       
                                            @foreach ($anio as $anio2)                                        
                                            <option value='{{$anio2->anio}}' >{{$anio2->anio}}</option>
                                            @endforeach                                    
                                        </select><i></i>                        
                                </section>
                            </div>                            
                        </fieldset>
                    </div>
                </div>
                <div class="panel panel-success" style="border: 0px !important">                    
                    <div class="panel-body">    
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0px !important">
                            <table id="table_apersonamiento"></table>
                            <div id="pager_table_apersonamiento">
                                <div style="float: right; font-weight: bold;">
                                    Total S/. <input type="text" id="inp_coactivo_tot" class="input-xm text-right" style="width: 100px; height: 21px;padding-right: 4px;margin-bottom: -3px;" readonly="" value="0">
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('archivos_js/tesoreria/emision_rec_pago_varios.js') }}"></script>
<script src="{{ asset('archivos_js/tesoreria/emision_rec_pago_imp_predial.js') }}"></script>
<script src="{{ asset('archivos_js/tesoreria/emision_rec_pago_arbitrios.js') }}"></script>
<script src="{{ asset('archivos_js/tesoreria/emision_rec_pago_fracc.js') }}"></script>
<script src="{{ asset('archivos_js/tesoreria/pago_coactivo.js') }}"></script>
@endsection
