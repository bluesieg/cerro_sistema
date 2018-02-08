@extends('layouts.app')
@section('content')

            <section id="widget-grid" class="">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -12px">
                        <div class="well well-sm well-light">
                            <h1 class="txt-color-green"><b>
                                    <h1 class="txt-color-green"><b>.::USOS CATASTRALES::.</b></h1>
                            <div class="row">
  
                                <div class="col-xs-12">
                                    <div class="text-right">
                                        @if( $permisos[0]->btn_new ==1 )
                                            <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="nuevo_uso_catastrato();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="sin_permiso();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                            </button>
                                        @endif
                                        @if( $permisos[0]->btn_edit ==1 )
                                            <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="actualizar_uso_catastro();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                            </button>
                                        @else
                                            <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="sin_permiso();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                            </button>
                                        @endif
                                        @if( $permisos[0]->btn_del ==1 )
                                        <button  type="button" class="btn btn-labeled btn-danger" onclick="eliminar_uso_catastro();">
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
                        <input type="hidden" id="current_id" value="0">
                        <table id="tabla_uso_catastro"></table>
                        <div id="pager_tabla_uso_catastro"></div>
                    </article>
                </div>
            </section>


</section>

@section('page-js-script')
<script type="text/javascript">

    $(document).ready(function () {
        $("#menu_configuracion").show();
        $("#li_usos_catastrales").addClass('cr-active');    

        var pageWidth = $("#tabla_uso_catastro").parent().width() - 100;

        jQuery("#tabla_uso_catastro").jqGrid({
            url: 'listar_usos_catastrales',
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            toolbarfilter: true,
            colNames: ['ID','CODIGO','DESCRIPCION'],
            rowNum: 20,sortname: 'id_uso', viewrecords: true, caption: 'USOS CATASTRALES', align: "center",
            colModel: [
                {name: 'id_uso', index: 'id_uso', align: 'center',hidden:true,width:(pageWidth*(20/100))},
                {name: 'codi_uso', index: 'codi_uso', align: 'left', width:(pageWidth*(20/100))}, 
                {name: 'desc_uso', index: 'desc_uso', align: 'left', width:(pageWidth*(80/100))},

            ],
            pager: '#pager_tabla_uso_catastro',
            rowList: [10, 20],
            gridComplete: function () {
                    var idarray = jQuery('#tabla_uso_catastro').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_uso_catastro').jqGrid('getDataIDs')[0];
                            $("#tabla_uso_catastro").setSelection(firstid);
                        }
                },
            onSelectRow: function (Id){
                $('#current_id').val($("#tabla_uso_catastro").getCell(Id, "id_uso"));

            },
            ondblClickRow: function (Id){
                $('#current_id').val($("#tabla_uso_catastro").getCell(Id, "id_uso"));
                actualizar_uso_catastro();
            }
        }).hideCol('id_uso').setGridWidth(1270);

        $(window).on('resize.jqGrid', function () {
            $("#tabla_uso_catastro").jqGrid('setGridWidth', $("#content").width());
        });

    });

</script>
@stop

<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/configuracion_gonzalo/usos_catastrales.js') }}"></script>
<div id="dlg_nuevo_uso_catastro" style="display: none;">
    <div class='cr_content col-xs-12 ' style="margin-bottom: 10px;">
    <div class="col-xs-12 cr-body" >
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px; margin-top: 0px;">
                
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-info"></i> </span>
                                <h2>LLenado de Información::..</h2>
                        </header>
                    </div>
                </section>
                <input type="hidden" id="id_uso_catastro" value="0">
                
                <div class="col-xs-12" style="padding: 0px; margin-bottom: 10px; ">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 165px">Codigo: &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <input id="dlg_codigo_uso" type="text"  class="form-control text-center"  maxlength="6" style="height: 32px;" onkeypress="return soloNumeroTab(event);">
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="padding: 0px; margin-bottom: 10px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 165px">Descripcion: &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <input id="dlg_descripcion_uso" type="text" class="form-control text-center text-uppercase" style="height: 32px;" maxlength="250" onkeyup="javascript:this.value=this.value.toUpperCase();">
                        </div>
                    </div>
                </div>
                          
            </div>
          
        </div>
    </div>
    </div>
@endsection




