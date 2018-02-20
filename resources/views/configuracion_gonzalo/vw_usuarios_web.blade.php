@extends('layouts.app')
@section('content')

            <section id="widget-grid" class="">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -12px">
                        <div class="well well-sm well-light">
                            <h1 class="txt-color-green"><b>
                                    <h1 class="txt-color-green" style="padding-bottom: 20px;"><b>Mantenimiento de Usuarios Web</b></h1>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="text-right">
                                         <section class="col-lg-8" style="padding-left:2px; padding-bottom: 10px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">Buscar Usuarios Web<i class="icon-append fa fa-male" style="margin-left: 5px;"></i></span>
                                                 <input type="hidden" id="hidden_usuarios_web">
                                                <input type="text" id="dlg_usuarios_web" class="form-control text-uppercase">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-success" type="button" onclick="fn_bus_usuarios_web();" title="BUSCAR">
                                                        <i class="glyphicon glyphicon-search"></i>&nbsp;Buscar
                                                    </button>
                                                </span>
                                            </div>                                            
                                        </section>
                                        @if( $permisos[0]->btn_new ==1 )
                                            <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="nuevo_usuario_web();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="sin_permiso();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                            </button>
                                        @endif
                                        @if( $permisos[0]->btn_edit ==1 )
                                            <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="actualizar_usuario_web();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                            </button>
                                        @else
                                            <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="sin_permiso();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                            </button>
                                        @endif
                                        @if( $permisos[0]->btn_del ==1 )
                                        <button  type="button" class="btn btn-labeled btn-danger" onclick="eliminar_usuario_web();">
                                            <span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span>Eliminar
                                        </button>
                                        @else
                                            <button  type="button" class="btn btn-labeled btn-danger" onclick="sin_permiso();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span>Eliminar
                                            </button>
                                        @endif
                                      
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" id="current_id_usuarios" value="0">
                        <table id="tabla_usuarios_web"></table>
                        <div id="pager_tabla_usuarios_web"></div>
                    </article>
                </div>
            </section>


</section>

@section('page-js-script')
<script type="text/javascript">

    $(document).ready(function () {
        $("#menu_configuracion").show();
        $("#li_usuarios_web").addClass('cr-active');
        anio = $("#select_anio").val(); 

        var pageWidth = $("#tabla_usuarios_web").parent().width() - 100;

        jQuery("#tabla_usuarios_web").jqGrid({
            url: 'listar_usuarios_web',
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            toolbarfilter: true,
            colNames: ['ID','USUARIO','COD. CONTRIBUYENTE','CONTRIBUYENTE'],
            rowNum: 20,sortname: 'id', viewrecords: true, caption: 'USUARIOS WEB REGISTRADOS', align: "center",
            colModel: [
                {name: 'id', index: 'id', align: 'center', hidden:true,width:(pageWidth*(10/100))},
                {name: 'usuario', index: 'usuario', align: 'center', width:(pageWidth*(20/100))}, 
                {name: 'cod_contribuyente', index: 'cod_contribuyente', align: 'center', width:(pageWidth*(20/100))},
                {name: 'contribuyente', index: 'cod_contribuyente', align: 'center', width:(pageWidth*(50/100))},

            ],
            pager: '#tabla_usuarios_web',
            rowList: [10, 20],
            gridComplete: function () {
                    var idarray = jQuery('#tabla_usuarios_web').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_usuarios_web').jqGrid('getDataIDs')[0];
                            $("#tabla_usuarios_web").setSelection(firstid);
                        }
                },
            onSelectRow: function (Id){
                $('#current_id_usuarios').val($("#tabla_usuarios_web").getCell(Id, "id"));

            },
            ondblClickRow: function (Id){
                $('#current_id_usuarios').val($("#tabla_usuarios_web").getCell(Id, "id"));
                actualizar_tim();}
        });

        $(window).on('resize.jqGrid', function () {
            $("#tabla_usuarios_web").jqGrid('setGridWidth', $("#content").width());
        });





         jQuery("#table_contribuyente").jqGrid({
            url: 'obtener_contribuyente?dat=0',
            datatype: 'json', mtype: 'GET',
            height: 300, width: 480,
            toolbarfilter: true,
            colNames: ['ID','DNI','CONTRIBUYENTE','COD','TIPO','CONDICIÓN','DOMICILIO'],
            rowNum: 12,sortname: 'id_contrib', viewrecords: true, caption: 'CONTRIBUYENTES', align: "center",
            colModel: [
                {name: 'id_contrib', index: 'id_contrib', align: 'center', hidden:true,width:20},
                {name: 'nro_doc', index: 'nro_doc', align: 'center', width:15},
                {name: 'contribuyente', index: 'contribuyente', align: 'center', width:50},
                {name: 'id_persona', index: 'id_persona', align: 'center', hidden:true,width:20},
                {name: 'tipo_persona', index: 'tipo_persona', align: 'center', hidden:true,width:20},
                {name: 'condicion', index: 'condicion', align: 'center',hidden:true, width:10}, 
                {name: 'domic_fiscal', index: 'domic_fiscal', align: 'center',hidden:true, width:30},


            ],
            pager: '#pager_table_contribuyente',
            rowList: [10, 20],
            gridComplete: function () {
                    var idarray = jQuery('#table_contribuyente').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_contribuyente').jqGrid('getDataIDs')[0];
                            $("#table_contribuyente").setSelection(firstid);
                        }
                    jQuery('#table_contribuyente').jqGrid('bindKeys', {"onEnter": function (rowid) { fn_llenar_datos(rowid); }});
                },
            onSelectRow: function (Id){
                $('#current_id').val($("#table_contribuyente").getCell(Id, "id_contrib"));

            },
            ondblClickRow: function (Id){
                $('#current_id').val($("#table_contribuyente").getCell(Id, "id_contrib"));
                fn_llenar_datos(Id);}
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

       

    });

