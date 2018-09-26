@extends('layouts.app')
@section('content')
<input type="hidden" id="per_imp" value="{{$permisos[0]->btn_imp}}"/>
<section id="widget-grid" class=""> 
    <div class='cr_content col-xs-12 '>
        <div class="col-xs-9">
            <h1 class="txt-color-green"><b>Registro de Ordenanzas...</b></h1>
        </div>
        <div class="col-xs-3" style="margin-top: 5px; padding-right: 23px;">
            
        </div>
        <div class="col-xs-12 cr-body" >
                      
            <div class="col-xs-4" style="padding: 0px; margin-top: 5px">
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
                    <button type="button" class="btn btn-labeled bg-color-green txt-color-white" onclick="fn_bus_ani(0,4)">
                        <span class="btn-label"><i class="glyphicon glyphicon-search"></i></span>Buscar
                    </button>
                </div>
            </div>
            
            <div class="col-xs-12" style="padding: 0px; margin-top: 10px">
            <article class="col-xs-11" style=" padding: 0px !important">
                    <table id="table_ordenanzas"></table>
                    <div id="pager_table_ordenanzas"></div>
            </article>
            <div class="col-xs-1 text-center" style="padding-right: 0px;">
                @if( $permisos[0]->btn_new ==1 )
                    <button class="btn bg-color-green txt-color-white btn-circle btn-xl" onclick="fn_new(1);" >
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
                @if( $permisos[0]->btn_edit ==1 )
                    <button class="btn bg-color-blue txt-color-white btn-circle btn-xl" onclick="fn_new(2);" >
                        <span  >
                            <i class="glyphicon glyphicon-edit"></i>
                        </span>
                    </button>
                    <label><b>Editar</b></label>
                @else
                    <button class="btn bg-color-blue txt-color-white btn-circle btn-xl" onclick="sin_permiso();" >
                        <span  >
                            <i class="glyphicon glyphicon-edit"></i>
                        </span>
                    </button>
                    <label><b>Editar</b></label>
                @endif
            </div>
            </div>
        </div>
    </div>
</section>
@section('page-js-script')
<script type="text/javascript">
    $(document).ready(function (){
        $("#menu_configuracion").show();
        $("#li_mod_orde").addClass('cr-active')
        jQuery("#table_ordenanzas").jqGrid({
            url: '',
            datatype: 'json', mtype: 'GET',
            height: '320px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_alcab', 'N° Alcabala', 'Comprador', 'Vendedor','Predio', 'Fec. Alcab','Estado','Ver'],
            rowNum: 20, sortname: 'id_alcab', sortorder: 'desc', viewrecords: true, caption: 'Lista de Ordenanzas', align: "center",
            colModel: [
                {name: 'id_alcab', index: 'id_alcab', hidden: true},
                {name: 'nro_alcab', index: 'nro_alcab', align: 'left', width: 30},
                {name: 'id_adqui', index: 'id_cont_compr', align: 'left', width: 120},
                {name: 'id_trans', index: 'id_cont_vend', align: 'left', width: 120},
                {name: 'id_pred', index: 'id_pred', align: 'left', width: 100},
                {name: 'fecha_reg', index: 'fecha_reg', align: 'center', width: 37},
                {name: 'estado', index: 'estado', align: 'center', width: 38},
                {name: 'ver', index: 'ver', align: 'center', width: 50},
            ],
            pager: '#pager_table_ordenanzas',
            rowList: [13, 20],
            gridComplete: function () {
                    var idarray = jQuery('#table_ordenanzas').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_ordenanzas').jqGrid('getDataIDs')[0];
                            $("#table_ordenanzas").setSelection(firstid);    
                        }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){}
        });
        jQuery("#table_ordenanzas_predial").jqGrid({
            url: '',
            datatype: 'json', mtype: 'GET',
            height: '100px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_alcab', 'Periodos', 'Desc. Int.Pred. Nat','Desc. Int.Pred. Jur', 'Multa Nat','Multa Jur','Desc. Int.Multa Nat', 'Desc. Int.Multa Jur'],
            rowNum: 20, sortname: 'id_alcab', sortorder: 'desc', viewrecords: true, caption: 'Lista de Descuento Predial', align: "center",
            colModel: [
                {name: 'id_alcab', index: 'id_alcab', hidden: true},
                {name: 'nro_alcab', index: 'nro_alcab', align: 'left', width: 80},
                {name: 'id_adqui', index: 'id_cont_compr', align: 'left', width: 120},
                {name: 'id_trans', index: 'id_cont_vend', align: 'left', width: 120},
                {name: 'id_pred', index: 'id_pred', align: 'left', width: 120},
                {name: 'id_pred', index: 'id_pred', align: 'left', width: 120},
                {name: 'id_pred', index: 'id_pred', align: 'left', width: 120},
                {name: 'id_pred', index: 'id_pred', align: 'left', width: 120}
            ],
            pager: '#pager_table_ordenanzas_predial',
            rowList: [13, 20],
            gridComplete: function () {
                    var idarray = jQuery('#table_ordenanzas_predial').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_ordenanzas_predial').jqGrid('getDataIDs')[0];
                            $("#table_ordenanzas_predial").setSelection(firstid);    
                        }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){}
        });
        jQuery("#table_ordenanzas_arbitrios").jqGrid({
            url: '',
            datatype: 'json', mtype: 'GET',
            height: '100px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_alcab', 'desc. Arb Nat', 'desc. Arb Jur', 'desc. Int.Arb Nat', 'desc. Int.Arb Nat','Barrido','Recojo','Seguridad','Parques'],
            rowNum: 20, sortname: 'id_alcab', sortorder: 'desc', viewrecords: true, caption: 'Lista de Descuentos Arbitrios', align: "center",
            colModel: [
                {name: 'id_alcab', index: 'id_alcab', hidden: true},
                {name: 'nro_alcab', index: 'nro_alcab', align: 'left', width: 120},
                {name: 'id_adqui', index: 'id_cont_compr', align: 'left', width: 120},
                {name: 'id_trans', index: 'id_cont_vend', align: 'left', width: 120},
                {name: 'id_pred', index: 'id_pred', align: 'left', width: 120},
                {name: 'fecha_reg', index: 'fecha_reg', align: 'center', width: 80},
                {name: 'estado', index: 'estado', align: 'center', width: 80},
                {name: 'ver', index: 'ver', align: 'center', width: 80},
                {name: 'ver', index: 'ver', align: 'center', width: 80}
            ],
            pager: '#pager_table_ordenanzas_arbitrios',
            rowList: [13, 20],
            gridComplete: function () {
                    var idarray = jQuery('#table_ordenanzas_arbitrios').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_ordenanzas_arbitrios').jqGrid('getDataIDs')[0];
                            $("#table_ordenanzas_arbitrios").setSelection(firstid);    
                        }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){}
        });
      
       
    });
