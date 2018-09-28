@extends('layouts.app')
@section('content')
<input type="hidden" id="per_imp" value="{{$permisos[0]->btn_imp}}"/>
<input type="hidden" id="per_edit" value="{{$permisos[0]->btn_edit}}"/>
<input type="hidden" id="per_del" value="{{$permisos[0]->btn_del}}"/>
<section id="widget-grid" class=""> 
    <div class='cr_content col-xs-12 '>
        <div class="col-xs-9">
            <h1 class="txt-color-green"><b>Ingreso de Fichas de Verificación...</b></h1>
        </div>
        <div class="col-xs-3" style="margin-top: 5px; padding-right: 23px;">
            <div class="input-group input-group-md">
                <span class="input-group-addon">Año de Tramite <i class="fa fa-cogs"></i></span>
                <div class="icon-addon addon-md">
                    <select id='selantra' class="form-control col-lg-8" style="height: 32px;" onchange="call_list_contrib_carta(0)">
                    @foreach ($anio_tra as $anio)
                    <option value='{{$anio->anio}}' >{{$anio->anio}}</option>
                    @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-xs-12 cr-body" >
            
            <div class="col-xs-4" style="padding: 0px; margin-top: 5px">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 0px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-align-justify"></i> </span>
                                <h2>Busqueda de Cartas por Contribuyente</h2>
                        </header>
                    </div>
                </section>
            </div>
            <div class="col-xs-8" style="padding: 0px; margin-top: 5px">
                
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Contribuyente &nbsp;<i class="fa fa-male"></i></span>
                        <div>
                            <input id="dlg_contri_hidden" type="hidden" value="0">
                            <input id="dlg_contri" type="text"  class="form-control" style="height: 32px;font-size: 0.9em;width: 102% !important" autofocus="focus" >
                        </div>
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" onclick="fn_bus_contrib_carta('dlg_contri')">
                                <i class="glyphicon glyphicon-search"></i>
                            </button>
                        </span>
                    </div>
                </div>
                
            </div>
            
            <div class="col-xs-12"></div>
            <div class="col-xs-4" style="padding: 0px; margin-top: 5px">
                <div>
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 5px; padding: 0px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-align-justify"></i> </span>
                                <h2>Busqueda Por Número Carta de Requerimiento</h2>
                        </header>
                    </div>
                </section>
                </div>
            </div>
            <div class="col-xs-8" style="padding: 0px; margin-top: 5px">
                <div class="col-xs-10" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Número Carta Requerimiento &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div class=""  >
                            <input id="dlg_bus_num" type="text"  class="form-control" style="height: 32px; " maxlength="7" onkeypress="return soloNumeroTab(event);" >
                        </div>
                    </div>
                </div>
                <div class='col-lg-2'style="padding: 0px;" >
                    <button type="button" class="btn btn-labeled bg-color-green txt-color-white" onclick="call_list_contrib_carta(3)">
                        <span class="btn-label"><i class="glyphicon glyphicon-search"></i></span>Buscar
                    </button>
                </div>
            </div>
            <div class="col-xs-12"></div>
            <div class="col-xs-4" style="padding: 0px; margin-top: 5px">
                <div>
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 5px; padding: 0px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-align-justify"></i> </span>
                                <h2>Busqueda de Carta Por Fechas</h2>
                        </header>
                    </div>
                </section>
                </div>
            </div>
            <div class="col-xs-8" style="padding: 0px; margin-top: 5px">
                <div class="col-xs-5" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Desde &nbsp;<i class="fa fa-calendar"></i></span>
                        <div class=""  >
                            <input id="dlg_bus_fini" type="text"   class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 32px; width: 100%" placeholder="--/--/----" value="{{date('d/m/Y')}}">
                        </div>
                    </div>
                </div>
                <div class="col-xs-5" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Hasta &nbsp;<i class="fa fa-calendar"></i></span>
                        <div class=""  >
                            <input id="dlg_bus_ffin" type="text" class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 32px; width: 100%" placeholder="--/--/----" value="{{date('d/m/Y')}}">
                        </div>
                    </div>
                </div>
                <div class='col-lg-2'style="padding: 0px;" >
                    <button type="button" class="btn btn-labeled bg-color-green txt-color-white" onclick="call_list_contrib_carta(2)">
                        <span class="btn-label"><i class="glyphicon glyphicon-search"></i></span>Buscar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class='cr_content col-xs-12'>
       
        <div class="col-xs-12" style="padding: 0px; margin-top: 10px">
            <article class="col-xs-12" style=" padding: 0px !important">
                <table id="table_cartas" class="col-xs-12"></table>
                    <div id="pager_table_cartas"></div>
            </article>
        </div>
    </div>
    
</section>
@section('page-js-script')
<script type="text/javascript">
    $(document).ready(function (){
        $("#menu_fisca").show();
        $("#li_ficha_ver").addClass('cr-active')
        jQuery("#table_cartas").jqGrid({
            url: 'trae_cartas/'+$("#selantra").val()+'/0/0/0/0',
            datatype: 'json', mtype: 'GET',
            height: '280px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_car', 'Nro', 'contribuyente', 'Registro','Fiscalizacion','Notificacion','Estado','Ver','Anulado','Ingresar Ficha'],
            rowNum: 20, sortname: 'id_car', sortorder: 'desc', viewrecords: true, caption: 'Cartas de Requerimiento', align: "center",
            colModel: [
                {name: 'id_car', index: 'id_gen_fis', hidden: true},
                {name: 'nro_car', index: 'nro_car', align: 'center', width: 5},
                {name: 'contribuyente', index: 'contribuyente', align: 'center', width: 30},
                {name: 'fec_reg', index: 'fec_reg', align: 'center', width: 8},
                {name: 'fec_fis', index: 'fec_fis', align: 'center', width: 15},
                {name: 'fecha_notificacion', index: 'fecha_notificacion', hidden: true},
                {name: 'flg_est', index: 'flg_est', align: 'center', width: 10},
                {name: 'ver', index: 'ver', align: 'center', width: 8},
                {name: 'flg_anu', index: 'flg_anu', align: 'center', width: 8},
                {name: 'fis', index: 'fis', align: 'center', width: 10},
            ],
            pager: '#pager_table_cartas',
            rowList: [20, 50],
            gridComplete: function () {
                    var idarray = jQuery('#table_cartas').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_cartas').jqGrid('getDataIDs')[0];
                            $("#table_cartas").setSelection(firstid);    
                        }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){}
        });
        contrib_global=0;
        jQuery("#table_contrib").jqGrid({
            url: 'obtiene_cotriname?dat=0',
            datatype: 'json', mtype: 'GET',
            height: '300px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_pers','codigo','DNI/RUC','contribuyente','Dom Fiscal'],
            rowNum: 20, sortname: 'contribuyente', sortorder: 'asc', viewrecords: true, caption: 'Contribuyentes', align: "center",
            colModel: [
                {name: 'id_pers', index: 'id_pers', hidden: true},
                {name: 'id_per', index: 'id_per', align: 'center',width: 100},
                {name: 'nro_doc', index: 'nro_doc', align: 'center',width: 100},
                {name: 'contribuyente', index: 'contribuyente', align: 'left',width: 260},
                {name: 'dom_fiscal', index: 'dom_fiscal', align: 'left',width: 260},
                
            ],
            pager: '#pager_table_contrib',
            rowList: [13, 20],
            gridComplete: function () {
                    var idarray = jQuery('#table_contrib').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_contrib').jqGrid('getDataIDs')[0];
                            $("#table_contrib").setSelection(firstid);    
                        }
                    if(contrib_global==0)
                    {   contrib_global=1;    
                        jQuery('#table_contrib').jqGrid('bindKeys', {"onEnter":function( rowid ){fn_bus_contrib_list_ficha(rowid);} } ); 
                    }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){fn_bus_contrib_list_ficha(Id)}
        });
        jQuery("#table_predios_contri").jqGrid({
            url: 'trae_pred_carta/0',
            datatype: 'json', mtype: 'GET',
            height: '300px', width: '500px',
            toolbarfilter: true,
            colNames: ['id_pred_anio','id_fic','id_puente','Tipo','codigo','Ubicación','N° Ficha ver','Fiscalizar'],
            rowNum: 20, sortname: 'id_puente', sortorder: 'asc', viewrecords: true, caption: 'Predios a Fiscalizar', align: "center",
            colModel: [
                {name: 'id_pred_anio', index: 'id_pred_anio', hidden: true},
                {name: 'id_puente', index: 'id_puente', hidden: true},
                {name: 'id_fic', index: 'id_fic', hidden: true},
                {name: 'tp', index: 'tp', align: 'center',width: 30},
                {name: 'cod_cat', index: 'cod_cat', align: 'center',width: 70},
                {name: 'dir', index: 'dir', align: 'left',width: 450},
                {name: 'est', index: 'est', align: 'left',width: 60},
                {name: '', index: '', align: 'left',width: 115},
            ],
            pager: '#pager_table_predios_contri',
            rowList: [13, 20],
            gridComplete: function () {
                    var idarray = jQuery('#table_predios_contri').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_predios_contri').jqGrid('getDataIDs')[0];
                            $("#table_predios_contri").setSelection(firstid);    
                        }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){}
        });
        jQuery("#table_pisos").jqGrid({
            url: 'traepisos_fic/0/0',
            datatype: 'json', mtype: 'GET',
            height: '200px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_pi','Piso', 'Fecha', 'MEP', 'ECS', 'ECC', 'Muro', 'Techo', 'Piso', 'Puerta','Reves','baños','I.Elect','Area Constr.','id_pisos_fic','Fiscalizado'],
            rowNum: 20, sortname: 'id_pi', sortorder: 'desc', viewrecords: true, caption: 'Pisos del Predio', align: "center",
            colModel: [
                {name: 'id_pi', index: 'id_pi', hidden: true},
                {name: 'piso', index: 'piso', align: 'center', width: 40},
                {name: 'fech', index: 'fech', align: 'center', width: 50},
                {name: 'mep', index: 'mep', align: 'center', width: 70},
                {name: 'ecs', index: 'ecs', align: 'center', width: 70},
                {name: 'ecc', index: 'ecc', align: 'center', width: 70},
                {name: 'muro', index: 'muro', width: 70,align: "center"},
                {name: 'techo', index: 'techo', width: 70,align: 'center'},
                {name: 'piso', index: 'piso', width: 70,align: 'center'},
                {name: 'puerta', index: 'puerta', width: 70,align: 'center'},
                {name: 'reves', index: 'reves', width: 70,align: 'center'},
                {name: 'banio', index: 'banio', width: 70,align: 'center'},
                {name: 'I.Elect', index: 'I.Elect', width: 70,align: 'center'},
                {name: 'aconst', index: 'aconst', width: 80,align: 'center'},
                {name: 'id_pisos_fic', index: 'id_pisos_fic', hidden: true},
                {name: 'fis', index: 'fis', width: 70,align: 'center'}
            ],
            pager: '#pager_table_pisos',
            rowList: [13, 20],
            gridComplete: function () {
                    var idarray = jQuery('#table_pisos').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_pisos').jqGrid('getDataIDs')[0];
                            $("#table_pisos").setSelection(firstid);    
                        }
                },
            ondblClickRow: function (Id){clickmodpiso();}
        });
        jQuery("#table_instal").jqGrid({
            url: 'traeinsta_fic/0/0',
            datatype: 'json', mtype: 'GET',
            height: '200px', autowidth: true,
            toolbarfilter: true,
            colNames: ['cod_obra','Código', 'Descripción', 'año de antiguedad', 'MEP','ECS','ECC','lARGO','ANCHO','ALTO','UND.MED','PROD.TOTAL','id_inst_fic','Fiscalizado'],
            rowNum: 20, sortname: 'id_inst', sortorder: 'desc', viewrecords: true, caption: 'Instalación del Predio', align: "center",
            colModel: [
                {name: 'cod_obra', index: 'cod_obra', hidden: true},
                {name: 'cod_inst', index: 'cod_inst', align: 'center', width: 50},
                {name: 'des', index: 'des', align: 'left', width: 210},
                {name: 'anio', index: 'anio', align: 'center', width: 80},
                {name: 'mep', index: 'mel', align: 'center', width: 50},
                {name: 'ecs', index: 'ecs', align: 'center', width: 50},
                {name: 'ecc', index: 'ecc', align: 'center', width: 50},
                {name: 'dim_lar', index: 'dim_lar', align: 'center', width: 80},
                {name: 'dim_anch', index: 'dim_anch', align: 'center', width: 80},
                {name: 'dim_alt', index: 'dim_alt', align: 'center', width: 80},
                {name: 'uni_med', index: 'uni_med', align: 'center', width: 70},
                {name: 'tot_inst', index: 'tot_inst', align: 'right', width: 80},
                {name: 'id_inst_fic', index: 'id_inst_fic', hidden: true},
                {name: 'fis', index: 'fis', width: 70,align: 'center'}

            ],
            pager: '#pager_table_instal',
            rowList: [13, 20],
            gridComplete: function () {
                    var idarray = jQuery('#table_instal').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_instal').jqGrid('getDataIDs')[0];
                            $("#table_instal").setSelection(firstid);    
                        }
                },
            ondblClickRow: function (Id){clickmodinst();}
        });
        var globalvalidador=0;
        $("#dlg_contri").keypress(function (e) {
            if (e.which == 13) {
                if(globalvalidador==0)
                {
                    fn_bus_contrib_carta("dlg_contri");
                    globalvalidador=1;
                }
                else
                {
                    globalvalidador=0;
                }
                
            }
        });
        $("#dlg_contri_carta").keypress(function (e) {
            if (e.which == 13) {
                if(globalvalidador==0)
                {
                    fn_bus_contrib_carta("dlg_contri_carta");
                    globalvalidador=1;
                }
                else
                {
                    globalvalidador=0;
                }
            }
        });
        $("#dlg_bus_num").keypress(function (e) {
            if (e.which == 13) {
                call_list_contrib_carta(3);
            }
        });
        jQuery('#rpiso_inp_estruc_fis').keypress(function(tecla) {
        $("#rpiso_inp_estruc_fis").val($("#rpiso_inp_estruc_fis").val().toUpperCase());
        if(tecla.charCode < 65 || tecla.charCode > 73)
        {
            if(tecla.charCode < 97 || tecla.charCode > 105) return false;
        }
    });
    });
