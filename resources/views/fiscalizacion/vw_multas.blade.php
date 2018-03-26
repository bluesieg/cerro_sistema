@extends('layouts.app')
@section('content')
<input type="hidden" id="per_imp" value="{{$permisos[0]->btn_imp}}"/>
<input type="hidden" id="per_del" value="{{$permisos[0]->btn_del}}"/>
<input type="hidden" id="per_edit" value="{{$permisos[0]->btn_edit}}"/>
<section id="widget-grid" class=""> 
    <div class='cr_content col-xs-12 '>
        <div class="col-xs-9">
            <h1 class="txt-color-green"><b>Generación de Multas Fiscalización</b></h1>
        </div>
        <div class="col-xs-3" style="margin-top: 5px; padding-right: 23px;">
            <div class="input-group input-group-md">
                <span class="input-group-addon">Año de Tramite <i class="fa fa-cogs"></i></span>
                <div class="icon-addon addon-md">
                    <select id='selantra' class="form-control col-lg-8" style="height: 32px;" onchange="call_list_contrib_multa(0)">
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
                                <h2>Busqueda de Multa por Contribuyente</h2>
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
                            <button class="btn btn-default" type="button" onclick="fn_bus_contrib_multa('dlg_contri')">
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
                                <h2>Busqueda Por Número de Multa</h2>
                        </header>
                    </div>
                </section>
                </div>
            </div>
            <div class="col-xs-8" style="padding: 0px; margin-top: 5px">
                <div class="col-xs-10" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Número Multa &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div class=""  >
                            <input id="dlg_bus_num" type="text"  class="form-control" style="height: 32px; " maxlength="7" onkeypress="return soloNumeroTab(event);" >
                        </div>
                    </div>
                </div>
                <div class='col-lg-2'style="padding: 0px;" >
                    <button type="button" class="btn btn-labeled bg-color-green txt-color-white" onclick="call_list_contrib_multa(3)">
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
                                <h2>Busqueda de Multa Por Fechas</h2>
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
                    <button type="button" class="btn btn-labeled bg-color-green txt-color-white" onclick="call_list_contrib_multa(2)">
                        <span class="btn-label"><i class="glyphicon glyphicon-search"></i></span>Buscar
                    </button>
                </div>
            </div>
            <div class='col-xs-12'style="padding: 0px; margin-top: 5px" >
                @if( $permisos[0]->btn_new ==1 )
                <button type="button" class="btn btn-labeled bg-color-red txt-color-white" onclick="new_multa()">
                    <span class="btn-label"><i class="glyphicon glyphicon-new-window"></i></span>Mantenimiento y Creación de nuevos criterios de Multas
                </button>
                @else
                <button type="button" class="btn btn-labeled bg-color-red txt-color-white" onclick="sin_permiso()">
                    <span class="btn-label"><i class="glyphicon glyphicon-new-window"></i></span>Mantenimiento y Creación de nuevos criterios de Multas
                </button>
                @endif
            </div>
        </div>
    </div>
    <div class='cr_content col-xs-12'>
       
        <div class="col-xs-12" style="padding: 0px; margin-top: 10px">
            <article class="col-xs-11" style=" padding: 0px !important">
                    <table id="table_multas"></table>
                    <div id="pager_table_multas"></div>
            </article>
            <div class="col-xs-1 text-center" style="padding-right: 0px;">
            @if( $permisos[0]->btn_new ==1 )
                <button class="btn bg-color-green txt-color-white btn-circle btn-xl" onclick="fn_multa_registada();" >
                    <span  >
                        <i class="glyphicon glyphicon-plus"></i>
                    </span>
                </button>
                    <label><b>Nuevo</b></label>
            @else
                <button class="btn bg-color-green txt-color-white btn-circle btn-xl" onclick="sin_permiso();" >
                    <span  >
                        <i class="glyphicon glyphicon-plus"></i>
                    </span>
                </button>
                    <label><b>Nuevo</b></label>
            @endif
            </div>
            </div>
    </div>
    
