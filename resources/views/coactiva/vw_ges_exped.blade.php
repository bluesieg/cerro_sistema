@extends('layouts.app')
@section('content')
<style>
    .icon-addon .form-control, .icon-addon.addon-md .form-control {
        padding-left: 10px; 
    }
    .btn-label {        
        left: -12px;        
        padding: 5px 8px;        
    }
</style>
<section id="widget-grid" class="">    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -12px">            
            <div class="well well-sm well-light" style="padding:0px;margin-bottom: 5px">                
                <div class="jarviswidget jarviswidget-color-white" style="margin-bottom: 1px;">
                    <header style="background: #01a858 !important;color: white; padding-right: 15px !important" >
                        <span class="widget-icon"> <i class="fa fa-align-justify"></i> </span>
                        <h2>GESTION DE EXPEDIENTES...</h2>
                    </header>                                
                </div>
                <ul id="tabs1" class="nav nav-tabs bordered">
                    <li class="active">
                        <a href="#s1" data-toggle="tab" aria-expanded="true">
                            CONSOLIDADO DE EXPEDIENTES
                            <i class="fa fa-lg fa-fw fa-cog fa-spin"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#s2" data-toggle="tab" aria-expanded="false">
                            VER POR CONTRIBUYENTE
                            <i class="fa fa-lg fa-fw fa-cog fa-spin"></i>
                        </a>
                    </li>
                    <div class="col-xs-3" style="margin-top: 2px; padding-right: 23px;margin-left: 335px">
                        <div class="input-group input-group-md">
                            <span class="input-group-addon">Año de Tramite <i class="fa fa-cogs"></i></span>
                            <div class="icon-addon addon-md">
                                <select id='selanio_tra' class="form-control col-lg-8" style="height: 32px;">
                                    <option value="0">Seleccione</option>
                                    @foreach ($anio_tra as $anio)
                                    <option value='{{$anio->anio}}' >{{$anio->anio}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </ul>
                <div id="myTabContent1" class="tab-content padding-10">
                    <div id="s1" class="tab-pane fade active in">
                        <section style="margin-top: 5px">                                                 
                            <div class="row">
                                <section class="col-lg-6">
                                    <div class="input-group input-group-md">
                                        <span class="input-group-addon">Contribuyente<i class="icon-append fa fa-male" style="margin-left: 15px;"></i></span>
                                        <div class="icon-addon addon-md">
                                            <input id="vw_coa_bus_contrib_exp" class="form-control text-uppercase" type="text">
                                        </div>
                                        <span class="input-group-btn">
                                            <button onclick="bus_contrib_expediente();" class="btn btn-primary" type="button" title="BUSCAR">
                                                <i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;Buscar
                                            </button>
                                        </span>                                        
                                    </div>                                    
                                </section>
                                <section class="col-lg-2" style="padding-left: 5px;padding-right: 5px">
                                    <div class="input-group input-group-md">
                                        <span class="input-group-addon">Mat.</span>
                                        <div class="icon-addon addon-md">
                                            <select id="ges_exped_mat" class="form-control" onchange="select_materia();">
                                                <option value='1' >TRIBUTARIA</option>
                                                <option value='0' >NO TRIBUTARIA</option>
                                            </select>
                                        </div>
                                    </div>
                                </section>
                                <section class="col-lg-4 text-right">                                    
                                    <button id="btn_hab_pago_coa" onclick="habilitar_pago();" type="button" class="btn btn-labeled bg-color-green txt-color-white">
                                        <span class="btn-label"><i class="fa fa-file-text"></i></span>Habilitar Pago
                                    </button>
<!--                                    <button onclick="activar_exped();" type="button" class="btn btn-labeled bg-color-green txt-color-white">
                                        <span class="btn-label"><i class="fa fa-file-text"></i></span>Activar
                                    </button>-->
                                    <button onclick="devolver_valor();" type="button" class="btn btn-labeled bg-color-blue txt-color-white">
                                        <span class="btn-label"><i class="fa fa-file-text"></i></span>Devolver
                                    </button>
                                </section>
                            </div>
                        </section>                        
                        <hr style="border: 1px solid #DDD;margin: 10px -10px">
                        <section style="">              
                            <div class="row">
                                <section id="content_3" class="col col-lg-12"> 
                                    <table id="all_tabla_expedientes"></table>
                                    <div id="p_all_tabla_expedientes"></div>                     
                                </section>                 
                            </div>
                        </section>  
                    </div>
                    <div id="s2" class="tab-pane fade" style="height: auto">
                        <section style="margin-top: 5px">                                                 
                            <div class="row">
                                <section class="col-lg-6" style="padding-right:5px">
                                    <input id="hidden_vw_ges_exped_codigo" type="hidden" value="0">
                                    <input id="vw_ges_exped_codigo" type="hidden">
                                    <div class="input-group input-group-md">
                                        <span class="input-group-addon">Contribuyente<i class="icon-append fa fa-male" style="margin-left: 5px;"></i></span>
                                        <div class="icon-addon addon-md">
                                            <input id="vw_ges_exped_contrib" class="form-control text-uppercase" type="text">
                                        </div>
                                        <span class="input-group-btn">
                                            <button onclick="bus_contrib();" class="btn btn-primary" type="button" title="BUSCAR">
                                                <i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;Buscar
                                            </button>
                                        </span>                                        
                                    </div>                                    
                                </section>
                                <section class="col-lg-6 text-right" style="padding-left: 5px;">                                     
                                    <button onclick="exped_no_trib();" type="button" class="btn btn-labeled bg-color-blue txt-color-white">
                                        <span class="btn-label"><i class="fa fa-folder"></i></span>Crear Expediente
                                    </button>                                    
                                    <button onclick="dlg_select_new_doc();" type="button" class="btn btn-labeled bg-color-blue txt-color-white">
                                        <span class="btn-label"><i class="fa fa-file-text"></i></span>Nuevo Documento
                                    </button>
                                    <button onclick="eliminar_documento();" type="button" class="btn btn-labeled bg-color-red txt-color-white">
                                        <span class="btn-label"><i class="fa fa-trash"></i></span>Eliminar Doc.
                                    </button>
                                </section>
                            </div>
                        </section>                        
                        <hr style="border: 1px solid #DDD;margin: 10px -10px">
                        <section style="">              
                            <div class="row">
                                <section id="content_1" class="col col-lg-2" style="padding-right:5px;width: 20%"> 
                                    <table id="tabla_expedientes"></table>
                                    <div id="p_tabla_expedientes"></div>                     
                                </section>                                
                                <section id="content_2" class="col col-lg-10" style="padding-left:5px;width: 80%">
                                    <table id="tabla_doc_coactiva"></table>
                                    <div id="p_tabla_doc_coactiva"></div>
                                </section>                            
                            </div>
                        </section>        
                    </div>
                </div>
            </div>
        </div>       
    </div>
</section>
@section('page-js-script')
<script type="text/javascript">
    $(document).ready(function () {
        $("#menu_coactiva").show();
        $("#li_gesion_exped").addClass('cr-active');
        jQuery("#all_tabla_expedientes").jqGrid({
            url: 'get_all_exped?contrib=&materia=1',
            datatype: 'json', mtype: 'GET',
            height: 329, autowidth: true,
            toolbarfilter: true,
            colNames: ['Expediente','id_contrib', 'Contribuyente', 'Materia', 'Ultimo Documento Emitido', 'Monto', 'Estado','id_val'],
            rowNum: 20, sortname: 'id_coa_mtr', sortorder: 'desc', viewrecords: true, caption: 'Consolidado de Expedientes', align: "center",
            colModel: [
                {name: 'nro_exped', index: 'nro_exped', align: 'center', width: 80},
                {name: 'id_contrib', index: 'id_contrib', hidden: true},
                {name: 'contribuyente', index: 'contribuyente', align: 'left', width: 210},
                {name: 'materia', index: 'materia', align: 'left', width: 70},
                {name: 'ult_doc', index: 'ult_doc', align: 'left', width: 200},
                {name: 'monto', index: 'monto', align: 'right', width: 70},
                {name: 'estado', index: 'estado', align: 'left', width: 120},
                {name: 'id_val', index: 'id_val', hidden: true}
            ],
            rowList: [13, 20],
            pager: '#p_all_tabla_expedientes',
            gridComplete: function () {
                var idarray = jQuery('#all_tabla_expedientes').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                    var firstid = jQuery('#all_tabla_expedientes').jqGrid('getDataIDs')[0];
                    $("#all_tabla_expedientes").setSelection(firstid);
                }
            },
            onSelectRow: function (Id) {},
            ondblClickRow: function (Id) {}
        });        
        jQuery("#tabla_expedientes").jqGrid({
            url: 'get_exped?id_contrib=0',
            datatype: 'json', mtype: 'GET',
            height: 329, autowidth: true,
            toolbarfilter: true,
            colNames: ['Nro', 'Expediente', 'monto', 'estado','Materia'],
            rowNum: 20, sortname: 'nro_procedimiento', sortorder: 'asc', viewrecords: true, caption: 'Expedientes', align: "center",
            colModel: [
                {name: 'nro_procedimiento', index: 'nro_procedimiento', align: 'center', width: 30},
                {name: 'nro_exped', index: 'nro_exped', align: 'center', width: 90},
                {name: 'monto', index: 'monto', hidden: true},
                {name: 'estado', index: 'estado', hidden: true},
                {name: 'materia', index: 'materia', align: 'center', width: 70}
            ],
            rowList: [13, 20],
            gridComplete: function () {},
            onSelectRow: function (Id) {},
            ondblClickRow: function (Id) {
                ver_docum_exped(Id);
            }
        });
        jQuery("#tabla_doc_coactiva").jqGrid({
            url: 'get_doc_exped?id_coa_mtr=0',
            datatype: 'json', mtype: 'GET',
            height: 300, autowidth: true,
            toolbarfilter: true,
            colNames: ['Nro', 'Fch.Emision', 'Tipo Gestion', 'N° Resolucion', 'Fch.Recep', 'Ver', 'Editar'],
            rowNum: 20, sortname: 'id_doc', sortorder: 'asc', viewrecords: true, caption: 'Documentos', align: "center",
            colModel: [
                {name: 'nro', index: 'nro', align: 'center', width: 20},
                {name: 'fch_emi', index: 'fch_emi', align: 'center', width: 50},
                {name: 'tip_gestion', index: 'tip_gestion', align: 'left', width: 230},
                {name: 'nro_resol', index: 'nro_resol', align: 'center', width: 60},
                {name: 'fch_recep', index: 'fch_recep', align: 'center', width: 50},
                {name: 'ver', index: 'ver', align: 'center', width: 60},
                {name: 'edit', index: 'edit', align: 'center', width: 60}
            ],
            pager: '#p_tabla_doc_coactiva',
            rowList: [13, 20],
            gridComplete: function () {
                var idarray = jQuery('#tabla_doc_coactiva').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_doc_coactiva').jqGrid('getDataIDs')[0];
                    $("#tabla_doc_coactiva").setSelection(firstid);
                }
            },
            onSelectRow: function (Id) {},
            ondblClickRow: function (Id) {}
        });
        $(window).on('resize.jqGrid', function () {
            $("#tabla_expedientes").jqGrid('setGridWidth', $("#content_1").width());
            $("#tabla_doc_coactiva").jqGrid('setGridWidth', $("#content_2").width());
            $("#all_tabla_expedientes").jqGrid('setGridWidth', $("#content_3").width());
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
                fn_bus_contrib_select(Id);
            }
        });
        jQuery("#t_cta_cte").jqGrid({
            url: 'get_ctacte?id_contrib=0',
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            toolbarfilter: true,
            colNames: ['Descripcion', 'Trim I', 'Trim II', 'Trim III', 'Trim IV'],
            rowNum: 20, sortname: 'id_coa_mtr', sortorder: 'desc', viewrecords: true, caption: 'Trimestres en Coactiva', align: "center",
            colModel: [
                {name: 'descrip', index: 'descrip', align: 'center', width: 200},
                {name: 'trim1', index: 'abo1', align: 'right', width: 80},
                {name: 'trim2', index: 'abo2', align: 'right', width: 80},
                {name: 'trim3', index: 'abo3', align: 'right', width: 80},
                {name: 'trim4', index: 'abo4', align: 'right', width: 80}                    
            ],
            rowList: [13, 20],
            pager: '#p_t_cta_cte',
            gridComplete: function () {
                var idarray = jQuery('#t_cta_cte').jqGrid('getDataIDs');
                for (var i = 0; i < idarray.length; i++) {                    
                    for (var a = 1; a <= 4; a++) {
                        var val = $("#t_cta_cte").getCell(idarray[i], 'trim' + a);                        
                        if (val == 2) {
                            $("#t_cta_cte").jqGrid("setCell", idarray[i], 'trim' + a ,
                                "<input type='checkbox' name='est_trim' value='" + a + "' onchange='trim_select(" + a + ")'>", {'text-align': 'center'});
                        }
                        if (val == 10) {
                            $("#t_cta_cte").jqGrid("setCell", idarray[i], 'trim' + a ,
                                "PAGADO", {'text-align': 'center'});
                        }
                    }                    
                }
            }                
        });
        var globalvalidador = 0;
        $("#vw_ges_exped_contrib").keypress(function (e) {
            if (e.which == 13) {
                if (globalvalidador == 0) {
                    bus_contrib();
                    globalvalidador = 1;
                } else {
                    globalvalidador = 0;
                }
            }
        });
        $("#vw_coa_bus_contrib_exp").keypress(function (e) {
            if (e.which == 13) {
                bus_contrib_expediente();
            }
        });
    });
