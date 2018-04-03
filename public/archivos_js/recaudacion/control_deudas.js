function fn_bus_contrib()
{
    if($("#dlg_contribuyente").val()=="")
    {
        mostraralertasconfoco("Ingresar Información de busqueda","#dlg_contribuyente"); 
        return false;
    }
    if($("#dlg_contribuyente").val().length<4)
    {
        mostraralertasconfoco("Ingresar al menos 4 caracteres de busqueda","#contribuyente"); 
        return false;
    }

    jQuery("#table_contribuyente").jqGrid('setGridParam', {url: 'obtener_contribuyentes?dat='+$("#dlg_contribuyente").val()}).trigger('reloadGrid');

    $("#dlg_bus_contribuyente").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Busqueda de Contribuyentes :.</h4></div>"       
        }).dialog('open');
       
}
function fn_bus_est_cta_cte(per){
    //$("#dlg_id_contribuyente").val(per);
    anio = $("#dlg_anio").val();
    
    $("#dlg_codigo").val($('#table_contribuyente').jqGrid('getCell',per,'pers_nro_doc'));    
    $("#dlg_contribuyente").val($('#table_contribuyente').jqGrid('getCell',per,'contribuyente'));
    $("#dlg_hidden_contribuyente").val($('#table_contribuyente').jqGrid('getCell',per,'id_contrib'));
    
    id_contrib=$('#table_contribuyente').jqGrid('getCell',per,'id_contrib');
    
    fn_actualizar_grilla('table_predios','get_predios_arbitrios?id_contrib='+id_contrib+'&anio='+anio);
    fn_actualizar_grilla('table_deuda_actual','get_est_cta_cte?id_contrib='+id_contrib+'&anio='+anio);
    $("#dlg_bus_contribuyente").dialog("close");    
}

function selecciona_anio(){
    anio = $("#dlg_anio").val();
    id_contrib = $("#dlg_hidden_contribuyente").val();

        if (id_contrib == '' || anio == '') {
            jQuery("#table_deuda_actual").jqGrid('setGridParam', {
             url: 'get_est_cta_cte?id_contrib='+0+'&anio='+0
            }).trigger('reloadGrid');
            
            
        }else{
            jQuery("#table_deuda_actual").jqGrid('setGridParam', {
             url: 'get_est_cta_cte?id_contrib='+id_contrib+'&anio='+anio
            }).trigger('reloadGrid');

            jQuery("#table_predios").jqGrid('setGridParam', {
             url: 'get_predios_arbitrios?id_contrib='+id_contrib+'&anio='+anio
            }).trigger('reloadGrid');
            
        }       
}

function compensacion_predial(){
    anio = $("#dlg_anio").val();
    tipo = $("#dlg_tipo").val();
    observacion = $("#dlg_observacion").val();
    resolucion = $("#dlg_resolucion").val();
    
    
    id_contrib = $("#dlg_hidden_contribuyente").val();
    monto = $("#dlg_monto").val();
    
    if(id_contrib==""){
        mostraralertasconfoco('El Campo Contribuyente Es Obligatorio','#dlg_contribuyente');
        return false;
    }
    if(anio==""){
        mostraralertasconfoco('El Campo Año Es Obligatorio','#dlg_anio');
        return false;
    }
    
    
    if($("#dlg_arbitrio").is(':checked')){
       var arbitrio = 1;
    }else{
        arbitrio = 0;
    }
    if($("#dlg_predial").is(':checked')){
       var predial = 1;
    }else{
        predial = 0;
    }

    if(observacion==""){
        mostraralertasconfoco('El Campo Observacion Es Obligatorio','#dlg_observacion');
        return false;
    }
    if(resolucion==""){
        mostraralertasconfoco('El Campo Resolucion Es Obligatorio','#dlg_resolucion');
        return false;
    }
    if(monto==""){
        mostraralertasconfoco('El Campo Monto Es Obligatorio','#dlg_monto');
        return false;
    }
    
    $.confirm({
        title: '<h1><b>El Monto Ingresado es: S/.' + monto + '</b></h1>',
        content: '<h4><b>¿Esta Seguro de Continuar con la Operacion?...</b></h4>',
        buttons: {
            Confirmar: function () {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: 'compensacion_predial',
                    type: 'GET',
                    data: { 
                        id_contrib:id_contrib,
                        anio:anio,
                        monto:monto,
                        tipo:tipo,
                        observacion:observacion,
                        resolucion:resolucion,
                        arbitrio:arbitrio,
                        predial:predial
                    },
                    beforeSend: function(){
                          MensajeDialogLoadAjax('content','.: Cargando :.');                
                    },
                    success: function (data) {
                            if (data.msg === 'si'){
                                MensajeExito('Operación Exitosa','Compensacion de Deuda se Ejecuto Correctamente...');
                                MensajeDialogLoadAjaxFinish('content');
                                fn_actualizar_grilla('table_deuda_actual','get_est_cta_cte?id_contrib='+id_contrib+'&anio='+anio);
                                $("#dlg_monto").val("");
                                $('input[name=dlg_check]').attr('checked',false);
                                $("#dlg_observacion").val("");
                                $("#dlg_resolucion").val("");
                            }else{
                                mostraralertas('* Contactese con el Administrador...');
                                MensajeAlerta('Operación Fallida','No, Se Ejecuto La Compensacion de Deuda...');
                                MensajeDialogLoadAjaxFinish('content');
                            }
                    },
                    error: function (data) {
                        MensajeAlerta('Error de Red.', 'Contactese con el Administrador');
                    }
                });
            },
            Cancelar: function () {
                MensajeAlerta('Compensacion de Deuda','Operacion Cancelada.');
                MensajeDialogLoadAjaxFinish('content');

            }
        }
    }); 
}

function cambiarDescripcion(){
    if ($('#dlg_tipo').val() == 0) {
        $('#descripcion').html('Resolucion.'+'&nbsp;<i class="fa fa-hashtag"></i>');
        $('#dlg_resolucion').val("");
    }else{
        $('#descripcion').html('Nº Recibo.'+'&nbsp;<i class="fa fa-hashtag"></i>');
        $('#dlg_resolucion').val("");
    }
}
