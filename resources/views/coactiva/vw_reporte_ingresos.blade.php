@extends('layouts.app')
@section('content')
<section id="widget-grid" class="">    
    
</section>
@section('page-js-script')
<script type="text/javascript">
    $("#menu_coactiva").show();
    $("#li_rep_coa_ingresos").addClass('cr-active');
    $(document).ready(function () {
        $("#dlg_rango_fecha").dialog({
            autoOpen: false, modal: true, width: 600,height: 'auto', show: {effect: "fade", duration: 300}, resizable: false,
            title: "<div class='widget-header'><h4>.: REPORTES :.</h4></div>",
            buttons: [{
                    html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                    "class": "btn btn-primary",
                    click: function () { 
                        
                    }
                }, {
                    html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                    "class": "btn btn-danger",
                    click: function () {$(this).dialog("close");}
                }],
            open: function(){ },
            close:function(){ }
        }).dialog('open');        
    });
</script>
@stop
<script src="{{ asset('archivos_js/coactiva/reportes.js') }}"></script>
<div id="dlg_rango_fecha" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">                
                <div class="panel panel-success" style="border: 0px !important">
                    <div class="panel-heading bg-color-success" >.:: Reporte de Ingresos ::.</div>
                    <div class="panel-body">
                        <fieldset>
                            <section class="col-lg-8" style="padding-right: 5px">
                                <div class="input-group">
                                    <span class="input-group-addon">Desde<i class="icon-append fa fa-calendar"></i></span>
                                    <input placeholder="dd/mm/aaaa" id="vw_rep_coa_fdesde" class="form-control datepicker" data-dateformat='dd/mm/yy' value="{{'01/01/'.date('Y')}}" maxlength="10" >
                                    <span class="input-group-addon">Hasta<i class="icon-append fa fa-calendar"></i></span>
                                    <input placeholder="dd/mm/aaaa" id="vw_rep_coa_fhasta" class="form-control datepicker" data-dateformat='dd/mm/yy' value="{{'31/12/'.date('Y')}}" maxlength="10" >
                                    <span class="input-group-btn">
                                        <button class="btn btn-success" id="vw_rep_coa_btnbuscar" type="button" onclick="rango_fecha_rep();" title="BUSCAR">
                                            <i class="glyphicon glyphicon-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </section>
                        </fieldset>
                    </div>
                </div>               
            </div>
        </div>
    </div>
</div>

@endsection




