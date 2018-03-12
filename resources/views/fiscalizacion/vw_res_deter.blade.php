@extends('layouts.app')
@section('content')
<input type="hidden" id="per_new" value="{{$permisos[0]->btn_new}}"/>
<input type="hidden" id="per_imp" value="{{$permisos[0]->btn_imp}}"/>
<input type="hidden" id="per_edit" value="{{$permisos[0]->btn_edit}}"/>
<section id="widget-grid" class=""> 
    <div class='cr_content col-xs-12 '>
        <div class="col-xs-9">
            <h1 class="txt-color-green"><b>Generación de Resolución de Determinación</b></h1>
        </div>
        <div class="col-xs-3" style="margin-top: 5px; padding-right: 23px;">
            <div class="input-group input-group-md">
                <span class="input-group-addon">Año de Tramite <i class="fa fa-cogs"></i></span>
                <div class="icon-addon addon-md">
                    <select id='selantra' class="form-control col-lg-8" style="height: 32px;" onchange="buscar_rd(0)">
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
                                <h2>Busqueda de R.D. por Contribuyente</h2>
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
                            <button class="btn btn-default" type="button" onclick="fn_bus_contrib_rd('dlg_contri')">
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
                                <h2>Busqueda Por Número de R.D.</h2>
                        </header>
                    </div>
                </section>
                </div>
            </div>
            <div class="col-xs-8" style="padding: 0px; margin-top: 5px">
                <div class="col-xs-10" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Número Hoja Liquidación &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div class=""  >
                            <input id="dlg_bus_num" type="text"  class="form-control" style="height: 32px; " maxlength="7" onkeypress="return soloNumeroTab(event);" >
                        </div>
                    </div>
                </div>
                <div class='col-lg-2'style="padding: 0px;" >
                    <button type="button" class="btn btn-labeled bg-color-green txt-color-white" onclick="buscar_rd(4)">
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
                                <h2>Busqueda de R.D. Por Fechas</h2>
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
                    <button type="button" class="btn btn-labeled bg-color-green txt-color-white" onclick="buscar_rd(5)">
                        <span class="btn-label"><i class="glyphicon glyphicon-search"></i></span>Buscar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class='cr_content col-xs-12'>
       
        <div class="col-xs-12" style="padding: 0px; margin-top: 10px">
            <article class="col-xs-11" style=" padding: 0px !important">
                    <table id="table_rd"></table>
                    <div id="pager_table_rd"></div>
            </article>
            <div class="col-xs-1 text-center" style="padding-right: 0px;">
                @if( $permisos[0]->btn_new ==1 )
                    <button class="btn bg-color-green txt-color-white btn-circle btn-xl" onclick="fn_sel_hoja();" >
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
        $("#li_reso_deter").addClass('cr-active');
        
        jQuery("#table_rd").jqGrid({
            url: 'trae_rd/'+$("#selantra").val()+'/0/0/0/0',
            datatype: 'json', mtype: 'GET',
            height: '280px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_rd', 'N° R.D.', 'contribuyente','Registro','Fec. Notificación','Ver'],
            rowNum: 20, sortname: 'id_rd', sortorder: 'desc', viewrecords: true, caption: 'Resoluciones de Determinación', align: "center",
            colModel: [
                {name: 'id_rd', index: 'id_rd', hidden: true},
                {name: 'nro_rd', index: 'nro_rd', align: 'center', width: 8},
                {name: 'contribuyente', index: 'contribuyente', align: 'left', width: 25},
                {name: 'fec_reg', index: 'fec_reg', align: 'center', width: 10},
                {name: 'fecha_notificacion', index: 'fecha_notificacion', align: 'center', width: 12},
                {name: '', index: '', align: 'center', width: 10},
            ],
            pager: '#pager_table_rd',
            rowList: [20, 50],
            gridComplete: function () {
                    var idarray = jQuery('#table_rd').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_rd').jqGrid('getDataIDs')[0];
                            $("#table_rd").setSelection(firstid);    
                        }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){}
        });
        jQuery("#table_sel_hojas").jqGrid({
            url: 'trae_hojas_liq/'+$("#selantra").val()+'/0/0/0/0/1',
            datatype: 'json', mtype: 'GET',
            height: '280px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_hoja_liq', 'N° Hoja Liq.', 'contribuyente', 'Carta Req. Relacionada','Registro','Notificacion','Días Plazo','Días Trasncurridos','Ver','N° R.D.','Generar R.D.'],
            rowNum: 20, sortname: 'id_hoja_liq', sortorder: 'desc', viewrecords: true, caption: 'Hojas de Liquidación', align: "center",
            colModel: [
                {name: 'id_hoja_liq', index: 'id_hoja_liq', hidden: true},
                {name: 'nro_hoja', index: 'nro_hoja', align: 'center', width: 80},
                {name: 'contribuyente', index: 'contribuyente', align: 'left', width: 330},
                {name: 'nro_car', index: 'nro_car', align: 'center', width: 100},
                {name: 'fec_reg', index: 'fec_reg', align: 'center', width: 100},
                {name: 'fecha_notificacion', index: 'fecha_notificacion', hidden: true},
                {name: 'dia_plazo', index: 'dia_plazo', align: 'center', width: 80},
                {name: 'dias', index: 'dias', align: 'center', width: 80},
                {name: '', index: '', align: 'center', width: 100,hidden: true},
                {name: 'est', index: 'est', align: 'center', width: 100},
                {name: '', index: '', align: 'center', width: 150}
            ],
            pager: '#pager_table_sel_hojas',
            rowList: [20, 50],
            gridComplete: function () {
                    var idarray = jQuery('#table_sel_hojas').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_sel_hojas').jqGrid('getDataIDs')[0];
                            $("#table_sel_hojas").setSelection(firstid);    
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
                        jQuery('#table_contrib').jqGrid('bindKeys', {"onEnter":function( rowid ){fn_bus_lis_rd(rowid);} } ); 
                    }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){fn_bus_lis_rd(Id)}
        });
        
     
        
        var globalvalidador=0;
        $("#dlg_contri").keypress(function (e) {
            if (e.which == 13) {
                if(globalvalidador==0)
                {
                    fn_bus_contrib_rd("dlg_contri");
                    globalvalidador=1;
                }
                else
                {
                    globalvalidador=0;
                }
                
            }
        });
       
        $("#dlg_contri_hoja").keypress(function (e) {
            if (e.which == 13) {
                if(globalvalidador==0)
                {
                    fn_bus_contrib_rd("dlg_contri_hoja");
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
                buscar_rd(4);
            }
        });
        $("#dlg_bus_num_hoja").keypress(function (e) {
            if (e.which == 13) {
                buscar_rd(3);
            }
        });
    });