</section>
@section('page-js-script')
<script src="js/plugin/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function (){
        $("#menu_fisca").show();
        $("#li_fis_multa").addClass('cr-active')
        jQuery("#table_multas").jqGrid({
            url: 'trae_multas/'+$("#selantra").val()+'/0/0/0/0',
            datatype: 'json', mtype: 'GET',
            height: '280px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_multa_reg', 'Nro', 'contribuyente', 'Registro','Notificación','Días Transcurridos','Ver'],
            rowNum: 20, sortname: 'id_multa_reg', sortorder: 'desc', viewrecords: true, caption: 'Multas Fiscalización', align: "center",
            colModel: [
                {name: 'id_multa_reg', index: 'id_multa_reg', hidden: true},
                {name: 'nro_multa', index: 'nro_multa', align: 'center', width: 10},
                {name: 'contribuyente', index: 'contribuyente', align: 'center', width: 30},
                {name: 'fec_reg', index: 'fec_reg', align: 'center', width: 10},
                {name: 'fecha_notificacion', index: 'fecha_notificacion', align: 'center', width: 20},
                {name: 'dias', index: 'dias', align: 'center', width: 10},
                {name: 'id_multa_reg', index: 'id_multa_reg', align: 'center', width: 10},
            ],
            pager: '#pager_table_multas',
            rowList: [20, 50],
            gridComplete: function () {
                    var idarray = jQuery('#table_multas').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_multas').jqGrid('getDataIDs')[0];
                            $("#table_multas").setSelection(firstid);    
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
            colNames: ['id_pers','codigo','DNI/RUC','contribuyente','email','Dom Fiscal'],
            rowNum: 20, sortname: 'contribuyente', sortorder: 'asc', viewrecords: true, caption: 'Contribuyentes', align: "center",
            colModel: [
                {name: 'id_pers', index: 'id_pers', hidden: true},
                {name: 'id_per', index: 'id_per', align: 'center',width: 100},
                {name: 'nro_doc', index: 'nro_doc', align: 'center',width: 100},
                {name: 'contribuyente', index: 'contribuyente', align: 'left',width: 260},
                {name: 'email', index: 'email', hidden: true},
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
                        jQuery('#table_contrib').jqGrid('bindKeys', {"onEnter":function( rowid ){fn_bus_contrib_list_multa(rowid);} } ); 
                    }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){fn_bus_contrib_list_multa(Id)}
        });
        jQuery("#table_multas_criterios").jqGrid({
            url: 'obtiene_multas/0',
            datatype: 'json', mtype: 'GET',
            height: '300px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_multa','Multa','Costo'],
            rowNum: 500, sortname: 'des_multa', sortorder: 'asc', viewrecords: true, caption: 'Multas', align: "center",
            colModel: [
                {name: 'id_multa', index: 'id_multa', hidden: true},
                {name: 'des_multa', index: 'des_multa', align: 'left',width: 650},
                {name: 'cos_multa', index: 'cos_multa', align: 'right',width: 100},
                
            ],
            pager: '#pager_table_multas_criterios',
            rowList: [500, 1000],
            gridComplete: function () {
                    var idarray = jQuery('#table_multas_criterios').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_multas_criterios').jqGrid('getDataIDs')[0];
                            $("#table_multas_criterios").setSelection(firstid);    
                        }
                    if(contrib_global==0)
                    {   contrib_global=1;    
                        jQuery('#table_multas_criterios').jqGrid('bindKeys', {"onEnter":function( rowid ){fn_selecciona_multa_criterio(rowid);} } ); 
                    }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){fn_selecciona_multa_criterio(Id)}
        });
        
        
        
        var globalvalidador=0;
        $("#dlg_contri").keypress(function (e) {
            if (e.which == 13) {
                if(globalvalidador==0)
                {
                    fn_bus_contrib_multa("dlg_contri");
                    globalvalidador=1;
                }
                else
                {
                    globalvalidador=0;
                }
                
            }
        });
        $("#dlg_contri_multa_registrada").keypress(function (e) {
            if (e.which == 13) {
                if(globalvalidador==0)
                {
                    fn_bus_contrib_multa("dlg_contri_multa_registrada");
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
                call_list_contrib_multa(3);
            }
        });
    });
</script>
@stop
<script src="{{ asset('archivos_js/fiscalizacion/multas.js') }}"></script>
<div id="dlg_bus_contr" style="display: none;">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
        <table id="table_contrib"></table>
        <div id="pager_table_contrib"></div>
    </article>
