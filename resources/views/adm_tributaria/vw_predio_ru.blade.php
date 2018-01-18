@extends('layouts.app')
@section('content')
<input type="hidden" id="per_edit" value="{{$permisos[0]->btn_edit}}"/>
<input type="hidden" id="per_del" value="{{$permisos[0]->btn_del}}"/>
<section id="widget-grid" class=""> 
    
<div class='cr_content col-xs-12'>
    <div class="col-xs-12">
        <div class="col-lg-9">
            <h1 class="txt-color-green"><b>Predios Rusticos...</b></h1>
        </div>
        <div class="col-lg-3 col-md-6 col-xs-12">
            <div class="input-group input-group-md">
                <span class="input-group-addon">Año de Trabajo <i class="fa fa-cogs"></i></span>
                <div class="icon-addon addon-md">
                    <select id='selantra' class="form-control col-lg-8" style="height: 32px;" onchange="call_list_contrib_carta(0)">
                    @foreach ($anio_tra as $anio)
                    <option value='{{$anio->anio}}' >{{$anio->anio}}</option>
                    @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 col-xs-12">
          
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
                    <button  type="button" class="btn btn-labeled btn-danger">
                        <span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span>Eliminar
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
        $("#li_preru").addClass('cr-active')
        jQuery("#table_predios").jqGrid({
            url: 'gridpredio?tpre=2&mnza=0&ctr=0&an='+$("#selantra").val(),
            datatype: 'json', mtype: 'GET',
            height: '300px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_pred','t_pred','Sector','Manzana', 'Lote Cat', 'Código Predial', 'Mz Dist', 'Lt Dist', 'N° Munic', 'Est. Construcción', 'Contribuyente o Razon Social', 'Calle/Vía','id_via','A.Terreno','S/.Terreno','S/.Construct'],
            rowNum: 20, sortname: 'id_pred', sortorder: 'desc', viewrecords: true, caption: 'Predios Rusticos', align: "center",
            colModel: [
                {name: 'id_pred', index: 'id_pred', hidden: true},
                {name: 'tp', index: 'tp', align: 'center', width: 50},
                {name: 'sec', index: 'sec', align: 'center', width: 20,hidden:true},
                {name: 'mnza', index: 'mnza', align: 'center', width: 20,hidden:true},
                {name: 'lote', index: 'lote', align: 'center', width: 50,hidden:true},
                {name: 'cod_cat', index: 'cod_cat', align: 'center',hidden:true},
                {name: 'mzna_dist', index: 'mzna_dist', align: 'center',hidden:true},
                {name: 'lote_dist', index: 'lote_dist', align: 'center',hidden:true},
                {name: 'nro_mun', index: 'nro_mun', width: 40,hidden:true},
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
            rowNum: 20, sortname: 'contribuyente', sortorder: 'asc', viewrecords: true, caption: 'Contribuyentes', align: "center",
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
                        jQuery('#table_contrib').jqGrid('bindKeys', {"onEnter":function( rowid ){fn_bus_contrib_list_rus(rowid);} } ); 
                    }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){fn_bus_contrib_list_rus(Id)}
        });
        var globalvalidador=0;
        $("#dlg_contri").keypress(function (e) {
            if (e.which == 13) {
                if(globalvalidador==0)
                {
                    fn_bus_contrib_rus();
                    globalvalidador=1;
                }
                else
                {
                    globalvalidador=0;
                }
            }
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
<script src="{{ asset('archivos_js/adm_tributaria/predios_ru.js') }}"></script>
<div id="dlg_bus_contr" style="display: none;">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
        <table id="table_contrib"></table>
        <div id="pager_table_contrib"></div>
    </article>
</div> 
<div id="dlg_reg_dj" style="display: none;">
                    <div class="widget-body">
                    <div  class="smart-form">
                        
                        <input id="dlg_idpre" type="hidden" value="0"/>
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
                        
                        <div class="panel-group col-xs-12 " style="margin-top: 5px;  ">                
                            <div class="panel panel-success ">
                                <div class="panel-heading bg-color-success">.:: Ubicación del Predio ::.</div>
                                <div class="panel-body cr-body">
                                   
                                    
                                    <div class="col col-4" style="padding-right: 3px;">
                                        <label class="label">Valle Lugar:</label>
                                        <label class="input">
                                            <input id="dlg_inp_valle" type="text"  class="input-sm" maxlength="100">
                                        </label>
                                    </div>
                                    <div class="col col-3 pdboth_dlg_cr">
                                        <label class="label">Carretera:</label>
                                        <label class="input">
                                            <input id="dlg_inp_carre" type="text"  class="input-sm" maxlength="100">
                                        </label>
                                    </div>
                                    <div class="col col-1 pdboth_dlg_cr">
                                        <label class="label">KM:</label>
                                        <label class="input">
                                            <input id="dlg_inp_km" type="text"  class="input-sm" maxlength="5">
                                        </label>
                                    </div>
                                    <div class="col col-4 pdboth_dlg_cr" style="padding-right: 10px !important;">
                                        <label class="label">Nombre Predio:</label>
                                        <label class="input">
                                            <input id="dlg_inp_nompre" type="text"  class="input-sm" maxlength="100">
                                        </label>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                        
                        <div class="panel-group col-xs-12 " style="margin-top: 5px;  ">                
                            <div class="panel panel-success ">
                                <div class="panel-heading bg-color-success">.:: Predios Colindantes ::.</div>
                                <div class="panel-body cr-body">
                                    <div class="col col-3 " style="padding-right: 5px">
                                        <label class="label">NORTE:</label>
                                        <label class="input">
                                            <input id="dlg_inp_norte" type="text"  class="input-sm" maxlength="50" placeholder="Nombre o Propietario predio Norte">
                                        </label>
                                    </div>
                                    <div class="col col-3 pdboth_dlg_cr" style="padding-right: 5px">
                                        <label class="label">SUR:</label>
                                        <label class="input">
                                            <input id="dlg_inp_sur" type="text"  class="input-sm" maxlength="50" placeholder="Nombre o Propietario predio Sur">
                                        </label>
                                    </div>
                                    <div class="col col-3 pdboth_dlg_cr" style="padding-right: 5px">
                                        <label class="label">ESTE:</label>
                                        <label class="input">
                                            <input id="dlg_inp_este" type="text"  class="input-sm" maxlength="50" placeholder="Nombre o Propietario predio Este">
                                        </label>
                                    </div>
                                    <div class="col col-3 pdboth_dlg_cr" style="padding-right: 10px !important;">
                                        <label class="label">OESTE:</label>
                                        <label class="input">
                                            <input id="dlg_inp_oeste" type="text"  class="input-sm" maxlength="50" placeholder="Nombre o Propietario predio Oeste">
                                        </label>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="panel-group col-xs-12 " style="margin-top: 5px;  ">                
                            <div class="panel panel-success ">
                                <div class="panel-heading bg-color-success">.:: Datos Relativos Al Terreno ::.</div>
                                <div class="panel-body cr-body">
                                    <div class='col col-3' >
                                        <label class="label">Tipo Terreno:</label>
                                        <select id='dlg_sel_tterr' class="form-control" >
                                                @foreach ($tipoterr as $tter)
                                                <option value='{{$tter->id_tip_pre_rus}}' >{{$tter->tipo_predio_rustico}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                    <div class='col col-3 pdboth_dlg_cr' >
                                        <label class="label">Uso Terreno:</label>
                                        <select id='dlg_sel_uterr' class="form-control" >
                                                @foreach ($usoterr as $uter)
                                                <option value='{{$uter->id_uso_pr}}' >{{$uter->uso_pr_rus}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                    <div class='col col-3 pdboth_dlg_cr'>
                                        <label class="label">Estado de Construccion:</label>
                                        <select id='dlg_sel_estcon' class="form-control" >
                                                @foreach ($ecc as $eccpre)
                                                <option value='{{$eccpre->id_ecc}}' >{{$eccpre->descripcion}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                    <div class='col col-3 pdboth_dlg_cr '>
                                        <label class="label">Tipo de Predio:</label>
                                        <select id='dlg_sel_tippre' class="form-control" >
                                            @foreach ($tpre as $tpre)
                                                <option value='{{$tpre->id_tip_p}}' >{{$tpre->descrip_tip_pre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        
                        <div class="panel-group col-xs-4 " style="margin-top: 5px; margin-bottom: 5px  ">                
                            <div class="panel panel-success">
                                <div class="panel-heading bg-color-success">.:: Foma de Adquisición ::.</div>
                                <div class="panel-body cr-body">
                                    <div class='col-lg-7 pd_dlg_cr'>
                                        <label class="label">Forma de Adquisición:</label>
                                        <select id='dlg_sel_foradq' class="form-control" onchange="llamar_otro_pr()">
                                                @foreach ($fadq as $fadq)
                                                <option value='{{$fadq->id_for}}' >{{$fadq->des_for_adq}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                    <div class='col-lg-4'>
                                        <label class="label">Fecha:</label>
                                        <label class="input">
                                            <input id="dlg_inp_fech" type="text"  class="input-sm datepicker"  data-dateformat='dd/mm/yy'  data-mask="99/99/9999" data-mask-placeholder="-" >
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="panel-group col-xs-8 " style="margin-top: 5px; margin-bottom: 5px  " >                
                            <div class="panel panel-success cr-panel-sep">
                                <div class="panel-heading bg-color-success">.:: Terreno ::.</div>
                                <div class="panel-body cr-body">
                                    <div class='col col-3' style="padding-right: 3px;" >
                                        <label class="label">Grupo Tierras:</label>
                                        <select id='dlg_sel_gpoterr' class="form-control" onchange="llamarcambio();">
                                                @foreach ($gpoterr as $gter)
                                                <option value='{{$gter->id_gpo}}' >{{$gter->gpo_descrip}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                    <div class='col col-3 pdboth_dlg_cr'  >
                                        <label class="label">Categoria Tierras:</label>
                                        <select id='dlg_sel_gpocatterr' class="form-control" onchange="callchangeoption('dlg_sel_gpocatterr',1);" >
                                                
                                        </select>
                                    </div>
                                    <div class=' col col-2 pdboth_dlg_cr' >
                                        <label class="label">Arancel:</label>
                                        <label class="input">
                                            <input id="dlg_inp_aranc" type="text"  class="input-sm" disabled="" style="text-align: right">
                                        </label>
                                    </div>
                                    <div class='col col-2 pdboth_dlg_cr'>
                                        <label class="label">Hectareas.:</label>
                                        <label class="input">
                                            <input id="dlg_inp_areter" type="text"  class="input-sm" onkeypress="return soloNumeroTab(event);" onkeyup="validarvalter();" style="text-align: right" placeholder="0.00">
                                        </label>
                                    </div>
                                    
                                    <div class='col col-2 pdboth_dlg_cr' style="padding-right: 10px !important;">
                                        <label class="label">Val Terr.:</label>
                                        <label class="input">
                                            <input id="dlg_inp_valterr" type="text"  class="input-sm" disabled="" style="text-align: right;">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 cr-body">
                        <ul id="sparks">
                            @if( $permisos[0]->btn_new ==1 )
                                    <button type="button" id="btnsavepre" class="btn btn-labeled bg-color-green txt-color-white" onclick="dlgSave();">
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
                            <label class="label">Año:</label>
                            <label class="input">
                                <input id="rpiso_inp_fech" type="text"  class="input-sm" maxlength="4" onkeypress="return soloDNI(event);">
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
 <!-- otros  -->
 <div id="dialog_otros_pr" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <!-- widget div-->
                <div class="row" style="padding: 20px 30px;">
                <div class="col-xs-12">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Especifique la opción OTROS <i class="fa fa-cogs"></i></span>
                        <label class="input">
                            <input id="dlg_inp_otros_pr" type="text"  class="input-sm text-uppercase" maxlength="150">
                        </label>    
                    </div>
                </div>
                </div> 
                <!-- end widget div -->
            </div>
        </div>
    </div>
</div>
 <!-- -->
@endsection




