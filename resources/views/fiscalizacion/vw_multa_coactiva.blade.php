@extends('layouts.app')
@section('content')
<section id="widget-grid" class=""> 
    <div class='cr_content col-xs-12'>
        <div class="col-xs-12">
        <div class="col-lg-9">
            <h1 class="txt-color-green"><b>Envio de Multas a Ejecucion Coactiva...</b></h1>
        </div>
        <div class="col-lg-3 col-md-6 col-xs-12">
            <div class="input-group input-group-md">
                <span class="input-group-addon">Año de Trabajo <i class="fa fa-cogs"></i></span>
                <div class="icon-addon addon-md">
                    <select id='selantra' class="form-control col-lg-8" style="height: 32px;" onchange="callfilltab(1)">
                    @foreach ($anio_tra as $anio)
                    <option value='{{$anio->anio}}' >{{$anio->anio}}</option>
                    @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
        
    </div>
    <div class='cr_content col-xs-12'>
                
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; padding: 0px !important">
            <table id="tabla_Doc_multa"></table>
            <div id="p_tabla_Doc_multa"></div>
        </article>
    </div>
    <div class='cr_content col-xs-12'>
                
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 50px; padding: 0px !important">
            <table id="tabla_Doc_multa_2"></table>
            <div id="p_tabla_Doc_multa_2"></div>
        </article>
    </div>
    
         
</section>


@section('page-js-script')
<script type="text/javascript">
    $(document).ready(function () {
        $("#menu_fisca").show();
        $("#li_evn_mul_coactiva").addClass('cr-active');
        jQuery("#tabla_Doc_multa").jqGrid({
            url: 'get_multa/'+$("#selantra").val()+"/0",
            datatype: 'json', mtype: 'GET',
            height: '150', autowidth: true,
            toolbarfilter: true,
            colNames: ['Nro', 'Fecha', 'Año', 'Contribuyente o Razon Social','Monto S/.','<button onclick="all_right()">Env. Todos</button>'],
            rowNum: 15, sortname: 'id_multa_reg', sortorder: 'desc', viewrecords: true, align: "center",caption:"Fiscalización",
            colModel: [                
                {name: 'nro_rd', index: 'nro_rd', align: 'center', width: 70},
                {name: 'fec_reg', index: 'fec_reg', align: 'center', width: 75},
                {name: 'anio', index: 'anio', hidden: true},                               
                {name: 'contribuyente', index: 'contribuyente', align: 'left', width: 250},
                {name: 'monto', index: 'monto', width: 85,align:'center'},
                {name: 'env', index: 'env', width: 85,align:'center'}
            ],
            pager: '#p_tabla_Doc_multa',
            rowList: [15, 20],
            gridComplete: function () {}            
        });
        jQuery("#tabla_Doc_multa_2").jqGrid({
            url: 'get_multa/'+$("#selantra").val()+"/1",
            datatype: 'json', mtype: 'GET',
            height: '150', autowidth: true,
            toolbarfilter: true,
            colNames: ['Nro', 'Fecha', 'Año', 'Contribuyente o Razon Social','Monto S/.','<button onclick="all_right()">Retornar Todos</button>'],
            rowNum: 15, sortname: 'id_multa_reg', sortorder: 'desc', viewrecords: true, align: "center",caption:"Coactiva",
            colModel: [                
                {name: 'nro_rd', index: 'nro_rd', align: 'center', width: 70},
                {name: 'fec_reg', index: 'fec_reg', align: 'center', width: 75},
                {name: 'anio', index: 'anio', hidden: true},                               
                {name: 'contribuyente', index: 'contribuyente', align: 'left', width: 250},
                {name: 'monto', index: 'monto', width: 85,align:'center'},
                {name: 'env', index: 'env', width: 85,align:'center'}

            ],
            pager: '#p_tabla_Doc_multa_2',
            rowList: [15, 20],
            gridComplete: function () {}
        });
        $(window).on('resize.jqGrid', function () {
            $("#tabla_Doc_multa").jqGrid('setGridWidth', $("#content_2").width());
        });
        $(window).on('resize.jqGrid', function () {
            $("#tabla_Doc_multa_2").jqGrid('setGridWidth', $("#content_3").width());
        });
        jQuery("#table_contrib").jqGrid({
            url: 'obtiene_cotriname?dat=0',
            datatype: 'json', mtype: 'GET',
            height: '300px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_pers', 'codigo', 'DNI/RUC', 'contribuyente'],
            rowNum: 20, sortname: 'contribuyente', sortorder: 'asc', viewrecords: true, caption: 'Contribuyentes', align: "center",
            colModel: [
                {name: 'id_pers', index: 'id_pers', hidden: true},
                {name: 'id_per', index: 'id_per', align: 'center', width: 100},
                {name: 'nro_doc', index: 'nro_doc', align: 'center', width: 100},
                {name: 'contribuyente', index: 'contribuyente', align: 'left', width: 260}
            ],
            pager: '#pager_table_contrib',
            rowList: [13, 20],
            gridComplete: function () {
                var idarray = jQuery('#table_contrib').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                    var firstid = jQuery('#table_contrib').jqGrid('getDataIDs')[0];
                    $("#table_contrib").setSelection(firstid);
                }                
            },
            onSelectRow: function (Id) {},
            ondblClickRow: function (Id) { fn_bus_contrib_list_env_doc(Id);}
        });        
    });
</script>
@stop
<script src="{{ asset('archivos_js/fiscalizacion/multas_a_coactiva.js') }}"></script>
<div id="dlg_bus_contr" style="display: none;">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
        <table id="table_contrib"></table>
        <div id="pager_table_contrib"></div>
    </article>
</div>
<div id="dlg_iframe_op" style="display: none;">
    <iframe id="myIframe_op" width="885" height="580"></iframe>
</div>

@endsection