</div> 
<div id="buscar_multa" style="display: none;">
    
    <div class="col-xs-12" style="padding: 0px;">
        <div class="input-group input-group-md">
            <span class="input-group-addon">Busqueda. &nbsp;<i class="fa fa-search"></i></span>
            <div class=""  >
                <input id="dlg_filtrar_multas" type="text"  class="form-control" style="height: 32px; " placeholder="Filtrar Multas" onkeyup="FilterTableAllFields(this, 'table_multas_criterios');">
            </div>
        </div>
    </div>
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
        <table id="table_multas_criterios"></table>
        <div id="pager_table_multas_criterios"></div>
    </article>
</div> 
<div id="dlg_new_multa" style="display: none;">
    <input type="hidden" id="hidden_id_carta" value="0"/>
    <div class='cr_content col-xs-12 ' style="margin-bottom: 10px;">
        <div id="div_adquiere" class="col-xs-12 cr-body" >
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px; margin-top: 0px;">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-info"></i> </span>
                                <h2>LLenado de Información::..</h2>
                        </header>
                    </div>
                </section>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width:200px">Descripción Multa &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div class=""   >
                            <input id="dlg_des_multa" type="text"  class="form-control" style="height: 32px;  " >
                        </div>
                    </div>
                </div>
                <div class="col-xs-12" style="padding: 0px; margin-top: 10px ">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width:200px">costo &nbsp;<i class="fa fa-money"></i></span>
                        <div>
                            <input id="dlg_cost_multa" type="text"  class="form-control text-right" style="height: 32px;font-size: 0.9em;" onkeypress="return soloNumeroTab(event);"  >
                        </div>
                       
                    </div>
                </div>
                <div class="col-xs-12" style="margin-top: 10px;"></div>
               
            </div>
            
        </div>
    </div>
