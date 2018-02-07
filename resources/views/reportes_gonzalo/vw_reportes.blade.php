@extends('layouts.app')
@section('content')

    <section id="widget-grid" class="">
        <div class='cr_content col-xs-12'>
            
            <div class="col-lg-3 col-md-6 col-xs-12">
            </div>

            <div class="col-lg-3 col-md-6 col-xs-12">
            </div>
            <div class="col-lg-6 col-md-12 col-xs-12">
             
            </div>
        </div>
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
                                <h4><a href="#" onclick="dlg_reporte_contribuyentes(0);" id="titulo_r1">
                                        Listado de Contribuyentes(Pricos,Mecos,Pecos)
                                    </a>
                                    <small>Descripción reporte 0</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                        <!-- end TR -->

                        <!-- TR -->
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-group fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_reportes_andrea(1);" id="titulo_r1">
                                        REPORTE 1: Listado de datos de los Contribuyentes.
                                    </a>
                                    <small>Descripción reporte 1</small>
                                </h4>
                            </td>
          
                         
                        </tr>  

                        <!-- TR -->
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-group fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_reportes_andrea(2);" id="titulo_r1">
                                        REPORTE 2: Listado de datos de los Contribuyentes y Predios.
                                    </a>
                                    <small>Descripción reporte 2</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                        <!-- end TR -->
                        
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-group fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_reportes_andrea(4);" id="titulo_r1">
                                        REPORTE 4: Reporte de cantidad de contribuyentes y predios por zonas.
                                    </a>
                                    <small>Descripción reporte 4</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                        
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-group fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_reportes_andrea(5);" id="titulo_r1">
                                        REPORTE 5: Reporte Número de Predios de la emisión predial por usos.
                                    </a>
                                    <small>Descripción reporte 5</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-group fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_reportes_andrea(6);" id="titulo_r1">
                                        REPORTE 6: Reporte del Monto de la Base Imponible Afecto y Exonerado.
                                    </a>
                                    <small>Descripción reporte 6</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-group fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_reportes_andrea(8);" id="titulo_r1">
                                        REPORTE 8: Reporte Número de  Contribuyentes de la emision predial Afecto y Exonerado.
                                    </a>
                                    <small>Descripción reporte 6</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-group fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_reportes_andrea(10);" id="titulo_r1">
                                        REPORTE 10: Reporte de cantidad de contribuyentes con deducción de 50 UIT y monto de la Base Imponile.
                                    </a>
                                    <small>Descripción reporte 10</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                        
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-group fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_reportes_andrea(11);" id="titulo_r1">
                                        REPORTE 11: Reporte de Cantidad de Contribuyentes exonerados.
                                    </a>
                                    <small>Descripción reporte 5</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                      
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-group fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_reportes_andrea(13);" id="titulo_r1">
                                        REPORTE 13: Reporte de Impuesto Predial Corriente y no corriente.</a>
                                    <small>Descripción reporte 13</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                        
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-group fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_reportes_andrea(18);" id="titulo_r1">
                                        REPORTE 18: Reporte de Fracionamiento Realizados y cancelados.</a>
                                    <small>Descripción reporte 18</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-group fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_reportes_andrea(101);" id="titulo_r1">
                                        REPORTE 12: Reporte de Cantidad de Contribuyentes según Forma de Adquisición.</a>
                                    <small>Descripción reporte 12</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                         <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-group fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_reportes_andrea(102);" id="titulo_r1">
                                        REPORTE 13: Reporte de Cantidad de Contribuyentes Deudores y no Deudores.</a>
                                    <small>Descripción reporte 13</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                        
                        
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-group fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_reportes_andrea(103);" id="titulo_r1">
                                        REPORTE 13: Reporte General de Caja.</a>
                                    <small>Descripción reporte 13</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                                           
                               
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
        $("#menu_gonza").show();
        $("#li_rep_gonza").addClass('cr-active');
        
        contrib_global=0;
        jQuery("#table_usuario").jqGrid({
            url: 'obtener_usuarios?dat=0',
            datatype: 'json', mtype: 'GET',
            height: '300px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_usu','DNI','Nombre','Usuario'],
            rowNum: 20, sortname: 'ape_nom', sortorder: 'asc', viewrecords: true, caption: 'Usuarios', align: "center",
            colModel: [
                {name: 'id', index: 'id', hidden: true},
                {name: 'dni', index: 'dni', align: 'center',width: 100},
                {name: 'ape_nom', index: 'ape_nom', align: 'center',width: 264},
                {name: 'usuario', index: 'usuario', align: 'center',width: 100},
                
            ],
            pager: '#pager_table_usuario',
            rowList: [13, 20],
            gridComplete: function () {
                    var idarray = jQuery('#table_usuario').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_usuario').jqGrid('getDataIDs')[0];
                            $("#table_usuario").setSelection(firstid);    
                        }
                    if(contrib_global==0)
                    {   contrib_global=1;
                        jQuery('#table_usuario').jqGrid('bindKeys', {"onEnter":function( rowid ){fn_bus_contrib_list_rus(rowid);} } ); 
                    }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){fn_bus_contrib_list_rus(Id)}
        });
