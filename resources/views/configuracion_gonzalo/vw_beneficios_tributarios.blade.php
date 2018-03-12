@extends('layouts.app')
@section('content')

            <section id="widget-grid" class="">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -12px">
                        <div class="well well-sm well-light">
                            <h1 class="txt-color-green"><b>
                                    <h1 class="txt-color-green"><b>BENEFICIOS TRIBUTARIOS</b></h1>
                            <div class="row">
                                
                                <div class="col-xs-12">
                                    <div class="text-right">
                                        @if( $permisos[0]->btn_new ==1 )
                                            <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="nuevo_ben_trib();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="sin_permiso();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                            </button>
                                        @endif
                                        @if( $permisos[0]->btn_edit ==1 )
                                            <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="actualizar_ben_trib();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                            </button>
                                        @else
                                            <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="sin_permiso();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                            </button>
                                        @endif
                                        @if( $permisos[0]->btn_del ==1 )
                                        <button  type="button" class="btn btn-labeled btn-danger" onclick="eliminar_ben_trib();">
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
                        <table id="tabla_beneficios_tributarios"></table>
                        <div id="pager_tabla_beneficios_tributarios"></div>
                    </article>
                </div>
            </section>


</section>

@section('page-js-script')
<script type="text/javascript">

    $(document).ready(function () {
        $("#menu_configuracion").show();
        $("#li_beneficios_tributarios").addClass('cr-active');
        anio = $("#select_anio").val(); 

        var pageWidth = $("#tabla_tim").parent().width() - 100;

        jQuery("#tabla_beneficios_tributarios").jqGrid({
            url:'listar_beneficios_tributarios',
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            toolbarfilter: true,
            colNames: ['ID','DOCUMENTO','% TIM','% MULTA TRIBUTARIA','% INTERES MULTA TRIBUTARIA','% ARBITRIOS','VIGENCIA','ESTADO'],
            rowNum: 20,sortname: 'id_bene_trib', viewrecords: true, caption: 'BENEFICIOS TRIBUTARIOS', align: "center",
            colModel: [
                {name: 'id_bene_trib', index: 'id_bene_trib', align: 'center',hidden:true, width:(pageWidth*(20/100))},
                {name: 'documento', index: 'documento', align: 'center', width:(pageWidth*(80/100))},
                {name: 'tim', index: 'tim', align: 'center', width:(pageWidth*(20/100))},
                {name: 'multa_tributaria', index: 'multa_tributaria', align: 'center', width:(pageWidth*(30/100))},
                {name: 'interes_multa_tributaria', index: 'interes_multa_tributaria', align: 'center', width:(pageWidth*(30/100))},
                {name: 'arbitrios', index: 'arbitrios', align: 'center', width:(pageWidth*(30/100))},
                {name: 'fecha_emision', index: 'fecha_emision', align: 'center', width:(pageWidth*(50/100))},
                {name: 'estado', index: 'estado', align: 'center', width:(pageWidth*(50/100))}
            ],
            pager: '#pager_tabla_beneficios_tributarios',
            rowList: [10, 20],
            gridComplete: function () {
                    var idarray = jQuery('#tabla_beneficios_tributarios').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_beneficios_tributarios').jqGrid('getDataIDs')[0];
                            $("#tabla_beneficios_tributarios").setSelection(firstid);
                        }
                },
            onSelectRow: function (Id){
                $('#current_id').val($("#tabla_beneficios_tributarios").getCell(Id, "id_bene_trib"));

            },
            ondblClickRow: function (Id){
                $('#current_id').val($("#tabla_beneficios_tributarios").getCell(Id, "id_bene_trib"));
                actualizar_ben_trib();}
        });

        $(window).on('resize.jqGrid', function () {
            $("#tabla_beneficios_tributarios").jqGrid('setGridWidth', $("#content").width());
        });

    });

</script>
@stop

<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/configuracion_gonzalo/beneficios_tributarios.js') }}"></script>
<div id="dlg_nuevo_beneficio_tributario" style="display: none;">
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
                <input type="hidden" id="id_tim" value="0">
               
                <div class="col-xs-12" style="padding: 0px; margin-bottom: 10px; ">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 232px">DOCUMENTO: &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <input id="dlg_documento" type="text"  class="form-control text-uppercase" style="height: 32px;" maxlength="100" onkeyup="javascript:this.value=this.value.toUpperCase();">
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="padding: 0px; margin-bottom: 10px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 232px">FECHA EMISION: &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <input id="dlg_fecha_emision"  type="text"   class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 32px; width: 100%" placeholder="--/--/----" value="{{date('d/m/Y')}}">
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="padding: 0px; margin-bottom: 10px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 232px">INICIO VIGENCIA: &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <input id="dlg_inicio_vigencia"  type="text"   class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 32px; width: 100%" placeholder="--/--/----" value="{{date('d/m/Y')}}">
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="padding: 0px; margin-bottom: 10px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 232px">FIN VIGENCIA: &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <input id="dlg_fin_vigencia"  type="text"   class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 32px; width: 100%" placeholder="--/--/----" value="{{date('d/m/Y')}}">
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="padding: 0px; margin-bottom: 10px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 232px">TIM: &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <input id="dlg_tim" type="text" class="form-control" style="height: 32px;" maxlength="7" onkeypress="return soloNumeroTab(event);">
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="padding: 0px; margin-bottom: 10px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 232px">MULTA TRIBUTARIA: &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <input id="dlg_multa_tributaria" type="text" class="form-control" style="height: 32px;" maxlength="7" onkeypress="return soloNumeroTab(event);">
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="padding: 0px; margin-bottom: 10px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 165px">INTERES MULTA TRIBUTARIA: &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <input id="dlg_interes_multa_tributaria" type="text" class="form-control" style="height: 32px;" maxlength="7" onkeypress="return soloNumeroTab(event);">
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="padding: 0px; margin-bottom: 10px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 232px">ARBITRIOS: &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <input id="dlg_arbitrios" type="text" class="form-control" style="height: 32px;" maxlength="7" onkeypress="return soloNumeroTab(event);">
                        </div>
                    </div>
                </div>
                              
            </div>
          
        </div>
    </div>
    </div>
@endsection




