@extends('layouts.app')
@section('content')
<input type="hidden" id="per_edit" value="{{$permisos[0]->btn_edit}}"/>
<input type="hidden" id="per_del" value="{{$permisos[0]->btn_del}}"/>
<section id="widget-grid" class=""> 
<div class='cr_content col-xs-12'>
    <div class="col-xs-12">
        <div class="col-lg-9">
            <h1 class="txt-color-green"><b>Predios Urbanos...</b></h1>
        </div>
        <div class="col-lg-3 col-md-6 col-xs-12">
            <div class="input-group input-group-md">
                <span class="input-group-addon">Año de Trabajo <i class="fa fa-cogs"></i></span>
                <div class="icon-addon addon-md">
                    <select id='selantra' class="form-control col-lg-8" style="height: 32px;" onchange="callfilltab()">
                    @foreach ($anio_tra as $anio)
                    <option value='{{$anio->anio}}' >{{$anio->anio}}</option>
                    @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-6" style="padding-top: 17px; margin-top: 5px">
            <div class="col-xs-6" style="padding: 0px;">
                <div class="input-group input-group-md">
                    <span class="input-group-addon">Sector &nbsp;<i class="fa fa-list"></i></span>
                    <div class="icon-addon addon-md">
                        <select id='selsec' class="form-control" onchange="callpredtab()" style="height: 32px;" >
                        @foreach ($sectores as $sec)
                        <option value='{{$sec->id_sec}}' >{{$sec->sector}}</option>
                        @endforeach
                        </select>
                    </div>

                </div>
            </div>
            <div class="col-xs-6" style="padding: 0px;">
                <div class="input-group input-group-md">
                    <span class="input-group-addon">Manzana &nbsp;<i class="fa fa-list"></i></span>
                    <div class="icon-addon addon-md"  id="dvselmnza">
                        <select id="selmnza" class="form-control" style="height: 32px;" onchange="callfilltab()">
                        @foreach ($manzanas as $manzanas)
                        <option value='{{$manzanas->id_mzna}}'>{{$manzanas->codi_mzna}}</option>
                        @endforeach
                        </select>
                    </div>

                </div>
            </div>
             
            
            </div>
    <div class="col-lg-6 col-md-12 col-xs-12">
        <ul class="text-right" style="margin-top: 22px !important; margin-bottom: 0px !important">
                @if( $permisos[0]->btn_new ==1 )
                    <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="clicknewgrid();">
                        <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                    </button>
                @else
                    <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="sin_permiso()">
                        <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                    </button>
                @endif
                @if( $permisos[0]->btn_edit ==1 )
                    <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="clickmodgrid();">
                        <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                    </button>
                @else
                    <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="sin_permiso()">
                        <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                    </button>
                @endif
                @if( $permisos[0]->btn_del ==1 )
                <button  type="button" class="btn btn-labeled btn-danger" onclick="fn_confirmar_borrar_predio();">
                        <span class="btn-label"><i class="glyphicon glyphicon-trash" ></i></span>Eliminar
                    </button>
                @else
                    <button  type="button" class="btn btn-labeled btn-danger" onclick="sin_permiso()">
                        <span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span>Eliminar
                    </button> 
                @endif
                @if( $permisos[0]->btn_imp ==1 )
                <button type="button" class="btn btn-labeled bg-color-magenta txt-color-white" onclick="imppu()">
                        <span class="btn-label"><i class="glyphicon glyphicon-print"></i></span>Imprimir
                    </button>
                @else
                    <button type="button" class="btn btn-labeled bg-color-magenta txt-color-white" onclick="sin_permiso()">
                        <span class="btn-label"><i class="glyphicon glyphicon-print"></i></span>Imprimir
                    </button>
                @endif
        </ul>
    </div>
    
    
</div>
    <div class='cr_content col-xs-12'>
                
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; padding: 0px !important">
            <table id="table_predios"></table>
            <div id="pager_table_predios"></div>
        </article>
    </div>
</section>

