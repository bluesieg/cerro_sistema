@extends('layouts.app')
@section('content')
<input type="hidden" id="per_new" value="{{$permisos[0]->btn_new}}"/>
<input type="hidden" id="per_edit" value="{{$permisos[0]->btn_edit}}"/>
<section id="widget-grid" class=""> 
    <div class='cr_content col-xs-12 '>
        <div class="col-xs-9">
            <h1 class="txt-color-green"><b>Ingreso De Fecha de Notificacíon de Ordenes de Pago (OP)</b></h1>
        </div>
        <div class="col-xs-3" style="margin-top: 5px; padding-right: 23px;">
            <div class="input-group input-group-md">
                <span class="input-group-addon">Año de Tramite <i class="fa fa-cogs"></i></span>
                <div class="icon-addon addon-md">
                    <select id='selantra' class="form-control col-lg-8" style="height: 32px;">
                    @foreach ($anio_tra as $anio)
                    <option value='{{$anio->anio}}' >{{$anio->anio}}</option>
                    @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-xs-12 cr-body" >
            
            <div class="col-xs-3" style="padding: 0px; margin-top: 5px">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 0px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-align-justify"></i> </span>
                                <h2>Busqueda por Contribuyente</h2>
                        </header>
                    </div>
                </section>
            </div>
            <div class="col-xs-9" style="padding: 0px; margin-top: 5px">
                <div class="col-xs-3" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Cod. &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div class=""  >
                            <input id="dlg_contri_hidden" type="hidden" value="0">
                        <input id="dlg_dni" type="text"  class="form-control" style="height: 32px; " >
                        </div>
                    </div>
                </div>
                <div class="col-xs-9" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Contribuyente &nbsp;<i class="fa fa-male"></i></span>
                        <div   >
                            <input id="dlg_contri" type="text"  class="form-control" style="height: 32px;font-size: 0.9em;width: 102% !important" autofocus="focus" >
                        </div>
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" onclick="fn_bus_contrib()">
                                <i class="glyphicon glyphicon-search"></i>
                            </button>
                        </span>
                    </div>
                </div>
                
            </div>
            <div class="col-xs-12"></div>
            <div class="col-xs-3" style="padding: 0px; margin-top: 5px">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 0px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-align-justify"></i> </span>
                                <h2>Busqueda por Sector y Manzana</h2>
                        </header>
                    </div>
                </section>
            </div>
            <div class="col-xs-9" style="padding: 0px; margin-top: 5px">
            <div class="col-xs-4" style="padding: 0px;">
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
            <div class="col-xs-4" style="padding: 0px;">
                <div class="input-group input-group-md">
                    <span class="input-group-addon">Manzana &nbsp;<i class="fa fa-list"></i></span>
                    <div class="icon-addon addon-md"  id="dvselmnza">
                        <select id="selmnza" class="form-control" style="height: 32px;" >
                        @foreach ($manzanas as $manzanas)
                        <option value='{{$manzanas->id_mzna}}'>{{$manzanas->codi_mzna}}</option>
                        @endforeach
                        </select>
                    </div>

                </div>
            </div>
             
            
            <div class='col-lg-2' style="padding: 0px;" >
                <button type="button" class="btn btn-labeled bg-color-green txt-color-white" onclick="call_list_contrib(2)">
                    <span class="btn-label"><i class="glyphicon glyphicon-search"></i></span>Buscar
                </button>
            </div>
            
            </div>
            
            
            <div class="col-xs-12"></div>
            <div class="col-xs-3" style="padding: 0px; margin-top: 5px">
                <div>
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 5px; padding: 0px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-align-justify"></i> </span>
                                <h2>Busqueda Por Fechas</h2>
                        </header>
                    </div>
                </section>
                </div>
            </div>
            <div class="col-xs-9" style="padding: 0px; margin-top: 5px">
                <div class="col-xs-4" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Desde &nbsp;<i class="fa fa-calendar"></i></span>
                        <div class=""  >
                            <input id="dlg_bus_fini" type="text"   class="datepicker text-center" data-dateformat='dd/mm/yy' style="height: 32px; width: 100%" placeholder="--/--/----" value="{{date('d/m/Y')}}">
                        </div>
                    </div>
                </div>
                <div class="col-xs-4" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Hasta &nbsp;<i class="fa fa-calendar"></i></span>
                        <div class=""  >
                            <input id="dlg_bus_ffin" type="text" class="datepicker text-center" data-dateformat='dd/mm/yy' style="height: 32px; width: 100%" placeholder="--/--/----" value="{{date('d/m/Y')}}">
                        </div>
                    </div>
                </div>
                <div class='col-lg-2'style="padding: 0px;" >
                    <button type="button" class="btn btn-labeled bg-color-green txt-color-white" onclick="call_list_contrib(3)">
                        <span class="btn-label"><i class="glyphicon glyphicon-search"></i></span>Buscar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class='cr_content col-xs-12'>
        
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; padding: 0px !important">
            <table id="table_op"></table>
            <div id="pager_table_op"></div>
        </article>
        
    </div>
    