</script>
@stop
<script src="{{ asset('archivos_js/fiscalizacion/res_deter.js') }}"></script>

<div id="dlg_bus_contr" style="display: none;">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
        <table id="table_contrib"></table>
        <div id="pager_table_contrib"></div>
    </article>
</div> 
<div id="dlg_sel_hoja" style="display: none;">
    
    <div class='cr_content col-xs-12 ' style="margin-bottom: 10px;">
        <div  class="col-xs-12 cr-body" >
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px; margin-top: 0px;">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-info"></i> </span>
                                <h2>Seleccione Hoja de Liquidación Relacionada::..</h2>
                        </header>
                    </div>
                </section>
                <div class="col-xs-6" style="padding: 0px;">
                    <div class="col-xs-10" style="padding: 0px;">
                        <div class="input-group input-group-md">
                            <span class="input-group-addon">Número Hoja Liquidación &nbsp;<i class="fa fa-hashtag"></i></span>
                            <div class=""  >
                                <input id="dlg_bus_num_hoja" type="text"  class="form-control" style="height: 32px; " maxlength="7" onkeypress="return soloNumeroTab(event);" >
                            </div>
                        </div>
                    </div>
                    <div class='col-lg-2'style="padding: 0px;" >
                        <button type="button" class="btn btn-labeled bg-color-green txt-color-white" onclick="buscar_rd(3)">
                            <span class="btn-label"><i class="glyphicon glyphicon-search"></i></span>Buscar
                        </button>
                    </div>
                </div>
                <div class="col-xs-10" style="padding: 0px; margin-top: 10px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Contribuyente a Fiscalizar &nbsp;<i class="fa fa-male"></i></span>
                        <div>
                            <input id="dlg_contri_hoja_hidden" type="hidden" value="0">
                            <input id="dlg_contri_hoja" type="text"  class="form-control" style="height: 32px;font-size: 0.9em;width: 102% !important" autofocus="focus" >
                        </div>
                        <span class="input-group-btn" style="font-size: 13px;">
                            <button class="btn btn-default" type="button" onclick="fn_bus_contrib_rd('dlg_contri_hoja')">
                                <i class="glyphicon glyphicon-search"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="col-xs-12" style="padding: 0px; margin-top: 0px; margin-bottom: 20px">
        <table id="table_sel_hojas"></table>
        <div id="pager_table_sel_hojas"></div>
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
                                <h2>Ingresar Fecha de Notificacíon de Carta.::..</h2>
                        </header>
                    </div>
                </section>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">N° Hoja de Liq. &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div class=""  >
                            <input id="input_num_rd" type="text"  class="form-control" style="height: 32px; " disabled="" >
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
	

<div id="dlg_motivacion" style="display: none;">
    
    <div class='cr_content col-xs-12 ' style="margin-bottom: 10px;">
        <div class="col-xs-12 cr-body" style="padding-left: 0px;padding-right: 10px;" >
            <div class="col-xs-12" style="padding: 0px; margin-top: 0px;">
                <textarea name="ckeditor" id="ckeditor" >
                    Este es el textarea que es modificado por la clase ckeditor
                </textarea> 
               
            </div>
        </div>
    </div>
</div> 



@endsection




