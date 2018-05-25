@extends('layouts.app')
@section('content')
<style>
    .icon-addon .form-control, .icon-addon.addon-md .form-control {
        padding-left: 10px; 
    }
    .vl_check{
        background: white !important;
        margin-top: 3px;
        margin-right: 5px;
    }
</style>
<section id="widget-grid" class=""> 
    <div class='cr_content col-xs-12 '>
        <div class="col-xs-12">
            <h1 class="txt-color-green"><b>Recepcion de Documentos...</b></h1>
        </div>
        
         <div class="col-xs-12 cr-body" >
            
            <div class="col-xs-4" style="padding: 0px; margin-top: 5px">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 0px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-align-justify"></i> </span>
                                <h2>Tipo de Documento a recepcionar</h2>
                        </header>
                    </div>
                </section>
            </div>
            <div class="col-xs-8" style="padding: 0px; margin-top: 5px">
                
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Documento<i class="icon-append fa fa-file-text" style="margin-left: 5px;"></i></span>
                        <div class="icon-addon addon-md">
                            <select id="vw_recep_doc_tip_doc" class="form-control" onchange="llamargrilla()">
                                @foreach ($valores as $val)
                                <option value='{{$val->id_val}}' >{{$val->desc_val}}</option>
                                @endforeach
                                
                            </select>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <div class="col-xs-12"></div>
            <div class="col-xs-4" style="padding: 0px; margin-top: 5px">
                <div>
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 5px; padding: 0px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-align-justify"></i> </span>
                                <h2>Busqueda Por Número de Documento</h2>
                        </header>
                    </div>
                </section>
                </div>
            </div>
            <div class="col-xs-8" style="padding: 0px; margin-top: 5px">
                <div class="col-xs-5" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Del &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div class=""  >
                            <input id="vw_resep_doc_nrode" type="text"   class="form-control text-center" style="height: 32px; width: 100%" >
                        </div>
                    </div>
                </div>
                <div class="col-xs-5" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">AL &nbsp;<i class="fa fa-hashtag"></i></span>
                        <div class=""  >
                            <input id="vw_resep_doc_nroa" type="text" class="form-control text-center" style="height: 32px; width: 100%" >
                        </div>
                    </div>
                </div>
                
                <div class='col-lg-2'style="padding: 0px;" >
                    <button type="button" class="btn btn-labeled bg-color-green txt-color-white" onclick="up_resep_doc(2)">
                        <span class="btn-label"><i class="glyphicon glyphicon-search"></i></span>Buscar
                    </button>
                </div>
            </div>
            <div class="col-xs-12"></div>
            <div class="col-xs-4" style="padding: 0px; margin-top: 5px">
                <div>
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 5px; padding: 0px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-align-justify"></i> </span>
                                <h2>Busqueda Por Fechas</h2>
                        </header>
                    </div>
                </section>
                </div>
            </div>
            <div class="col-xs-8" style="padding: 0px; margin-top: 5px">
                <div class="col-xs-5" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Desde &nbsp;<i class="fa fa-calendar"></i></span>
                        <div class=""  >
                            <input id="vw_resep_doc_fdesde" type="text"   class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 32px; width: 100%" placeholder="--/--/----" value="{{date('d/m/Y')}}">
                        </div>
                    </div>
                </div>
                <div class="col-xs-5" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Hasta &nbsp;<i class="fa fa-calendar"></i></span>
                        <div class=""  >
                            <input id="vw_resep_doc_fhasta" type="text" class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 32px; width: 100%" placeholder="--/--/----" value="{{date('d/m/Y')}}">
                        </div>
                    </div>
                </div>
                <div class='col-lg-2'style="padding: 0px;" >
                    <button type="button" class="btn btn-labeled bg-color-green txt-color-white" onclick="up_resep_doc(1)">
                        <span class="btn-label"><i class="glyphicon glyphicon-search"></i></span>Buscar
                    </button>
                </div>
            </div>
            
        </div>
    </div>
   
    
