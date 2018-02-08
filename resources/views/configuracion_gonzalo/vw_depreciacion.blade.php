@extends('layouts.app')
@section('content')

            <section id="widget-grid" class="">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -12px">
                        <div class="well well-sm well-light">
                            <h1 class="txt-color-green"><b>
                                    <h1 class="txt-color-green"><b>DEPRECIACIÓN</b></h1>
                            <div class="row">
                                
                                
                                <div class="col-xs-8" style="font-size: 0.8em;">
                                    <label>Tipo de Edificación:</label>
                                    <label class="select">
                                        <select onchange="selecciona_edificacion();" id="select_edificacion" class="input-sm" style="font-size: 0.8em;">
                                            @foreach ($tipo_edificacion as $tipo_e)
                                                <option value='{{$tipo_e->id_cla_pre}}' >{{$tipo_e->desc_clasific}}</option>
                                            @endforeach
                                        </select><i></i>
                                    </label>
                                </div>
                                
                                <div class="col-xs-4">
                                    <div class="text-right">
                                        
                                       
                                        @if( $permisos[0]->btn_edit ==1 )
                                            <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="actualizar_depreciacion();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                            </button>
                                        @else
                                            <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="sin_permiso();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                            </button>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" id="current_id" value="0">
                        <table id="tabla_depreciacion"></table>
                        <div id="pager_tabla_dep"></div>
                    </article>
                </div>
            </section>


</section>

@section('page-js-script')
<script type="text/javascript">

    $(document).ready(function () {
        $("#menu_configuracion").show();
        $("#li_depreciacion").addClass('cr-active');
        edificacion = $("#select_edificacion").val(); 

        var pageWidth = $("#tabla_depreciacion").parent().width() - 100;

        jQuery("#tabla_depreciacion").jqGrid({
            url: 'listar_depreciacion?edificacion=' + edificacion,
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            toolbarfilter: true,
            colNames: ['ID','ANTIGUEDAD','MATERIAL','ESTADO CONS.','%'],
            rowNum: 20,sortname: 'id_dep', viewrecords: true, caption: 'DEPRECIACIÓN', align: "center",
            colModel: [
                {name: 'id_dep', index: 'id_dep', align: 'center', hidden:true,width:(pageWidth*(20/100))},
                {name: 'ant_dep', index: 'ant_dep', align: 'center', width:(pageWidth*(40/100))}, 
                {name: 'mep', index: 'mep', align: 'center', width:(pageWidth*(40/100))}, 
                {name: 'ecs', index: 'ecs', align: 'center', width:(pageWidth*(50/100))},
                {name: 'por_dep', index: 'por_dep', align: 'center', width:(pageWidth*(20/100))},

            ],
            pager: '#pager_tabla_dep',
            rowList: [10, 20],
            gridComplete: function () {
                    var idarray = jQuery('#tabla_depreciacion').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_depreciacion').jqGrid('getDataIDs')[0];
                            $("#tabla_depreciacion").setSelection(firstid);
                        }
                },
            onSelectRow: function (Id){
                $('#current_id').val($("#tabla_depreciacion").getCell(Id, "id_dep"));

            },
            ondblClickRow: function (Id){
                $('#current_id').val($("#tabla_depreciacion").getCell(Id, "id_dep"));
                actualizar_depreciacion();}
        });

        $(window).on('resize.jqGrid', function () {
            $("#tabla_depreciacion").jqGrid('setGridWidth', $("#content").width());
        });

    });

</script>
@stop

<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/configuracion_gonzalo/depreciacion.js') }}"></script>
<div id="dlg_nueva_depreciacion" style="display: none;">
    <div class='cr_content col-xs-12 ' style="margin-bottom: 10px;">
    <div class="col-xs-12 cr-body" >
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px; margin-top: 0px;">
                
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-info"></i> </span>
                                <h2>Llenado de Información::..</h2>
                        </header>
                    </div>
                </section>
                
                <input type="hidden" id="id_dep" value="0">
                
                
                
                
                <div class="col-xs-12" style="padding: 0px; margin-bottom: 10px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 165px">Depreciación: &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <input id="por_dep" type="text" class="form-control" style="height: 32px;" maxlength="7" onkeypress="return soloNumeroTab(event);">
                        </div>
                    </div>
                </div>
                              
            </div>
          
        </div>
    </div>
    </div>
@endsection




