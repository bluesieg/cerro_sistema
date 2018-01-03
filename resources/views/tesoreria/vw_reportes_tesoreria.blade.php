@extends('layouts.app')
@section('content')

    <section id="widget-grid" class="">
        <div class='cr_content col-xs-12'>
            <div class="col-xs-12">
                <h1 class="txt-color-green"><b>Reportes de Tesoreria...</b></h1>
            </div>
        </div>
        <!-- row -->
        <div class="row">

            <div class="col-sm-12">

                <div class="well">
                    
                    <table class="table table-striped table-forum">
                        
                        <tbody>

                        <!-- TR -->
                        <tr>
                            <td class="text-center" style="width: 80px;"><i class="fa fa-file-o fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_teso_reportes(1);" >
                                       Reporte de Ingresos Por Partida
                                    </a>
                                    <small>Descripción reporte: Lista de todos los Ingresos por Partida</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                        <tr>
                            <td class="text-center" style="width: 80px;"><i class="fa fa-file-o fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_teso_reportes(2);" >
                                       Reporte de Ingresos Por Tributo
                                    </a>
                                    <small>Descripción reporte: Lista de todos los Ingresos por Tributo</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                        
                                                                      
                        <!-- end TR -->
                        </tbody>
                    </table>

                </div>
            </div>

        </div>

        <!-- end row -->
    </section>
@section('page-js-script')

<script type="text/javascript">
    $(document).ready(function () {
        $("#menu_tesoreria").show();
        $("#li_rep_teso").addClass('cr-active');
        
       
    });
    
    function autocompletar_tributo(textbox){
    $.ajax({
        type: 'GET',
        url: 'autocomplete_tributos',
        success: function (data) {
            var $datos = data;
            $("#tributo").autocomplete({
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
                    
                    return false;
                }
            });
        }
    });
}
</script>

@stop
<script src="{{ asset('archivos_js/tesoreria/reportes.js') }}"></script>


<div id="dialog_por_partida" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <!-- widget div-->
                <div class="row" style="padding: 10px 30px;">
                    
                   
                    <div class="col-xs-12" style="padding: 0px; margin-top: 10px;">
                        <div class="input-group input-group-md" style="width: 100%">
                            <span class="input-group-addon" style="width: 165px">Fecha inicio &nbsp;<i class="fa fa-calendar"></i></span>
                            <div>
                            <input id="fec_ini" name="dlg_fec" type="text"   class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 32px; width: 100%" placeholder="--/--/----" value="{{date('01/m/Y')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12" style="padding: 0px; margin-top: 10px; ">
                        <div class="input-group input-group-md" style="width: 100%">
                            <span class="input-group-addon" style="width: 165px">Fecha fin &nbsp;<i class="fa fa-calendar"></i></span>
                            <div>
                            <input id="fec_fin" name="dlg_fec" type="text"   class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 32px; width: 100%" placeholder="--/--/----" value="{{date('d/m/Y')}}">
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
                <!-- end widget div -->
            </div>
        </div>
    </div>
</div>
<div id="dialog_por_tributo" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <!-- widget div-->
                <div class="row" style="padding: 20px 30px;">
                    
                    <div class="col-xs-12" style="padding: 0px; margin-top: 10px;">
                        <div class="input-group input-group-md" style="width: 100%">
                            <span class="input-group-addon" style="width: 165px">Tributo &nbsp;<i class="fa fa-file-archive-o"></i></span>
                            <div> 
                                 <input type="hidden" id="hiddentributo" value="0">
                                 <input id="tributo" type="text" placeholder="Escriba un tributo" class="form-control" style="height: 32px; padding-left: 10px" >
                            </div>
                        </div> 
                        
                    </div>
                    
                    <div class="col-xs-6" style="padding: 0px; margin-top: 10px;">
                        <div class="input-group input-group-md" style="width: 98%">
                            <span class="input-group-addon" style="width: 165px">Fecha inicio &nbsp;<i class="fa fa-calendar"></i></span>
                            <div>
                            <input id="fec_ini_tributo" name="dlg_fec" type="text"   class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 32px; width: 100%" placeholder="--/--/----" value="{{date('01/m/Y')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6" style=" margin-top: 10px; ">
                        <div class="input-group input-group-md" style="width: 99%">
                            <span class="input-group-addon" style="width: 165px">Fecha fin &nbsp;<i class="fa fa-calendar"></i></span>
                            <div>
                            <input id="fec_fin_tributo" name="dlg_fec" type="text"   class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 32px; width: 100%" placeholder="--/--/----" value="{{date('d/m/Y')}}">
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
                <!-- end widget div -->
            </div>
        </div>
    </div>
</div>


@endsection

