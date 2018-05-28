@extends('layouts.app')
@section('content')

            <section id="widget-grid" class="">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -12px">
                        <div class="well well-sm well-light">
                            <h1 class="txt-color-green"><b>
                                    <h1 class="txt-color-green"><b>::TRIBUTOS::</b></h1>
                           <div class="row"> 
                               
                            <div class="col-xs-2">
                                    <label>Año:</label>
                                    <label class="select">
                                        <select onchange="selecciona_anio_oficina();" id="select_anio" class="input-sm">
                                            @foreach ($anio as $anio_t)
                                                <option value='{{$anio_t->anio}}' >{{$anio_t->anio}}</option>
                                            @endforeach
                                        </select><i></i>
                                    </label>
                            </div>
                                    
                            <!-- <div class="col-xs-6">
                                    <label>Oficina:</label>
                                    <label class="select">
                                        <select onchange="selecciona_anio_oficina();" id="select_oficinas" class="input-sm">
                                           @foreach ($oficinas as $oficinas_t)
                                                <option value='{{$oficinas_t->id_ofi}}' >{{$oficinas_t->nombre}}</option>
                                            @endforeach
                                        </select><i></i>
                                    </label>
                            </div> -->

                             <div class="col-xs-3"> 
                                 <input type="hidden" id="hiddenproced_ofi" value="0">
                                <input style="width: 100%;" id="proced_ofi" type="text" placeholder="Escriba el nombre de de la oficina" class="input-sm text-uppercase">
                             </div>
                            
                            <div class="col-xs-3"> 
                                <input style="width: 100%;" id="dlg_buscar_tributos" type="text"  class="input-sm text-uppercase" placeholder="ESCRIBIR EL NOMBRE DEL TRIBUTO">
                            </div>

                            <div class="col-xs-4">
                                <div class="text-left">
                                    
                                    @if( $permisos[0]->btn_new ==1 )
                                        <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="nuevo_tributo();">
                                            <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="sin_permiso();">
                                            <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                        </button>
                                    @endif
                                    @if( $permisos[0]->btn_edit ==1 )
                                        <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="actualizar_tributo();">
                                            <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                        </button>
                                    @else
                                        <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="sin_permiso();">
                                            <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                        </button>
                                    @endif
                                    @if( $permisos[0]->btn_del ==1 )
                                        <button  type="button" class="btn btn-labeled btn-danger" onclick="eliminar_tributo();">
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
                        <table id="tabla_tributo"></table>
                        <div id="pager_table_tributos"></div>
                    </article>
                </div>
            </section>


</section>

@section('page-js-script')
<script type="text/javascript">
    function autocompletar_oficinas(textbox){
    $.ajax({
        type: 'GET',
        url: 'autocomplete_oficinas',
        success: function (data) {
            var $datos = data;
            $("#proced_ofi").autocomplete({
                source: $datos,
                focus: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hidden" + textbox).val(ui.item.value);
                    $("#" + textbox).attr('maxlength', ui.item.label.length);
                    return false;
                },
                select: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hidden" + textbox).val(ui.item.value);
                    selecciona_anio_oficina();
                    return false;
                }
            });
        }
    });
}

    $(document).ready(function () {
        selecciona_anio_oficina();
        autocompletar_oficinas('proced_ofi');
        $("#menu_presupuesto").show();
        $("#li_tributos").addClass('cr-active');
        anio = $("#select_anio").val();
        oficina = $("#hiddenproced_ofi").val();

        var pageWidth = $("#tabla_tributo").parent().width() - 100;

        jQuery("#tabla_tributo").jqGrid({
            url: 'listar_tributos?anio=' + anio + '&id_ofi=' + oficina,
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            toolbarfilter: true,
            colNames: ['ID','PROCEDIMIENTO','TRIBUTO','VALOR','OFICINA'],
            rowNum: 20,sortname: 'id_tributo', viewrecords: true, caption: 'TRIBUTOS', align: "center",
            colModel: [
                {name: 'id_tributo', index: 'id_tributo', align: 'center',width:(pageWidth*(10/100))},
                {name: 'descrip_procedim', index: 'descrip_procedim', align: 'left', width:(pageWidth*(70/100))}, 
                {name: 'descrip_tributo', index: 'descrip_tributo', align: 'left', width:(pageWidth*(70/100))},
                {name: 'soles', index: 'soles', align: 'center', width:(pageWidth*(10/100))},
                {name: 'nombre', index: 'nombre', align: 'center', hidden:true ,width:(pageWidth*(10/100))}

            ],
            pager: '#pager_table_tributos',
            rowList: [10, 20, 30, 40],
            gridComplete: function () {
                    var idarray = jQuery('#tabla_tributo').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_tributo').jqGrid('getDataIDs')[0];
                            $("#tabla_tributo").setSelection(firstid);
                        }
                },
            onSelectRow: function (Id){
                $('#current_id').val($("#tabla_tributo").getCell(Id, "id_tributo"));

            },
            ondblClickRow: function (Id){
                $('#current_id').val($("#tabla_tributo").getCell(Id, "id_tributo"));
                actualizar_tributo();}
        });

        $(window).on('resize.jqGrid', function () {
            $("#tabla_tributo").jqGrid('setGridWidth', $("#content").width());
        });

    });
    
    $("#dlg_buscar_tributos").keypress(function (e) {
        if (e.which == 13) {

               fn_buscar_tributos();

        }
    });


        
</script>
@stop

<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/tributos_gonzalo/tributos.js') }}"></script>

<div id="dlg_nuevo_tributo" style="display: none;">
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
            
                <div class="col-xs-12" style="padding: 0px; margin-bottom: 10px; ">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 165px">Oficina &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <input id="dlg_ofi" type="text"  class="form-control" style="height: 32px;" disabled="">
                        </div>
                    </div>
                </div>
                
                
                <div class="col-xs-12" style="padding: 0px; margin-bottom: 10px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 165px">Procedimiento &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <input type="hidden" id="hidden_nombre_procedimiento" value="0">
                            <!-- <input id="nombre_procedimiento" type="text"  placeholder="Escriba el tipo de Procedimiento" class="form-control text-uppercase" style="height: 32px;"> -->
                            <textarea id="nombre_procedimiento" rows="10" placeholder="Escriba el tipo de Procedimiento" class="form-control text-uppercase"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="padding: 0px; margin-bottom: 10px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 165px">Tributo &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <!-- <input id="nombre_tributo" type="text"  class="form-control text-uppercase" style="height: 32px;" onkeyup="javascript:this.value=this.value.toUpperCase();"  maxlength="200"> -->
                            <textarea id="nombre_tributo" rows="10" class="form-control text-uppercase" onkeyup="javascript:this.value=this.value.toUpperCase();" class="input-sm text-uppercase"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="padding: 0px; ">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 165px">Valor &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div>
                            <input id="valor_tributo" type="text"  class="form-control" style="height: 32px;" maxlength="20" onkeypress="return soloNumeroTab(event);">
                        </div>
                    </div>
                </div>    
            </div>
          
        </div>
    </div>
    
    </div>
@endsection




