@extends('layouts.app')
@section('content')

    <section id="widget-grid" class="">
        
        <!-- row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="well">
                    <table class="table table-striped table-forum">
                        <thead>
                        <tr>
                            <th colspan="2" style="width: 100%;">REPORTES</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- TR -->
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-group fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_rep_fisca(1);" id="titulo_r1">
                                        REPORTE 1: Contribuyentes Fiscalizados.
                                    </a>
                                    <small>Lista de Contribiyentes fiscalizados filtrados por año.</small>
                                </h4>
                            </td>
                        </tr>  
                        <!-- end TR -->
                        <!-- TR -->
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-home fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_rep_fisca(2);" id="titulo_r2">
                                        REPORTE 2: M2 Derterminados X Fiscalizados.
                                    </a>
                                    <small>Muestra diferencia de los M2 declarados contra los M2 medidos en la Fiscalización.</small>
                                </h4>
                            </td>
                        </tr>  
                        <!-- end TR -->
                        <!-- TR -->
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-file-archive-o fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_rep_fisca(3);" id="titulo_r3">
                                        REPORTE 3: Estado de Hoja de Liquidación.
                                    </a>
                                    <small>Muestra el estado en el que se encuentra una hoja de liquidacion.</small>
                                </h4>
                            </td>
                        </tr>  
                        <!-- end TR -->
                        <!-- TR -->
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-file-archive-o fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_rep_fisca(4);" id="titulo_r4">
                                        REPORTE 4: Estado de Resolución de Determinación.
                                    </a>
                                    <small>Muestra el estado en el que se encuentra una Resolución de Determinación.</small>
                                </h4>
                            </td>
                        </tr>  
                        <!-- end TR -->
                        <!-- TR -->
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-file-archive-o fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_rep_fisca(5);" id="titulo_r4">
                                        REPORTE 5: RD Enviado a Ejecución Coactiva.
                                    </a>
                                    <small>Muestra Resolución de Determinación enviadas a coactiva.</small>
                                </h4>
                            </td>
                        </tr>  
                        <!-- end TR -->
                        <!-- TR -->
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-file-archive-o fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_rep_fisca(6);" id="titulo_r4">
                                        REPORTE 6: Impuesto predial producto de la Fiscalizacion.
                                    </a>
                                    <small>Muestra Impuesto.</small>
                                </h4>
                            </td>
                        </tr>  
                        <!-- end TR -->
                        <!-- TR -->
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-file-archive-o fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_rep_fisca(7);" id="titulo_r4">
                                        REPORTE 7: Lista de Declaraciones Juradas por Fiscalización.
                                    </a>
                                    <small>Muestra Pu o Pr creados por fiscalizacion</small>
                                </h4>
                            </td>
                        </tr>  
                        <!-- end TR -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- end row -->
    </section>
@section('page-js-script')

