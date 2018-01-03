@extends('layouts.app')
@section('content')
<section id="widget-grid" class="">    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -12px">
            <div class="well well-sm well-light">                
                <div class="row">
                    <div class="col-xs-12">                        
                        <div class="text-right">                            
                            <section>
                                <div class="jarviswidget jarviswidget-color-white" style="margin-bottom: 15px;"  >
                                    <header style="background: #01a858 !important;color: white" >
                                            <span class="widget-icon"> <i class="fa fa-align-justify"></i> </span>
                                            <h2>REPORTES COACTIVA</h2>
                                    </header>
                                </div>
                            </section>
                            <div class="row">
                                <section class="col-lg-4" style="padding-right: 5px">
                                    <div class="input-group">
                                        <span class="input-group-addon">Desde<i class="icon-append fa fa-calendar" style="margin-left: 5px;"></i></span>
                                        <input placeholder="dd/mm/aaaa" id="vw_rep_coa_fdesde" class="form-control datepicker" data-dateformat='dd/mm/yy' value="{{'01/01/'.date('Y')}}" maxlength="10" style="padding-left:7px;padding-right:5px">
                                        <span class="input-group-addon">Hasta<i class="icon-append fa fa-calendar" style="margin-left: 5px;"></i></span>
                                        <input placeholder="dd/mm/aaaa" id="vw_rep_coa_fhasta" class="form-control datepicker" data-dateformat='dd/mm/yy' value="{{'31/12/'.date('Y')}}" maxlength="10" style="padding-left:7px;padding-right:5px">
                                        <span class="input-group-btn">
                                            <button class="btn btn-success" id="vw_rep_coa_btnbuscar" type="button" onclick="rango_fecha_rep();" title="BUSCAR">
                                                <i class="glyphicon glyphicon-search"></i>
                                            </button>
                                        </span>
                                    </div>
                                </section> 
                                <section class="col-lg-3" style="padding-left: 5px;padding-right: 5px">
                                    <div class="input-group input-group-md">
                                        <span class="input-group-addon">Materia:</span>
                                        <div class="icon-addon addon-md">
                                            <select id='vw_rep_coa_materia' class="form-control" onchange="fil_materia(this.value);" style="padding-left:5px;padding-right:2px">                                            
                                                <option value='' >TODOS</option>
                                                <option value='1' >TRIBUTARIA</option>
                                                <option value='0' >NO TRIBUTARIA</option>
                                            </select>
                                        </div>
                                    </div>
                                </section>
                                <section class="col-lg-3" style="padding-left: 5px;">
                                    <div class="input-group input-group-md">
                                        <span class="input-group-addon">Estado:</span>
                                        <div class="icon-addon addon-md">
                                            <select id='vw_rep_coa_estado' onchange="fil_estado(this.value);" class="form-control" style="padding-left:5px;padding-right:5px"> 
                                               <option value='' >TODOS</option>
                                               @foreach ($est_exped as $est)                                        
                                                <option value='{{$est->id_est}}' >{{$est->desc_est}}</option>
                                               @endforeach  
                                            </select>
                                        </div>
                                    </div>
                                </section>
                                <section class="col-lg-2 text-align-left" style="padding-left: 5px;">
                                    <button onclick="print_report();" type="button" class="btn btn-labeled bg-color-magenta txt-color-white">
                                        <span class="btn-label"><i class="glyphicon glyphicon-print"></i></span>Imprimir
                                    </button>
                                </section>
                            </div>
                            
                            <div class="row" style="margin-top: 10px">
                                <section class="col-lg-6" style="padding-right: 5px;">
                                    <div class="input-group input-group-md">
                                        <span class="input-group-addon">Valor:</span>
                                        <div class="icon-addon addon-md">
                                            <select id='vw_rep_coa_valor' onchange="fil_valor(this.value);" class="form-control" style="padding-left:5px;padding-right:5px" disabled=""> 
                                               <option value='' >TODOS</option>
                                                 
                                            </select>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div> 
            </div> 
            <div class="well well-sm well-light" style="margin-top:-20px;">                
                <div class="row">
                    <div class="col-xs-12">                        
                        <div class="row">
                            <section id="content_2" class="col-lg-12">
                                <table id="all_tabla_expedientes"></table>
                                <div id="p_all_tabla_expedientes"></div>
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
    memory_glob_dni = '';
    memory_glob_usuario = '';
    $("#menu_coactiva").show();
    $("#li_rep_coa").addClass('cr-active');
    $(document).ready(function () {
        desde = $("#vw_rep_coa_fdesde").val();
        hasta = $("#vw_rep_coa_fhasta").val();
        materia = $("#vw_rep_coa_materia").val();
        estado = $("#vw_rep_coa_estado").val();
        jQuery("#all_tabla_expedientes").jqGrid({
            url: 'rep_exped?desde='+desde+'&hasta='+hasta+'&mat='+materia+'&estado='+estado,
            datatype: 'json', mtype: 'GET',
            height: 329, autowidth: true,
            toolbarfilter: true,
            colNames: ['Expediente', 'Contribuyente', 'Materia', 'Ultimo Documento Emitido', 'Monto', 'Estado','Valor','Dias Atraso'],
            rowNum: 20, sortname: 'id_coa_mtr', sortorder: 'desc', viewrecords: true, align: "center",
            colModel: [
                {name: 'nro_exped', index: 'nro_exped', align: 'center', width: 70},
                {name: 'contribuyente', index: 'contribuyente', align: 'left', width: 180},
                {name: 'materia', index: 'materia', align: 'left', width: 70},
                {name: 'ult_doc', index: 'ult_doc', align: 'left', width: 180},
                {name: 'monto', index: 'monto', align: 'right', width: 60},
                {name: 'estado', index: 'estado', align: 'left', width: 100},
                {name: 'valor', index: 'valor', align: 'left', width: 100},
                {name: 'dias', index: 'dias', align: 'right', width: 60}
            ],
            rowList: [13, 20],
            pager: '#p_all_tabla_expedientes',
            gridComplete: function () {
                var idarray = jQuery('#all_tabla_expedientes').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                    var firstid = jQuery('#all_tabla_expedientes').jqGrid('getDataIDs')[0];
                    $("#all_tabla_expedientes").setSelection(firstid);
                }
            },
            onSelectRow: function (Id) {},
            ondblClickRow: function (Id) {}
        });
        
        $(window).on('resize.jqGrid', function () {
            $("#all_tabla_expedientes").jqGrid('setGridWidth', $("#content_2").width());
        });
        $("#vw_usuario_txt_dni").keypress(function (e) {
            if (e.which == 13) {
                
            }
        });
        
    });
</script>
@stop
<script src="{{ asset('archivos_js/coactiva/reportes.js') }}"></script>

@endsection