</div> 
<div id="dlg_new_multa_registada" style="display: none;">
    <input type="hidden" id="hidden_id_carta" value="0"/>
    <div class='cr_content col-xs-12 ' style="margin-bottom: 10px;">
        <div id="div_adquiere" class="col-xs-12 cr-body" >
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px; margin-top: 0px;">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-info"></i> </span>
                                <h2>Seleccion de Contribuyente::..</h2>
                        </header>
                    </div>
                </section>
                <div class="col-xs-3" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">N° Doc. &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div class=""  >
                            <input id="dlg_contri_multa_registrada_doc" type="text"  class="form-control" style="height: 32px; " disabled="" >
                        </div>
                    </div>
                </div>
                <div class="col-xs-9" style="padding: 0px; ">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Contribuyente a Fiscalizar &nbsp;<i class="fa fa-male"></i></span>
                        <div>
                            <input id="dlg_contri_multa_registrada_hidden" type="hidden" value="0">
                            <input id="dlg_contri_multa_registrada" type="text"  class="form-control" style="height: 32px;font-size: 0.9em;width: 102% !important" autofocus="focus" >
                        </div>
                        <span class="input-group-btn" style="font-size: 13px;">
                            <button id="bus_dlg_contri_multa_registrada" class="btn btn-default" type="button" onclick="fn_bus_contrib_multa('dlg_contri_multa_registrada')">
                                <i class="glyphicon glyphicon-search"></i>
                            </button>
                        </span>
                    </div>
                </div>
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                
                <div class="col-xs-12" style="padding: 0px; ">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Domicilio Fiscal &nbsp;<i class="fa fa-map"></i></span>
                        <div>
                            <input id="dlg_contri_multa_registrada_dom" type="text"  class="form-control" style="height: 32px;font-size: 0.9em;" disabled="">
                        </div>
                    </div>
                </div>
                <div class="col-xs-12" style="padding: 0px; margin-top: 10px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Glosa Multa &nbsp;<i class="fa fa-map"></i></span>
                        <div>
                            <input id="dlg_glosa_multa_registrada_dom" type="text"  class="form-control" style="height: 32px;font-size: 0.9em;" >
                        </div>
                    </div>
                </div>
            </div>
           
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px; margin-top: 10px;margin-bottom: 10px;">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-users"></i> </span>
                                <h2>Seleccion de Multa ::..</h2>
                        </header>
                    </div>
                </section>
                
                <div class="col-xs-6" style="padding: 0px; ">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Multa &nbsp;<i class="fa fa-list"></i></span>
                        <div>
                            <input id="sel_multa_registrada_hidden" type="hidden" value="0">
                            <input id="sel_multa_registrada" type="text"  class="form-control" style="height: 32px;font-size: 0.9em;width: 102% !important" disabled="" >
                        </div>
                        <span class="input-group-btn" style="font-size: 13px;">
                            <button class="btn btn-default" type="button" onclick="buscar_multa()">
                                <i class="glyphicon glyphicon-search"></i>
                            </button>
                        </span>
                    </div>
                </div>
                
                <div class="col-xs-2" style="padding: 0px; ">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Costo &nbsp;<i class="fa fa-money"></i></span>
                        <div>
                            <input id='costo_multa_registrada' type="text" class="form-control " style="height: 32px;" disabled="">
                               
                        </div>
                    </div>
                </div>
                <div class="col-xs-2" style="padding: 0px; ">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Año<i class="fa fa-cogs"></i></span>
                        <div class="icon-addon addon-md">
                            <select id='selantra_multa' class="form-control col-lg-8" style="height: 32px;">
                            @foreach ($anio_tra as $anio_multa)
                            <option value='{{$anio_multa->anio}}' >{{$anio_multa->anio}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <button id="btn_bus_multa" type="button" class="btn btn-labeled bg-color-green txt-color-white col-xs-2" onclick="poner_multa()">
                    <span class="cr-btn-label"><i class="glyphicon glyphicon-plus"></i></span>Agregar Multa
                </button>
                
                <div id="div_table_multa_sel" class="table-responsive col-xs-12" style="margin-top: 10px; height: 130px; border: 1px solid #bbb; padding:10px;">

                    <table class="table " id="table_multa_sel" >
                            <thead>
                                    <tr>
                                        <th class="text-center" style="border: 1px solid #bbb; width: 10%; height: 30px">Codigo</th>
                                        <th class="text-center"  style="border: 1px solid #bbb; width: 10%; height: 30px">Periodo</th>
                                        <th class="text-center"  style="border: 1px solid #bbb; width: 60%;height: 30px">Multa</th>
                                        <th class="text-center"  style="border: 1px solid #bbb; width: 10%; height: 30px">Costo</th>
                                        <th class="text-center"  style="border: 1px solid #bbb; width: 10%; height: 30px">Borrar</th>

                                    </tr>
                            </thead>
                            <tbody>


                            </tbody>
                    </table>

                </div>
            </div>
            
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px; margin-top: 10px;">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-info"></i> </span>
                                <h2>FUNDAMENTOS Y DISPOSICIONES DE AMPARO::..</h2>
                        </header>
                    </div>
                </section>
                <textarea name="ckeditor" id="ckeditor" >
                    Este es el textarea que es modificado por la clase ckeditor
                </textarea> 
               
            </div>
        </div>
    </div>
</div> 

<div id="dlg_fec_notificacion" style="display: none;">
    
    <div class='cr_content col-xs-12 ' style="margin-bottom: 10px;">
        <div class="col-xs-12 cr-body" style="padding-left: 0px;padding-right: 10px;" >
            <div class="col-xs-12" style="padding: 0px; margin-top: 0px;">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-info"></i> </span>
                                <h2>Ingresar Fecha de Notificacíon de Multa.::..</h2>
                        </header>
                    </div>
                </section>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">N° Carta Req. &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div class=""  >
                            <input id="input_num_multa_fn" type="text"  class="form-control" style="height: 32px; " disabled="" >
                        </div>
                    </div>
                </div>
                               
                <div class="col-xs-12" style="padding: 0px; margin-top: 10px ">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Fecha Notificación &nbsp;<i class="fa fa-calendar"></i></span>
                        <div>
                            <input id="input_fec_notifica" type="text" class="datepicker text-center" data-dateformat='dd/mm/yy' style="height: 32px; width: 100%" placeholder="--/--/----" value="{{date('d/m/Y')}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div> 

@endsection