</section>


<section id="widget-grid" class="">    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -12px">
            
            <div class="well well-sm well-light" style="margin-top:-20px;">                
                <div class="row">
                    <div class="col-xs-12">                        
                        <div class="row">
                            <section id="content_2" class="col col-lg-10" style="padding-right:5px">
                                <table id="t_recep_doc"></table>
                                <div id="p_t_recep_doc"></div>
                            </section>
                            <section class="col col-lg-2" style="padding-left:5px"> 
                                <div style="background: #eee !important;padding:0px 7px; border: 1px solid #DDD;border-radius: 3px;margin-bottom: 8px;">
                                    <form class="smart-form">
                                        <label class="toggle" >
                                            <input type="checkbox" onclick="check_all_resep_doc();" id="chk_sel_todo_doc" disabled="">
                                            <i data-swchon-text="ON" data-swchoff-text="OFF"></i>Check Todo</label>
                                    </form>
                                </div>
                                <button class="btn bg-color-green txt-color-white cr-btn-big" onclick="recibir_doc();" >
                                    <span>
                                        <i class="glyphicon glyphicon-check"></i>
                                    </span>
                                    <label>Recibir Doc.</label>
                                </button>                                                                                             
                            </section>
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
        $("#menu_coactiva").show();
        $("#li_recep_doc").addClass('cr-active');
        tip_bus=$("input:radio[name='myradio_resep_doc']:checked").val();
        jQuery("#t_recep_doc").jqGrid({
            url: 'coactiva_recep_doc?tip_doc='+$("#vw_recep_doc_tip_doc").val(),
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_contrib', 'Nro', 'Fecha','Hora', 'Año','N° Documento', 'Contribuyente o Razon Social','estado','Monto S/.','Recibir'],
            rowNum: 15, sortname: 'id_gen_fis', sortorder: 'desc', viewrecords: true, caption:'Documentos Enviados', align: "center",
            colModel: [
                {name: 'id_contrib', index: 'id_contrib', hidden: true},
                {name: 'nro_fis', index: 'nro_fis', align: 'center', width: 80},
                {name: 'fec_reg', index: 'fec_reg', align: 'center', width: 60},
                {name: 'hora', index: 'hora', align: 'center', width: 60},
                {name: 'anio', index: 'anio', hidden: true},
                {name: 'nro_doc', index: 'nro_doc',hidden: true},                
                {name: 'contribuyente', index: 'contribuyente', align: 'left', width: 250},
                {name: 'estado', index: 'estado', hidden: true},
                {name: 'monto', index: 'monto', width: 85,align:'center'},
                {name: 'recibir', index: 'recibir', width: 60,align:'center'}
            ],
            pager: '#p_t_recep_doc',
            rowList: [15, 20],
            gridComplete: function () {
                var idarray = jQuery('#t_recep_doc').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                        var firstid = jQuery('#t_recep_doc').jqGrid('getDataIDs')[0];
                        $("#t_recep_doc").setSelection(firstid);
                        $("#chk_sel_todo_doc").attr('disabled',false);    
                    }else{
                        $("#chk_sel_todo_doc").attr('disabled',true);
                    }
            },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){}
        });
        $(window).on('resize.jqGrid', function () {
            $("#t_recep_doc").jqGrid('setGridWidth', $("#content_2").width());
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
        $("#vw_recep_doc_contrib").keypress(function (e) {
            if (e.which == 13) {
                if(globalvalidador==0){
                    fn_bus_contrib_recep_doc();
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
<script src="{{ asset('archivos_js/coactiva/resep_doc.js') }}"></script>
<div id="dlg_bus_contr" style="display: none;">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
        <table id="table_contrib"></table>
        <div id="pager_table_contrib"></div>
    </article>
</div>
<div id="vw_coact_ver_doc" style="display: none;">
    <iframe id="vw_coa_iframe_doc" width="885" height="580"></iframe>
</div>

@endsection