</script>
@stop
<script src="{{ asset('archivos_js/fiscalizacion/ficha_ver.js') }}"></script>
<div id="dlg_bus_contr" style="display: none;">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
        <table id="table_contrib"></table>
        <div id="pager_table_contrib"></div>
    </article>
</div> 
<div id="dlg_vista_carta" style="display: none;">
    
    <div class='cr_content col-xs-12 ' style="margin-bottom: 10px;">
        <div class="col-xs-12 cr-body" style="padding-left: 0px;padding-right: 10px;" >
            <div class="col-xs-5" style="padding: 0px; margin-top: 0px;">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-info"></i> </span>
                                <h2>Información Carta de Requerimiento::..</h2>
                        </header>
                    </div>
                </section>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">N° Carta Requerimiento. &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div class=""  >
                            <input id="dlg_nro_car" type="text"  class="form-control" style="height: 32px; " disabled="" >
                        </div>
                    </div>
                </div>
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px; ">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Contribuyente &nbsp;<i class="fa fa-male"></i></span>
                        <div>
                            <input id="dlg_contri_carta" type="text"  class="form-control" style="height: 32px;font-size: 0.9em;width: 100% !important" disabled="" >
                        </div>
                       
                    </div>
                </div>
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                
                <div class="col-xs-12" style="padding: 0px; ">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Domicilio Fiscal &nbsp;<i class="fa fa-map"></i></span>
                        <div>
                            <input id="dlg_contri_carta_dom" type="text"  class="form-control" style="height: 32px;font-size: 0.9em;" disabled="">
                        </div>
                    </div>
                </div>
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                
                <div class="col-xs-12" style="padding: 0px; ">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Fecha Fiscalización &nbsp;<i class="fa fa-calendar"></i></span>
                        <div>
                            <input id="dlg_fec_fis" type="text"  class="form-control" style="height: 32px;font-size: 0.9em;" disabled="">
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                
                <div class="col-xs-12" style="padding: 0px; ">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Hora Fiscalización &nbsp;<i class="fa fa-clock-o"></i></span>
                        <div>
                            <input id="dlg_hor_fis" type="text"  class="form-control " style="height: 32px;font-size: 0.9em;" disabled="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-7" style="padding: 0px; margin-top: 0px;">
                <article class="col-xs-12" style=" padding-left: 0px !important">
                    <table id="table_predios_contri"></table>
                    <div id="pager_table_predios_contri"></div>
                </article>
            </div>
        </div>
    </div>
</div> 

