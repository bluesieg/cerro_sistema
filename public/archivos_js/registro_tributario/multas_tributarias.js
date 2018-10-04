function fn_buscar_contrib()
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

    jQuery("#table_contribuyente").jqGrid('setGridParam', {url: 'multas_tributarias/0?show=datos_contribuyentes&dat='+$("#dlg_contribuyente").val()}).trigger('reloadGrid');

    $("#dlg_bus_contribuyente").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  BUSQUEDA DE CONTRIBUYENTES :.</h4></div>"       
        }).dialog('open');
       
}

function fn_extraer_datos(per){
    $("#dlg_contribuyente").val($('#table_contribuyente').jqGrid('getCell',per,'contribuyente'));
    $("#dlg_hidden_contribuyente").val($('#table_contribuyente').jqGrid('getCell',per,'id_contrib'));
    $("#dlg_bus_contribuyente").dialog("close");    
}

function anular_multa() {
    
    id_contribuyente = $("#dlg_hidden_contribuyente").val();
    anio = $("#sel_anio").val();
    
    if (id_contribuyente == '') {
        mostraralertasconfoco('* El campo Contribuyente es obligatorio...', '#dlg_contribuyente');
        return false;
    }
    
    if (anio == '0') {
        mostraralertasconfoco('* Debe Seleccionar una opcion...', '#sel_anio');
        return false;
    }
    
    MensajeDialogLoadAjax('configuracion_multas', '.:: Cargando ...');
    
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'multas_tributarias/create',
        type: 'GET',
        data: {
            id_contribuyente:id_contribuyente,
            anio:anio          
        },
        success: function(data) 
        {
            MensajeExito('LA MULTA FUE ANULADA', 'El registro fue guardado Correctamente');
            MensajeDialogLoadAjaxFinish('configuracion_multas');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
 
}