<script type="text/javascript">
    $(document).ready(function () {
        $("#menu_fisca").show();
        $("#li_rep_fisca").addClass('cr-active');
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
                        jQuery('#table_contrib').jqGrid('bindKeys', {"onEnter":function( rowid ){fn_bus_contrib_list_rep_fis(rowid);} } ); 
                    }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){fn_bus_contrib_list_rep_fis(Id)}
        });
        var globalvalidador=0;
        $("#dlg_contri").keypress(function (e) {
            if (e.which == 13) {
                if(globalvalidador==0)
                {
                    fn_bus_contrib_rep_fis("dlg_contri");
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
<script src="{{ asset('archivos_js/fiscalizacion/reporte.js') }}"></script>
<div id="dlg_bus_contr" style="display: none;">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
        <table id="table_contrib"></table>
        <div id="pager_table_contrib"></div>
    </article>
</div>

<div id="dialog_contri_fiscalizados" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <div class="row" style="padding-left: 15px;padding-right: 35px">
                    <div class="col-xs-12" style="padding: 0px; ">
                        <div class="input-group input-group-md col-xs-12">
                            <span class="input-group-addon" style="width:190px">SELECCIONAR AÑO: &nbsp;<i class="fa fa-calendar"></i></span>
                        <div>
                            <select id='selantra_r0' class="form-control col-lg-12" style="padding-left:15px; width: 100%">
                                @foreach ($anio_tra as $anio)
                                    <option value='{{$anio->anio}}' >{{$anio->anio}}</option>
                                @endforeach
                            </select>
                        </div>
                        </div>
                    </div>
                    <div class="col-xs-12" style="padding: 0px; margin-top: 5px">
                        <div class="input-group input-group-md col-xs-12">
                            <span class="input-group-addon" style="width:190px">POR ZONAS: &nbsp;<i class="fa fa-map"></i></span>
                        <div>
                                <input id="hidden_dlg_bus_zonas" type="hidden" value="0">
                                <input id="dlg_bus_zonas" type="text"  class="form-control" style="height: 32px;font-size: 0.9em;width: 100% !important;padding-left:15px;" placeholder="TODOS">
                        </div>
                        </div>
                    </div>
                    <div class="col-xs-12" style="padding: 0px; margin-top: 5px">
                        <div class="input-group input-group-md col-xs-12">
                            <span class="input-group-addon" style="width:190px">Tipo Predio: &nbsp;<i class="fa fa-list"></i></span>
                        <div>
                            <select id='sel_tip_1' class="form-control col-lg-12" style="padding-left:15px; width: 100%">
                                <option value='0' >TODOS</option>
                                <option value='1' >URBANO</option>
                                <option value='2' >RUSTICO</option>
                            </select>
                        </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<div id="dialog_m2" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <div class="row" style="padding-left: 15px;padding-right: 35px">
                    <div class="col-xs-12" style="padding: 0px; ">
                        <div class="input-group input-group-md col-xs-12">
                            <span class="input-group-addon" style="width:190px">SELECCIONAR AÑO: &nbsp;<i class="fa fa-calendar"></i></span>
                        <div>
                            <select id='selantra_r0' class="form-control col-lg-12" style="padding-left:15px; width: 100%">
                                @foreach ($anio_tra as $anio)
                                    <option value='{{$anio->anio}}' >{{$anio->anio}}</option>
                                @endforeach
                            </select>
                        </div>
                        </div>
                    </div>
                    <div class="col-xs-12" style="padding: 0px; margin-top: 5px">
                        <div class="input-group input-group-md col-xs-12">
                            <span class="input-group-addon" style="width:190px">POR ZONAS: &nbsp;<i class="fa fa-map"></i></span>
                        <div>
                                <input id="hidden_dlg_bus_zonas_2" type="hidden" value="0">
                                <input id="dlg_bus_zonas_2" type="text"  class="form-control" style="height: 32px;font-size: 0.9em;width: 100% !important;padding-left:15px;"  placeholder="TODOS">
                        </div>
                        </div>
                    </div>
                    <div class="col-xs-12" style="padding: 0px; margin-top: 5px">
                        <div class="input-group input-group-md col-xs-12">
                            <span class="input-group-addon" style="width:190px">Tipo Predio: &nbsp;<i class="fa fa-list"></i></span>
                        <div>
                            <select id='sel_tip_2' class="form-control col-lg-12" style="padding-left:15px; width: 100%">
                                <option value='0' >TODOS</option>
                                <option value='1' >URBANO</option>
                                <option value='2' >RUSTICO</option>
                            </select>
                        </div>
                        </div>
                    </div>
                    <div class="col-xs-12" style="padding: 0px;margin-top: 10px ">
                        <div class="input-group input-group-md" style="width: 100%">
                            <span class="input-group-addon" style="width:190px">Contribuyente &nbsp;<i class="fa fa-male"></i></span>
                            <div>
                                <input id="dlg_contri_hidden" type="hidden" value="0">
                                <input id="dlg_contri" type="text"  class="form-control" style="height: 32px;font-size: 0.9em;width: 100% !important;padding-left:15px;" autofocus="focus" >
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<div id="dialog_estado_hoja_liq" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <div class="row" style="padding-left: 15px;padding-right: 35px">
                    
                    <div class="row" style="padding: 5px 30px;">
                        <div class="col-xs-12">
                            <div class="input-group input-group-md">
                                <span class="input-group-addon" style="width: 165px">Año <i class="fa fa-cogs"></i></span>
                                    <div class="icon-addon addon-md">
                                        <select id='select_anio_hoja_liq' class="form-control col-lg-8" style="height: 32px; width: 90%" onchange="callfilltab()">
                                            @foreach ($anio_tra as $anio_con)
                                                <option value='{{$anio_con->anio}}' >{{$anio_con->anio}}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                            </div>
                        </div>                       
                    </div>
                    <div class="row" style="padding: 5px 30px;">
                        <div class="col-xs-12">
                            <div class="input-group input-group-md">
                                <span class="input-group-addon" style="width: 165px">ESTADO <i class="fa fa-cogs"></i></span>
                                    <div class="icon-addon addon-md">
                                          <select id='select_estado_hl' class="form-control col-lg-8" >
                                            <option value='0'>NO NOTIFICADO </option>
                                            <option value='1'>NOTIFICADO </option>
                                            <option value='2'>NO PAGADO </option>
                                            <option value='3'>PAGADO </option>
                                                                             
                                          </select>                                 
                                    </div>
                            </div>
                        </div>
                    </div>
                        
                    </div>
                </div>
            </div>
        </div>
</div>

<div id="dialog_estado_resolucion_det" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <div class="row" style="padding-left: 15px;padding-right: 35px">
                    
                    <div class="row" style="padding: 5px 30px;">
                        <div class="col-xs-12">
                            <div class="input-group input-group-md">
                                <span class="input-group-addon" style="width: 165px">Año <i class="fa fa-cogs"></i></span>
                                    <div class="icon-addon addon-md">
                                        <select id='select_anio_rd' class="form-control col-lg-8" style="height: 32px; width: 90%" onchange="callfilltab()">
                                            @foreach ($anio_tra as $anio_rd)
                                                <option value='{{$anio_rd->anio}}' >{{$anio_rd->anio}}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                            </div>
                        </div>                       
                    </div>
                    <div class="row" style="padding: 5px 30px;">
                        <div class="col-xs-12">
                            <div class="input-group input-group-md">
                                <span class="input-group-addon" style="width: 165px">ESTADO <i class="fa fa-cogs"></i></span>
                                    <div class="icon-addon addon-md">
                                          <select id='select_estado_rd' class="form-control col-lg-8" >
                                            <option value='0'>NO NOTIFICADO </option>
                                            <option value='1'>NOTIFICADO </option>
                                            <option value='2'>NO PAGADO </option>
                                            <option value='3'>PAGADO </option>
                                                                             
                                          </select>                                 
                                    </div>
                            </div>
                        </div>
                    </div>
                        
                    </div>
                </div>
            </div>
        </div>
</div>
<div id="dialog_estado_resolucion_det_coactivo" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <div class="row" style="padding-left: 15px;padding-right: 35px">
                    
                    <div class="row" style="padding: 5px 30px;">
                        <div class="col-xs-12">
                            <div class="input-group input-group-md">
                                <span class="input-group-addon" style="width: 165px">Año <i class="fa fa-cogs"></i></span>
                                    <div class="icon-addon addon-md">
                                        <select id='select_anio_rd_coactivo' class="form-control col-lg-8" style="height: 32px; width: 90%" onchange="callfilltab()">
                                            @foreach ($anio_tra as $anio_rd_co)
                                                <option value='{{$anio_rd_co->anio}}' >{{$anio_rd_co->anio}}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                            </div>
                        </div>                       
                    </div>
                   
                        
                    </div>
                </div>
            </div>
        </div>
</div>
<div id="dialog_impuesto_6" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <div class="row" style="padding-left: 15px;padding-right: 35px">
                    
                    <div class="row" style="padding: 5px 30px;">
                        <div class="col-xs-12">
                            <div class="input-group input-group-md">
                                <span class="input-group-addon" style="width: 165px">Año <i class="fa fa-cogs"></i></span>
                                    <div class="icon-addon addon-md">
                                        <select id='select_impuesto_6' class="form-control col-lg-8" style="height: 32px; width: 90%" onchange="callfilltab()">
                                            @foreach ($anio_tra as $anio_6)
                                                <option value='{{$anio_6->anio}}' >{{$anio_6->anio}}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                            </div>
                        </div>                       
                    </div>
                   
                        
                    </div>
                </div>
            </div>
        </div>
</div>

<div id="dialog_pu_pr" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <div class="row" style="padding-left: 15px;padding-right: 35px">
                    <div class="col-xs-12" style="padding: 0px; ">
                        <div class="input-group input-group-md col-xs-12">
                            <span class="input-group-addon" style="width:190px">SELECCIONAR AÑO: &nbsp;<i class="fa fa-calendar"></i></span>
                        <div>
                            <select id='sel_pred_fis' class="form-control col-lg-12" style="padding-left:15px; width: 100%">
                                @foreach ($anio_tra as $anio_6)
                                    <option value='{{$anio_6->anio}}' >{{$anio_6->anio}}</option>
                                @endforeach
                            </select>
                        </div>
                        </div>
                    </div>
                 
                    <div class="col-xs-12" style="padding: 0px; margin-top: 5px">
                        <div class="input-group input-group-md col-xs-12">
                            <span class="input-group-addon" style="width:190px">Tipo Predio: &nbsp;<i class="fa fa-list"></i></span>
                        <div>
                            <select id='sel_tip_pred_fis' class="form-control col-lg-12" style="padding-left:15px; width: 100%">
                                <option value='1' >URBANO</option>
                                <option value='2' >RUSTICO</option>
                            </select>
                        </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection