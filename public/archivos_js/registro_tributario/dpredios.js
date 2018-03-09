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

function fn_bus_contrib_predio(per){
    $("#dlg_id_contribuyente").val(per);
    anio = $("#dlg_anio").val();
    
    $("#dlg_codigo").val($('#table_contribuyente').jqGrid('getCell',per,'pers_nro_doc'));    
    $("#dlg_contribuyente").val($('#table_contribuyente').jqGrid('getCell',per,'contribuyente'));
    $("#dlg_hidden_contribuyente").val($('#table_contribuyente').jqGrid('getCell',per,'id_contrib'));
    
    //tam=($('#table_contrib').jqGrid('getCell',per,'contribuyente')).length;
    //$("#vw_caja_est_cta_contrib").attr('maxlength',tam);
    
    id_pers=$('#table_contribuyente').jqGrid('getCell',per,'id_contrib');
    fn_actualizar_grilla('tabla','obtener_predios?id_contrib='+id_pers+'&anio='+anio);
    $("#dlg_bus_contribuyente").dialog("close");    
}



function limpiar_dl_dpredios(tip)
{
    if(tip==1)
    {
        $('#dlg_glosa').val("");
        $('#dlg_contribuyente').val("");
        $('#dlg_codigo').val("");
        fn_actualizar_grilla('tabla','obtener_predios?id_contrib='+0+'&anio='+0);
        $('#dlg_hidden_contribuyente').val("");
        $('#current_id_tabla').val("");
    }
}

function nuevo_dpredios()
{
    limpiar_dl_dpredios(1);
    $("#dlg_nuevo_dpredios").dialog({
        autoOpen: false, modal: true, width: 1000, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  DESCARGAR PREDIOS :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Guardar",
            "class": "btn btn-success bg-color-green",
            click: function () {
                guardar_predio();
            }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }],
    });
    $("#dlg_nuevo_dpredios").dialog('open');
}


function guardar_predio() {

    fecha_trasnferencia = $("#dlg_fecha").val();
    id_predio_contribuyente = $("#current_id_tabla").val();
    motivo = $("#dlg_motivos").val();
    glosa = $("#dlg_glosa").val();
    contribuyente = $("#dlg_hidden_contribuyente").val();
    comprador = $("#dlg_comprador").val();
    
    if (contribuyente == '') {
        mostraralertasconfoco('* El campo Contribuyente es obligatorio...', 'contribuyente');
        return false;
    }
    if (glosa == '') {
        mostraralertasconfoco('* El campo Glosa es obligatorio...', 'glosa');
        return false;
    }

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'descarga_predios/create',
            type: 'GET',
            data: {
                fch_transf:fecha_trasnferencia,
                id_pred_contrib: id_predio_contribuyente,
                glosa: glosa,
                motivo: motivo,
                id_contribuyente: contribuyente,
                comprador:comprador
            },
            success: function (data) {
                if (data.msg === 'si'){
                    mostraralertasconfoco('* DEBE SELECCIONAR UN PREDIO');
                }else{
                    fn_actualizar_grilla('tabla_descarga_predios');
                    dialog_close('dlg_nuevo_dpredios');
                    MensajeExito('Nueva Descarga de Predios', 'La Descarga se ha creado correctamente.');
                }
            },
            error: function (data) {
                mostraralertas('* Contactese con el Administrador...');
            }
        });
}


function selecciona_fecha(){
    
    fecha_desde = $("#dlg_fec_desde").val(); 
    fecha_hasta = $("#dlg_fec_hasta").val(); 

    jQuery("#tabla_descarga_predios").jqGrid('setGridParam', {
         url: 'obtener_descarga_predios?fecha_desde='+fecha_desde +'&fecha_hasta='+fecha_hasta
    }).trigger('reloadGrid');

}

function obtener_predios_anio(){
    
    anio = $("#dlg_anio").val(); 
    id_contrib = $("#dlg_hidden_contribuyente").val(); 

    jQuery("#tabla").jqGrid('setGridParam', {
         url: 'obtener_predios?id_contrib='+id_contrib +'&anio='+anio
    }).trigger('reloadGrid');

}

function verDocumento(id)
{
    window.open('ver_documentos/'+id);
}
