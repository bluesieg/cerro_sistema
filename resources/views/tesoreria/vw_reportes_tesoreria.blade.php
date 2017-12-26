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
                            <td class="text-center" style="width: 80px;"><i class="fa fa-male fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_teso_reportes(1);" >
                                       Reporte de Ingresos Por Partida
                                    </a>
                                    <small>Descripción reporte: Lista de todos los Ingresos por Partida</small>
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

@endsection