@section('page-js-script')
<script type="text/javascript">
    
    $(document).ready(function () {
        $("#menu_admtri").show();
        $("#li_preurb").addClass('cr-active')
        jQuery("#table_predios").jqGrid({
            url: 'gridpredio?tpre=1&mnza='+$("#selmnza").val()+'&ctr=0&an='+$("#selantra").val(),
            datatype: 'json', mtype: 'GET',
            height: '380px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_pred','t_pred', 'Sector','Manzana','Lote', 'Código Predial', 'Mz Dist', 'Lt Dist', 'N° Munic', 'Est. Construcción', 'Contribuyente o Razon Social', 'Calle/Vía','id_via','A.Terreno','S/.Terreno','S/.Construct'],
            rowNum: 20, sortname: 'id_pred', sortorder: 'desc', viewrecords: true, caption: 'Predios Urbanos', align: "center",
            colModel: [
                {name: 'id_pred', index: 'id_pred', hidden: true},
                {name: 'tp', index: 'tp', align: 'center', width: 50},
                {name: 'sec', index: 'sec', align: 'center', width: 20},
                {name: 'mnza', index: 'mnza', align: 'center', width: 20},
                {name: 'lote', index: 'lote', align: 'center', width: 50},
                {name: 'cod_cat', index: 'cod_cat', align: 'center', width: 80},
                {name: 'mzna_dist', index: 'mzna_dist', align: 'center', width: 40},
                {name: 'lote_dist', index: 'lote_dist', align: 'center', width: 40},
                {name: 'nro_mun', index: 'nro_mun', width: 40,align: "right"},
                {name: 'descripcion', index: 'descripcion', width: 100},
                {name: 'contribuyente', index: 'contribuyente', width: 150},
                {name: 'nom_via', index: 'nom_via', width: 100},
                {name: 'id_via', index: 'id_via', hidden: true},
                {name: 'are_terr', index: 'are_terr', width: 60,align: "right"},
                {name: 'val_ter', index: 'val_ter', width: 60,align: "right"},
                {name: 'val_const', index: 'val_const', width: 60, align: "right"},
               
            ],
            pager: '#pager_table_predios',
            rowList: [13, 20],
            gridComplete: function () {
                    var idarray = jQuery('#table_predios').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_predios').jqGrid('getDataIDs')[0];
                            $("#table_predios").setSelection(firstid);    
                        }
                        
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){clickmodgrid();}
        });
        $("#table_predios").jqGrid('setGridHeight',$(window).height()*0.45);
        $("#dlg_dni").keypress(function (e) {
            if (e.which == 13) {
                get_global_contri("dlg_contri",$("#dlg_dni").val());
            }
        });
        $("#rcondo_inp_dni").keypress(function (e) {
            if (e.which == 13) {
                get_global_contri("rcondo_inp_rsoc",$("#rcondo_inp_dni").val());
            }
        });
        jQuery("#table_pisos").jqGrid({
            url: 'gridpisos/0',
            datatype: 'json', mtype: 'GET',
            height: '200px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_pi','Piso', 'Fecha', 'MEP', 'ECS', 'ECC', 'Muro', 'Techo', 'Piso', 'Puerta','Reves','baños','I.Elect','Area Constr.'],
            rowNum: 20, sortname: 'id_pi', sortorder: 'desc', viewrecords: true, caption: 'Pisos del Predio', align: "center",
            colModel: [
                {name: 'id_pi', index: 'id_pi', hidden: true},
                {name: 'piso', index: 'piso', align: 'center', width: 80},
                {name: 'fech', index: 'fech', align: 'center', width: 90},
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
        jQuery("#table_condos").jqGrid({
            url: 'gridcondos/0',
            datatype: 'json', mtype: 'GET',
            height: '200px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_condom','dni/ruc', 'Nombre', 'Direccion', '% Condominio'],
            rowNum: 20, sortname: 'id_condom', sortorder: 'desc', viewrecords: true, caption: 'Condominios del Predio', align: "center",
            colModel: [
                {name: 'id_condom', index: 'id_condom', hidden: true},
                {name: 'dni_ruc', index: 'dni_ruc', align: 'center', width: 180},
                {name: 'nombre', index: 'nombre', align: 'center', width: 350},
                {name: 'direccion', index: 'direccion', align: 'center', width: 350},
                {name: 'porcent', index: 'porcent', align: 'center', width: 100},
               
            ],
            pager: '#pager_table_condos',
            rowList: [13, 20],
            gridComplete: function () {
                    var idarray = jQuery('#table_condos').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_condos').jqGrid('getDataIDs')[0];
                            $("#table_condos").setSelection(firstid);    
                        }
                },
            ondblClickRow: function (Id){clickmodcondo();}
        });
        jQuery("#table_instal").jqGrid({
            url: 'gridinsta/0',
            datatype: 'json', mtype: 'GET',
            height: '200px', autowidth: true,
            toolbarfilter: true,
            colNames: ['cod_obra','Código', 'Descripción', 'año de antiguedad', 'MEP','ECS','ECC','lARGO','ANCHO','ALTO','UND.MED','PROD.TOTAL'],
            rowNum: 20, sortname: 'id_inst', sortorder: 'desc', viewrecords: true, caption: 'Instalación del Predio', align: "center",
            colModel: [
                {name: 'cod_obra', index: 'cod_obra', hidden: true},
                {name: 'cod_inst', index: 'cod_inst', align: 'center', width: 50},
                {name: 'des', index: 'des', align: 'center', width: 250},
                {name: 'anio', index: 'anio', align: 'center', width: 100},
                {name: 'mep', index: 'mel', align: 'center', width: 50},
                {name: 'ecs', index: 'ecs', align: 'center', width: 50},
                {name: 'ecc', index: 'ecc', align: 'center', width: 50},
                {name: 'dim_lar', index: 'dim_lar', align: 'center', width: 80},
                {name: 'dim_anch', index: 'dim_anch', align: 'center', width: 80},
                {name: 'dim_alt', index: 'dim_alt', align: 'center', width: 80},
                {name: 'uni_med', index: 'uni_med', align: 'center', width: 80},
                {name: 'tot_inst', index: 'tot_inst', align: 'right', width: 85},
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
        contrib_global=0;
        jQuery("#table_contrib").jqGrid({
            url: 'obtiene_cotriname?dat=0',
            datatype: 'json', mtype: 'GET',
            height: '300px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_pers','codigo','DNI/RUC','contribuyente'],
            rowNum: 100, sortname: 'contribuyente', sortorder: 'asc', viewrecords: true, caption: 'Contribuyentes', align: "center",
            colModel: [
                {name: 'id_pers', index: 'id_pers', hidden: true},
                {name: 'id_per', index: 'id_per', align: 'center',width: 100},
                {name: 'nro_doc', index: 'nro_doc', align: 'center',width: 100},
                {name: 'contribuyente', index: 'contribuyente', align: 'left',width: 260},
                
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
                        jQuery('#table_contrib').jqGrid('bindKeys', {"onEnter":function( rowid ){fn_bus_contrib_list_pred(rowid);} } ); 
                    }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){fn_bus_contrib_list_pred(Id)}
        });
        
        var globalvalidador=0;
        $("#dlg_contri").keypress(function (e) {
            if (e.which == 13) {
                if(globalvalidador==0)
                {
                    fn_bus_contrib_pred();
                    globalvalidador=1;
                }
                else
                {
                    globalvalidador=0;
                }
                
            }
        });
        
        jQuery("#table_arbitrios").jqGrid({
            url: 'gridarbitrios?pre=0&an=0',
            datatype: 'json', mtype: 'GET',
            height: '200px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_arb','Cod. Predio', 'Año','Piso', 'Frec. Barrido', 'Costo Barrido','Frecu. Recojo Residuos',
                'Costo Recojo Residuos','Cat. Parques y Jadines',
            'Costo Parq. y Jard.','Cat. Serenazgo','Costo Serenazgo'],
            rowNum: 20, sortname: 'id_pred', sortorder: 'desc', viewrecords: true, caption: 'Arbitrios del Predio Seleccionado', align: "center",
            colModel: [
                {name: 'id_arb', index: 'id_arb', hidden: true},
                {name: 'cod_cat', index: 'cod_cat', align: 'center', width: 120},
                {name: 'anio', index: 'anio', align: 'center', width: 50},
                {name: 'cod_piso', index: 'cod_piso', align: 'center', width: 50},
                {name: 'frecu_bar', index: 'frecu_bar', align: 'center', width: 90},
                {name: 'cos_bar', index: 'cos_bar', align: 'center', width: 90},
                {name: 'frecu_rrs', index: 'frecu_rrs', align: 'center', width: 90},
                {name: 'cos_rrs', index: 'cos_rrs', align: 'center', width: 90},
                {name: 'par_cat_des', index: 'par_cat_des', align: 'center', width: 90},
                {name: 'cos_jar', index: 'cos_jar', align: 'center', width: 90},
                {name: 'des_cat_seren', index: 'des_cat_seren', align: 'center', width: 90},
                {name: 'cos_seren', index: 'cos_seren', align: 'center', width: 90},
            ],
            pager: '#pager_table_arbitrios',
            rowList: [20, 50],
            gridComplete: function () {
                    var idarray = jQuery('#table_arbitrios').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_arbitrios').jqGrid('getDataIDs')[0];
                            $("#table_arbitrios").setSelection(firstid); 
                        }
                     
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){mod_arb();}
        });
    });
    jQuery('#rpiso_inp_estruc').keypress(function(tecla) {
        $("#rpiso_inp_estruc").val($("#rpiso_inp_estruc").val().toUpperCase());
        if(tecla.charCode < 65 || tecla.charCode > 73)
        {
            if(tecla.charCode < 97 || tecla.charCode > 105) return false;
        }
    });