var globalvalidador=0;
$("#dlg_usuario").keypress(function (e) {
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
    
function autocompletar_haburb(textbox){
    $.ajax({
        type: 'GET',
        url: 'autocomplete_hab_urba',
        success: function (data) {
            var $datos = data;
            $("#"+ textbox).autocomplete({
                source: $datos,
                focus: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hidden_"+ textbox).val(ui.item.value);
                    $("#" + textbox).attr('maxlength', ui.item.label.length);
                    return false;
                },
                select: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hidden_"+ textbox).val(ui.item.value);
                    
                    return false;
                }
            });
        }
    });
}

</script>
@stop
<script src="{{ asset('archivos_js/reportes_gonzalo/reportes.js') }}"></script>

<div id="dialog_supervisores" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <!-- widget div-->
                <div class="row">
                    <section class="col col-4" style="padding-right:5px;">
                        <label class="label">AÑO:</label>
                        <label class="select">
                            <select id='select_sup_anio' class="form-control col-lg-8">
                                @foreach ($anio_tra as $anio_7)
                                    <option value='{{$anio_7->anio}}' >{{$anio_7->anio}}</option>
                                @endforeach
                            </select><i></i> </label>
                    </section>
                    <section class="col col-4" style="padding-left:5px;padding-right:5px;">
                        <label class="label">SECTOR:</label>
                        <label class="select">
                            <select id='select_sup_sec' class="form-control col-lg-8" onchange="cargar_manzana('select_sup_mz');">
                                @foreach ($sectores as $sector_7)
                                    <option value='{{$sector_7->id_sec}}' >{{$sector_7->sector}}</option>
                                @endforeach
                            </select><i></i> </label>
                    </section>
                    <section class="col col-4" style="padding-left:5px;padding-right:5px;">
                        <label class="label">MANZANA:</label>
                        <label class="select">
                            <select id='select_sup_mz' class="form-control col-lg-8" >
                               
                            </select><i></i> </label>
                    </section>
                </div>
                <!-- end widget div -->
            </div>
        </div>
    </div>
</div>

