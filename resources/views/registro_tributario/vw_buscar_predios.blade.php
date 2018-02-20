@extends('layouts.app')
@section('content')
<section id="widget-grid" class=""> 
    <div class='cr_content col-xs-12 '>
        <div class="col-xs-9">
            <h1 class="txt-color-green"><b>BUSCAR PREDIOS</b></h1>
        </div>
        <input id="current_id_tabla" type="hidden" value="0">
        
         <div class="col-xs-10 cr-body" >
             
           <div class="col-xs-4" style="padding: 0px; margin-top: 5px">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 0px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-align-justify"></i> </span>
                                <h2>Patron de Busqueda</h2>
                        </header>
                    </div>
                </section>
            </div>
            <div class="col-xs-8" style="padding: 0px; margin-top: 5px">
                
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Busquedas &nbsp;<i class="fa fa-tasks" style="width: 67px"></i></span>
                        <div>
                            <select id="select_filtro" onchange="seleccionar_filtro()" class="input-sm col-xs-12 text-center" style="height: 35px;font-size: 0.9em;width: 102% !important;">
                                       
                                        <option value='0' >.:: SELECCIONE UNA OPCION ::.</option>
                                        <option value='1' >CALLE</option>
                                        <option value='2' >HABILITACION URBANA</option>
                                       <!-- <option value='3' >REFERENCIA</option>
                                        <option value='4' >UBICACION</option> -->
                                     
                            </select>
                        </div>     
                    </div>
                </div>
                
            </div>  
             
           <div class="col-xs-12"></div>
           
           <div class="col-xs-4" style="padding: 0px; margin-top: 5px">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 0px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-align-justify"></i> </span>
                                <h2>Filtro de Busqueda</h2>
                        </header>
                    </div>
                </section>
            </div>
            <div class="col-xs-8" style="padding: 0px; margin-top: 5px">
                
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon" id="dlg_direccion_span">- &nbsp;<i class="fa fa-map-marker" style="width: 130px"></i></span>
                        <div>
                            <input type="hidden" id="hidden_dlg_direccion" value="0">
                            <input id="dlg_direccion" type="text"  class="form-control" style="height: 36px;font-size: 0.9em;width: 102% !important" placeholder="ESCRIBIR POR TIPO DE PATRON DE BUSQUEDA" disabled="">
                        </div>     
                    </div>
                </div>
                
            </div>
           
           <div class="col-xs-12"></div>
            
            <div class="col-xs-4" style="padding: 0px; margin-top: 5px">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 0px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-align-justify"></i> </span>
                                <h2>Busqueda por Contribuyente</h2>
                        </header>
                    </div>
                </section>
            </div>
            <div class="col-xs-8" style="padding: 0px; margin-top: 5px">
                
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Contribuyente &nbsp;<i class="fa fa-user-plus" style="width: 46px"></i></span>
                        <div>
                            <input id="dlg_contribuyente_hidden" type="hidden" value="0">
                            <input id="dlg_contribuyente" type="text"  class="form-control" style="height: 35px;font-size: 0.9em;width: 102% !important" placeholder="ESCRIBIR EL NOMBRE DEL CONTRIBUYENTE">
                        </div>     
                    </div>
                </div>
                
            </div>
            
            <div class="col-xs-12"></div>
            
        </div>
        
        <div class="col-lg-2 text-center" style="padding-right: 0px;">
            <br>
            @if( $permisos[0]->btn_new ==1 )
                <button class="btn bg-color-green txt-color-white btn-circle btn-xl" onclick="fn_buscar_predios();" >
                    <span  >
                        <i class="glyphicon glyphicon-search"></i>
                    </span>
                </button>
                    <label><b>BUSCAR</b></label>
            @else
                <button class="btn bg-color-green txt-color-white btn-circle btn-xl" onclick="sin_permiso();" >
                    <span  >
                        <i class="glyphicon glyphicon-search"></i>
                    </span>
                </button>
                    <label><b>BUSCAR</b></label>
            @endif
        </div>
    </div>
    
    <div class='cr_content col-xs-12'>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0px; margin-top: 10px">
            <article class="col-xs-12" style=" padding: 0px !important">
                    <input type="hidden" id="current_id" value="0">
                        <table id="tabla_predio"></table>
                        <div id="pager_tabla_predio"></div>
            </article>
        </div>
    </div>