</script>
@stop
<script src="{{ asset('archivos_js/coactiva/gestion_expediente.js') }}"></script>
<div id="dlg_select_doc" style="display: none;">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <section style="margin-top: 10px;margin-left: 30px;">
                    <label class="radio">
                        <input type="radio" name="add_doc_radio" value="2">
                        <i></i>RESOLUCION DE EJECUCION COACTIVA</label>
                    <label class="radio">
                        <input type="radio" name="add_doc_radio" value="3">
                        <i></i>RESOLUCION DE SUSPENCION TEMPORAL DE PROCEDIMIENTO</label>
                    <!--                        <label class="radio">
                                                    <input type="radio" name="add_doc_radio" value="4">
                                                    <i></i>RESOLUCION DE EMBARGO EN FORMA DE RETENCION</label>-->
                    <!--                        <label class="radio">
                                                    <input type="radio" name="add_doc_radio" value="5">
                                                    <i></i>RESOLUCION DE EMBARGO EN FORMA DE INSCRIPCION</label>
                    -->
                    <label class="radio">
                        <input onchange="desactivar_adjuntos(this);" type="radio" name="add_doc_radio" value="6">
                        <i></i>CONSTANCIA DE NOTIFICACION</label>
                    <label class="radio">
                        <input type="radio" name="add_doc_radio" value="7">
                        <i></i>REQUERIMIENTO DE PAGO</label>
                    <!--                        <label class="radio">
                                                    <input type="radio" name="add_doc_radio" value="8">
                                                    <i></i>CARTA INFORMATIVA</label>-->
                    <label class="radio">
                        <input type="radio" name="add_doc_radio" value="9">
                        <i></i>ACTA DE APERSONAMIENTO</label>
                    <label class="radio">
                        <input type="radio" name="add_doc_radio" value="10">
                        <i></i>RESOLUCION DE SUSPENCION DEFINITIVA DE PROCEDIMIENTO</label>
                </section>
                <div class="panel panel-success">                    
                    <div class="panel-body">
                        <fieldset>
                            <section class="col-lg-10 text-right">
                                <div class="smart-form">
                                    <label class="toggle">
                                        <input id="adjuntar_const" onchange="adjuntar_const_chk(this);" value="1" type="checkbox" name="checkbox-toggle" checked="checked">
                                        <i data-swchon-text="SI" data-swchoff-text="NO"></i>ADJUNTAR CONSTANCIA DE NOTIFICACIÓN</label>
                                </div>
                            </section>
                        </fieldset>
                    </div>
                </div>               
            </div>
        </div>
    </div>    
