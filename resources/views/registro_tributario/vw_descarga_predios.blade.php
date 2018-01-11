@extends('layouts.app')
@section('content')

            <section id="widget-grid" class="">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -12px">
                        <div class="well well-sm well-light">
                            <h1 class="txt-color-green"><b>
                                    <h1 class="txt-color-green"><b>DESCARGA DE PREDIOS</b></h1>
                            <div class="row">
                                
                                
                                <div class="col-xs-3">
                                    <div class="input-group input-group-md" style="width: 100%">
                                        <span class="input-group-addon" style="width: 120px">DESDE &nbsp;<i class="fa fa-calendar"></i></span>
                                        <div>
                                        <input id="dlg_fec_desde" name="dlg_fec_desde" type="text"   class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 30px; width: 110%" placeholder="--/--/----" value="{{date('01/m/Y')}}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xs-3">
                                    <div class="input-group input-group-md" style="width: 100%">
                                        <span class="input-group-addon" style="width: 120px">HASTA &nbsp;<i class="fa fa-calendar"></i></span>
                                        <div>
                                        <input id="dlg_fec_hasta" name="dlg_fec_hasta" type="text"   class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 30px; width: 110%" placeholder="--/--/----" value="{{date('01/m/Y')}}">
                                        </div>
                                    </div>
                                </div>
                                
                                
                                
                                <div class="col-xs-6">
                                    <div class="text-right">
                                        @if( $permisos[0]->btn_new ==1 )
                                            <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="nuevo_dpredios();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="sin_permiso();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                            </button>
                                        @endif
                                        @if( $permisos[0]->btn_edit ==1 )
                                            <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="actualizar_tim();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                            </button>
                                        @else
                                            <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="sin_permiso();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                            </button>
                                        @endif
                                        @if( $permisos[0]->btn_del ==1 )
                                        <button  type="button" class="btn btn-labeled btn-danger" onclick="eliminar_tim();">
                                            <span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span>Eliminar
                                        </button>
                                        @else
                                            <button  type="button" class="btn btn-labeled btn-danger" onclick="sin_permiso();">
                                                <span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span>Eliminar
                                            </button>
                                        @endif
                                        @if( $permisos[0]->btn_imp ==1 )
                                        <button type="button" class="btn btn-labeled bg-color-magenta txt-color-white">
                                            <span class="btn-label"><i class="glyphicon glyphicon-print"></i></span>Imprimir
                                        </button>
                                        @else
                                        <button type="button" class="btn btn-labeled bg-color-magenta txt-color-white" onclick="sin_permiso();">
                                            <span class="btn-label"><i class="glyphicon glyphicon-print"></i></span>Imprimir
                                        </button>
                                        @endif
                                      
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" id="current_id" value="0">
                        <table id="tabla_tim"></table>
                        <div id="pager_tabla_tim"></div>
                    </article>
                </div>
            </section>


</section>

@section('page-js-script')
<script type="text/javascript">

    $(document).ready(function () {
        $("#menu_registro_tributario").show();
        $("#li_descarga_predios").addClass('cr-active');
        anio = $("#select_anio").val(); 

        var pageWidth = $("#tabla_tim").parent().width() - 100;

        jQuery("#tabla_tim").jqGrid({
            url: 'listar_tim?anio=' + anio,
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

        
        
        $(window).on('resize.jqGrid', function () {
            $("#tabla_tim").jqGrid('setGridWidth', $("#content").width());
        });
        

    });

</script>
@stop

<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/registro_tributario/dpredios.js') }}"></script>
<div id="dlg_nuevo_dpredios" style="display: none;">
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
                        <span class="input-group-addon" style="width: 165px">Codigo: &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <input id="codigo" type="text"  class="form-control text-uppercase" style="height: 32px;" maxlength="100" onkeyup="javascript:this.value=this.value.toUpperCase();">
                        </div>
                    </div>
                </div>
                
                
                <div class="col-xs-12" style="padding: 0px; margin-bottom: 10px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 165px">Contribuyente: &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <input id="contribuyente" type="text"  class="form-control text-uppercase" style="height: 32px;" maxlength="100" onkeyup="javascript:this.value=this.value.toUpperCase();">
                        </div>
                    </div>
                </div>
      
               
                    
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0px !important; margin-bottom: 10px !important">
                        <div>
                        <fieldset>
                                                          
                                <section class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-right:5px !important;width: 60% !important">                                    
                                    <!--<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0px !important">-->
                                        <table id="tabla"></table>
                                        <div id="pager_tabla"></div>
                                    <!--</article>-->               
                                </section>                             
                            
                        </fieldset>
                        </div>
                    </div>
        
                
                <div class="col-xs-12" style="padding: 0px; margin-bottom: 10px; ">
                    <div class="input-group input-group-md" style="width: 80%">
                        <span class="input-group-addon" style="width: 165px" style="height: 5px;">Motivo: &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <select id="select_tip_mes" class="input-sm col-xs-8 text-center" style="height: 32px;">
                                       
                                        <option value='1' >DJ Parte</option>
                                        <option value='2' >Automatico</option>
                                        <option value='3' >Judicial</option>
                                        <option value='4' >Otros</option>
          
                            </select><i></i>
                        </div>
                        
                        <div class="col-xs-4">
                            <div class="input-group input-group-md" style="width: 100%">
                            <span class="input-group-addon" style="width: 120px">Fecha de Transferencia &nbsp;<i class="fa fa-calendar"></i></span>
                            <input id="dlg_fec_transferencia" name="dlg_fec_transferencia" type="text"   class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 32px" placeholder="--/--/----" value="{{date('01/m/Y')}}">
                            </div>
                        </div>
                    </div>
                    
                   
                </div>
                
                
                <div class="col-xs-12" style="padding: 0px; margin-bottom: 10px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 165px">Glosa: &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <input id="glosa" type="text"  class="form-control text-uppercase" style="height: 32px;" maxlength="100" onkeyup="javascript:this.value=this.value.toUpperCase();">
                        </div>
                    </div>
                </div>
                              
            </div>
          
        </div>
    </div>
    </div>
@endsection




