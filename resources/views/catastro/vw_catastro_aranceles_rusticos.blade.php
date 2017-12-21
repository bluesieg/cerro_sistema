@extends('layouts.app')
@section('content')

            <section id="widget-grid" class="">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -12px">
                        <div class="well well-sm well-light">
                            <h1 class="txt-color-green"><b>
                                    <h1 class="txt-color-green"><b>Aranceles Rusticos...</b></h1>
                            <div class="row">

                                <div class="col-xs-4">
                                    <label>Año:</label>
                                    <label class="select">
                                        <select onchange="seleccionar_anio();" id="select_anio" class="input-sm">
                                            @foreach ($anios as $a)
                                                <option value='{{$a->anio}}' >{{$a->anio}}</option>
                                            @endforeach
                                        </select><i></i>
                                    </label>
                                </div>

                                <div class="col-xs-8">
                                    <div class="text-right">
                                        <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="clicknewcatrust();">
                                            <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                        </button>
                                        <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="clickmod_aranrust();">
                                            <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                        </button>
                                        <!--
                                        <button type="button" class="btn btn-labeled bg-color-greenDark txt-color-white" onclick="clicknewmznamasivo();">
                                            <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Crear masivo
                                        </button>-->
                                        <button  type="button" class="btn btn-labeled btn-danger" onclick="delete_aranrust();">
                                            <span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span>Eliminar
                                        </button>



                                        <!--
                                        <button type="button" class="btn btn-labeled bg-color-magenta txt-color-white">
                                            <span class="btn-label"><i class="glyphicon glyphicon-print"></i></span>Imprimir
                                        </button>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" id="current_id" value="0">
                        <table id="tabla_aran_rust"></table>
                        <div id="pager_tabla_aran_rust"></div>
                    </article>
                </div>
            </section>


</section>

@section('page-js-script')
<script type="text/javascript">

    $(document).ready(function () {
        $("#menu_configuracion").show();
        $("#conf_aran_rust").addClass('cr-active');
        seleccionar_anio();
        anio = $("#select_anio").val();

        var pageWidth = $("#tabla_aran_rust").parent().width() - 100;

        jQuery("#tabla_aran_rust").jqGrid({
            url: 'list_aran_pred_rust?anio=' + anio,
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_ara_p_r','Año','id_gpo_tierra','id_gpo_cat','Descripción','categoria','arancel_rustico'],
            rowNum: 20,sortname: 'id_ara_p_r',sortorder: 'asc', viewrecords: true, caption: 'Manzanas', align: "center",
            colModel: [
                {name: 'id_ara_p_r', index: 'id_ara_p_r',hidden: true, align: 'center'},
                {name: 'anio', index: 'sector', align: 'center', width:(pageWidth*(10/100))},
                {name: 'id_gpo_tierra', index: 'id_gpo_tierra',hidden: true, align: 'left'},
                {name: 'id_gpo_cat', index: 'id_gpo_cat',hidden: true, align: 'center'},
                {name: 'gpo_descrip', index: 'arancel_rustico', align: 'left', width:(pageWidth*(50/100))},
                {name: 'categoria', index: 'arancel_rustico', align: 'center', width:(pageWidth*(20/100))},
                {name: 'arancel_rustico', index: 'arancel_rustico', align: 'center', width:(pageWidth*(20/100))}
            ],
            pager: '#pager_tabla_aran_rust',
            rowList: [13, 20],
            gridComplete: function () {
                    var idarray = jQuery('#tabla_aran_rust').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_aran_rust').jqGrid('getDataIDs')[0];
                            $("#tabla_aran_rust").setSelection(firstid);
                        }
                },
            onSelectRow: function (Id){
                $('#current_id').val($("#tabla_aran_rust").getCell(Id, "id_ara_p_r"));

            },
            ondblClickRow: function (Id){
                $('#current_id').val($("#tabla_aran_rust").getCell(Id, "id_ara_p_r"));
                clickmod_aranrust();}
        });

        $(window).on('resize.jqGrid', function () {
            $("#tabla_aran_rust").jqGrid('setGridWidth', $("#content").width());
        });

    });

</script>
@stop

<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/catastro/cat_aran_rust.js') }}"></script>
<div id="dlg_new_edit_aran_rust" style="display: none;">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <div class="panel panel-success">
                    <div class="panel-heading bg-color-success">.:: Datos de la creación ::.</div>
                    <div class="panel-body">
                        <input type="hidden" id="id_ara_p_r" value="0">
                        <fieldset>
                            <div class="row">
                                <section class="col col-xs-12" style="padding-right: 15px; text-align: center">
                                    <label class="label" style="text-align: center">Selecciona grupo de tierra:</label>
                                    <select id="select_grup_tierra" class="input-sm col-xs-12">
                                        @foreach ($grupo_tierras as $gt)
                                            <option value='{{$gt->id_gpo}}' >{{$gt->gpo_descrip}}</option>
                                        @endforeach
                                    </select><i></i>
                                </section>
                            </div>
                                <div class="row">
                                <section class="col col-xs-12" style="padding-right: 15px; text-align: center">
                                    <label class="label" style="text-align: center">Selecciona categoría:</label>
                                    <select id="select_cat_rus" class="input-sm col-xs-12">
                                        @foreach ($grupo_cat_rust as $g_cat_rust)
                                            <option value='{{$g_cat_rust->id_cat}}' >{{$g_cat_rust->categoria}}</option>
                                        @endforeach
                                    </select><i></i>
                                </section>
</div>
                            <div class="row">
                                <section class="col col-xs-12" style="padding-left: 15px">
                                    <label class="label" style="text-align: center">Arancel:</label>
                                    <input class="text-center col-xs-12 form-control" id="arancel_rustico" type="text" name="sector">
                                </section>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection




