@extends('layouts.app')
@section('content')

    <section id="widget-grid" class="">
        <div class='cr_content col-xs-12'>
            <div class="col-xs-12">
                <h1 class="txt-color-green"><b>Reportes de Archivo...</b></h1>
            </div>
        </div>
        <!-- row -->
        <div class="row">

            <div class="col-sm-12">

                <div class="well">
                    
                    <table class="table table-striped table-forum">
                        
                        <tbody>

                        <!-- TR -->
                        <tr>
                            <td class="text-center" style="width: 80px;"><i class="fa fa-male fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_ar_reportes(1);" >
                                        Documentos Por Contribuyente
                                    </a>
                                    <small>Descripción reporte: Lista de todos los Documentos Pertenecientes a un Contribuyente</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                        <tr>
                            <td class="text-center" style="width: 80px;"><i class="fa fa-plus fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_ar_reportes(2);" >
                                        Personas Fallecidas
                                    </a>
                                    <small>Descripción reporte: Contribuyentes cuyo ultimo documento es acta de defunción</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                        
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-crosshairs fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_ar_reportes(3);" >
                                        Contribuyentes que no tributan por más de 7 años
                                    </a>
                                    <small>Descripción reporte: Muestra Contribuyetes que no tienen ningun Documento en los Últimos 7 años</small>
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-home fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_ar_reportes(4);" >
                                        Contribuyentes por Direccion
                                    </a>
                                    <small>Descripción reporte: Muestra Contribuyetes que registran una dirección en su ultimo documento presentado</small>
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-info fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_ar_reportes(5);" >
                                        Contribuyentes Inafecto
                                    </a>
                                    <small>Descripción reporte: Todos los Contribuyentes inafectos a la fecha</small>
                                </h4>
                            </td>
                        </tr>
                        
                         <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-dollar fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_ar_reportes(6);" >
                                        Contribuyentes con Compra y Venta
                                    </a>
                                    <small>Descripción reporte: Muestra todos los contribuyentes que tengan una Compra o Venta en su Declaración de Inscripción </small>
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-gift fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_ar_reportes(7);" >
                                        Contribuyentes con Donaciones
                                    </a>
                                    <small>Descripción reporte: Muestra todos los contribuyentes que tengan Donaciones en su Declaración de Inscripción </small>
                                </h4>
                            </td>
                        </tr>
                        
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-refresh fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_ar_reportes(8);" >
                                        Contribuyentes con Transferencias
                                    </a>
                                    <small>Descripción reporte: Muestra todos los contribuyentes que tengan Transferencias en su Declaración de Inscripción </small>
                                </h4>
                            </td>
                        </tr>
                        
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-share fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_ar_reportes(9);" >
                                        Contribuyentes con Sucesión Intestada
                                    </a>
                                    <small>Descripción reporte: Muestra todos los contribuyentes que tengan  Sucesión Intestada en su Declaración de Inscripción </small>
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-file-text-o fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_ar_reportes(10);" >
                                        Contribuyentes con Sucesión Testamentaria
                                    </a>
                                    <small>Descripción reporte: Muestra todos los contribuyentes que tengan  Sucesión Testamentaria en su Declaración de Inscripción </small>
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-child fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_ar_reportes(11);" >
                                        Contribuyentes con Anticipo de Legítima
                                    </a>
                                    <small>Descripción reporte: Muestra todos los contribuyentes que tengan Anticipo de Legítima en su Declaración de Inscripción </small>
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-users fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_ar_reportes(12);" >
                                        Contribuyentes con Subdivisión
                                    </a>
                                    <small>Descripción reporte: Muestra todos los contribuyentes que tengan Subdivisión en su Declaración de Inscripción </small>
                                </h4>
                            </td>
                        </tr>
                         <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-user fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_ar_reportes(13);" >
                                        Contribuyentes con Independización
                                    </a>
                                    <small>Descripción reporte: Muestra todos los contribuyentes que tengan Independización en su Declaración de Inscripción </small>
                                </h4>
                            </td>
                        </tr>
                         <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-file-o fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_ar_reportes(14);" >
                                        Documentos: Declaración de descargo
                                    </a>
                                    <small>Descripción reporte: Muestra todos los contribuyentes que tengan Declaración de descargo </small>
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-file-o fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_ar_reportes(15);" >
                                        Rectificatoria Unificación
                                    </a>
                                    <small>Descripción reporte: Muestra todos los contribuyentes que realizaron una rectificación por Unificación </small>
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-file-o fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_ar_reportes(16);" >
                                        Rectificatoria Subdivisión
                                    </a>
                                    <small>Descripción reporte: Muestra todos los contribuyentes que realizaron una rectificación por Subdivisión </small>
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-file-o fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_ar_reportes(17);" >
                                        Rectificatoria Aumento de valor
                                    </a>
                                    <small>Descripción reporte: Muestra todos los contribuyentes que realizaron una rectificación por Aumento de valor </small>
                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-users fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_ar_reportes(18);" >
                                        Avance por Usuario
                                    </a>
                                    <small>Descripción reporte: Muestra todos expedientes avanzados por un usuario </small>
                                </h4>
                            </td>
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
        $("#menu_archivo").show();
        $("#li_rep_arch").addClass('cr-active');
        $("#dlg_num_exp").keypress(function (e) {
            if (e.which == 13) {
                busqueda(2);
            }
        });
        var globalvalidador=0;
        $("#dlg_contrib").keypress(function (e) {
            if (e.which == 13) {
                if(globalvalidador==0)
                {
                    fn_bus_contrib_arch("dlg_contrib");
                    globalvalidador=1;
                }
                else
                {
                    globalvalidador=0;
                }
                
            }
        });
        contrib_global=0;
        jQuery("#table_contrib").jqGrid({
            url: 'grid_contrib_arch?dat=0',
            datatype: 'json', mtype: 'GET',
            height: '300px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_contrib','DNI/RUC','contribuyente','Dom Fiscal','Expediente'],
            rowNum: 20, sortname: 'nombres', sortorder: 'asc', viewrecords: true, caption: 'Contribuyentes', align: "center",
            colModel: [
                {name: 'id_contrib', index: 'id_contrib', hidden: true},
                {name: 'nro_doc', index: 'nro_doc', align: 'center',width: 100},
                {name: 'contribuyente', index: 'contribuyente', align: 'left',width: 260},
                {name: 'dom_fiscal', index: 'dom_fiscal', align: 'left',width: 260},
                {name: 'nro_expediente', index: 'nro_expediente', align: 'left',width: 100},
                
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
                        jQuery('#table_contrib').jqGrid('bindKeys', {"onEnter":function( rowid ){fn_bus_contrib_list_arch(rowid);} } ); 
                    }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){fn_bus_contrib_list_arch(Id)}
        });
    });
