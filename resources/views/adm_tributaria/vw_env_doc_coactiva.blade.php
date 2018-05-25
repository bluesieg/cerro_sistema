@extends('layouts.app')
@section('content')

<section id="widget-grid" class=""> 
    <div class='cr_content col-xs-12'>
        <div class="col-xs-12">
        <div class="col-lg-9">
            <h1 class="txt-color-green"><b>Envio OP a Coactiva...</b></h1>
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
        <div class="col-xs-6" style="padding-top: 0px; margin-top: 5px"></div>
        <div class="col-lg-6 col-md-12 col-xs-12">
            <ul class="text-right" style="margin-top: 0px !important; margin-bottom: 0px !important">
                    @if( $permisos[0]->btn_imp ==1 )
                    <button type="button" class="btn btn-labeled bg-color-magenta txt-color-white" onclick="print_op()">
                            <span class="btn-label"><i class="glyphicon glyphicon-print"></i></span>Imprimir
                        </button>
                    @else
                        <button type="button" class="btn btn-labeled bg-color-magenta txt-color-white" onclick="sin_permiso()">
                            <span class="btn-label"><i class="glyphicon glyphicon-print"></i></span>Imprimir
                        </button>
                    @endif
            </ul>
        </div>
    
    
    </div>
    <div class='cr_content col-xs-12'>
                
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; padding: 0px !important">
            <table id="tabla_Doc_OP"></table>
            <div id="p_tabla_Doc_OP"></div>
        </article>
    </div>
    <div class='cr_content col-xs-12'>
                
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 50px; padding: 0px !important">
            <table id="tabla_Doc_OP_2"></table>
            <div id="p_tabla_Doc_OP_2"></div>
        </article>
    </div>
    
         
</section>
@section('page-js-script')
<script type="text/javascript">
    $(document).ready(function () {
        $("#menu_admtri").show();
        $("#li_env_doc_a_coac").addClass('cr-active');
        jQuery("#tabla_Doc_OP").jqGrid({
            url: 'recaudacion_get_op/'+$("#selantra").val()+"/0",
            datatype: 'json', mtype: 'GET',
            height: '150', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_gen_fis', 'Nro', 'Fecha','Hora', 'Año','N° Documento', 'Contribuyente o Razon Social','estado','verif','Monto S/.','<button onclick="all_right()">Env. Todos</button>'],
            rowNum: 50, sortname: 'nro_fis', sortorder: 'desc', viewrecords: true, align: "center",caption: 'Recaudación',
            colModel: [
                {name: 'id_gen_fis', index: 'id_gen_fis', hidden: true},
                {name: 'nro_fis', index: 'nro_fis', align: 'center', width: 70},
                {name: 'fec_reg', index: 'fec_reg',  hidden: true},
                {name: 'hora', index: 'hora', hidden:true},
                {name: 'anio', index: 'anio', hidden: true},
                {name: 'nro_doc', index: 'nro_doc',hidden: true},                
                {name: 'contribuyente', index: 'contribuyente', align: 'left', width: 250},
                {name: 'estado', index: 'estado', hidden: true},
                {name: 'verif_env', index: 'verif_env', hidden: true},
                {name: 'monto', index: 'monto', width: 80,align:'center'},
                {name: 'env', index: 'env', width: 80,align:'center'}                

            ],
            pager: '#p_tabla_Doc_OP',
            rowList: [50, 100],
            gridComplete: function () {},
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){}
        });
        jQuery("#tabla_Doc_OP_2").jqGrid({
            url: 'recaudacion_get_op/'+$("#selantra").val()+"/1",
            datatype: 'json', mtype: 'GET',
            height: '150', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_gen_fis', 'Nro', 'Fecha Envio','Hora Env', 'Año','N° Documento', 'Contribuyente o Razon Social','estado','verif','Monto S/.','<button onclick="all_left()">Retornar Todos</button>'],
            rowNum: 50, sortname: 'nro_fis', sortorder: 'desc', viewrecords: true, align: "center",caption: 'coactiva',
            colModel: [
                {name: 'id_gen_fis', index: 'id_gen_fis', hidden: true},
                {name: 'nro_fis', index: 'nro_fis', align: 'center', width: 70},
                {name: 'fec_reg', index: 'fec_reg', align: 'center', width: 75},
                {name: 'hora', index: 'hora', width: 70,align:'center'},
                {name: 'anio', index: 'anio', hidden: true},
                {name: 'nro_doc', index: 'nro_doc',hidden: true},                
                {name: 'contribuyente', index: 'contribuyente', align: 'left', width: 240},
                {name: 'estado', index: 'estado', hidden: true},
                {name: 'verif_env', index: 'verif_env', hidden: true},
                {name: 'monto', index: 'monto', width: 80,align:'center'},             
                {name: 'env', index: 'env', width: 80,align:'center'}                
            ],
            pager: '#p_tabla_Doc_OP_2',
            rowList: [50, 100],
            gridComplete: function () {},
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){}
        });
        $(window).on('resize.jqGrid', function () {
            $("#tabla_Doc_OP").jqGrid('setGridWidth', $("#content_2").width());
        });
        $(window).on('resize.jqGrid', function () {
            $("#tabla_Doc_OP_2").jqGrid('setGridWidth', $("#content_3").width());
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
        var globalvalidador = 0;
        $("#vw_env_doc_contrib").keypress(function (e) {
            if (e.which == 13) {
                if(globalvalidador==0){
                    fn_bus_contrib_env_doc();
                    $("#chk_sel_todo_doc").removeAttr('disabled');
                    globalvalidador=1;
                }else{
                    globalvalidador=0;
                }
            }
        });
    });
</script>
@stop
<script src="{{ asset('archivos_js/adm_tributaria/envio_doc_coactiva.js') }}"></script>
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