</script>
@stop
<script src="{{ asset('archivos_js/adm_tributaria/predios.js') }}"></script>
<div id="dlg_bus_contr" style="display: none;">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
        <table id="table_contrib"></table>
        <div id="pager_table_contrib"></div>
    </article>
</div> 
<div id="dlg_reg_dj" style="display: none;">
                    <div class="widget-body">
                    <div  class="smart-form">
                        <div class="col-xs-10">
                        <div class="panel-group">                
                            <div class="panel panel-success">
                                <div class="panel-heading bg-color-success">.:: Codigo de Referencia ::.<span id="tipeado"></span></div>
                                <div class="panel-body cr-body" style="padding-top: 15px;">
                                    <div class="col-xs-2"></div>
                                    
                                    <div class="col col-3">
                                        <div class="input-group input-group-md">
                                            <input type="hidden" id="dlg_idpre" value="0">
                                            <span class="input-group-addon">Sector &nbsp;&nbsp;<i class="fa fa-cogs"></i></span>
                                            <div class="icon-addon addon-md">
                                                <input class="text-center col-xs-12 form-control"  style="height: 32px;" id="dlg_sec" type="text" name="dlg_sec" disabled="" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col col-3">
                                        <div class="input-group input-group-md">
                                            <span class="input-group-addon">Manzana &nbsp;&nbsp;<i class="fa fa-apple"></i></span>
                                            <div class="icon-addon addon-md">
                                                <input class="text-center form-control" style="height: 32px;" id="dlg_mzna" type="text" name="dlg_mzna" disabled="" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col col-2">
                                        <div class="input-group input-group-md">
                                            <span class="input-group-addon">Lotes &nbsp;<i class="fa fa-home"></i></span>
                                            <div class="icon-addon addon-md">
                                                <select id='dlg_lot' class="form-control col-lg-8" style="height: 32px; text-align: center" onchange="traerfoto()">
                                                
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <div class="panel-group col-xs-8 " style="margin-top: 5px">                
                            <div class="panel panel-success">
                                <div class="panel-heading bg-color-success">.:: Datos del Propietario ::.</div>
                                <div class="panel-body cr-body">
                                    <div class="col col-3">
                                        <label class="label">Dni/Ruc:</label>
                                        <label class="input">
                                            <input id="dlg_contri_hidden" type="hidden" value="0">
                                            <input id="dlg_dni" onkeypress="return soloDNI(event);" type="text" placeholder="Nro. Documento" class="input-sm" disabled="">
                                        </label>
                                    </div>
                                    <div class="col col-9">
                                        <label class="label">Contribuyente/Razón Social:</label>
                                        <label class="input">
                                            <input id="dlg_contri" type="text"  class="input-sm" autofocus="">
                                        </label>
                                    </div>
                      
                                </div>
                            </div>
                        </div>
                        
                        <div class="panel-group col-xs-4 " style="margin-top: 5px">                
                            <div class="panel panel-success cr-panel-sep">
                                <div class="panel-heading bg-color-success">.:: Condicion de Propiedad ::.</div>
                                <div class="panel-body cr-body">
                                    <div class="col col-8">
                                        <label class="label">Condicion de Propiedad:</label>                                   
                                        <select id="dlg_sel_condpre"  class="form-control" onchange="validacond()">
                                            @foreach ($condicion as $condicion)
                                            <option value='{{$condicion->id_cond}}' >{{$condicion->descripcion}}</option>
                                            @endforeach
                                        </select>                       
                                     </div>
                                    <div class="col col-4">
                                        <label class="label">% Condomin.</label>
                                        <label class="input">
                                            <input id="dlg_inp_condos"  type="text"  class="input-sm text-right" maxlength="3" onkeypress="return soloNumeroTab(event);" disabled="">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="col-xs-2">
                            <div class="panel panel-success cr-panel-sep" style="height: 161px;overflow-y: scroll;">
                                <div class="panel-heading bg-color-success">.:: Foto Predio ::.</div>
                                <div class="panel-body cr-body">
                                    <div id="dlg_img_view" style="padding: 5px; " onclick="viewlong()"></div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group col-xs-8 " style="margin-top: 5px;  ">                
                            <div class="panel panel-success ">
                                <div class="panel-heading bg-color-success">.:: Ubicación del Predio ::.</div>
                                <div class="panel-body cr-body">
                                   
                                    <div class="col-xs-1" style="padding-left:15px !important">
                                        <label class="label">Cod. Via:</label>
                                        <label class="input">
                                            <input id="dlg_inp_nvia_des" type="text" onkeypress="return soloDNI(event);" class="input-sm" onblur="ajustar(5,'dlg_inp_nvia_des')" disabled="" >
                                        </label>
                                    </div>
                                    <div class="col-xs-3">
                                        <label class="label">Avenidad,Jirón, Calle o Pasaje:</label>
                                        <label class="input">
                                            <select id="dlg_inp_nvia"  class="form-control" onchange="callchangeoption('dlg_inp_nvia',1);" >
                                                
                                            </select>
                                        </label>
                                    </div>
                                    
                                    <div class="col-xs-7 " style="padding: 0px !important; margin:0px !important">
                                        <table style="width: 105%">
                                            <tr>
                                                <td>
                                                    <label class="label ">N°</label>
                                                    <label class="input">
                                                        <input id="dlg_inp_n" type="text"  class="input-sm text-uppercase" maxlength="19">
                                                    </label>
                                                </td>
                                                <td>
                                                    <label class="label ">MZ</label>
                                                    <label class="input">
                                                        <input id="dlg_inp_mz" type="text"  class="input-sm text-uppercase" maxlength="5">
                                                    </label>
                                                </td>
                                                <td>
                                                    <label class="label ">LT</label>
                                                    <label class="input">
                                                        <input id="dlg_inp_lt" type="text"  class="input-sm text-uppercase" maxlength="5">
                                                    </label>
                                                </td>
                                        
                                                <td>
                                                    <label class="label ">ZN</label>
                                                    <label class="input">
                                                        <input id="dlg_inp_zn" type="text"  class="input-sm text-uppercase" maxlength="15">
                                                    </label>
                                                </td>
                                                <td>    
                                                    <label class="label ">SECC</label>
                                                    <label class="input">
                                                        <input id="dlg_inp_secc" type="text"  class="input-sm text-uppercase" maxlength="15">
                                                    </label>
                                                </td>
                                                <td>
                                                    <label class="label ">PISO</label>
                                                    <label class="input">
                                                        <input id="dlg_inp_piso" type="text"  class="input-sm text-uppercase" maxlength="2">
                                                    </label>
                                                </td>
                                                <td>
                                                    <label class="label ">DPTO</label>
                                                    <label class="input">
                                                        <input id="dlg_inp_dpto" type="text"  class="input-sm text-uppercase" maxlength="5">
                                                    </label>
                                                </td>
                                                <td>
                                                    <label class="label ">TDA/STAND</label>
                                                    <label class="input">
                                                        <input id="dlg_inp_tdastand" type="text"  class="input-sm text-uppercase">
                                                    </label>
                                                </td>
                                                <td>
                                                    <label class="label ">Sp.Mzna</label>
                                                    <label class="input">
                                                        <input id="dlg_inp_supMzna" type="text"  class="input-sm text-uppercase" maxlength="10">
                                                    </label>
                                                </td>
                                                <td>
                                                    <label class="label ">Gp.Zonal</label>
                                                    <label class="input">
                                                        <input id="dlg_inp_gpoZonal" type="text"  class="input-sm text-uppercase" maxlength="20">
                                                    </label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group col-xs-4 " style="margin-top: 5px; ">                
                            <div class="panel panel-success cr-panel-sep">
                                <div class="panel-heading bg-color-success">.:: Referencia ::.</div>
                                <div class="panel-body cr-body">
                                    <div class="pd_dlg_cr" >
                                        <label class="label">Referencia:</label>
                                        <label class="input">
                                            <input id="dlg_inp_refe" type="text"  class="input-sm text-uppercase" maxlength="150">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group col-xs-9 " style="margin-top: 5px; margin-bottom: 5px  ">                
                            <div class="panel panel-success">
                                <div class="panel-heading bg-color-success">.:: Datos Relativos del Predio ::.</div>
                                <div class="panel-body cr-body">
                                    <div class='col-lg-2 pd_dlg_cr'>
                                        <label class="label">Estado de Construccion:</label>
                                        <select id='dlg_sel_estcon' class="form-control" >
                                                @foreach ($ecc as $eccpre)
                                                <option value='{{$eccpre->id_ecc}}' >{{$eccpre->descripcion}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                    <div class='col-lg-2 '>
                                        <label class="label">Tipo de Predio:</label>
                                        <select id='dlg_sel_tippre' class="form-control" >
                                            @foreach ($tpre as $tpre)
                                                <option value='{{$tpre->id_tip_p}}' >{{$tpre->descrip_tip_pre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class='col-lg-1 '>
                                        <label class="label">Cod. Uso:</label>
                                        <label class="input">
                                            <input id="dlg_inp_usopre_cod" type="text"  class="input-sm" disabled="">
                                        </label>
                                    </div>
                                    <div class='col-lg-6 '>
                                        <label class="label">Uso Predio(catastro):</label>
                                        <label class="input">
                                            <input type="hidden" id="hidden_dlg_inp_usopre">
                                            <input id="dlg_inp_usopre" type="text"  class="input-sm" placeholder="Escribir Uso Predio">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group col-xs-3 " style="margin-top: 5px; margin-bottom: 5px  ">                
                            <div class="panel panel-success cr-panel-sep">
                                <div class="panel-heading bg-color-success">.:: Foma de Adquisición ::.</div>
                                <div class="panel-body cr-body">
                                    <div class='col-lg-7 pd_dlg_cr'>
                                        <label class="label">Forma de Adquisición:</label>
                                        <select id='dlg_sel_foradq' class="form-control" >
                                                @foreach ($fadq as $fadq)
                                                <option value='{{$fadq->id_for}}' >{{$fadq->des_for_adq}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                    <div class='col-lg-4'>
                                        <label class="label">Fecha:</label>
                                        <label class="input">
                                            <input id="dlg_inp_fech" type="text"  class="input-sm datepicker"  data-dateformat='dd/mm/yy' data-mask="99/99/9999" >
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="panel-group col-xs-2 " >                
                            <div class="panel panel-success">
                                <div class="panel-heading bg-color-success">.:: Servicios Básicos ::.</div>
                                <div class="panel-body cr-body">
                                    <div class='col-lg-5 pd_dlg_cr' >
                                        <label class="label">Luz:</label>
                                        <label class="input">
                                            <input id="dlg_inp_luz" type="text"  class="input-sm" maxlength="10" >
                                        </label>
                                    </div>
                                    <div class='col-lg-5'>
                                        <label class="label">Agua:</label>
                                        <label class="input">
                                            <input id="dlg_inp_agua" type="text"  class="input-sm" maxlength="10">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group col-xs-2 " >                
                            <div class="panel panel-success cr-panel-sep">
                                <div class="panel-heading bg-color-success">.:: Licencia de Construcción ::.</div>
                                <div class="panel-body cr-body">
                                    <div class="col-lg-1"></div>
                                    <div class='col-lg-5 '>
                                        <label class="col-xs-4 cr-lb-radio">Si:</label>
                                        <div class="col-xs-5">
                                            <input name="dlg_rd_lcons" type="radio" value="1" >
                                        </div>
                                    </div>
                                    <div class='col-lg-5 ' >
                                        <label class="col-xs-4 cr-lb-radio">No:</label>
                                        <div class="col-xs-5">
                                            <input name="dlg_rd_lcons" type="radio" checked="" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group col-xs-2 " >                
                            <div class="panel panel-success cr-panel-sep">
                                <div class="panel-heading bg-color-success">.:: Conformidad de Obra ::.</div>
                                <div class="panel-body cr-body">
                                    <div class="col-lg-1"></div>
                                    <div class='col-lg-5 '>
                                        <label class="col-xs-4 cr-lb-radio">Si:</label>
                                        <div class="col-xs-5">
                                        <input name="dlg_rd_confobr" type="radio" value="1" >
                                        </div>
                                    </div>
                                    <div class='col-lg-5 ' >
                                        <label class="col-xs-4 cr-lb-radio">No:</label>
                                        <div class="col-xs-5">
                                            <input name="dlg_rd_confobr" type="radio" checked="" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group col-xs-2 " >                
                            <div class="panel panel-success cr-panel-sep">
                                <div class="panel-heading bg-color-success">.:: Declaratoria de Fábrica ::.</div>
                                <div class="panel-body cr-body">
                                    <div class="col-lg-1"></div>
                                    <div class='col-lg-5 '>
                                        <label class="col-xs-4 cr-lb-radio">Si:</label>
                                        <div class="col-xs-5">
                                            <input name="dlg_rd_defra" type="radio" value="1" >
                                        </div>
                                    </div>
                                    <div class='col-lg-5 ' >
                                        <label class="col-xs-4 cr-lb-radio">No:</label>
                                        <div class="col-xs-5">
                                            <input name="dlg_rd_defra" type="radio" checked="" value="0" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="panel-group col-xs-4 " >                
                            <div class="panel panel-success cr-panel-sep">
                                <div class="panel-heading bg-color-success">.:: Terreno ::.</div>
                                <div class="panel-body cr-body">
                                    <div class='col-lg-2 pd_dlg_cr' >
                                        <label class="label">Arancel:</label>
                                        <label class="input">
                                            <input id="dlg_inp_aranc" type="text"  class="input-sm" disabled="" style="text-align: right">
                                        </label>
                                    </div>
                                    <div class='col-lg-3'>
                                        <label class="label">Area Terreno:</label>
                                        <label class="input">
                                            <input id="dlg_inp_areter" type="text"  class="input-sm" onkeypress="return soloNumeroTab(event);" onkeyup="validarvalter();" style="text-align: right" placeholder="0.00">
                                        </label>
                                    </div>
                                    <div class='col-lg-3'>
                                        <label class="label">Area comun Terr.:</label>
                                        <label class="input">
                                            <input id="dlg_inp_arecomter" type="text"  class="input-sm" onkeypress="return soloNumeroTab(event);" onkeyup="validarvalter();" style="text-align: right" placeholder="0.00">
                                        </label>
                                    </div>
                                    <div class='col-lg-2'>
                                        <label class="label">Val Terreno:</label>
                                        <label class="input">
                                            <input id="dlg_inp_valterr" type="text"  class="input-sm" disabled="" style="text-align: right; width: 100px">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                    <div class="col-xs-12 cr-body">
                            <ul id="sparks">
                                @if( $permisos[0]->btn_new ==1 )
                                        <button type="button" id="btnsavepre" class="btn btn-labeled bg-color-green txt-color-white" onclick="fn_confirmar_predio();">
                                            <span class="cr-btn-label"><i class="glyphicon glyphicon-save"></i></span>Guardar Predio
                                        </button>
                                @else
                                        <button type="button" id="btnsavepre" class="btn btn-labeled bg-color-green txt-color-white" onclick="sin_permiso();">
                                            <span class="cr-btn-label"><i class="glyphicon glyphicon-save"></i></span>Guardar Predio
                                        </button>
                                @endif
                                @if( $permisos[0]->btn_edit ==1 )
                                        <button  type="button" id="btnmodpre" class="btn btn-labeled bg-color-blue txt-color-white" onclick="dlgUpdate();">
                                            <span class="cr-btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar Predio
                                        </button>
                                @else
                                        <button  type="button" id="btnmodpre" class="btn btn-labeled bg-color-blue txt-color-white" onclick="sin_permiso();">
                                            <span class="cr-btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar Predio
                                        </button>
                                @endif
                            </ul>
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
                                <li class="">
                                    <a href="#s3" data-toggle="tab" aria-expanded="false">
                                        Condominios
                                        <i class="fa fa-lg fa-fw fa-cog fa-spin"></i>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="#s4" data-toggle="tab" aria-expanded="false">
                                        Arbitrios
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
                                                <button class="btn bg-color-green txt-color-white cr-btn-big" onclick="sin_permiso();" >
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
                                            @if( $permisos[0]->btn_del ==1 )
                                                <button id="btn_s1_delpiso" data-token="{{ csrf_token() }}" class="btn bg-color-red txt-color-white cr-btn-big" onclick="pisoDelete()">
                                                    <span>
                                                        <i class="glyphicon glyphicon-trash"></i>
                                                    </span>
                                                    <label>Borrar Piso</label>
                                                </button>
                                            @else
                                                <button id="btn_s1_delpiso" class="btn bg-color-red txt-color-white cr-btn-big" onclick="sin_permiso()">
                                                    <span>
                                                        <i class="glyphicon glyphicon-trash"></i>
                                                    </span>
                                                    <label>Borrar Piso</label>
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
                                        @if( $permisos[0]->btn_edit ==1 )
                                            <button id="btn_s2_delinst" data-token="{{ csrf_token() }}" class="btn bg-color-red txt-color-white cr-btn-big" onclick="instDelete()">
                                                <span>
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                </span>
                                                <label>Borrar Inst</label>
                                            </button>
                                        @else
                                            <button id="btn_s2_delinst" class="btn bg-color-red txt-color-white cr-btn-big" onclick="sin_permiso()">
                                                <span>
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                </span>
                                                <label>Borrar Inst</label>
                                            </button>
                                        @endif
                                        </div>
                                </div>
                                <div id="s3" class="tab-pane fade" style="height: 300px">
                                    <div class="col-xs-10">
                                            <table id="table_condos" ></table>
                                            <div id="pager_table_condos"></div>
                                        </div>
                                        <div class="col-xs-2">
                                            @if( $permisos[0]->btn_new ==1 )
                                                <button class="btn bg-color-green txt-color-white cr-btn-big" onclick="clicknewcondo()" >
                                                    <span>
                                                        <i class="glyphicon glyphicon-plus-sign"></i>
                                                    </span>
                                                    <label>Nuevo Cond</label>
                                                </button>
                                            @else
                                                <button class="btn bg-color-green txt-color-white cr-btn-big" onclick="sin_permiso()" >
                                                    <span>
                                                        <i class="glyphicon glyphicon-plus-sign"></i>
                                                    </span>
                                                    <label>Nuevo Cond</label>
                                                </button>
                                            @endif
                                            @if( $permisos[0]->btn_edit ==1 )
                                                <button class="btn bg-color-blue txt-color-white cr-btn-big" onclick="clickmodcondo()" >
                                                    <span>
                                                        <i class="glyphicon glyphicon-edit"></i>
                                                    </span>
                                                    <label>Editar Cond</label>
                                                </button>
                                            @else
                                                <button class="btn bg-color-blue txt-color-white cr-btn-big" onclick="sin_permiso()" >
                                                    <span>
                                                        <i class="glyphicon glyphicon-edit"></i>
                                                    </span>
                                                    <label>Editar Cond</label>
                                                </button> 
                                            @endif
                                            @if( $permisos[0]->btn_del ==1 )
                                                <button id="btn_s3_delcondos" data-token="{{ csrf_token() }}" class="btn bg-color-red txt-color-white cr-btn-big" onclick="condoDelete()">
                                                    <span>
                                                        <i class="glyphicon glyphicon-trash"></i>
                                                    </span>
                                                    <label>Borrar Cond</label>
                                                </button>
                                            @else
                                                <button id="btn_s3_delcondos" class="btn bg-color-red txt-color-white cr-btn-big" onclick="sin_permiso()">
                                                    <span>
                                                        <i class="glyphicon glyphicon-trash"></i>
                                                    </span>
                                                    <label>Borrar Cond</label>
                                                </button>
                                            @endif
                                        </div>
                                </div>
                                <div id="s4" class="tab-pane fade" style="height: 300px">
                                    <div class="col-xs-10">
                                        <table id="table_arbitrios"></table>
                                        <div id="pager_table_arbitrios"></div>
                                    </div>
                                    <div class="col-xs-2">
                                        @if( $permisos[0]->btn_new ==1 )
                                            <button class="btn bg-color-green txt-color-white cr-btn-big" onclick="new_arb()" >
                                                <span>
                                                    <i class="glyphicon glyphicon-plus-sign"></i>
                                                </span>
                                                <label>Nuevo Arbi</label>
                                            </button>
                                        @else
                                            <button class="btn bg-color-green txt-color-white cr-btn-big" onclick="sin_permiso()" >
                                                <span>
                                                    <i class="glyphicon glyphicon-plus-sign"></i>
                                                </span>
                                                <label>Nuevo Arbi</label>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                    </div>    
                </div>
</div>
<div id="dlg_reg_piso" style="display: none;">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">                
                <div class="panel panel-success" style="border: 0px !important">
                    <div class="panel-heading bg-color-success">.:: Datos del piso ::.</div>
                    <div class="panel-body cr-body">
                        <div class='col-lg-3 pd_dlg_cr'>
                            <input type="hidden" id="dlg_idpiso" value="0">
                            <label class="label">N° Piso:</label>
                            <label class="input">
                                <input id="rpiso_inp_nro" type="text"  class="input-sm" maxlength="5" >
                            </label>
                        </div>
                        <div class='col-lg-3 '>
                            <label class="label">Año de construccion:</label>
                            <label class="input">
                                <input id="rpiso_inp_fech" type="text"  class="input-sm" maxlength="4" >
                            </label>
                        </div>
                        <div class="col-xs-12"></div>
                        <div class='col-xs-3 pd_dlg_cr'>
                            <label class="label">Clasificación:</label>
                            <select id='rpiso_inp_clasi' class="form-control col-lg-8" onchange="callchangeoption('rpiso_inp_clasi',0)">
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
                            <select id='rpiso_inp_mat' class="form-control col-lg-8" onchange="callchangeoption('rpiso_inp_mat',0)">
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
                            <select id='rpiso_inp_econserv' class="form-control col-lg-8" onchange="callchangeoption('rpiso_inp_econserv',0)">
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
                            <select id='rpiso_inp_econstr' class="form-control col-lg-8" onchange="callchangeoption('rpiso_inp_econstr',0)">
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
                                <input id="rpiso_inp_estruc" type="text"  class="input-sm" maxlength="7" placeholder="7 Letras Entre A-I">
                            </label>
                        </div>
                        
                        <div class='col-lg-3 '>
                            <label class="label">Area Construida:</label>
                            <label class="input">
                                <input id="rpiso_inp_aconst" type="text"  class="input-sm text-right" placeholder="0.00" onkeypress="return soloNumeroTab(event);" >
                            </label>
                        </div>
                        <div class='col-lg-3'>
                            <label class="label">Areas Comunes:</label>
                            <label class="input">
                                <input id="rpiso_inp_acomun" type="text"  class="input-sm text-right" placeholder="0.00" onkeypress="return soloNumeroTab(event);" >
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>             
<div id="dlg_reg_condo" style="display: none;">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">                
                <div class="panel panel-success" style="border: 0px !important">
                    <div class="panel-heading bg-color-success">.:: Datos del Condominio ::.</div>
                    <div class="panel-body cr-body">
                        <div class="col-xs-12" style="height: 15px"></div>
                        <div class='col-lg-3 pd_dlg_cr'>
                            <input type="hidden" id="dlg_idcondo" value="0">
                            <label class="label">DNI/RUC:</label>
                            <label class="input">
                                <input type="hidden" id="rcondo_inp_rsoc_hidden">
                                <input id="rcondo_inp_dni" type="text"  class="input-sm" maxlength="12" onkeypress="return soloDNI(event);" placeholder="DNI/RUC" >
                            </label>
                        </div>
                        <div class='col-lg-8 '>
                            <label class="label">Apellidos Nombres o Razon Social:</label>
                            <label class="input">
                                <input id="rcondo_inp_rsoc" type="text"  class="input-sm" disabled="" >
                            </label>
                        </div>
                        <div class="col-xs-12" style="height: 15px"></div>
                        <div class='col-xs-11 pd_dlg_cr'>
                            <label class="label">Dirección:</label>
                            <label class="input">
                                <input id="rcondo_inp_dir" type="text"  class="input-sm" placeholder="Ingresar Dirección" maxlength="150">
                            </label>
                        </div>
                        <div class="col-xs-12" style="height: 15px"></div>
                        <div class='col-xs-4 pd_dlg_cr'>
                            <label class="label">Porcentaje:</label>
                            <label class="input">
                                <input id="rcondo_inp_porcent" type="text"  class="input-sm text-right" onkeypress="return soloNumeroTab(event);" placeholder="0.00%" >
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
            <div class="panel-group">                
                <div class="panel panel-success" style="border: 0px !important">
                    <div class="panel-heading bg-color-success">.:: Datos de Instalación ::.</div>
                    <div class="panel-body cr-body">
                        <div class='col-lg-1 pd_dlg_cr'>
                            <input type="hidden" id="dlg_idinst" value="0">
                            <label class="label">Codigo:</label>
                            <label class="input">
                                <input id="rinst_inp_des_cod" type="text"  class="input-sm" maxlength="2" disabled="" >
                            </label>
                        </div>
                        <div class='col-lg-6'>
                            <label class="label">Descripción:</label>
                            <label class="input">
                                <input id="hidden_rinst_inp_des" type="hidden" value="0">
                                <input id="rinst_inp_des" type="text"  class="input-sm" maxlength="150" >
                            </label>
                        </div>
                        <div class='col-lg-2'>
                            <label class="label">Unidad de Medida:</label>
                            <label class="input">
                                <input id="rinst_inp_undmed" type="text"  class="input-sm" disabled="">
                            </label>
                        </div>
                        <div class='col-lg-2'>
                            <label class="label">Año Construc.:</label>
                            <label class="input">
                                <input id="rinst_inp_anio" type="text"  class="input-sm" maxlength="4" onkeypress="return soloDNI(event);" >
                            </label>
                        </div>
                        <div class='col-lg-11 pd_dlg_cr'>
                            <label class="label">Glosa:</label>
                            <label class="input">   
                                <input id="glosa" type="text"  class="input-sm" maxlength="50" >
                            </label>
                        </div>
                        <div col="col-xs-12"></div>
                        <div class='col-xs-3 pd_dlg_cr'>
                            <label class="label">Clasificación:</label>
                            <select id='rinst_inp_clasi' class="form-control col-lg-8" onchange="callchangeoption('rinst_inp_clasi',0)">
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
                            <select id='rinst_inp_mat' class="form-control col-lg-8" onchange="callchangeoption('rinst_inp_mat',0)">
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
                            <select id='rinst_inp_econserv' class="form-control col-lg-8" onchange="callchangeoption('rinst_inp_econserv',0)">
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
                            <select id='rinst_inp_econstr' class="form-control col-lg-8" onchange="callchangeoption('rinst_inp_econstr',0)">
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
                                <input id="rinst_inp_largo" type="text"  class="input-sm text-right" onkeypress="return soloNumeroTab(event);" >
                            </label>
                        </div>
                        <div class='col col-3'>
                            <label class="label">Ancho:</label>
                            <label class="input">
                                <input id="rinst_inp_ancho" type="text"  class="input-sm text-right" onkeypress="return soloNumeroTab(event);"  >
                            </label>
                        </div>
                        <div class='col col-3'>
                            <label class="label">Alto:</label>
                            <label class="input">
                                <input id="rinst_inp_alto" type="text"  class="input-sm text-right" onkeypress="return soloNumeroTab(event);" >
                            </label>
                        </div>
                        <div class='col col-3'>
                            <label class="label">Cantidad:</label>
                            <label class="input">
                                <input id="rinst_inp_canti" type="text"  class="input-sm text-right" onkeypress="return soloNumeroTab(event);" >
                            </label>
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
<div id="dlg_new_arbi" style="display: none;">
    <input type="hidden" id="inp_hidd_arb" value="0" />
    <div class="col-xs-6" style="background-color: white">
        <div class="panel-body" style="padding-left: 0px;" >
             <label class="label col-xs-4 text-align-left" style="font-size: 1.4em; color:black; padding: 0px;">Mes de Inicio</label>
                <div class="col-xs-4" style="padding: 0px">
                    <select id="sel_mes_ini"  class="form-control">
                        <option value='1'>Enero</option>
                        <option value='2'>Febrero</option>
                        <option value='3'>Marzo</option>
                        <option value='4'>Abril</option>
                        <option value='5'>Mayo</option>
                        <option value='6'>Junio</option>
                        <option value='7'>Julio</option>
                        <option value='8'>Agosto</option>
                        <option value='9'>Septiembre</option>
                        <option value='10'>Octubre</option>
                        <option value='11'>Noviembre</option>
                        <option value='12'>Diciembre</option>
                    </select>
                </div>
         </div>
        <div class="panel-body" style="padding-left: 0px;" >
             <label class="label col-xs-4 text-align-left" style="font-size: 1.4em; color:black; padding: 0px;">Piso de uso</label>
                <div class="col-xs-4" style="padding: 0px">
                    <select id="sel_pis_uso"  class="form-control" onchange="cambiarpis()">
                        <option value='0'>Todos</option>
                    </select>
                </div>
         </div>
    </div>
    <div class="col-xs-6 text-align-right" style="background-color: white">
        <h1 id="tit_anio" class="txt-color-green" style="margin-bottom: 0px;"> </h1>
    </div>
    <div class="col-xs-12">
    <div class="jarviswidget jarviswidget-color-green" id="wid-id-10" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
        <header>
                <span class="widget-icon"> <i class="fa fa-list-alt"></i> </span>
                <h2>Lista de Arbítrios </h2>
        </header>
        <!-- widget div-->
        <div>
            <!-- widget content -->
            <div class="widget-body no-padding">
                <div class="panel-group smart-accordion-default" id="accordion-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#collapseOne-1"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i> (1) Barrido de Calles </a></h4>
                        </div>
                        <div id="collapseOne-1" class="panel-collapse collapse in cr_toogle">
                            <div class="panel-body" >
                            <div class="col-xs-2" style="padding: 0px">
                                <label class="label col-xs-12 text-align-left" style="padding-left: 15px;">Frentera(m)</label>
                                <label class="input col-xs-12">
                                    <input id="inp_bar_frent" type="text"  class="form-control text-align-right" onkeypress="return soloNumeroTab(event);" onkeyup="calculos(1)" placeholder="Metros de frentera" >
                                </label>
                            </div>
                            <div class="col-xs-2" style="padding: 0px">
                                <label class="label col-xs-12 text-align-left" >Frecuencia:</label> 
                                <select id="sel_bar_frecu"  class="form-control" onchange="change_select('sel_bar_frecu',1)">
                                    <option value="0" costo="0">-- Seleccione --</option>
                                          
                                </select>
                            </div>
                            
                            <div class="col-xs-2" style="padding: 0px">
                                <label class="label col-xs-12 text-align-left" style="padding-left: 15px;">Costo Anual por metro</label>
                                <label class="input col-xs-12">
                                    <input id="sel_bar_frecu_cos" type="text"  class="form-control text-align-right" disabled="" >
                                </label>
                            </div>
                            <div class="col-xs-2" style="padding: 0px" >
                                <label class="label col-xs-12 text-align-left" style="padding-left: 15px;">Costo Anual Total</label>
                                <label class="input col-xs-12">
                                    <input id="inp_bar_costot" type="text"  class="form-control text-align-right" disabled="" >
                                </label>
                            </div>
                            <div class="col-xs-2" style="padding: 0px" >
                                <label class="label col-xs-12 text-align-left" style="padding-left: 15px;">Costo Trimestral Total</label>
                                <label class="input col-xs-12">
                                    <input id="inp_bar_costri" type="text"  class="form-control text-align-right" disabled="" >
                                </label>
                            </div>
                            <div class="col-xs-2" style="padding: 0px" >
                                <label class="label col-xs-12 text-align-left" style="padding-left: 15px;">Costo Mensual Total</label>
                                <label class="input col-xs-12">
                                    <input id="inp_bar_cosmes" type="text"  class="form-control text-align-right" disabled="" >
                                </label>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#collapseTwo-1" class="collapsed"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i> (2) Recojo de Residuos Sólidos </a></h4>
                        </div>
                        <div id="collapseTwo-1" class="panel-collapse collapse cr_toogle">
                            <div class="panel-body">
                                <div class="col-xs-2" style="padding: 0px" >
                                    <label class="label col-xs-12 text-align-left" style="padding-left: 15px;">Tipo de Predio</label>
                                    
                                    <select id="sel_ressol_tp"  class="form-control" onchange="call_frec_rrs(0);">
                                        <option value="0" costo="0">-- Seleccione --</option>
                                        @foreach ($upa as $upa)
                                            <option value='{{$upa->id_uso_arb}}' >{{$upa->uso_arbitrio}}</option>
                                        @endforeach         
                                    </select>
                                </div>
                                <div class="col-xs-12"></div>
                                <div class="col-xs-2" style="padding: 0px" >
                                    <label class="label col-xs-12 text-align-left" style="padding-left: 15px;">Area Construida(m2)</label>
                                    <label class="input col-xs-12">
                                        <input id="inp_ressol_area" type="text"  class="form-control text-align-right"  onkeypress="return soloNumeroTab(event);" >
                                    </label>
                                </div>
                                <div class="col-xs-2" style="padding: 0px">
                                    <label class="label col-xs-12 text-align-left" >Frecuencia:</label> 
                                    <select id="sel_ressol_frecu"  class="form-control" onchange="change_select('sel_ressol_frecu',2)">
                                        <option value="0" costo="0">-- Seleccione --</option>
                                    </select>
                                </div>
                                <div class="col-xs-2" style="padding: 0px">
                                    <label class="label col-xs-12 text-align-left" >Costo m2:</label> 
                                    <label class="input col-xs-12">
                                        <input id="sel_ressol_frecu_cos" type="text"  class="form-control text-align-right" disabled="" >
                                    </label>
                                </div>
                                <div class="col-xs-2" style="padding: 0px" >
                                    <label class="label col-xs-12 text-align-left" style="padding-left: 15px;">Costo Total Anual</label>
                                    <label class="input col-xs-12">
                                        <input id="inp_ressol_costot" type="text"  class="form-control text-align-right" disabled="" >
                                    </label>
                                </div>
                                <div class="col-xs-2" style="padding: 0px" >
                                    <label class="label col-xs-12 text-align-left" style="padding-left: 15px;">Costo Total Trimestal</label>
                                    <label class="input col-xs-12">
                                        <input id="inp_ressol_costri" type="text"  class="form-control text-align-right" disabled="" >
                                    </label>
                                </div>
                                <div class="col-xs-2" style="padding: 0px" >
                                    <label class="label col-xs-12 text-align-left" style="padding-left: 15px;">Costo Total Mensual</label>
                                    <label class="input col-xs-12">
                                        <input id="inp_ressol_cosmes" type="text"  class="form-control text-align-right" disabled="" >
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#collapseThree-1" class="collapsed"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i> (3) Seguridad Ciudadana </a></h4>
                        </div>
                        <div id="collapseThree-1" class="panel-collapse collapse">
                            <div class="panel-body cr_toogle">
                                <div class="col-xs-2" >
                                    <label class="label col-xs-12 text-align-left" >Categoría:</label> 
                                    <select id="sel_seren_cat"  class="form-control" onchange="change_select('sel_seren_cat',3)">
                                        <option value="0" costo="0">-- Seleccione --</option>
                                       
                                    </select>
                                </div>
                                <div class="col-xs-2" style="padding: 0px" >
                                    <label class="label col-xs-12 text-align-left" style="padding-left: 15px;">Costo Total Anual</label>
                                    <label class="input col-xs-12">
                                        <input id="inp_seren_costot" type="text"  class="form-control text-align-right" disabled="" >
                                    </label>
                                </div>
                                <div class="col-xs-2" style="padding: 0px" >
                                    <label class="label col-xs-12 text-align-left" style="padding-left: 15px;">Costo Total Trimestral</label>
                                    <label class="input col-xs-12">
                                        <input id="inp_seren_costri" type="text"  class="form-control text-align-right" disabled="" >
                                    </label>
                                </div>
                                <div class="col-xs-2" style="padding: 0px" >
                                    <label class="label col-xs-12 text-align-left" style="padding-left: 15px;">Costo Total Mensual</label>
                                    <label class="input col-xs-12">
                                        <input id="inp_seren_cosmes" type="text"  class="form-control text-align-right" disabled="" >
                                    </label>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#collapseFour-1" class="collapsed"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i> (4) Parques y Jardines </a></h4>
                        </div>
                        <div id="collapseFour-1" class="panel-collapse collapse">
                            <div class="panel-body cr_toogle">
                                <div class="col-xs-2" >
                                    <label class="label col-xs-12 text-align-left" >Categoría:</label> 
                                    <select id="sel_parq_cat"  class="form-control" onchange="change_select('sel_parq_cat',4)">
                                        <option value="0" costo="0">-- Seleccione --</option>
                                        
                                    </select>
                                </div>
                                <div class="col-xs-2" style="padding: 0px" >
                                    <label class="label col-xs-12 text-align-left" style="padding-left: 15px;">Costo Total Anual</label>
                                    <label class="input col-xs-12">
                                        <input id="inp_parq_costot" type="text"  class="form-control text-align-right" disabled="" >
                                    </label>
                                </div>
                                <div class="col-xs-2" style="padding: 0px" >
                                    <label class="label col-xs-12 text-align-left" style="padding-left: 15px;">Costo Total Trimestral</label>
                                    <label class="input col-xs-12">
                                        <input id="inp_parq_costri" type="text"  class="form-control text-align-right" disabled="" >
                                    </label>
                                </div>
                                <div class="col-xs-2" style="padding: 0px" >
                                    <label class="label col-xs-12 text-align-left" style="padding-left: 15px;">Costo Total Mensual</label>
                                    <label class="input col-xs-12">
                                        <input id="inp_parq_mes" type="text"  class="form-control text-align-right" disabled="" >
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end widget content -->
        </div>
        <!-- end widget div -->
     </div>
    </div>
</div> 
@endsection