</section>



@section('page-js-script')
<script type="text/javascript">

    $(document).ready(function () {
        $("#menu_registro_tributario").show();
        $("#li_buscar_predios").addClass('cr-active');
        anio = $("#select_anio").val(); 

        var pageWidth = $("#tabla_tim").parent().width() - 100;
        contrib_global=0;
        jQuery("#tabla_tim").jqGrid({
            url: 'obtener_usuarios?dat=0',
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            toolbarfilter: true,
            colNames: ['ID','FECHA','MOTIVO','AÑO'],
            rowNum: 20,sortname: 'id_tim', viewrecords: true, caption: 'DESCARGA DE PREDIOS', align: "center",
            colModel: [
                {name: 'id_tim', index: 'id_tim', align: 'center', hidden:true,width:(pageWidth*(20/100))},
                {name: 'documento_aprob', index: 'documento_aprob', align: 'center', width:(pageWidth*(80/100))}, 
                {name: 'tim', index: 'tim', align: 'center', width:(pageWidth*(50/100))},
                {name: 'anio', index: 'anio', align: 'center', hidden:true, width:(pageWidth*(20/100))},

            ],
            pager: '#pager_tabla_tim',
            rowList: [10, 20],
            gridComplete: function () {
                    var idarray = jQuery('#tabla_tim').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_tim').jqGrid('getDataIDs')[0];
                            $("#tabla_tim").setSelection(firstid);
                        }
                },
            onSelectRow: function (Id){
                $('#current_id').val($("#tabla_tim").getCell(Id, "id_tim"));

            },
            ondblClickRow: function (Id){
                $('#current_id').val($("#tabla_tim").getCell(Id, "id_tim"));
                actualizar_tim();}
        });
        
        var globalvalidador=0;
        $("#dlg_contribuyente").keypress(function (e) {
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
        
        jQuery("#table_contribuyente").jqGrid({
            url: 'obtener_contribuyentes?dat=0',
            datatype: 'json', mtype: 'GET',
            height: 300, width: 480,
            toolbarfilter: true,
            colNames: ['ID','DNI','PERSONA'],
            rowNum: 12,sortname: 'id_contrib', viewrecords: true, caption: 'CONTRIBUYENTES', align: "center",
            colModel: [
                {name: 'id_contrib', index: 'id_contrib', align: 'center', hidden:true,width:20},
                {name: 'pers_nro_doc', index: 'pers_nro_doc', align: 'center', width:10}, 
                {name: 'contribuyente', index: 'contribuyente', align: 'center', width:30},

            ],
            pager: '#pager_table_contribuyente',
            rowList: [10, 20],
            gridComplete: function () {
                    var idarray = jQuery('#table_contribuyente').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_contribuyente').jqGrid('getDataIDs')[0];
                            $("#table_contribuyente").setSelection(firstid);
                        }
                    jQuery('#table_contribuyente').jqGrid('bindKeys', {"onEnter": function (rowid) { fn_bus_contrib_predio(rowid); }});
                },
            onSelectRow: function (Id){
                $('#current_id').val($("#table_contribuyente").getCell(Id, "id_contrib"));

            },
            ondblClickRow: function (Id){
                $('#current_id').val($("#table_contribuyente").getCell(Id, "id_contrib"));
                actualizar_tim();}
        });
        
         

    });
    
        function autocompletar_hab_urb(textbox){
            $.ajax({
                type: 'GET',
                url: 'autocompletar_hab_urb',
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
        
        function autocompletar_via(textbox){
            $.ajax({
                type: 'GET',
                url: 'autocompletar_via',
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

<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/registro_tributario/bpredios.js') }}"></script>



<div id="dlg_bus_contribuyente" style="display: none;">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
        <table id="table_contribuyente"></table>
        <div id="pager_table_contribuyente"></div>
    </article>
</div> 
@endsection