</script>
@stop
<script src="{{ asset('archivos_js/ordenanzas/ordenanzas.js') }}"></script>

<div id="dlg_new_ordenanza" style="display: none;">
    <div class='cr_content col-xs-12 ' style="margin-bottom: 10px;">
        <div id="div_adquiere" class="col-xs-12 cr-body" >
            <div class="col-xs-10 col-md-10 col-lg-10" style="padding: 0px;">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-user"></i> </span>
                                <h2>Datos de Ordenanza ::..</h2>
                        </header>
                    </div>
                </section>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 100px">Referencia &nbsp;<i class="fa fa-cog"></i></span>
                        <div class="">
                            <input id="dlg_orde_hidden" type="hidden" value="0">
                            <input id="dlg_orde_refe" type="text"  class="form-control" style="height: 32px; " >
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-6" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 100px">Fecha ini. &nbsp;<i class="fa fa-calendar"></i></span>
                        <div class=""  >
                            <input id="dlg_fec_ini" type="text"  class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 32px; width: 100%" placeholder="--/--/----" value="{{date('d/m/Y')}}" >
                        </div>
                    </div>
                </div>
                <div class="col-xs-6" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 100px">Fecha Fin. &nbsp;<i class="fa fa-calendar"></i></span>
                        <div class=""  >
                            <input id="dlg_fec_fin" type="text"  class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 32px; width: 100%" placeholder="--/--/----" value="{{date('d/m/Y')}}">
                        </div>
                    </div>
                </div>
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 100px">Glosa. &nbsp;<i class="fa fa-list"></i></span>
                        <div class=""  >
                            <textarea id="dlg_glosa_orde" type="text"  class="form-control" style="height: 50px; width: 100%" ></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                
            </div>
            <div id="btn_save_div" class="col-xs-2 text-align-right" style="padding: 0px;padding-top: 153px;">
                <button type="button" class="btn btn-labeled bg-color-green txt-color-white" onclick="save_ordenanza()">
                    <span class="cr-btn-label"><i class="glyphicon glyphicon-save"></i></span>Grabar Ordenanza
                </button>
            </div>
            <div id="btn_mod_div" class="col-xs-2 text-align-right" style="padding: 0px;padding-top: 153px;">
                <button type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="mod_ordenanza()">
                    <span class="cr-btn-label"><i class="glyphicon glyphicon-save"></i></span>Modificar Ordenanza
                </button>
            </div>
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px;">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-user"></i> </span>
                                <h2>Datos Para Predial ::..</h2>
                        </header>
                    </div>
                </section>
                <div class="col-xs-10" style="padding: 0px; margin-bottom: 10px">
                    <article class="col-xs-11" style=" padding: 0px !important">
                        <table id="table_ordenanzas_predial"></table>
                        <div id="pager_table_ordenanzas_predial"></div>
                    </article>
                </div>
                <div class="col-xs-2 text-align-right" style="padding: 0px;">
                    <button type="button" class="btn btn-labeled bg-color-green txt-color-white" onclick="new_desc_pred()">
                        <span class="cr-btn-label"><i class="glyphicon glyphicon-new-window"></i></span>Nuevo Descuento
                    </button>
                </div>
            </div>
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px;">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-user"></i> </span>
                                <h2>Datos Para Arbitrios ::..</h2>
                        </header>
                    </div>
                </section>
                <div class="col-xs-10" style="padding: 0px;">
                    <article class="col-xs-11" style=" padding: 0px !important">
                        <table id="table_ordenanzas_arbitrios"></table>
                        <div id="pager_table_ordenanzas_arbitrios"></div>
                    </article>
                </div>
                <div class="col-xs-2 text-align-right" style="padding: 0px;">
                    <button type="button" class="btn btn-labeled bg-color-green txt-color-white" onclick="">
                        <span class="cr-btn-label"><i class="glyphicon glyphicon-new-window"></i></span>Nuevo Descuento
                    </button>
                </div>
            </div>
            
        </div>
        
    </div>