<div id="dlg_reg_dj" style="display: none;">
                    <div class="widget-body">
                    <div  class="smart-form">
                        <div class="col-xs-10">
                             
                            <div class="panel-group col-xs-3 " style="margin-top: 5px;  ">                
                                <div class="panel panel-success">
                                    <div class="panel-heading bg-color-success">N° Ficha de Verificacion</div>
                                    <div class="panel-body cr-body">

                                        <div class="col col-12" style=" margin-top: 8px;">
                                            <div class="input-group input-group-md">
                                                <input type="hidden" id="dlg_idfic" value="0">
                                                <span class="input-group-addon">Numero &nbsp;<i class="fa fa-hashtag"></i></span>
                                                <div class="icon-addon addon-md" style="padding: 0px;">
                                                    <input id="dlg_nro_ficha" class="col-xs-12 form-control"  style="height: 32px; padding-left: 10px; padding-right: 0px;" type="text" maxlength="7" onkeypress="return soloDNI(event);" onblur="ajustar(6, 'dlg_nro_ficha')">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>    

                            <div id="div_urb">    
                                <div class="panel-group col-xs-9 " style="margin-top: 5px">               
                                    <div class="panel panel-success cr-panel-sep">
                                        <div class="panel-heading bg-color-success">.:: Codigo de Referencia ::.</div>
                                        <div class="panel-body cr-body" style="padding-top: 8px;">
                                            <div class="col col-4">
                                                <div class="input-group input-group-md">
                                                    <input type="hidden" id="dlg_idpre" value="0">
                                                    <span class="input-group-addon">Sector &nbsp;&nbsp;<i class="fa fa-cogs"></i></span>
                                                    <div class="icon-addon addon-md">
                                                        <input class="text-center col-xs-12 form-control"  style="height: 32px; width: 90%" id="dlg_sec" type="text" name="dlg_sec" disabled="" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col col-4">
                                                <div class="input-group input-group-md">
                                                    <span class="input-group-addon">Manzana &nbsp;&nbsp;<i class="fa fa-apple"></i></span>
                                                    <div class="icon-addon addon-md">
                                                        <input class="text-center form-control" style="height: 32px; ; width: 90%" id="dlg_mzna" type="text" name="dlg_mzna" disabled="" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col col-4">
                                                <div class="input-group input-group-md">
                                                    <span class="input-group-addon">Lotes &nbsp;<i class="fa fa-home"></i></span>
                                                    <div class="icon-addon addon-md">
                                                        <select id='dlg_lot' class="form-control col-lg-8" style="height: 32px; text-align: center; width: 90%" onchange="traerfoto()">

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="div_rus">    
                                <div class="panel-group col-xs-3 " style="margin-top: 5px">               
                                    <div class="panel panel-success cr-panel-sep">
                                        <div class="panel-heading bg-color-success">.:: Tipo de Predio ::.</div>
                                        <div class="panel-body cr-body text-align-center" style="padding-top: 8px;padding-bottom: 18px;">
                                            <h1 class="txt-color-green"><b>Predio Rustico</b></h1>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-group col-xs-4 " style="margin-top: 5px;  ">                
                                    <div class="panel panel-success cr-panel-sep">
                                        <div class="panel-heading bg-color-success">Grupo Tierras</div>
                                        <div class="panel-body cr-body">

                                            <div class="col col-12" style=" margin-top: 8px;">
                                                <select id='dlg_sel_gpoterr' class="form-control" onchange="llamarcambio();" style="height: 32px; padding-left: 10px; width: 100%">
                                                    @foreach ($gpoterr as $gter)
                                                    <option value='{{$gter->id_gpo}}' >{{$gter->gpo_descrip}}</option>
                                                    @endforeach
                                                </select> 
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                <div class="panel-group col-xs-2 " style="margin-top: 5px;  ">                
                                    <div class="panel panel-success cr-panel-sep">
                                        <div class="panel-heading bg-color-success">Categoria Tierras</div>
                                        <div class="panel-body cr-body">

                                            <div class="col col-12" style=" margin-top: 8px;">
                                                <select id='dlg_sel_gpocatterr' class="form-control" onchange="callchangeoption('dlg_sel_gpocatterr',1);" style="height: 32px; padding-left: 10px; width: 100%" >
                                                
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                               
                            </div>
                                    
                               
                            <div class="panel-group col-xs-6 " style="margin-top: 5px">                
                                    <div class="panel panel-success">
                                        <div class="panel-heading bg-color-success">.:: Datos del Propietario (Contribuyente) ::.</div>
                                        <div class="panel-body cr-body">
                                            <div class="col col-3" style="padding-right: 0px; margin-top: 8px;">
                                                <div class="input-group input-group-md" style="padding: 0px;">
                                                    <span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
                                                    <div class="icon-addon addon-md" style="padding: 0px;">
                                                        <input id="dlg_dni_pred" class="text-center col-xs-12 form-control"  style="height: 32px; padding-left: 3px; padding-right: 0px;" type="text" disabled="" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col col-9" style="padding-right: 10px; margin-top: 8px;">
                                                <div class="input-group input-group-md" >
                                                    <span class="input-group-addon"><i class="fa fa-male"></i></span>
                                                    <div class="icon-addon addon-md" >
                                                        <input id="dlg_contri_pred" class="col-xs-12 form-control"  style="height: 32px; padding-left: 5px; padding-right: 0px;" type="text" disabled="" >
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            <div class="panel-group col-xs-6 " style="margin-top: 5px;  ">                
                                <div class="panel panel-success cr-panel-sep">
                                    <div class="panel-heading bg-color-success">.:: Ubicación del Predio ::.</div>
                                    <div class="panel-body cr-body">

                                        <div class="col col-12" style=" margin-top: 8px;">
                                            <div class="input-group input-group-md">
                                                <span class="input-group-addon"><i class="fa fa-map-signs"></i></span>
                                                <div class="icon-addon addon-md" style="padding: 0px;">
                                                    <input id="dlg_inp_direcc" class="text-center col-xs-12 form-control"  style="height: 32px; padding-left: 3px; padding-right: 0px;" type="text" disabled="" >
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        
                        </div>
                       
                            <div class="col-xs-2">
                                <div class="panel panel-success cr-panel-sep" style="height: 154px">
                                    <div class="panel-heading bg-color-success">.:: Foto Predio ::.</div>
                                    <div class="panel-body cr-body">
                                        <div id="dlg_img_view" style="padding-top: 10px" onclick="viewlong()"></div>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="panel-group col-xs-2 " style="margin-top: 5px; margin-bottom: 5px  ">                
                                <div class="panel panel-success ">
                                    <div class="panel-heading bg-color-success">Condicion de Propiedad</div>
                                    <div class="panel-body cr-body" style="margin-top: 8px">
                                        <div class="col-xs-12" style="padding-left: 15px;">
                                            <select id="dlg_sel_condpre"  class="form-control" style="width: 85%" onchange="validacond()">
                                                @foreach ($condicion as $condicion)
                                                <option value='{{$condicion->id_cond}}' >{{$condicion->descripcion}}</option>
                                                @endforeach
                                            </select>                       
                                         </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-group col-xs-1 " style="margin-top: 5px; margin-bottom: 5px  ">                
                                <div class="panel panel-success cr-panel-sep">
                                    <div class="panel-heading bg-color-success">% Codominio</div>
                                    <div class="panel-body cr-body" style="margin-top: 8px">
                                        <div class="col-xs-12" >
                                            <label class="input" style="padding-left: 15px;">
                                                <input id="dlg_inp_condos"  type="text"  class="input-sm text-right" maxlength="3" onkeypress="return soloNumeroTab(event);" disabled="" style="width: 85%">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-group col-xs-2 " style="margin-top: 5px; margin-bottom: 5px  ">                
                                <div class="panel panel-success cr-panel-sep">
                                    <div class="panel-heading bg-color-success">Estado de Construccion</div>
                                    <div class="panel-body cr-body" style="margin-top: 8px">
                                        <div class='col-xs-12 pd_dlg_cr'>
                                            <select id='dlg_sel_estcon' class="form-control" style="width: 85%" >
                                                    @foreach ($ecc as $eccpre)
                                                    <option value='{{$eccpre->id_ecc}}' >{{$eccpre->descripcion}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-group col-xs-2 " style="margin-top: 5px; margin-bottom: 5px  ">                
                                    <div class="panel panel-success cr-panel-sep">
                                        <div class="panel-heading bg-color-success">Tipo de Predio</div>
                                        <div class="panel-body cr-body" style="margin-top: 8px">
                                            <div class='col col-12 '>
                                                <select id='dlg_sel_tippre' class="form-control" >
                                                    @foreach ($tpre as $tpre)
                                                        <option value='{{$tpre->id_tip_p}}' >{{$tpre->descrip_tip_pre}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        
                            
                            <div class="col-xs-5" style="padding: 0px;">
                            <div class="panel-group col-xs-3 " style="margin-top: 5px; ">                
                                <div class="panel panel-success cr-panel-sep">
                                    <div class="panel-heading bg-color-success">Arancel</div>
                                    <div class="panel-body cr-body">
                                        <div class='col-xs-12 pd_dlg_cr' style="margin-top: 8px; padding-left: 10px;" >
                                            <label class="input">
                                                <input id="dlg_inp_aranc" type="text"  class="input-sm" disabled="" style="text-align: right;width: 85%">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="div_urb2">
                                <div class="panel-group col-xs-3 " style="margin-top: 5px;   ">                
                                    <div class="panel panel-success cr-panel-sep">
                                        <div class="panel-heading bg-color-success">Are. Terr./Matriz</div>
                                        <div class="panel-body cr-body">
                                            <div class='col-lg-12 pd_dlg_cr' style="margin-top: 8px; padding-left: 10px;">
                                                <label class="input">
                                                    <input id="dlg_inp_areter" type="text"  class="input-sm" onkeypress="return soloNumeroTab(event);" onkeyup="validarvalter(1);" style="text-align: right;width: 85%" placeholder="0.00">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="panel-group col-xs-3 " style="margin-top: 5px;  ">                
                                    <div class="panel panel-success cr-panel-sep">
                                        <div class="panel-heading bg-color-success">% Area Comun:</div>
                                        <div class="panel-body cr-body">
                                            <div class='col-lg-12 pd_dlg_cr' style="margin-top: 8px; padding-left: 10px;">
                                                <label class="input">
                                                    <input id="dlg_inp_arecomter" type="text"  class="input-sm" onkeypress="return soloNumeroTab(event);" onkeyup="validarvalter(1);" style="text-align: right;width: 85%" placeholder="0.00">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="div_rus2" >
                                <div class="panel-group col-xs-3 " style="margin-top: 5px;   ">                
                                    <div class="panel panel-success cr-panel-sep">
                                        <div class="panel-heading bg-color-success">Hectareas</div>
                                        <div class="panel-body cr-body">
                                            <div class='col-lg-12 pd_dlg_cr' style="margin-top: 8px; padding-left: 10px;">
                                                <label class="input">
                                                    <input id="dlg_inp_hectarea" type="text"  class="input-sm" onkeypress="return soloNumeroTab(event);" onkeyup="validarvalter(2);" style="text-align: right;width: 85%" placeholder="0.00">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-group col-xs-3 " style="margin-top: 5px; ">                
                                <div class="panel panel-success cr-panel-sep">
                                <div class="panel-heading bg-color-success">Valor de Terreno</div>
                                <div class="panel-body cr-body">
                                    <div class='col-lg-12 pd_dlg_cr' style="margin-top: 8px; padding-left: 10px;">
                                        <label class="input">
                                            <input id="dlg_inp_valterr" type="text"  class="input-sm" disabled="" style="text-align: right; width: 85%">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        
                        <div class="panel-group col-xs-9 " style="margin-bottom: 5px  ">                
                            <div class="panel panel-success">
                                <div class="panel-body cr-body">
                                    <div class="col col-12" style=" margin-top: 10px;">
                                        <div class="input-group input-group-md">
                                            <span class="input-group-addon">Observaciones &nbsp;<i class="fa fa-asterisk"></i></span>
                                            <div class="icon-addon addon-md" style="padding: 0px;">
                                                <input id="dlg_observa" class="col-xs-12 form-control"  style="height: 32px; padding-left: 10px; padding-right: 0px;" type="text" maxlength="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-3">
                            @if( $permisos[0]->btn_new ==1 )
                            <button id="btnsaveficha" class="btn bg-color-green txt-color-white cr-btn-big" onclick="dlgsave();" style="margin-left: 10px; width: 97%" >
                                <span style="left:-40px !important">
                                    <i class="glyphicon glyphicon-plus-sign"></i>
                                </span>
                                <label>Guardar Ficha de Verificación</label>
                            </button>
                            @else
                            <button id="btnsaveficha" class="btn bg-color-green txt-color-white cr-btn-big" onclick="sin_permiso();" style="margin-left: 10px; width: 97%" >
                                <span style="left:-40px !important">
                                    <i class="glyphicon glyphicon-plus-sign"></i>
                                </span>
                                <label>Guardar Ficha de Verificación</label>
                            </button>
                            @endif
                            @if( $permisos[0]->btn_edit ==1 )
                            <button id="btnmodficha" class="btn bg-color-blue txt-color-white cr-btn-big" onclick="dlgupdate();" style="margin-left: 10px; width: 97%" >
                                <span style="left:-40px !important">
                                    <i class="glyphicon glyphicon-plus-sign"></i>
                                </span>
                                <label>Modificar Ficha de Verificación</label>
                            </button>
                            @else
                                <button id="btnmodficha" class="btn bg-color-blue txt-color-white cr-btn-big" onclick="sin_permiso()" style="margin-left: 10px; width: 97%" >
                                    <span style="left:-40px !important">
                                        <i class="glyphicon glyphicon-plus-sign"></i>
                                    </span>
                                    <label>Modificar Ficha de Verificación</label>
                                </button>
                            @endif
                            
                        </div> 
                    </div>
                    
                    <div class="col-xs-12" style="margin-top: 5px; margin-bottom: 10px">
                            <ul id="tabs1" class="nav nav-tabs bordered">
                                <li class="active">
                                    <a href="#s1" data-toggle="tab" aria-expanded="true">
                                        Construcciones
                                        <i class="fa fa-lg fa-fw fa-cog fa-spin"></i>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="#s2" data-toggle="tab" aria-expanded="false">
                                        Otras Instalaciones
                                        <i class="fa fa-lg fa-fw fa-cog fa-spin"></i>
                                    </a>
                                </li>
                            </ul>
                            <div id="myTabContent1" class="tab-content padding-10">
                                <div id="s1" class="tab-pane fade active in" style="height: 300px">
                                        <div class="col-xs-10">
                                            <table id="table_pisos" ></table>
                                            <div id="pager_table_pisos"></div>
                                        </div>
                                        <div class="col-xs-2">
                                            @if( $permisos[0]->btn_new ==1 )
                                            <button class="btn bg-color-green txt-color-white cr-btn-big" onclick="clicknewpiso()" >
                                                <span>
                                                    <i class="glyphicon glyphicon-plus-sign"></i>
                                                </span>
                                                <label>Nuevo Piso</label>
                                            </button>
                                            @else
                                            <button class="btn bg-color-green txt-color-white cr-btn-big" onclick="sin_permiso()" >
                                                <span>
                                                    <i class="glyphicon glyphicon-plus-sign"></i>
                                                </span>
                                                <label>Nuevo Piso</label>
                                            </button>
                                            @endif
                                            @if( $permisos[0]->btn_edit ==1 )
                                            <button class="btn bg-color-blue txt-color-white cr-btn-big" onclick="clickmodpiso()" >
                                                <span>
                                                    <i class="glyphicon glyphicon-edit"></i>
                                                </span>
                                                <label>Editar Piso</label>
                                            </button>
                                            @else
                                            <button class="btn bg-color-blue txt-color-white cr-btn-big" onclick="sin_permiso()" >
                                                <span>
                                                    <i class="glyphicon glyphicon-edit"></i>
                                                </span>
                                                <label>Editar Piso</label>
                                            </button>
                                            @endif
                                            
                                            
                                        </div>
                                </div>
                                <div id="s2" class="tab-pane fade" style="height: 300px">
                                    <div class="col-xs-10">
                                        <table id="table_instal" ></table>
                                        <div id="pager_table_instal"></div>
                                    </div>
                                    <div class="col-xs-2">
                                        @if( $permisos[0]->btn_new ==1 )
                                            <button class="btn bg-color-green txt-color-white cr-btn-big" onclick="clicknewinst()" >
                                                <span>
                                                    <i class="glyphicon glyphicon-plus-sign"></i>
                                                </span>
                                                <label>Nueva Inst</label>
                                            </button>
                                        @else
                                            <button class="btn bg-color-green txt-color-white cr-btn-big" onclick="sin_permiso()" >
                                                <span>
                                                    <i class="glyphicon glyphicon-plus-sign"></i>
                                                </span>
                                                <label>Nueva Inst</label>
                                            </button>
                                        @endif
                                        @if( $permisos[0]->btn_edit ==1 )
                                            <button class="btn bg-color-blue txt-color-white cr-btn-big" onclick="clickmodinst()" >
                                                <span>
                                                    <i class="glyphicon glyphicon-edit"></i>
                                                </span>
                                                <label>Editar Inst</label>
                                            </button>
                                        @else
                                        <button class="btn bg-color-blue txt-color-white cr-btn-big" onclick="sin_permiso()" >
                                                <span>
                                                    <i class="glyphicon glyphicon-edit"></i>
                                                </span>
                                                <label>Editar Inst</label>
                                            </button>
                                        @endif
                                            
                                        </div>
                                </div>
                                
                                
                            </div>
                    </div>    
                </div>
</div>
<div id="dlg_reg_piso" style="display: none;">
    <div class="widget-body" >
        <div  class="smart-form">
            <div class="panel-group col-xs-6" style="margin-bottom: 10px;">
                    <div class="panel panel-success " >
                        <div class="panel-heading bg-color-success">.:: Datos del piso Declarado ::.</div>
                        <div class="panel-body cr-body">
                        <div class='col-lg-3 pd_dlg_cr'>
                            <input type="hidden" id="dlg_idpiso" value="0">
                            <label class="label">N° Piso:</label>
                            <label class="input">
                                <input id="rpiso_inp_nro" type="text" class="input-sm" disabled="" >
                            </label>
                        </div>
                        <div class='col-lg-6 '>
                            <label class="label">Año de construccion:</label>
                            <label class="input">
                                <input id="rpiso_inp_fech" type="text"  class="input-sm" disabled="" >
                            </label>
                        </div>
                        <div class="col-xs-12"></div>
                        <div class='col-xs-3 pd_dlg_cr'>
                            <label class="label">Clasificación:</label>
                            <select id='rpiso_inp_clasi' class="form-control col-lg-8" disabled="">
                                @foreach ($pisclasi as $pisclasi1)
                                <option value='{{$pisclasi1->id_cla_pre}}' descri="{{$pisclasi1->desc_clasific}}" >{{$pisclasi1->id_cla_pre}}</option>
                                @endforeach
                            </select>
                            
                        </div>
                        <div class='col-xs-8'>
                            <label class="label">&nbsp;</label>
                            <label class="input">
                                <input id="rpiso_inp_clasi_des" type="text"  class="input-sm" disabled="">
                            </label>
                        </div>
                        <div class="col-xs-12"></div>
                        <div class='col-lg-3 pd_dlg_cr'>
                            <label class="label">Material:</label>
                            <select id='rpiso_inp_mat' class="form-control col-lg-8" disabled="">
                                @foreach ($pismat as $pismat1)
                                <option value='{{$pismat1->id_mep}}' descri="{{$pismat1->mep}}" >{{$pismat1->id_mep}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-xs-8'>
                            <label class="label">&nbsp;</label>
                            <label class="input">
                                <input id="rpiso_inp_mat_des" type="text"  class="input-sm" disabled="">
                            </label>
                        </div>
                        <div class="col-xs-12"></div>
                        <div class='col-lg-3 pd_dlg_cr'>
                            <label class="label">Estado Conservación:</label>
                            <select id='rpiso_inp_econserv' class="form-control col-lg-8" disabled="">
                                @foreach ($pisecs as $pisecs1)
                                <option value='{{$pisecs1->id_ecs}}' descri="{{$pisecs1->ecs}}" >{{$pisecs1->id_ecs}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-xs-8'>
                            <label class="label">&nbsp;</label>
                            <label class="input">
                                <input id="rpiso_inp_econserv_des" type="text"  class="input-sm" disabled="">
                            </label>
                        </div>
                        <div class="col-xs-12"></div>
                        <div class='col-lg-3 pd_dlg_cr'>
                            <label class="label">Estado Construcción:</label>
                            <select id='rpiso_inp_econstr' class="form-control col-lg-8" disabled="">
                                @foreach ($ecc as $ecc2)
                                <option value='{{$ecc2->id_ecc}}' descri="{{$ecc2->descripcion}}" >{{$ecc2->id_ecc}}</option>
                                @endforeach
                            </select>
                            
                        </div>
                        <div class='col-xs-8'>
                            <label class="label">&nbsp;</label>
                            <label class="input">
                                <input id="rpiso_inp_econstr_des" type="text"  class="input-sm" disabled="">
                            </label>
                        </div>
                        <div class="col-xs-12"></div>
                        <div class='col-lg-5 pd_dlg_cr'>
                            <label class="label">Estructuras:</label>
                            <label class="input">
                                <input id="rpiso_inp_estruc" type="text"  class="input-sm" disabled="">
                            </label>
                        </div>
                        
                        <div class='col-lg-3 '>
                            <label class="label">Area Construida:</label>
                            <label class="input">
                                <input id="rpiso_inp_aconst" type="text"  class="input-sm text-right" disabled="" >
                            </label>
                        </div>
                        <div class='col-lg-3'>
                            <label class="label">Areas Comunes:</label>
                            <label class="input">
                                <input id="rpiso_inp_acomun" type="text"  class="input-sm text-right" disabled="" >
                            </label>
                        </div>
                    </div>
                    </div>
               
            </div>
            <div class="panel-group col-xs-6" style="margin-bottom: 10px;">
                    <div class="panel panel-success cr-panel-sep" >
                        <div class="panel-heading bg-color-success">.:: Datos del piso Fiscalizado ::.</div>
                        <div class="panel-body cr-body">
                        <div class='col-lg-3 pd_dlg_cr'>
                            <input type="hidden" id="dlg_idpiso_fis" value="0">
                            <label class="label">N° Piso:</label>
                            <label class="input">
                                <input id="rpiso_inp_nro_fis" type="text"  class="input-sm" maxlength="5" >
                            </label>
                        </div>
                        <div class='col-lg-3 '>
                            <label class="label">Año de construccion:</label>
                            <label class="input">
                                <input id="rpiso_inp_fech_fis" type="text"  class="input-sm" maxlength="4" >
                            </label>
                        </div>
                        <div class='col-lg-3 '>
                            <label class="label">Año de Demolición:</label>
                            <label class="input">
                                <input id="rpiso_inp_fech_demoli_fis" type="text"  class="input-sm" maxlength="4" >
                            </label>
                        </div>
                        <div class="col-xs-12"></div>
                        <div class='col-xs-3 pd_dlg_cr'>
                            <label class="label">Clasificación:</label>
                            <select id='rpiso_inp_clasi_fis' class="form-control col-lg-8" onchange="callchangeoption('rpiso_inp_clasi_fis',0)">
                                @foreach ($pisclasi as $pisclasi1)
                                <option value='{{$pisclasi1->id_cla_pre}}' descri="{{$pisclasi1->desc_clasific}}" >{{$pisclasi1->id_cla_pre}}</option>
                                @endforeach
                            </select>
                            
                        </div>
                        <div class='col-xs-8'>
                            <label class="label">&nbsp;</label>
                            <label class="input">
                                <input id="rpiso_inp_clasi_fis_des" type="text"  class="input-sm" disabled="">
                            </label>
                        </div>
                        <div class="col-xs-12"></div>
                        <div class='col-lg-3 pd_dlg_cr'>
                            <label class="label">Material:</label>
                            <select id='rpiso_inp_mat_fis' class="form-control col-lg-8" onchange="callchangeoption('rpiso_inp_mat_fis',0)">
                                @foreach ($pismat as $pismat1)
                                <option value='{{$pismat1->id_mep}}' descri="{{$pismat1->mep}}" >{{$pismat1->id_mep}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-xs-8'>
                            <label class="label">&nbsp;</label>
                            <label class="input">
                                <input id="rpiso_inp_mat_fis_des" type="text"  class="input-sm" disabled="">
                            </label>
                        </div>
                        <div class="col-xs-12"></div>
                        <div class='col-lg-3 pd_dlg_cr'>
                            <label class="label">Estado Conservación:</label>
                            <select id='rpiso_inp_econserv_fis' class="form-control col-lg-8" onchange="callchangeoption('rpiso_inp_econserv_fis',0)">
                                @foreach ($pisecs as $pisecs1)
                                <option value='{{$pisecs1->id_ecs}}' descri="{{$pisecs1->ecs}}" >{{$pisecs1->id_ecs}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-xs-8'>
                            <label class="label">&nbsp;</label>
                            <label class="input">
                                <input id="rpiso_inp_econserv_fis_des" type="text"  class="input-sm" disabled="">
                            </label>
                        </div>
                        <div class="col-xs-12"></div>
                        <div class='col-lg-3 pd_dlg_cr'>
                            <label class="label">Estado Construcción:</label>
                            <select id='rpiso_inp_econstr_fis' class="form-control col-lg-8" onchange="callchangeoption('rpiso_inp_econstr_fis',0)">
                                @foreach ($ecc as $ecc2)
                                <option value='{{$ecc2->id_ecc}}' descri="{{$ecc2->descripcion}}" >{{$ecc2->id_ecc}}</option>
                                @endforeach
                            </select>
                            
                        </div>
                        <div class='col-xs-8'>
                            <label class="label">&nbsp;</label>
                            <label class="input">
                                <input id="rpiso_inp_econstr_fis_des" type="text"  class="input-sm" disabled="">
                            </label>
                        </div>
                        <div class="col-xs-12"></div>
                        <div class='col-lg-5 pd_dlg_cr'>
                            <label class="label">Estructuras:</label>
                            <label class="input">
                                <input id="rpiso_inp_estruc_fis" type="text"  class="input-sm" maxlength="7" placeholder="7 Letras Entre A-I">
                            </label>
                        </div>
                        
                        <div class='col-lg-3 '>
                            <label class="label">Area Construida:</label>
                            <label class="input">
                                <input id="rpiso_inp_aconst_fis" type="text"  class="input-sm text-right" placeholder="0.00" onkeypress="return soloNumeroTab(event);" >
                            </label>
                        </div>
                        <div class='col-lg-3'>
                            <label class="label">Areas Comunes:</label>
                            <label class="input">
                                <input id="rpiso_inp_acomun_fis" type="text"  class="input-sm text-right" placeholder="0.00" onkeypress="return soloNumeroTab(event);" >
                            </label>
                        </div>
                    </div>
                    </div>
               
            </div>
        </div>
    </div>
</div>  
<div id="dlg_reg_inst" style="display: none;">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group col-xs-6" style="margin-bottom: 10px;">
                <div class="panel-group">                
                    <div class="panel panel-success">
                    <div class="panel-heading bg-color-success">.:: Datos de Instalación Declarada ::.</div>
                    <div class="panel-body cr-body">
                        <div class='col-lg-1 pd_dlg_cr'>
                            <input type="hidden" id="dlg_idinst" value="0">
                            <label class="label">Codigo:</label>
                            <label class="input">
                                <input id="rinst_inp_des_cod" type="text"  class="input-sm" disabled="" >
                            </label>
                        </div>
                        <div class='col-lg-6'>
                            <label class="label">Descripción:</label>
                            <label class="input">
                                <input id="hidden_rinst_inp_des" type="hidden" value="0">
                                <input id="rinst_inp_des" type="text"  class="input-sm" disabled="" >
                            </label>
                        </div>
                        <div class='col-lg-2'>
                            <label class="label">U.Med.:</label>
                            <label class="input">
                                <input id="rinst_inp_undmed" type="text"  class="input-sm" disabled="">
                            </label>
                        </div>
                        <div class='col-lg-2'>
                            <label class="label">Año Construc.:</label>
                            <label class="input">
                                <input id="rinst_inp_anio" type="text"  class="input-sm" disabled="" >
                            </label>
                        </div>
                        
                        <div col="col-xs-12"></div>
                        <div class='col-xs-3 pd_dlg_cr'>
                            <label class="label">Clasificación:</label>
                            <select id='rinst_inp_clasi' class="form-control col-lg-8" disabled="">
                                @foreach ($pisclasi as $pisinst)
                                <option value='{{$pisinst->id_cla_pre}}' descri="{{$pisinst->desc_clasific}}" >{{$pisinst->id_cla_pre}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-xs-8'>
                            <label class="label">&nbsp;</label>
                            <label class="input">
                                <input id="rinst_inp_clasi_des" type="text"  class="input-sm" disabled="">
                            </label>
                        </div>
                        <div class="col-xs-12"></div>
                        <div class='col-lg-3 pd_dlg_cr'>
                            <label class="label">Material:</label>
                            <select id='rinst_inp_mat' class="form-control col-lg-8" disabled="">
                                @foreach ($pismat as $pismat2)
                                <option value='{{$pismat2->id_mep}}' descri="{{$pismat2->mep}}" >{{$pismat2->id_mep}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-xs-8'>
                            <label class="label">&nbsp;</label>
                            <label class="input">
                                <input id="rinst_inp_mat_des" type="text"  class="input-sm" disabled="">
                            </label>
                        </div>
                        <div class="col-xs-12"></div>
                        <div class='col-lg-3 pd_dlg_cr'>
                            <label class="label">Estado Conservación:</label>
                            <select id='rinst_inp_econserv' class="form-control col-lg-8" disabled="">
                                @foreach ($pisecs as $pisecs2)
                                <option value='{{$pisecs2->id_ecs}}' descri="{{$pisecs2->ecs}}" >{{$pisecs2->id_ecs}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-xs-8'>
                            <label class="label">&nbsp;</label>
                            <label class="input">
                                <input id="rinst_inp_econserv_des" type="text"  class="input-sm" disabled="">
                            </label>
                        </div>
                        <div class="col-xs-12"></div>
                        <div class='col-lg-3 pd_dlg_cr'>
                            <label class="label">Estado Construcción:</label>
                            <select id='rinst_inp_econstr' class="form-control col-lg-8" disabled="">
                                @foreach ($ecc as $ecc3)
                                <option value='{{$ecc3->id_ecc}}' descri="{{$ecc3->descripcion}}" >{{$ecc3->id_ecc}}</option>
                                @endforeach
                            </select>
                            
                        </div>
                        <div class='col-xs-8'>
                            <label class="label">&nbsp;</label>
                            <label class="input">
                                <input id="rinst_inp_econstr_des" type="text"  class="input-sm" disabled="">
                            </label>
                        </div>
                        
                    </div>
                    <div class="panel-heading bg-color-success">.:: Dimensiones Verificadas ::.</div>
                    <div class="panel-body cr-body">
                        <div class='col col-3'>
                            <label class="label">Largo:</label>
                            <label class="input">
                                <input id="rinst_inp_largo" type="text"  class="input-sm text-right" disabled="">
                            </label>
                        </div>
                        <div class='col col-3'>
                            <label class="label">Ancho:</label>
                            <label class="input">
                                <input id="rinst_inp_ancho" type="text"  class="input-sm text-right" disabled="" >
                            </label>
                        </div>
                        <div class='col col-3'>
                            <label class="label">Alto:</label>
                            <label class="input">
                                <input id="rinst_inp_alto" type="text"  class="input-sm text-right" disabled="" >
                            </label>
                        </div>
                        <div class='col col-3'>
                            <label class="label">Cantidad:</label>
                            <label class="input">
                                <input id="rinst_inp_canti" type="text"  class="input-sm text-right" disabled="" >
                            </label>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="panel-group col-xs-6" style="margin-bottom: 10px;">
                <div class="panel-group">                
                <div class="panel panel-success cr-panel-sep">
                    <div class="panel-heading bg-color-success">.:: Datos de Instalación Fiscalizada ::.</div>
                    <div class="panel-body cr-body">
                        <div class='col-lg-1 pd_dlg_cr'>
                            <input type="hidden" id="dlg_idinst_fis" value="0">
                            <label class="label">Codigo:</label>
                            <label class="input">
                                <input id="rinst_inp_des_fis_cod" type="text"  class="input-sm" maxlength="2" disabled="" >
                            </label>
                        </div>
                        <div class='col-lg-6'>
                            <label class="label">Descripción:</label>
                            <label class="input">
                                <input id="hidden_rinst_inp_des_fis" type="hidden" value="0">
                                <input id="rinst_inp_des_fis" type="text"  class="input-sm" maxlength="150" >
                            </label>
                        </div>
                        <div class='col-lg-2'>
                            <label class="label">U.Med.:</label>
                            <label class="input">
                                <input id="rinst_inp_undmed_fis" type="text"  class="input-sm" disabled="">
                            </label>
                        </div>
                        <div class='col-lg-2'>
                            <label class="label">Año Construc.:</label>
                            <label class="input">
                                <input id="rinst_inp_anio_fis" type="text"  class="input-sm" maxlength="4" onkeypress="return soloDNI(event);" >
                            </label>
                        </div>
                        
                        <div col="col-xs-12"></div>
                        <div class='col-xs-3 pd_dlg_cr'>
                            <label class="label">Clasificación:</label>
                            <select id='rinst_inp_clasi_fis' class="form-control col-lg-8" onchange="callchangeoption('rinst_inp_clasi_fis',0)">
                                @foreach ($pisclasi as $pisinst)
                                <option value='{{$pisinst->id_cla_pre}}' descri="{{$pisinst->desc_clasific}}" >{{$pisinst->id_cla_pre}}</option>
                                @endforeach
                            </select>
                            
                        </div>
                        <div class='col-xs-8'>
                            <label class="label">&nbsp;</label>
                            <label class="input">
                                <input id="rinst_inp_clasi_fis_des" type="text"  class="input-sm" disabled="">
                            </label>
                        </div>
                        <div class="col-xs-12"></div>
                        <div class='col-lg-3 pd_dlg_cr'>
                            <label class="label">Material:</label>
                            <select id='rinst_inp_mat_fis' class="form-control col-lg-8" onchange="callchangeoption('rinst_inp_mat_fis',0)">
                                @foreach ($pismat as $pismat2)
                                <option value='{{$pismat2->id_mep}}' descri="{{$pismat2->mep}}" >{{$pismat2->id_mep}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-xs-8'>
                            <label class="label">&nbsp;</label>
                            <label class="input">
                                <input id="rinst_inp_mat_fis_des" type="text"  class="input-sm" disabled="">
                            </label>
                        </div>
                        <div class="col-xs-12"></div>
                        <div class='col-lg-3 pd_dlg_cr'>
                            <label class="label">Estado Conservación:</label>
                            <select id='rinst_inp_econserv_fis' class="form-control col-lg-8" onchange="callchangeoption('rinst_inp_econserv_fis',0)">
                                @foreach ($pisecs as $pisecs2)
                                <option value='{{$pisecs2->id_ecs}}' descri="{{$pisecs2->ecs}}" >{{$pisecs2->id_ecs}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-xs-8'>
                            <label class="label">&nbsp;</label>
                            <label class="input">
                                <input id="rinst_inp_econserv_fis_des" type="text"  class="input-sm" disabled="">
                            </label>
                        </div>
                        <div class="col-xs-12"></div>
                        <div class='col-lg-3 pd_dlg_cr'>
                            <label class="label">Estado Construcción:</label>
                            <select id='rinst_inp_econstr_fis' class="form-control col-lg-8" onchange="callchangeoption('rinst_inp_econstr_fis',0)">
                                @foreach ($ecc as $ecc3)
                                <option value='{{$ecc3->id_ecc}}' descri="{{$ecc3->descripcion}}" >{{$ecc3->id_ecc}}</option>
                                @endforeach
                            </select>
                            
                        </div>
                        <div class='col-xs-8'>
                            <label class="label">&nbsp;</label>
                            <label class="input">
                                <input id="rinst_inp_econstr_fis_des" type="text"  class="input-sm" disabled="">
                            </label>
                        </div>
                        
                    </div>
                    <div class="panel-heading bg-color-success">.:: Dimensiones Verificadas ::.</div>
                    <div class="panel-body cr-body">
                        <div class='col col-3'>
                            <label class="label">Largo:</label>
                            <label class="input">
                                <input id="rinst_inp_largo_fis" type="text"  class="input-sm text-right" onkeypress="return soloNumeroTab(event);" >
                            </label>
                        </div>
                        <div class='col col-3'>
                            <label class="label">Ancho:</label>
                            <label class="input">
                                <input id="rinst_inp_ancho_fis" type="text"  class="input-sm text-right" onkeypress="return soloNumeroTab(event);"  >
                            </label>
                        </div>
                        <div class='col col-3'>
                            <label class="label">Alto:</label>
                            <label class="input">
                                <input id="rinst_inp_alto_fis" type="text"  class="input-sm text-right" onkeypress="return soloNumeroTab(event);" >
                            </label>
                        </div>
                        <div class='col col-3'>
                            <label class="label">Cantidad:</label>
                            <label class="input">
                                <input id="rinst_inp_canti_fis" type="text"  class="input-sm text-right" onkeypress="return soloNumeroTab(event);" >
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<div id="dlg_view_foto" style="display: none;">
    <div class="panel panel-success cr-panel-sep" style="height: 650px">
        <div class="panel-body cr-body">
            <div id="dlg_img_view_big" style="padding-top: 0px"></div>
        </div>
    </div>
</div> 
@endsection




