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

function fn_bus_deuda(per){
    $("#dlg_hidden_contribuyente").val(per);
       
    $("#dlg_contribuyente").val($('#table_contribuyente').jqGrid('getCell',per,'contribuyente'));
    //$("#dlg_hidden_contribuyente").val($('#table_contribuyente').jqGrid('getCell',per,'id_contrib'));
    
    id_contrib=$('#table_contribuyente').jqGrid('getCell',per,'id_contrib');
    fn_actualizar_grilla('tabla_deuda','obtener_deudas?id_contrib='+id_contrib);
    $("#dlg_bus_contribuyente").dialog("close");    
}

function limpiar_dl_prescripcion(tip)
{
    if(tip==1)
    {
        $('#dlg_hidden_contribuyente').val("");
        $('#dlg_contribuyente').val("");
        $('#dlg_nro_resolucion').val("");
        $('#dlg_fecha_resolucion').val("");
        fn_actualizar_grilla('tabla_deuda','obtener_deudas?id_contrib='+0);
        tot_deuda=0;
    }
}

function nueva_prescripcion()
{
    limpiar_dl_prescripcion(1);
    $("#dlg_nueva_prescripcion").dialog({
        autoOpen: false, modal: true, width: 900, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  NUEVA PRESCRIPCION :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Guardar",
            "class": "btn btn-success bg-color-green",
            click: function () {

                guardar_prescripcion();
            }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }]
    });
    $("#dlg_nueva_prescripcion").dialog('open');
}

function guardar_prescripcion() {

    id_contribuyente = $("#dlg_hidden_contribuyente").val();
    nro_resolucion = $("#dlg_nro_resolucion").val();
    fecha_resolucion = $("#dlg_fecha_resolucion").val();
    
    var arreglo = new Array();
      $("input[type=checkbox][id=cta_cte]:checked").each(function() {
           arreglo.push($(this).val());
      });
      s_checks=arreglo.join("and");

   
    if (id_contribuyente == '') {
        mostraralertasconfoco('* El campo Contribuyente es obligatorio...', 'id_contribuyente');
        return false;
    }
    if (s_checks == '') {
        mostraralertasconfoco('* Seleccione una Deuda...');
        return false;
    }
    if (nro_resolucion == '') {
        mostraralertasconfoco('* El campo Nº de Resolucion obligatorio...', 'nro_resolucion');
        return false;
    }
    if (fecha_resolucion == '') {
        mostraralertasconfoco('* El campo Fecha de Resolucion obligatorio...', 'fecha_resolucion');
        return false;
    }
    

    MensajeDialogLoadAjax('dlg_nueva_prescripcion', '.:: CARGANDO ...');
    $.confirm({
        title: '.:Cuidado... !',
        content: 'Esta Seguro de Prescribir la Deuda...',
        buttons: {
            Confirmar: function () {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: 'prescripciones/create',
                    type: 'GET',
                    data: {
                        id_contrib:id_contribuyente,
                        nro_resolucion: nro_resolucion,
                        fecha_resolucion: fecha_resolucion,
                        cta_cte:s_checks,
                        total: $("#total_deuda").val().replace(',', '')
                    },
                    success: function (data) {
                        MensajeExito('Nueva Prescripcion', 'La Prescripcion se a creado correctamente.');
                        fn_actualizar_grilla('tabla_prescripciones');
                        dialog_close('dlg_nueva_prescripcion');
                        MensajeDialogLoadAjaxFinish('dlg_nueva_prescripcion', '.:: CARGANDO ...');
                    },
                    error: function (data) {
                        mostraralertas('* Contactese con el Administrador...');
                        MensajeAlerta('Nueva Prescripcion','Ocurrio un Error en la Operacion.');
                        dialog_close('dlg_nueva_prescripcion');
                        MensajeDialogLoadAjaxFinish('dlg_nueva_prescripcion', '.:: CARGANDO ...');
                    }
                });
            },
            Cancelar: function () {
                MensajeAlerta('Guardar Prescripcion','Operacion Cancelada.');
                MensajeDialogLoadAjaxFinish('dlg_nueva_prescripcion', '.:: CARGANDO ...');

            }
        }
    }); 
}


function selecciona_anio_prescripcion(){
    
    anio = $("#select_anio").val(); 

    jQuery("#tabla_prescripciones").jqGrid('setGridParam', {
         url: 'get_prescripciones?anio='+anio
    }).trigger('reloadGrid');

}


tot_deuda=0;
function check_tot_deuda(val,source){
    if($(source).is(':checked')){
        tot_deuda=tot_deuda+val;
    } else {
        tot_deuda=tot_deuda-val;      
    }
    $("#total_deuda").val(formato_numero(tot_deuda,2,'.',','));
}

function reporte_prescripciones(){
    anio = $("#select_anio").val(); 
    window.open('reporte_preinscripciones?anio='+anio);
}
