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
                                <h4><a href="#" onclick="" id="titulo_r3">
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
                                <h4><a href="#" onclick="" id="titulo_r4">
                                        REPORTE 4: Estado de Resolucion de Determinación.
                                    </a>
                                    <small>Muestra el estado en el que se encuentra una Resolución de Determinación.</small>
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

@endsection