</div>
<div id="vw_coa_acta_apersonamiento" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <div class="panel panel-success">
                    <div class="panel-heading bg-color-success">.:: Acta de Apersonamiento ::.</div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="row">                                
                                <section class="col col-3" style="padding-right: 5px;">                                    
                                    <label class="label">N° Cuotas:</label>
                                    <label class="input">
                                        <input id="nro_cuo_apersonamiento" type="text" class="input-sm">
                                    </label>                      
                                </section>
                                <section class="col col-3" style="padding-right: 5px;padding-left: 5px;">                                    
                                    <label class="label">Monto Total:</label>
                                    <label class="input">
                                        <input id="nro_cuo_monto" onkeypress="return soloDNI(event);" type="text" class="input-sm" disabled="">
                                    </label>                      
                                </section>
                                <section class="col col-6" style="padding-right:5px;">                                    
                                    <button onclick="add_cuo_acta_aper();" class="btn btn-primary btn-lg" style="margin-top:11px" type="button" title="Agregar Cuotas al Acta">
                                        <i class="glyphicon glyphicon-plus"></i>&nbsp;&nbsp;Agregar
                                    </button>
                                </section>                                                      
                            </div>
                        </fieldset>
                    </div>
                </div>                
                <div class="panel panel-success" style="border: 0px !important;height: 325px; overflow-y: scroll">
                    <div class="panel-heading bg-color-success">.:: Vista Cuotas ::.</div>
                    <div class="panel-body">    
                        <div style="border: 1px solid #DDD; margin-bottom: 6px;">
                            <table id="t_dina_acta_aper" class="table table-bordered table-sm" cellspacing="10px">
                                <thead>
                                    <tr>
                                        <th width="5%" style="text-align: center">N°</th>                                        
                                        <th width="20%" style="text-align: center">Fecha de Pago</th>
                                        <th width="10%" style="text-align: center">%</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
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
<div id="dlg_editor" style="display: none;">
    <iframe id="ck_editor_resol" width="770" height="515" marginheight="0" marginwidth="0" noresize scrolling="No" frameborder="0" style="border:1px solid #DDD"></iframe>
</div>
<div id="vw_coact_ver_doc" style="display: none;">
    <iframe id="vw_coa_iframe_doc" width="885" height="580"></iframe>
</div>
<div id="dlg_new_exp_notrib" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">                
                <div class="panel panel-success" style="border: 0px !important">
                    <div class="panel-heading bg-color-success" >.:: Datos del Expediente ::.</div>
                    <div class="panel-body">
                        <fieldset>
                            <section> 
                                <label class="label">Contribuyente:</label>
                                <label class="input">
                                    <input type="hidden" id="exp_notrib_id_contrib">
                                    <input id="exp_notrib_contrib" type="text" placeholder="CONTRIBUYENTE" class="input-sm text-uppercase" disabled="">
                                </label>                      
                            </section>
                            <div class="row">
                                <section class="col col-6" style="padding-right:5px;"> 
                                    <label class="label">Materia:</label>
                                    <label class="select">
                                        <select id="exp_notrib_codmateria" onchange="fn_trae_val(this.value)" class="input-sm">
                                            <option value='1'>TRIBUTARIA</option>
                                            <option value='0'>NO TRIBUTARIA</option>
                                        </select><i></i></label>
                                </section>
                                <section class="col col-4" style="padding-left:5px;padding-right: 5px">
                                    <label class="label">Monto:</label>
                                    <label class="input">
                                        <input id="exp_notrib_monto" type="text" onkeypress="return soloNumeroTab(event);" placeholder="000.00" class="input-sm text-right" >
                                    </label>                        
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-10" style="padding-right:5px;"> 
                                    <label class="label">Valor:</label>
                                    <label class="input">
                                        <input type="hidden" id="hiddenexp_notrib_valor">
                                        <input id="exp_notrib_valor" type="text" placeholder="Ejm: Resolucion de multa" class="input-sm text-uppercase">                                        
                                    </label>
                                </section>
                                <section class="col col-2" style="padding-left:5px;"> 
                                    <label class="label">&nbsp;</label>
                                    <button onclick="new_otro_valor();" type="button" class="btn btn-labeled bg-color-blue txt-color-white" style="width: 80px;">
                                       <span class="btn-label"><i class="fa fa-file-text"></i></span>Otro
                                    </button>        
                                </section>
                            </div>
                        </fieldset>
                    </div>
                </div>               
            </div>
        </div>
    </div>
