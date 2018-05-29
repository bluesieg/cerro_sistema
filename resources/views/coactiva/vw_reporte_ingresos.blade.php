@extends('layouts.app')
@section('content')
<section id="widget-grid" class="">
        <div class="col-xs-5" style="padding: 0px; margin-top: 5px">
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
            <div class="col-xs-7" style="padding: 0px; margin-top: 5px">
                <div class="col-xs-6" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Desde &nbsp;<i class="fa fa-calendar"></i></span>
                        <div class=""  >
                            <input id="dlg_bus_fini" type="text"   class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 32px; width: 100%" placeholder="--/--/----" value="{{date('d/m/Y')}}">
                        </div>
                    </div>
                </div>
                <div class="col-xs-6" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Hasta &nbsp;<i class="fa fa-calendar"></i></span>
                        <div class=""  >
                            <input id="dlg_bus_ffin" type="text" class="datepicker text-center" data-dateformat='dd/mm/yy' data-mask="99/99/9999" style="height: 32px; width: 100%" placeholder="--/--/----" value="{{date('d/m/Y')}}">
                        </div>
                    </div>
                </div>
                
            </div>
        <!-- row -->
        <div class="row">

            <div class="col-sm-12">

                <div class="well">

                    <table class="table table-striped table-forum">
                        <thead>
                        <tr>
                            <th colspan="2" style="width: 100%;">REPORTES</th>
                        </tr>
                        </thead>
                        <tbody>
                            
                        <!-- TR -->
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-group fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_reporte_coactivo(1);" id="titulo_r1">
                                        Reporte Ingresos Coactiva
                                    </a>
                                    <small>Muestra Ingresos Coactivos de una fecha a otra fecha</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>

        <!-- end row -->
    </section>
@section('page-js-script')
<script type="text/javascript">
    $("#menu_coactiva").show();
    $("#li_rep_coa_ingresos").addClass('cr-active');
    
</script>
@stop
<script src="{{ asset('archivos_js/coactiva/reportes.js') }}"></script>


@endsection




