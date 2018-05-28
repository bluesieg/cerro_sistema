@extends('layouts.app')

@section('content')

<section id="widget-grid" class="">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -15px">
            <div class="well well-sm well-light">
                <h1 class="txt-color-green"><b>Listado de Oficinas...</b></h1>
                <div class="row">
                    <div class="col-xs-12">                        
                        <div class="text-right">
                            <button 
                                @if($permisos[0]->btn_new==1) onclick="open_dialog_new_edit_Oficinas('NUEVO');" @else onclick="sin_permiso();" @endif
                                 
                                id="btn_vw_oficinas_Nuevo" type="button" class="btn btn-labeled bg-color-greenLight txt-color-white">
                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                            </button> 
                            <button 
                                @if($permisos[0]->btn_edit==1) onclick="open_dialog_new_edit_Oficinas('EDITAR')" @else onclick="sin_permiso();" @endif
                                id="btn_vw_oficinas_Editar" type="button" class="btn btn-labeled bg-color-blue txt-color-white">
                                <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Editar
                            </button>
                            <button 
                                @if($permisos[0]->btn_del==1) onclick="config_eliminar_oficina()" @else onclick="sin_permiso();" @endif
                                id="btn_vw_oficinas_Eliminar" type="button" class="btn btn-labeled btn-danger">
                                <span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span>Eliminar
                            </button> 
                        </div>
                    </div>                        
                </div>
            </div>                   
        </div>
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <table id="table_vw_oficinas"></table>
            <div id="pager_table_vw_oficinas"></div>
        </article>
    </div>
</section>
<div id="dialog_open_list_oficinas" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">                
                <div class="panel panel-success" style="border: 0px !important">
                    <div class="panel-heading bg-color-success">.:: Oficina ::.</div>
                    <div class="panel-body">
                        <fieldset>  
                            <section> 
                                <label class="label">Nombre de la Oficina:</label>
                                <label class="textarea">
                                    <textarea id="ofi_txt_nombre_textarea" rows="2"  class="custom-scroll" required="required" size="55"></textarea>
                                </label>                      
                            </section>         
                        </fieldset>
                    </div>
                </div>               
            </div>
        </div>
    </div>
</div>

@section('page-js-script')
<script type="text/javascript">
    global = 0;
    $(document).ready(function () {
        $("#menu_configuracion").show();
        $("#li_config_oficinas").addClass('cr-active');
        jQuery("#table_vw_oficinas").jqGrid({
            url: 'list_oficinas',
            datatype: 'json', mtype: 'GET',
            autowidth: true, height: 'auto',
            colNames: ['Codigo', 'Nombre de la Oficina', 'codigo Oficina '],            
            rowNum: 15, sortname: 'id_ofi', sortorder: 'asc', viewrecords: true, caption: 'LISTADO DE OFICINAS', align: "center",
            colModel: [
                {name: 'id_ofi', index: 'id_ofi', align: 'center', width: '15%'},
                {name: 'nombre', index: 'anio'},
                {name: 'cod_oficina', index: 'cod_oficina', align: 'center', hidden: true},
            ],
            pager: '#pager_table_vw_oficinas',
            rowList: [15, 25],
            onSelectRow: function (Id) {},
            ondblClickRow: function (Id) {
                $('#btn_vw_oficinas_Editar').click();
            }
        });

        $(window).on('resize.jqGrid', function () {
            $("#table_vw_oficinas").jqGrid('setGridWidth', $("#content").width());
        });
    });
</script>
@stop
<script src="{{ asset('archivos_js/configuracion/uit.js') }}"></script>
@endsection