<div id="dialog_reporte_contribuyentes" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">

                <div class="row" style="padding-left: 15px;padding-right: 35px">
                    
                    <section class="col col-12" style="padding-left:15px;padding-right:5px;">
                        <div class="input-group input-group-md">
                            <span class="input-group-addon" style="width:190px">SELECCIONAR AÑO: &nbsp;<i class="fa fa-calendar"></i></span>
                        <div>
                            <select id='selantra_r0' class="form-control col-lg-6" style="padding-left:15px">
                                @foreach ($anio_tra as $anio)
                                    <option value='{{$anio->anio}}' >{{$anio->anio}}</option>
                                @endforeach
                            </select>
                        </div>
                        </div>
                    </section>
                    
                    <section class="col col-12" style="padding-left:15px;padding-right:5px;">
                        <div class="input-group input-group-md">
                        <span class="input-group-addon" style="width:190px">CANTIDAD DE MINIMA: &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <input id="min" type="min"  class="form-control col-lg-8" value="0" style="padding-left:15px" onkeypress="return soloNumeroTab(event);">
                        </div>
                        </div>
                    </section>
                    
                    <section class="col col-9" style="padding-left:15px;padding-right:5px;">
                        <div class="input-group input-group-md">
                        <span class="input-group-addon" style="width:190px">CANTIDAD DE MAXIMA: &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <input id="max" type="max"  class="form-control col-lg-8" value="50000" style="padding-left:15px" onkeypress="return soloNumeroTab(event);">
                        </div>
                        </div>
                        
                        
                    </section>
                    
                    <section class="col col-3" style="padding-right:15px;padding-right:5px;">
                    
                    <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input"  id="mostrar_todo" onclick="cambiar_estado();" name="mostrar_todo">
                              MOSTRAR TODOS
                            </label>
                        </div>
                     </section>
                    
                    <section class="col col-12" style="padding-left:15px;padding-right:5px;">
                        <div class="input-group input-group-md">
                        <span class="input-group-addon" style="width:190px">CANTIDAD DE REGISTROS: &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <input id="num_reg" type="text"  class="form-control col-lg-8" value="50" style="padding-left:15px" onkeypress="return soloNumeroTab(event);">
                        </div>
                        </div>
                    </section>
                       
                </div>
            </div>
        </div>
    </div>
</div>

<div id="dialog_listado_datos_contribuyente_predios" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <!-- widget div-->
                <div class="row">
                    <section class="col col-6" style="padding-right:5px;">
                        <label class="label">AÑO:</label>
                        <label class="select">
                            <select id='select_sup_anio_dcp' class="form-control col-lg-8">
                                @foreach ($anio_tra as $anio_dcp)
                                    <option value='{{$anio_dcp->anio}}' >{{$anio_dcp->anio}}</option>
                                @endforeach
                            </select><i></i> </label>
                    </section>
                    <section class="col col-6" style="padding-left:5px;padding-right:5px;">
                        <label class="label">SECTOR:</label>
                        <label class="select">
                            <select id='select_sect_dcp' class="form-control col-lg-8">
                                <option value='0'>-- TODOS --</option>
                                @foreach ($sectores as $sector_dcp)
                                    <option value='{{$sector_dcp->id_sec}}' >{{$sector_dcp->sector}}</option>
                                @endforeach
                            </select><i></i> </label>
                    </section>
                    
                </div>
                <!-- end widget div -->
            </div>
        </div>
    </div>
</div>

<div id="dialog_reporte_contribuyentes_exonerados" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <div class="row">
                    <section class="col col-4" style="padding-right:5px;">
                        <label class="label">AÑO:</label>
                        <label class="select">
                            <select id='selantra_5' class="form-control col-lg-8">
                                @foreach ($anio_tra as $anio_5)
                                    <option value='{{$anio_5->anio}}' >{{$anio_5->anio}}</option>
                                @endforeach
                            </select><i></i> </label>
                    </section>
                    <section class="col col-4" style="padding-left:5px;padding-right:5px;">
                        <label class="label">SECTOR:</label>
                        <label class="select">
                            <select id='selsec_5' class="form-control col-lg-8">
                                @foreach ($sectores as $sector_5)
                                    <option value='{{$sector_5->id_sec}}' >{{$sector_5->sector}}</option>
                                @endforeach
                            </select><i></i> </label>
                    </section>
                    <section class="col col-4" id="div_condicion" style="padding-left:5px;padding-right:5px">
                        <label class="label">CONDICIÓN:</label>
                        <label class="select_5">
                            <select id="selcond_5" class="form-control" >
                                @foreach ($condicion as $cond)
                                    <option value='{{$cond->id_exo}}' >{{$cond->desc_exon}}</option>
                                @endforeach
                            </select><i></i> </label>
                    </section>
                   
                </div>
            </div>
        </div>
    </div>