</script>
@stop

<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/configuracion_gonzalo/usuarios_web.js') }}"></script>
<div id="dlg_nuevo_usuario_web" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <div class="panel panel-success">
                    <div class="panel-heading bg-color-success">.:: Buscar Contribuyente ::.</div>
                    <div class="panel-body cr-body">
                        <fieldset>
                            <div class="row">                                
                                <section class="col col-3" style="padding-right: 5px;">
                                    <input type="hidden" id="hidden_id_contribuyente">
                                    <label class="label">DNI:</label>
                                    <label class="input">
                                        <input id="dlg_dni" type="text" onkeypress="return soloDNI(event);"  placeholder="00000000" class="input-sm">
                                    </label>                      
                                </section>
                                <section class="col col-9" style="padding-left: 5px;padding-right:5px; ">
                                    <label class="label">Contribuyente:</label>
                                    <label class="input">
                                        <input id="dlg_contribuyente" type="text" placeholder="ejm. jose min 4 caracteres" class="input-sm text-uppercase">
                                    </label>
                                </section>
                                
                            </div>                            
                        </fieldset>
                    </div>
                </div>
                <div class="panel panel-success">
                    <div class="panel-heading bg-color-success">.:: Datos de Contribuyente ::.</div>
                    <div class="panel-body">                        
                        <fieldset>
                            
                            
                            <div class="row">
                                
                                <section class="col col-4" style="padding-right: 5px;">
                                   
                                    <label class="label">Cod Contrib:</label>
                                    <label class="input">
                                        <input id="dlg_codigo_contribuyente" type="text" disabled="" class="input-sm">
                                    </label>                      
                                </section>
                                <section class="col col-4" style="padding-right: 5px;">
                                    <label class="label">Tipo de Persona:</label>
                                    <label class="input">
                                        <input id="dlg_tipo_persona" type="text" disabled="" class="input-sm">
                                    </label>                      
                                </section>
                                <section class="col col-4" style="padding-right: 15px; ">
                                   <label class="label">Condición:</label>
                                    <label class="input">
                                        <input id="dlg_condicion" type="text" disabled="" class="input-sm">
                                    </label>                      
                                </section>
                                
                                                                                       
                            </div>                            
                            <div class="col-lg-12" style="padding-right: 5px;">                                    
                                    <label class="label">Domicilio Fiscal:</label>
                                    <label class="input">
                                        <input id="dlg_domicilio_fiscal" type="text" placeholder="000000" value=" " class="input-sm" disabled="">
                                    </label>                        
                                </div>
                            <div class="col-lg-5" style="padding-right: 5px; padding-left: 3px;">                                    
                                    <label class="label">Usuario:</label>
                                    <label class="input">
                                        <input id="dlg_usuario" style="text-transform: uppercase" type="text" placeholder="Escriba Usuario"  class="input-sm" >
                                    </label>                        
                                </div>
                            <div class="col-lg-6" style="padding-left: 55px; padding-right: 5px; padding-bottom: 10px;" >                                    
                                    <label class="label">Contraseña:</label>
                                    <label class="input">
                                        <input id="dlg_password" style="text-transform: uppercase" type="password" placeholder="Escriba Contraseña"  class="input-sm" maxlength="8">
                                    </label>                        
                                </div>
                            
                        </fieldset>
                    </div>
                </div>
            </div>                   
        </div>        
    </div>
</div>
<div id="dlg_bus_contribuyente" style="display: none;">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
        <table id="table_contribuyente"></table>
        <div id="pager_table_contribuyente"></div>
    </article>
</div>
@endsection