</div>
<div id="dlg_new_valor" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">                
                <div class="panel panel-success" style="border: 0px !important">
                    <div class="panel-heading bg-color-success" >.:: Documentos Adjuntos ::.</div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="row">
                                <section class="col col-6"> 
                                    <label class="label">Materia:</label>
                                    <label class="input">
                                        <input type="hidden" id="hiddendlg_new_val_txt_mat">
                                        <input id="dlg_new_val_txt_mat" type="text" class="input-sm text-uppercase" disabled="">
                                    </label>                      
                                </section>
                            </div>
                            <section> 
                                <label class="label">Especificar Valor:</label>
                                <label class="input">
                                    <input id="dlg_new_val_txt_valor" type="text" placeholder="Ejm: RESOLUCION DE MULTA" class="input-sm text-uppercase">
                                </label>                      
                            </section>
                            <section> 
                                <label class="label">Abreviatura:</label>
                                <label class="input">
                                    <input id="dlg_new_val_txt_abrev" type="text" placeholder="Ejm: OP, RD" class="input-sm text-uppercase">
                                </label>                      
                            </section>
                        </fieldset>
                    </div>
                </div>               
            </div>
        </div>
    </div>
</div>
<div id="dlg_up_doc_adjuntos" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">                
                <div class="panel panel-success" style="border: 0px !important">
                    <div class="panel-heading bg-color-success" >.:: Documentos Adjuntos ::.</div>
                    <div class="panel-body">
                        <fieldset>
                            <section> 
                                <label class="label">Se Adjunta:</label>
                                <label class="input">
                                    <input id="exp_notif_txt" type="text" placeholder="Se adjunta" class="input-sm text-uppercase">
                                </label>                      
                            </section>
                        </fieldset>
                    </div>
                </div>               
            </div>
        </div>
    </div>
</div>

<div id="dlg_enable_pago" style="display: none;">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">                
                <div class="panel panel-success" style="border: 0px !important">
                    <div class="panel-body">
                        <fieldset>                            
                            <section> 
                                <label class="label">Contribuyente:</label>
                                <label class="input">
                                    <input type="hidden" id="dlg_enable_pago_idcontrib">
                                    <input id="dlg_enable_pago_contrib" type="text" placeholder="CONTRIBUYENTE" class="input-sm text-uppercase" disabled="">
                                </label>                      
                            </section>                            
                            <section style="margin-top:10px"> 
                                <table id="t_cta_cte"></table>
                                <div id="p_t_cta_cte"></div>                     
                            </section>                            
                        </fieldset>
                    </div>
                </div>               
            </div>
        </div>
    </div>
</div>
@endsection