</div>

<div id="dialog_reporte_cantidad_contribuyentes" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <div class="row">
                    <section class="col col-6" style="padding-right:5px;">
                        <label class="label">AÑO:</label>
                        <label class="select">
                            <select id='selantra_7' class="form-control col-lg-8">
                                @foreach ($anio_tra as $anio_7)
                                    <option value='{{$anio_7->anio}}' >{{$anio_7->anio}}</option>
                                @endforeach
                            </select><i></i> </label>
                    </section>
                    <section class="col col-6" style="padding-left:5px;padding-right:5px;">
                        <label class="label">SECTOR:</label>
                        <label class="select">
                            <select id='selsec_7' class="form-control col-lg-8">
                                @foreach ($sectores as $sector_7)
                                    <option value='{{$sector_7->id_sec}}' >{{$sector_7->sector}}</option>
                                @endforeach
                            </select><i></i> </label>
                    </section>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<div id="dialog_busqueda_usuarios" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <div class="row">
                    
                    <div class="col-xs-9" style="margin-top: 5px;padding-left:80px;">                
                            <div class="panel panel-success">
                                <div class="panel-heading bg-color-success">.:: DATOS DEL USUARIO ::.</div>
                                <div class="panel-body cr-body">
                                    
                                    <div class="col-xs-9" style="padding-left:50px;">
                                        <label class="label">Usuarios:</label>
                                        <label class="input">
                                            <input id="dlg_id" type="hidden">
                                            <input id="dlg_usuario" type="text"  class="input-sm" autofocus="">
                                        </label>
                                    </div>
                      
                                </div>
                            </div>
                        </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<div id="dlg_bus_usuario" style="display: none;">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
        <table id="table_usuario"></table>
        <div id="pager_table_usuario"></div>
    </article>
</div> 

<!--NUEVOS-->

<div id="dialog_reporte_contribuyente_predio" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <!-- widget div-->
                <div class="row">
                    <section class="col col-6" style="padding-right:5px;">
                        <label class="label">AÑO:</label>
                        <label class="select">
                            <select id='select_sup_anio_rcp' class="form-control col-lg-8">
                                @foreach ($anio_tra as $anio_cp)
                                    <option value='{{$anio_cp->anio}}' >{{$anio_cp->anio}}</option>
                                @endforeach
                            </select><i></i> </label>
                    </section>
                    <section class="col col-6" style="padding-left:5px;padding-right:5px;">
                        <label class="label">SECTOR:</label>
                        <label class="select">
                            <select id='select_sector_rcp' class="form-control col-lg-8">
                                <option value='0'>-- TODOS --</option>
                                @foreach ($sectores as $sector_cp)
                                    <option value='{{$sector_cp->id_sec}}' >{{$sector_cp->sector}}</option>
                                @endforeach
                            </select><i></i> </label>
                    </section>
   
                </div>
                <!-- end widget div -->
            </div>
        </div>
    </div>
</div>



<!-- dialogs andrea----------------------------------------------------->
<div id="dialog_corriente" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <!-- widget div-->
                <div class="row" style="padding: 20px 30px;">
                <div class="col-xs-12">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Año de Trabajo <i class="fa fa-cogs"></i></span>
                            <div class="icon-addon addon-md">
                                <select id='anio_corriente' class="form-control col-lg-8" style="height: 32px; width: 90%" onchange="callfilltab()">
                                @foreach ($anio_tra as $anio)
                                <option value='{{$anio->anio}}' >{{$anio->anio}}</option>
                                @endforeach
                                </select>
                            </div>
                    </div>
                </div>
                </div> 
                <!-- end widget div -->
            </div>
        </div>
    </div>
