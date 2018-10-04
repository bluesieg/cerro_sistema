@extends('layouts.app')
@section('content')
<section id="widget-grid" style="padding-top: 90px;">    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="configuracion_multas" style="margin-bottom: -12px">
            <div class="well well-sm well-light">
                <h1 class="txt-color-green"><b>:: ELIMINAR MULTAS POR OMISION A LA DECLARACION JURADA ::</b></h1>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="text-center">
                            <div class='cr_content col-xs-12 ' style="margin-bottom: 10px;">
                                <div class="col-xs-12 cr-body" >
                                    <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px; margin-top: 0px;">

                                        <section>
                                            <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                                                <header>
                                                        <span class="widget-icon"> <i class="fa fa-info"></i> </span>
                                                        <h2>..:: PARAMETROS DE CONFIGURACION ::..</h2>
                                                </header>
                                            </div>
                                        </section>
                                        
                                        <div class="col-xs-12" style="padding: 0px; margin-top:10px">
                                            <div class="input-group input-group-md" style="width: 100%">
                                                <span class="input-group-addon" style="width: 180px">NOMBRE CONTRIBUYENTE: &nbsp;<i class="fa fa-user"></i></span>
                                                <div>
                                                    <input type="hidden" id="dlg_hidden_contribuyente">
                                                    <input id="dlg_contribuyente" type="text" maxlength="255" class="form-control text-uppercase" style="height: 30px;">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xs-12" style="padding: 0px;margin-top: 10px;margin-bottom: 10px ">
                                            <div class="input-group input-group-md">
                                                <span class="input-group-addon" style="width: 180px">AÑO DE TRABAJO: &nbsp;<i class="fa fa-list"></i></span>
                                                <div>
                                                    <select id='sel_anio' class="form-control col-lg-12" style="height: 32px;">
                                                        <option value="0">--SELECCIONE AÑO--</option>
                                                        @foreach($anio as $sql)
                                                            <option value="{{ $sql->anio }}">{{ $sql->anio }}</option>
                                                        @endforeach                                
                                                    </select>                       
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="col-xs-12" style="padding: 0px; margin-top:10px">
                                            <div class="text-right">   
                                            @if( $permisos[0]->btn_new ==1 )
                                                <button onclick="anular_multa();" type="button" class="btn btn-labeled bg-color-greenLight txt-color-white">
                                                    <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>ANULAR MULTA
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="sin_permiso()">
                                                    <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>SIN PERMISO
                                                </button>
                                            @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div> 
            </div>
        </div>       
    </div>
</section>
@section('page-js-script')

<script type="text/javascript">
$(document).ready(function () {
    $("#menu_admtri").show();
    $("#li_config_multas_tributarias").addClass('cr-active');
    
    jQuery("#table_contribuyente").jqGrid({
        url: 'multas_tributarias/0?show=datos_contribuyentes&dat=0',
        datatype: 'json', mtype: 'GET',
        height: 300, width: 480,
        toolbarfilter: true,
        colNames: ['ID','DNI','PERSONA'],
        rowNum: 12,sortname: 'contribuyente', viewrecords: true, caption: 'LISTADO DE CONTRIBUYENTES', align: "center",
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
            jQuery('#table_contribuyente').jqGrid('bindKeys', {"onEnter": function (rowid) { fn_extraer_datos(rowid); }});
        },
        ondblClickRow: function (Id){fn_extraer_datos(Id);}
    });   
    
    var globalvalidador1=0;
    $("#dlg_contribuyente").keypress(function (e) {
        if (e.which == 13) {
            if(globalvalidador1==0)
            {
                fn_buscar_contrib();
                globalvalidador1=1;
            }
            else
            {
                globalvalidador1=0;
            }
        }
    });
    
});
</script>
@stop
<script src="{{ asset('archivos_js/registro_tributario/multas_tributarias.js') }}"></script>

<div id="dlg_bus_contribuyente" style="display: none;">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
        <table id="table_contribuyente"></table>
        <div id="pager_table_contribuyente"></div>
    </article>
</div> 

@endsection
