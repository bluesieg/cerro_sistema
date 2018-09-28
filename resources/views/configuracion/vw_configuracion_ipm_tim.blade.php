@extends('layouts.app')
@section('content')
<section id="widget-grid" style="padding-top: 90px;">    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="configuracion" style="margin-bottom: -12px">
            <div class="well well-sm well-light">
                <h1 class="txt-color-green"><b>:: CONFIGURACION IPM - TIM ::</b></h1>
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
                                        
                                        <input id="id_institucion" type="hidden" value="{{ $institucion[0]->ide_inst }}">
                                        <div class="col-xs-12" style="padding: 0px; margin-top:10px">
                                            <div class="input-group input-group-md" style="width: 100%">
                                                <span class="input-group-addon" style="width: 192px">APLICAR IPM: &nbsp;<i class="fa fa-hashtag"></i></span>
                                                <div>
                                                    <input id="chkbox_ipm" type="checkbox" class="form-control" style="height: 30px;">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xs-12" style="padding: 0px; margin-top:10px">
                                            <div class="input-group input-group-md" style="width: 100%">
                                                <span class="input-group-addon" style="width: 192px">APLICAR TIM: &nbsp;<i class="fa fa-hashtag"></i></span>
                                                <div>
                                                    <input id="chkbox_tim" type="checkbox" class="form-control" style="height: 30px;">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xs-12" style="padding: 0px; margin-top:10px">
                                            <div class="text-right">   
                                            @if( $permisos[0]->btn_new ==1 )
                                                <button onclick="guardar_datos();" type="button" class="btn btn-labeled bg-color-greenLight txt-color-white">
                                                    <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>GUARDAR CAMBIOS
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="sin_permiso()">
                                                    <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>GRABAR
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
    $("#menu_configuracion").show();
    $("#li_config_ipm_tim").addClass('cr-active');
    
    llamar_datos();    
});
</script>
@stop
<script src="{{ asset('archivos_js/configuracion/configuracion_ipm_tim.js') }}"></script>

@endsection