</div> 
<div id="dlg_new_ordenanza_predial" style="display: none;">
    <div class='cr_content col-xs-12 ' style="margin-bottom: 10px;">
        <div  class="col-xs-12 cr-body" >
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px;">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-cogs"></i> </span>
                                <h2>Años a los que se Aplica el Descuento ::..</h2>
                        </header>
                    </div>
                </section>

                <div class="col-xs-6" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 100px">Año de Inicial <i class="fa fa-calendar"></i></span>
                        <div class="icon-addon addon-md">
                            <select id='anio_ini' class="form-control col-lg-8" style="height: 32px;">
                            @foreach ($anio_tra as $anio)
                            <option value='{{$anio->anio}}' >{{$anio->anio}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 100px">Año de Final <i class="fa fa-calendar"></i></span>
                        <div class="icon-addon addon-md">
                            <select id='anio_ini' class="form-control col-lg-8" style="height: 32px;">
                            @foreach ($anio_tra as $anio)
                            <option value='{{$anio->anio}}' >{{$anio->anio}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12" style="margin-top: 10px;"></div>
            </div>
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px;">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-cogs"></i> </span>
                                <h2>Datos de Descuento Inpuesto Predial ::..</h2>
                        </header>
                    </div>
                </section>
                <div class="col-xs-6" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 100px"> Persona Natural&nbsp;<i class="fa fa-percent"></i></span>
                        <div class="">
                            <input id="dlg_desc_ip_nat" type="text"  class="form-control" style="height: 32px; " placeholder="Porcentaje" onkeypress="return soloNumeroTab(event);">
                        </div>
                    </div>
                </div>
                <div class="col-xs-6" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 100px"> Persona Juridica&nbsp;<i class="fa fa-percent"></i></span>
                        <div class=""   >
                            <input id="dlg_desc_ip_jur" type="text"  class="form-control" style="height: 32px; " placeholder="Porcentaje" onkeypress="return soloNumeroTab(event);">
                        </div>
                    </div>
                </div>
                <div class="col-xs-12" style="margin-top: 10px;"></div>
            </div>
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px;">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-cogs"></i> </span>
                                <h2>Datos de Monto a Cobrar de Multa ::..</h2>
                        </header>
                    </div>
                </section>
                <div class="col-xs-6" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 100px"> Persona Natural&nbsp;<i class="fa fa-hashtag"></i></span>
                        <div class="">
                            <input id="dlg_desc_multa_nat" type="text"  class="form-control" style="height: 32px; " placeholder="valor" onkeypress="return soloNumeroTab(event);">
                        </div>
                    </div>
                </div>
                <div class="col-xs-6" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 100px"> Persona Juridica&nbsp;<i class="fa fa-hashtag"></i></span>
                        <div class=""   >
                            <input id="dlg_desc_multa_jur" type="text"  class="form-control" style="height: 32px; " placeholder="valor" onkeypress="return soloNumeroTab(event);">
                        </div>
                    </div>
                </div>
                <div class="col-xs-12" style="margin-top: 10px;"></div>
            </div>
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px;">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-cogs"></i> </span>
                                <h2>Datos de Descuento Inpuesto Multa ::..</h2>
                        </header>
                    </div>
                </section>
                <div class="col-xs-6" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 100px"> Persona Natural&nbsp;<i class="fa fa-percent"></i></span>
                        <div class="">
                            <input id="dlg_desc_im_nat" type="text"  class="form-control" style="height: 32px; " placeholder="Porcentaje" onkeypress="return soloNumeroTab(event);">
                        </div>
                    </div>
                </div>
                <div class="col-xs-6" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 100px"> Persona Juridica&nbsp;<i class="fa fa-percent"></i></span>
                        <div class=""   >
                            <input id="dlg_desc_im_jur" type="text"  class="form-control" style="height: 32px; " placeholder="Porcentaje" onkeypress="return soloNumeroTab(event);">
                        </div>
                    </div>
                </div>
                <div class="col-xs-12" style="margin-top: 10px;"></div>
            </div>
            
        </div>
        
    </div>
</div> 





@endsection




