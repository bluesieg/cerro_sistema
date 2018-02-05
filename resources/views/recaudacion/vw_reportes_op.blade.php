@extends('layouts.app')
@section('content')

    <section id="widget-grid" class="">
        <div class='cr_content col-xs-12'>
            <div class="col-xs-12">
                <h1 class="txt-color-green"><b>Reportes de Ordenes de Pago...</b></h1>
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
                            <td class="text-center" style="width: 80px;"><i class="fa fa-calendar fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_op_reportes(1);" >
                                        Órdenes de Pago Notificadas
                                    </a>
                                    <small>Descripción reporte: Muestra El Número y Lista de O.P. Notificadas</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                        <tr>
                            <td class="text-center" style="width: 80px;"><i class="fa fa-close fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_op_reportes(3);" >
                                        Órdenes de Pago Sin Notifidar
                                    </a>
                                    <small>Descripción reporte: Muestra El Número y Lista de O.P. Sin Notificar</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                        
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-money fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_op_reportes(2);" >
                                        Órdenes de Pago Pagadas
                                    </a>
                                    <small>Descripción reporte: Muestra El Número y Lista de O.P. Pagadas</small>
                                </h4>
                            </td>
          
                         
                        </tr>
                        <tr>
                            <td class="text-center" style="width: 40px;"><i class="fa fa-money fa-2x text-muted"></i></td>
                            <td>
                                <h4><a href="#" onclick="dlg_op_reportes(4);" >
                                        Órdenes de Pago Emitidas
                                    </a>
                                    <small>Descripción reporte: Muestra El Número y Lista de O.P. Emitidas</small>
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
        $("#menu_recaudacion").show();
        $("#li_rep_op").addClass('cr-active');
     


    });
</script>
@stop
<script src="{{ asset('archivos_js/recaudacion/reportes.js') }}"></script>

<div id="dialog_op" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <!-- widget div-->
                <div class="row" style="padding: 10px 30px;">
                    <div class="col-xs-11">
                        <div class="input-group input-group-md">
                            <span class="input-group-addon">Año de Trabajo <i class="fa fa-cogs"></i></span>
                            <div class="icon-addon addon-md">
                                <select id='selantra' class="form-control col-lg-5" style="height: 32px;" >
                                @foreach ($anio_tra as $anio)
                                <option value='{{$anio->anio}}' >{{$anio->anio}}</option>
                                @endforeach
                                </select>
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




