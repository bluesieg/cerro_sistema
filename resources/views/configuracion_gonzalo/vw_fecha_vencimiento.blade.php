@extends('layouts.app')
@section('content')

            <section id="widget-grid" class="">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -12px">
                        <div class="well well-sm well-light">
                            <h1 class="txt-color-green"><b>
                                    <h1 class="txt-color-green"><b>CONFIGURACIÓN FECHA DE VENCIMIENTO...</b></h1>
                            <div class="row">
                                
                                <div class="col-xs-2">
                                    <label>Año:</label>
                                    <label class="select">
                                        <select onchange="selecciona_anio();" id="select_anio" class="input-sm">
                                            @foreach ($anio as $anio_fecha_ven)
                                                <option value='{{$anio_fecha_ven->pk_uit}}' >{{$anio_fecha_ven->anio}}</option>
                                            @endforeach
                                        </select><i></i>
                                    </label>
                                </div>
                                
                                
                                <div class="col-xs-10">
                                    <div class="text-right">
                                        @if( $permisos[0]->btn_new ==1 )
                                            <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="nuevo_fv();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="sin_permiso();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                            </button>
                                        @endif
                                        @if( $permisos[0]->btn_edit ==1 )
                                            <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="actualizar_fv();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                            </button>
                                        @else
                                            <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="sin_permiso();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                            </button>
                                        @endif
                                        @if( $permisos[0]->btn_del ==1 )
                                        <button  type="button" class="btn btn-labeled btn-danger" onclick="eliminar_fv();">
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
                        <table id="tabla_fecha_vencimiento"></table>
                        <div id="pager_tabla_fv"></div>
                    </article>
                </div>
            </section>


</section>

@section('page-js-script')
<script type="text/javascript">

    $(document).ready(function () {
        $("#menu_configuracion").show();
        $("#li_fecha_vencimiento").addClass('cr-active');
        anio = $("#select_anio").val();        

        var pageWidth = $("#tabla_fecha_vencimiento").parent().width() - 100;

        jQuery("#tabla_fecha_vencimiento").jqGrid({
            url: 'listar_fecha_vencimiento?anio=' + anio,
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            toolbarfilter: true,
            colNames: ['ID','TRIMESTRE','FECHA'],
            rowNum: 20,sortname: 'id_pag', viewrecords: true, caption: 'Fechas de Vencimiento', align: "center",
            colModel: [
                {name: 'id_pag', index: 'id_pag', align: 'center',width:(pageWidth*(20/100))},
                {name: 'trimestre', index: 'trimestre', align: 'center', width:(pageWidth*(80/100))}, 
                {name: 'fecha_vencim', index: 'fecha_vencim', align: 'center', width:(pageWidth*(50/100))},

            ],
            pager: '#pager_tabla_fv',
            rowList: [10, 20],
            gridComplete: function () {
                    var idarray = jQuery('#tabla_fecha_vencimiento').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_fecha_vencimiento').jqGrid('getDataIDs')[0];
                            $("#tabla_fecha_vencimiento").setSelection(firstid);
                        }
                    
                },
            onSelectRow: function (Id){
                $('#current_id').val($("#tabla_fecha_vencimiento").getCell(Id, "id_pag"));

            },
            ondblClickRow: function (Id){
                $('#current_id').val($("#tabla_fecha_vencimiento").getCell(Id, "id_pag"));
                actualizar_fv();
            }
        }).hideCol('id_pag').setGridWidth(1270);

        $(window).on('resize.jqGrid', function () {
            $("#tabla_fecha_vencimiento").jqGrid('setGridWidth', $("#content").width());
        });

    });

</script>
@stop

<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/configuracion_gonzalo/fecha_vencimiento.js') }}"></script>
<div id="dlg_nuevo_fv" style="display: none;">
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
                <input type="hidden" id="id_pag" value="0">
                
                <div class="col-xs-12" style="padding: 0px; margin-bottom: 10px; ">
                    <div class="input-group input-group-md" style="width: 100%">
                        <input type="hidden" id="id_anio" value="0">
                        <span class="input-group-addon" style="width: 165px">Año: &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <input id="dlg_anio" type="text"  class="form-control text-center" style="height: 32px;" disabled="">
                        </div>
                    </div>
                </div>
                
                
                <div class="col-xs-12" style="padding: 0px; margin-bottom: 10px; ">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 165px">Trimestre: &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <select id="select_trim" class="input-sm col-xs-12 text-center" >
                                       
                                        <option value='1' >Trim I</option>
                                        <option value='2' >Trim II</option>
                                        <option value='3' >Trim III</option>
                                        <option value='4' >Trim IV</option>
                                    
                                     
                            </select><i></i>
                        </div>
                    </div>
                </div>
                
                
                <div class="col-xs-12" style="padding: 0px; margin-top: 10px;">
                        <div class="input-group input-group-md" style="width: 100%">
                            <span class="input-group-addon" style="width: 165px">Fecha inicio &nbsp;<i class="fa fa-calendar"></i></span>
                            <div>
                            <input id="fec_ven"  type="text"   class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 32px; width: 100%" placeholder="--/--/----" value="{{date('01/m/Y')}}">
                            </div>
                        </div>
                    </div>
               
            </div>
                          
           </div>
          
        </div>
    </div>
    </div>
@endsection