</div>
<div id="dialog_fraccionamiento" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <!-- widget div-->
                <div class="row">
                    <section class="col col-6" style="padding-right:5px;">
                        <label class="label">AÑO:</label>
                        <label class="select">
                            <select id='anio_fraccionamiento' class="form-control col-lg-8">
                                @foreach ($anio_tra as $anio_ep)
                                    <option value='{{$anio_ep->anio}}' >{{$anio_ep->anio}}</option>
                                @endforeach
                            </select><i></i> </label>
                    </section>
                    <section class="col col-6" style="padding-left:5px;padding-right:5px;">
                        <label class="label">ESTADO:</label>
                        <label class="select">
                            <select id='select_estado' class="form-control col-lg-8">
                                <option value='0'>-- TODOS --</option>
                                @foreach ($estado_frac as $estado)
                                    <option value='{{$estado->id_estado}}' >{{$estado->desc_estado}}</option>
                                @endforeach
                            </select><i></i> </label>
                    </section>
                    
                   
                </div>
                <!-- end widget div -->
            </div>
        </div>
    </div>
</div>
<div id="dialog_caja" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <!-- widget div-->
                <div class="row" style="padding: 10px 30px;">
                    
                   
                    <div class="col-xs-12" style="padding: 0px; margin-top: 10px;">
                        <div class="input-group input-group-md" style="width: 100%">
                            <span class="input-group-addon" style="width: 165px">Fecha inicio &nbsp;<i class="fa fa-calendar"></i></span>
                            <div>
                            <input id="fec_ini_cajas" name="dlg_fec" type="text"   class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 32px; width: 100%" placeholder="--/--/----" value="{{date('01/m/Y')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12" style="padding: 0px; margin-top: 10px; ">
                        <div class="input-group input-group-md" style="width: 100%">
                            <span class="input-group-addon" style="width: 165px">Fecha fin &nbsp;<i class="fa fa-calendar"></i></span>
                            <div>
                            <input id="fec_fin_cajas" name="dlg_fec" type="text"   class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 32px; width: 100%" placeholder="--/--/----" value="{{date('d/m/Y')}}">
                            </div>
                        </div>
                    </div>
                     <div class="col-xs-12" style="padding: 0px; margin-top: 10px; ">
                        <div class="input-group input-group-md" style="width: 100%">
                            <span class="input-group-addon" style="width: 165px">Agencia &nbsp;<i class="fa fa-users"></i></span>
                            <div>
                                <label class="select" >
                                    <select id='select_agencia' class="form-control col-lg-8" >
                                <option value='0'>-- TODOS --</option>
                                @foreach ($agencias as $agencias_caja)
                                    <option value='{{$agencias_caja->id_caj}}' >{{$agencias_caja->descrip_caja}}</option>
                                @endforeach
                            </select><i></i> </label>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                </div>
                <!-- end widget div -->
            </div>
        </div>
    </div>
</div>
<div id="dialog_datos_contribuyentes" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <!-- widget div-->
                
                    <div class="row" style="padding: 20px 30px;">
                        <div class="col-xs-12">
                            <div class="input-group input-group-md">
                                <span class="input-group-addon" style="width: 165px">Año <i class="fa fa-cogs"></i></span>
                                    <div class="icon-addon addon-md">
                                        <select id='select_sup_anio_dc1' class="form-control col-lg-8" style="height: 32px; width: 90%" onchange="callfilltab()">
                                            @foreach ($anio_tra as $anio_dc)
                                                <option value='{{$anio_dc->anio}}' >{{$anio_dc->anio}}</option>
                                            @endforeach
                                        </select>
                                        
                                    </div>
                            </div>
                        </div>
                    </div>                         
                    <div class="row" style="padding: 5px 30px;">
                        <div class="col-xs-12" >
                            <div class="input-group input-group-md" style="width: 100%">
                                <span class="input-group-addon" style="width: 165px">Hab. Urbana &nbsp;<i class="fa fa-file-archive-o"></i></span>
                                <div> 
                                     <input type="hidden" id="hidden_habilitacion_urbana" value="0">
                                     <textarea  id="habilitacion_urbana" type="text" placeholder="Escriba una Habilitación Urbana" class="form-control" style="height: 32px; padding-left: 10px"  ></textarea>
                                </div>
                            </div>
                         </div>
                    </div>  
                <!-- end widget div -->
            </div>
        </div>
    </div>
</div>
<div id="dialog_datos_contribuyentes_p" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <!-- widget div-->
                    <div class="row" style="padding: 5px 30px;">
                        <div class="col-xs-12">
                            <div class="input-group input-group-md">
                                <span class="input-group-addon" style="width: 165px">Año <i class="fa fa-cogs"></i></span>
                                    <div class="icon-addon addon-md">
                                        <select id='select_sup_anio_pred' class="form-control col-lg-8" style="height: 32px; width: 90%" onchange="callfilltab()">
                                            @foreach ($anio_tra as $anio_pred)
                                                <option value='{{$anio_pred->anio}}' >{{$anio_pred->anio}}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                            </div>
                        </div>                       
                    </div>
                    <div class="row" style="padding: 5px 30px;">
                        <div class="col-xs-12" >
                            <div class="input-group input-group-md" style="width: 100%">
                                <span class="input-group-addon" style="width: 165px">Hab. Urbana &nbsp;<i class="fa fa-file-archive-o"></i></span>
                                <div> 
                                     <input type="hidden" id="hidden_habilitacion_urbana1" value="0">
                                     <textarea  id="habilitacion_urbana1" type="text" placeholder="Escriba una Habilitación Urbana" class="form-control" style="height: 32px; padding-left: 10px"  ></textarea>
                                </div>
                            </div>
                         </div>
                    </div>  
                <!-- end widget div -->
            </div>
        </div>
    </div>
</div>
<div id="dialog_cantidad_por_zona" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <!-- widget div-->
                
                    <div class="row" style="padding: 20px 30px;">
                        <div class="col-xs-12">
                            <div class="input-group input-group-md">
                                <span class="input-group-addon" style="width: 165px">Año <i class="fa fa-cogs"></i></span>
                                    <div class="icon-addon addon-md">
                                        <select id='select_sup_anio_cpz' class="form-control col-lg-8" style="height: 32px; width: 90%" onchange="callfilltab()">
                                            @foreach ($anio_tra as $anio_cpz)
                                                <option value='{{$anio_cpz->anio}}' >{{$anio_cpz->anio}}</option>
                                            @endforeach
                                        </select>
                                        
                                    </div>
                            </div>
                        </div>
                    </div>                         
                    <div class="row" style="padding: 5px 30px;">
                        <div class="col-xs-12" >
                            <div class="input-group input-group-md" style="width: 100%">
                                <span class="input-group-addon" style="width: 165px">Hab. Urbana &nbsp;<i class="fa fa-file-archive-o"></i></span>
                                <div> 
                                     <input type="hidden" id="hidden_habilitacion_urbana2" value="0">
                                     <textarea  id="habilitacion_urbana2" type="text" placeholder="Escriba una Habilitación Urbana" class="form-control" style="height: 32px; padding-left: 10px"  ></textarea>
                                </div>
                            </div>
                         </div>
                    </div>  
                <!-- end widget div -->
            </div>
        </div>
    </div>
</div>
<div id="dialog_por_uso" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <!-- widget div-->
                
                    <div class="row" style="padding: 5px 30px;">
                        <div class="col-xs-12">
                            <div class="input-group input-group-md">
                                <span class="input-group-addon" style="width: 165px">Año <i class="fa fa-cogs"></i></span>
                                    <div class="icon-addon addon-md">
                                        <select id='select_sup_anio_uso' class="form-control col-lg-8" style="height: 32px; width: 90%" onchange="callfilltab()">
                                            @foreach ($anio_tra as $anio_uso)
                                                <option value='{{$anio_uso->anio}}' >{{$anio_uso->anio}}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                            </div>
                        </div>                       
                    </div>
                    <div class="row" style="padding: 5px 30px;">
                        <div class="col-xs-12">
                            <div class="input-group input-group-md">
                                <span class="input-group-addon" style="width: 165px">USO <i class="fa fa-cogs"></i></span>
                                    <div class="icon-addon addon-md">
                                         <select id='select_uso_ep' class="form-control col-lg-8" >
                                            <option value='0'>-- TODOS --</option>
                                            @foreach ($usos_predio_arb as $usos_ep)
                                                <option value='{{$usos_ep->id_uso_arb}}' >{{$usos_ep->uso_arbitrio}}</option>
                                            @endforeach
                                        </select>                                  
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding: 5px 30px;">
                        <div class="col-xs-12" >
                            <div class="input-group input-group-md" style="width: 100%">
                                <span class="input-group-addon" style="width: 165px">Hab. Urbana &nbsp;<i class="fa fa-file-archive-o"></i></span>
                                <div> 
                                     <input type="hidden" id="hidden_habilitacion_urbana3" value="0">
                                     <textarea  id="habilitacion_urbana3" type="text" placeholder="Escriba una Habilitación Urbana" class="form-control" style="height: 32px; padding-left: 10px"  ></textarea>
                                </div>
                            </div>
                         </div>
                    </div>  
                <!-- end widget div -->
            </div>
        </div>
    </div>
</div>
<div id="dialog_por_condicion" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <!-- widget div-->
                
                    <div class="row" style="padding: 5px 30px;">
                        <div class="col-xs-12">
                            <div class="input-group input-group-md">
                                <span class="input-group-addon" style="width: 165px">Año <i class="fa fa-cogs"></i></span>
                                    <div class="icon-addon addon-md">
                                        <select id='select_sup_anio_con' class="form-control col-lg-8" style="height: 32px; width: 90%" onchange="callfilltab()">
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
                                <span class="input-group-addon" style="width: 165px">CONDICION <i class="fa fa-cogs"></i></span>
                                    <div class="icon-addon addon-md">
                                          <select id='select_condicion_ccdmbi' class="form-control col-lg-8" >
                                            <option value='0'>-- TODOS --</option>
                                            @foreach ($condicion as $condicion_ccdmbi)
                                                <option value='{{$condicion_ccdmbi->id_exo}}' >{{$condicion_ccdmbi->desc_exon}}</option>
                                            @endforeach
                                          </select>                                 
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding: 5px 30px;">
                        <div class="col-xs-12" >
                            <div class="input-group input-group-md" style="width: 100%">
                                <span class="input-group-addon" style="width: 165px">Hab. Urbana &nbsp;<i class="fa fa-file-archive-o"></i></span>
                                <div> 
                                     <input type="hidden" id="hidden_habilitacion_urbana4" value="0">
                                     <textarea  id="habilitacion_urbana4" type="text" placeholder="Escriba una Habilitación Urbana" class="form-control" style="height: 32px; padding-left: 10px"  ></textarea>
                                </div>
                            </div>
                         </div>
                    </div>  
                <!-- end widget div -->
            </div>
        </div>
    </div>
</div>
<div id="dialog_por_deduccion_50UIT" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <!-- widget div-->
                
                    <div class="row" style="padding: 5px 30px;">
                        <div class="col-xs-12">
                            <div class="input-group input-group-md">
                                <span class="input-group-addon" style="width: 165px">Año <i class="fa fa-cogs"></i></span>
                                    <div class="icon-addon addon-md">
                                        <select id='select_sup_anio_ded' class="form-control col-lg-8" style="height: 32px; width: 90%" onchange="callfilltab()">
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
                                <span class="input-group-addon" style="width: 165px">CONDICION <i class="fa fa-cogs"></i></span>
                                    <div class="icon-addon addon-md">
                                          <select id='select_condicion_ded' class="form-control col-lg-8" >
                                            <option value='0'>-- TODOS --</option>
                                            @foreach ($pensionista_adulto as $pens_adul)
                                                <option value='{{$pens_adul->id_exo}}' >{{$pens_adul->desc_exon}}</option>
                                            @endforeach
                                          </select>                                 
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding: 5px 30px;">
                        <div class="col-xs-12" >
                            <div class="input-group input-group-md" style="width: 100%">
                                <span class="input-group-addon" style="width: 165px">Hab. Urbana &nbsp;<i class="fa fa-file-archive-o"></i></span>
                                <div> 
                                     <input type="hidden" id="hidden_habilitacion_urbana10" value="0">
                                     <textarea  id="habilitacion_urbana10" type="text" placeholder="Escriba una Habilitación Urbana" class="form-control" style="height: 32px; padding-left: 10px"  ></textarea>
                                </div>
                            </div>
                         </div>
                    </div>  
                <!-- end widget div -->
            </div>
        </div>
    </div>
</div>
<div id="dialog_por_exonerados" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <!-- widget div-->
                
                    <div class="row" style="padding: 5px 30px;">
                        <div class="col-xs-12">
                            <div class="input-group input-group-md">
                                <span class="input-group-addon" style="width: 165px">Año <i class="fa fa-cogs"></i></span>
                                    <div class="icon-addon addon-md">
                                        <select id='select_sup_anio_exo' class="form-control col-lg-8" style="height: 32px; width: 90%" onchange="callfilltab()">
                                            @foreach ($anio_tra as $anio_con)
                                                <option value='{{$anio_con->anio}}' >{{$anio_con->anio}}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                            </div>
                        </div>                       
                    </div>                    
                    <div class="row" style="padding: 5px 30px;">
                        <div class="col-xs-12" >
                            <div class="input-group input-group-md" style="width: 100%">
                                <span class="input-group-addon" style="width: 165px">Hab. Urbana &nbsp;<i class="fa fa-file-archive-o"></i></span>
                                <div> 
                                     <input type="hidden" id="hidden_habilitacion_urbana11" value="0">
                                     <textarea  id="habilitacion_urbana11" type="text" placeholder="Escriba una Habilitación Urbana" class="form-control" style="height: 32px; padding-left: 10px"  ></textarea>
                                </div>
                            </div>
                         </div>
                    </div>  
                <!-- end widget div -->
            </div>
        </div>
    </div>
</div>
<div id="dialog_por_ep_afecto_exonerado" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <!-- widget div-->
                
                    <div class="row" style="padding: 5px 30px;">
                        <div class="col-xs-12">
                            <div class="input-group input-group-md">
                                <span class="input-group-addon" style="width: 165px">Año <i class="fa fa-cogs"></i></span>
                                    <div class="icon-addon addon-md">
                                        <select id='select_sup_anio_ded' class="form-control col-lg-8" style="height: 32px; width: 90%" onchange="callfilltab()">
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
                                <span class="input-group-addon" style="width: 165px">CONDICION <i class="fa fa-cogs"></i></span>
                                    <div class="icon-addon addon-md">
                                          <select id='select_condicion_ded' class="form-control col-lg-8" >
                                            <option value='0'>-- TODOS --</option>
                                            @foreach ($afecto_exonerado as $afec_exo)
                                                <option value='{{$afec_exo->id_exo}}' >{{$afec_exo->desc_exon}}</option>
                                            @endforeach
                                          </select>                                 
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