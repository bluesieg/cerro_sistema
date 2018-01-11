function limpiar_dl_ipm(tip)
{
    if(tip==1)
    {

        $('#valor').val("");
    }
}

function nuevo_ipm()
{
    $("#dlg_anio").val($("#select_anio option:selected").html());
    
    $("#id_anio").val($("#select_anio").val());
    
    limpiar_dl_ipm(1);
    $("#dlg_nuevo_ipm").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  INDICE DE PRECIOS AL POR MAYOR  :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Guardar",
            "class": "btn btn-success bg-color-green",
            click: function () {

                guardar_editar_ipm(1);
            }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }],
    });
    $("#dlg_nuevo_ipm").dialog('open');
}

function actualizar_ipm()
{
    
     var idarray = jQuery('#tabla_ipm').jqGrid('getDataIDs');
        if (idarray.length == '') {
        mostraralertasconfoco('* No Existen Registros en la Tabla...', 'dlg_anio');
        }else{
            
    $("#id_anio").val($("#select_anio").val());
    limpiar_dl_ipm(1);
    $("#dlg_nuevo_ipm").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  EDITAR INDICE DE PRECIOS AL POR MAYOR  :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Guardar",
            "class": "btn btn-success bg-color-green",
            click: function () {
                guardar_editar_ipm(2);
            }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }],
    });
    $("#dlg_nuevo_ipm").dialog('open');


    MensajeDialogLoadAjax('dlg_nuevo_ipm', '.:: Cargando ...');

    id = $("#current_id").val();
    $.ajax({url: 'ipm/'+id,
        type: 'GET',
        success: function(r)
        {
            $("#id_ipm").val(r[0].id_ipm);
            $("#dlg_anio").val(r[0].anio);
            $("#select_tip_mes").val(r[0].mes);
            $("#valor").val(r[0].valor);
            MensajeDialogLoadAjaxFinish('dlg_nuevo_ipm');

        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            MensajeDialogLoadAjaxFinish('dlg_nuevo_ipm');
        }
    });
    
      }
}

function guardar_editar_ipm(tipo) {

    id_anio = $("id_anio").val();
    anio = $("#select_anio").val();
    mes = $("#select_tip_mes").val();
    valor = $("#valor").val();

    if (valor == '') {
        mostraralertasconfoco('* El campo Valor obligatorio...', 'valor');
        return false;
    }

    if (tipo == 1) {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'insertar_nuevo_ipm',
            type: 'POST',
            data: {
                id_anio: anio,
                mes: mes,
                valor: valor
            },
            success: function (data) {
                dialog_close('dlg_nuevo_ipm');
                fn_actualizar_grilla('tabla_ipm');
                MensajeExito('Nuevo IPM', 'La IPM se a creado correctamente.');
            },
            error: function (data) {
                mostraralertas('* Contactese con el Administrador...');
            }
        });
    }
    else if (tipo == 2) {
        id = $("#current_id").val();
        MensajeDialogLoadAjax('dlg_nuevo_ipm', '.:: CARGANDO ...');
        $.confirm({
            title: '.:Cuidado... !',
            content: 'Los Cambios no se podran revertir...',
            buttons: {
                Confirmar: function () {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: 'modificar_ipm',
                        type: 'POST',
                        data: {
                            id_ipm:id,
                            id_anio: id_anio,
                            mes: mes,
                            valor: valor
                        },
                        success: function (data) {
                            MensajeExito('Editar IPM', 'IPM: '+ id + '  -  Ha sido Modificado.');
                            fn_actualizar_grilla('tabla_ipm');
                            dialog_close('dlg_nuevo_ipm');
                            MensajeDialogLoadAjaxFinish('dlg_nuevo_ipm', '.:: CARGANDO ...');
                        },
                        error: function (data) {
                            mostraralertas('* Contactese con el Administrador...');
                            MensajeAlerta('Editar IPM','Ocurrio un Error en la Operacion.');
                            dialog_close('dlg_nuevo_ipm');
                            MensajeDialogLoadAjaxFinish('dlg_nuevo_ipm', '.:: CARGANDO ...');
                        }
                    });
                },
                Cancelar: function () {
                    MensajeAlerta('Editar IPM','Operacion Cancelada.');
                    MensajeDialogLoadAjaxFinish('dlg_nuevo_ipm', '.:: CARGANDO ...');
                    
                }
            }
        });

    }
}

function eliminar_ipm() {
    
    var idarray = jQuery('#tabla_ipm').jqGrid('getDataIDs');
        if (idarray.length == '') {
        mostraralertasconfoco('* No Existen Registros en la Tabla...', 'dlg_anio');
        }else{
    
    id = $("#current_id").val();

    $.confirm({
        title: '.:Cuidado... !',
        content: 'Los Cambios no se podran revertir...',
        buttons: {
            Confirmar: function () {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: 'eliminar_ipm',
                    type: 'POST',
                    data: {id_ipm: id},
                    success: function (data) {
                        fn_actualizar_grilla('tabla_ipm');
                        MensajeExito('Eliminar IPM', id + ' - Ha sido Eliminado');
                    },
                    error: function (data) {
                        MensajeAlerta('Eliminar IPM', id + ' - No se pudo Eliminar.');
                    }
                });
            },
            Cancelar: function () {
                MensajeAlerta('Eliminar IPM','Operacion Cancelada.');
            }

        }
    });
    }
    
}

function selecciona_anio(){
    
    aniox = $("#select_anio").val();

    jQuery("#tabla_ipm").jqGrid('setGridParam', {
         url: 'listar_ipm?anio=' + aniox 
    }).trigger('reloadGrid');

}