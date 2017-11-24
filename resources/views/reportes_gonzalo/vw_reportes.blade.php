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
                            <th colspan="2" style="width: 100%;">REPORTES DE CONTRIBUYENTES</th>
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


                   

                        </tbody>
                    </table>
                    
                 
                    
                    <table class="table table-striped table-forum">
                        <thead>
                        <tr>
                            <th colspan="2" style="width: 100%;">REPORTES ULTIMO</th>
                        </tr>
                        </thead>
                        <tbody>

                        <!-- TR -->
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-group fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_listado_datos_contribuyentes(0);" id="titulo_r1">
                                        REPORTE 1: Listado de datos de los contribuyentes.
                                    </a>
                                    <small>Descripción reporte 1</small>
                                </h4>
                            </td>
          
                         
                        </tr>  

                        <!-- TR -->
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-group fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_listado_datos_contribuyentes_predios(0);" id="titulo_r1">
                                        REPORTE 2: Listado de datos de los contribuyentes y predios.
                                    </a>
                                    <small>Descripción reporte 2</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                        <!-- end TR -->
                        
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-group fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_reporte_contribuyentes_predios(0);" id="titulo_r1">
                                        REPORTE 4: Reporte de cantidad de contribuyentes y predios por zonas.
                                    </a>
                                    <small>Descripción reporte 4</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                        
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-group fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_reporte_emision_predial_uso(0);" id="titulo_r1">
                                        REPORTE 5: Reporte Emision Predial por Uso.
                                    </a>
                                    <small>Descripción reporte 5</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                        
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-group fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_reporte_base_imponible(0);" id="titulo_r1">
                                        REPORTE 6: Reporte del Monto de la Base Imponible Afecto y Exonerado.
                                    </a>
                                    <small>Descripción reporte 6</small>
                                </h4>
                            </td>
          
                         
                        </tr>


                   

                        </tbody>
                    </table>
                    
                    <table class="table table-striped table-forum">
                        <thead>
                        <tr>
                            <th colspan="2" style="width: 100%;">REPORTE DE CANTIDAD DE CONTRIBUYENTES EXONERADOS</th>
                        </tr>
                        </thead>
                        <tbody>

                        <!-- TR -->
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-group fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_reporte_contribuyentes_exonerados(0);" id="titulo_r1">
                                        Reporte de cantidad de contribuyentes exonerados.
                                    </a>
                                    <small>Descripción reporte 5</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                        <!-- end TR -->


                   

                        </tbody>
                    </table>
                    
                    <table class="table table-striped table-forum">
                        <thead>
                        <tr>
                            <th colspan="2" style="width: 100%;">REPORTE DE CANTIDAD DE CONTRIBUYENTES CON DEDUCCION DE 50 UIT(PENSIONISTA Y ADULTO MAYOR) Y MONTO DE LA BASE IMPONIBLE </th>
                        </tr>
                        </thead>
                        <tbody>

                        <!-- TR -->
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-group fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_reporte_cantidad_contribuyentes(0);" id="titulo_r1">
                                        Cantidad de contribuyentes con deducción de 50 UIT(Pensionista y adulto mayor) y monto de la base imponible.
                                    </a>
                                    <small>Descripción reporte 6</small>
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
                    
                    <section class="col col-12" style="padding-left:15px;padding-right:5px;">
                        <div class="input-group input-group-md">
                        <span class="input-group-addon" style="width:190px">CANTIDAD DE MAXIMA: &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <input id="max" type="max"  class="form-control col-lg-8" value="50000" style="padding-left:15px" onkeypress="return soloNumeroTab(event);">
                        </div>
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


<div id="dialog_listado_datos_contribuyente" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <!-- widget div-->
                <div class="row">
                    <section class="col col-6" style="padding-right:5px;">
                        <label class="label">AÑO:</label>
                        <label class="select">
                            <select id='select_sup_anio' class="form-control col-lg-8">
                                @foreach ($anio_tra as $anio_7)
                                    <option value='{{$anio_7->anio}}' >{{$anio_7->anio}}</option>
                                @endforeach
                            </select><i></i> </label>
                    </section>
                    <section class="col col-6" style="padding-left:5px;padding-right:5px;">
                        <label class="label">SECTOR:</label>
                        <label class="select">
                            <select id='select_sector' class="form-control col-lg-8" onchange="cargar_manzana_contribuyente('select_manzana');">
                                @foreach ($sectores as $sector_7)
                                    <option value='{{$sector_7->id_sec}}' >{{$sector_7->sector}}</option>
                                @endforeach
                            </select><i></i> </label>
                    </section>
   
                </div>
                <!-- end widget div -->
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
                            <select id='select_sup_anio' class="form-control col-lg-8">
                                @foreach ($anio_tra as $anio_7)
                                    <option value='{{$anio_7->anio}}' >{{$anio_7->anio}}</option>
                                @endforeach
                            </select><i></i> </label>
                    </section>
                    <section class="col col-6" style="padding-left:5px;padding-right:5px;">
                        <label class="label">SECTOR:</label>
                        <label class="select">
                            <select id='select_sect' class="form-control col-lg-8" onchange="cargar_manzana_contribuyente_predio('select_mzna');">
                                @foreach ($sectores as $sector_7)
                                    <option value='{{$sector_7->id_sec}}' >{{$sector_7->sector}}</option>
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

<div id="dialog_emision_predial" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <!-- widget div-->
                <div class="row">
                    <section class="col col-4" style="padding-right:5px;">
                        <label class="label">AÑO:</label>
                        <label class="select">
                            <select id='select_anio_ep' class="form-control col-lg-8">
                                @foreach ($anio_tra as $anio_ep)
                                    <option value='{{$anio_ep->anio}}' >{{$anio_ep->anio}}</option>
                                @endforeach
                            </select><i></i> </label>
                    </section>
                    <section class="col col-4" style="padding-left:5px;padding-right:5px;">
                        <label class="label">SECTOR:</label>
                        <label class="select">
                            <select id='select_sec_ep' class="form-control col-lg-8" onchange="cargar_manzana_emision_predial('select_mz_ep');">
                                @foreach ($sectores as $sector_ep)
                                    <option value='{{$sector_ep->id_sec}}' >{{$sector_ep->sector}}</option>
                                @endforeach
                            </select><i></i> </label>
                    </section>
                    <section class="col col-4" style="padding-left:5px;padding-right:5px;">
                        <label class="label">MANZANA:</label>
                        <label class="select">
                            <select id='select_mz_ep' class="form-control col-lg-8" >
                               
                            </select><i></i> </label>
                    </section>
                   
                </div>
                <!-- end widget div -->
            </div>
        </div>
    </div>
</div>

@endsection