</section>
@section('page-js-script')
<script type="text/javascript">
    $(document).ready(function (){
        $("#menu_recaudacion").show();
        $("#li_noti_op").addClass('cr-active')
        jQuery("#table_op").jqGrid({
            url: 'obtiene_op/0/0/0/0/0/0',
            datatype: 'json', mtype: 'GET',
            height: '260px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_gen_fis', 'Nro', 'Fec. Emi', 'Año','N° Documento', 'Contribuyente o Razon Social','OP','Fecha Notificación','Btn','Dias Transcurridos'],
            rowNum: 20, sortname: 'id_gen_fis', sortorder: 'desc', viewrecords: true, caption: 'Lista de Ordenes', align: "center",
            colModel: [
                {name: 'id_gen_fis', index: 'id_gen_fis', hidden: true},
                {name: 'nro_fis', index: 'nro_fis', align: 'center', width: 80},
                {name: 'fec_reg', index: 'fec_reg', align: 'center', width: 70},
                {name: 'anio', index: 'anio', align: 'center', width: 60},
                {name: 'nro_doc', index: 'nro_doc', align: 'center', width: 130},
                {name: 'contribuyente', index: 'contribuyente', align: 'left', width: 250},
                {name: 'op', index: 'op',hidden: true},
                {name: 'fec_notifica', index: 'fec_notifica', align: 'center', width: 70},
                {name: 'op', index: 'op', align: 'left', width: 140},
                {name: 'dias', index: 'dias', align: 'center', width: 80},
            ],
            pager: '#pager_table_op',
            rowList: [13, 20],
            gridComplete: function () {
                    var idarray = jQuery('#table_op').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_op').jqGrid('getDataIDs')[0];
                            $("#table_op").setSelection(firstid);    
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
                        jQuery('#table_contrib').jqGrid('bindKeys', {"onEnter":function( rowid ){fn_bus_contrib_list(rowid);} } ); 
                    }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){fn_bus_contrib_list(Id)}
        });
        jQuery("#table_contrib_bysec").jqGrid({
            url: 'obtiene_con_sec?sec=0&man=0&an=0',
            datatype: 'json', mtype: 'GET',
            height: '400px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_contrib','DNI/RUC','contribuyente','Generar Op'],
            rowNum: 20, sortname: 'id_contrib', sortorder: 'desc', viewrecords: true, caption: 'Contribuyentes por Sector', align: "center",
            colModel: [
                {name: 'id_contrib', index: 'id_contrib', hidden: true},
                {name: 'nro_doc', index: 'nro_doc', align: 'center',width: 160},
                {name: 'contribuyente', index: 'contribuyente', align: 'left',width: 330},
                {name: 'op', index: 'op', align: 'left',width: 170},
            ],
            pager: '#pager_table_contrib_bysec',
            rowList: [13, 20],
            gridComplete: function () {
                    var idarray = jQuery('#table_contrib_bysec').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_contrib_bysec').jqGrid('getDataIDs')[0];
                            $("#table_contrib_bysec").setSelection(firstid);    
                        }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){}
        });
        
        $("#dlg_dni").keypress(function (e) {
            if (e.which == 13) {
                traer_contri_cod("dlg_contri",$("#dlg_dni").val());
            }
        });
        var globalvalidador=0;
        $("#dlg_contri").keypress(function (e) {
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
    });
</script>
@stop
<script src="{{ asset('archivos_js/recaudacion/notifica_op.js') }}"></script>
<div id="dlg_bus_contr" style="display: none;">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
        <table id="table_contrib"></table>
        <div id="pager_table_contrib"></div>
    </article>
</div> 
<div id="dlg_fec_notificacion" style="display: none;">
    
    <div class='cr_content col-xs-12 ' style="margin-bottom: 10px;">
        <div class="col-xs-12 cr-body" style="padding-left: 0px;padding-right: 10px;" >
            <div class="col-xs-12" style="padding: 0px; margin-top: 0px;">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-info"></i> </span>
                                <h2>Ingresar Fecha de Notificacíon de O.P.::..</h2>
                        </header>
                    </div>
                </section>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">N° Orden de Pago. &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div class=""  >
                            <input id="input_num_op" type="text"  class="form-control" style="height: 32px; " disabled="" >
                        </div>
                    </div>
                </div>
                               
                <div class="col-xs-12" style="padding: 0px; margin-top: 10px ">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Fecha Fiscalización &nbsp;<i class="fa fa-calendar"></i></span>
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