</script>
@stop
<script src="{{ asset('archivos_js/archivo/reportes.js') }}"></script>
<div id="dlg_bus_contr" style="display: none;">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
        <table id="table_contrib"></table>
        <div id="pager_table_contrib"></div>
    </article>
</div> 
<div id="dialog_doc_contri" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <!-- widget div-->
                <div class="row" style="padding: 10px 30px;">
                    <div class="col-xs-8" style="padding: 0px; ">
                        <div class="input-group input-group-md" style="width: 100%">
                            <span class="input-group-addon" style="width: 165px">N° de Expediente &nbsp;<i class="fa fa-file-archive-o"></i></span>
                            <div>
                                <input id="dlg_num_exp" type="text"  class="form-control" style="height: 32px;" maxlength="30">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12" style="padding: 0px; margin-top: 10px ">
                        <div class="input-group input-group-md" style="width: 100%">
                            <span class="input-group-addon" style="width: 165px">Contribuyente &nbsp;<i class="fa fa-male"></i></span>
                            <div>
                                <input id="id_contrib_hidden" name="id_contrib_hidden" type="hidden" value="0"/>
                                <input id="dlg_contrib" type="text"  class="form-control" style="height: 32px;font-size: 0.9em;width: 101% !important" autofocus="focus" >
                            </div>
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" onclick="fn_bus_contrib_arch('dlg_contrib')">
                                    <i class="glyphicon glyphicon-search"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- end widget div -->
            </div>
        </div>
    </div>
</div>
<div id="dialog_por_dir" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <!-- widget div-->
                <div class="row" style="padding: 10px 30px;">
                    <div class="col-xs-12" style="padding: 0px; ">
                        <div class="input-group input-group-md" style="width: 100%">
                            <span class="input-group-addon" style="width: 165px">Dirección &nbsp;<i class="fa fa-home"></i></span>
                            <div>
                                <input id="dlg_bus_dir" type="text"  class="form-control" style="height: 32px;" maxlength="30">
                            </div>
                        </div>
                    </div>
                    
                </div>
                <!-- end widget div -->
            </div>
        </div>
    </div>
</div>
<div id="dialog_por_usu" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <!-- widget div-->
                <div class="row" style="padding: 10px 30px;">
                    <div class="col-xs-12" style="padding: 0px; ">
                        <div class="input-group input-group-md" style="width: 100%">
                            <span class="input-group-addon" style="width: 165px">Usuario &nbsp;<i class="fa fa-male"></i></span>
                            <div>
                                <select id="sel_usu" class="form-control" style="height: 32px;">
                                    <option value="170">
                                        MEDINA VILLEGAS RAITH JOSEMAR
                                    </option>
                                    <option value="166">
                                        GAMBOA VEGA ELARD IVAN
                                    </option> 
                                    <option value="168">
                                        COLQUE SPARROW DONOVAN RAUL
                                    </option> 
                                    <option value="167">
                                        ZUÑIGA RUIZ ADRIANA LIERTAD
                                    </option> 
                                </select>
                            </div>
                        </div>
                    </div>
                   
                    <div class="col-xs-12" style="padding: 0px; margin-top: 10px;">
                        <div class="input-group input-group-md" style="width: 100%">
                            <span class="input-group-addon" style="width: 165px">Fecha inicio &nbsp;<i class="fa fa-calendar"></i></span>
                            <div>
                            <input id="fec_ini" name="dlg_fec" type="text"   class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 32px; width: 100%" placeholder="--/--/----" value="{{date('01/m/Y')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12" style="padding: 0px; margin-top: 10px; ">
                        <div class="input-group input-group-md" style="width: 100%">
                            <span class="input-group-addon" style="width: 165px">Fecha fin &nbsp;<i class="fa fa-calendar"></i></span>
                            <div>
                            <input id="fec_fin" name="dlg_fec" type="text"   class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 32px; width: 100%" placeholder="--/--/----" value="{{date('d/m/Y')}}">
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
                <!-- end widget div -->
            </div>
        </div>
    </div>
</div>

@endsection




