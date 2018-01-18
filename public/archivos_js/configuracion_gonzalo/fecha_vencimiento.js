function limpiar_dl_fv(tip)
{
    if(tip==1)
    {

        $('#fec_ven').val("");
    }
}

function nuevo_fv()
{
    $("#dlg_anio").val($("#select_anio option:selected").html());
    
    $("#id_anio").val($("#select_anio").val());
    
    limpiar_dl_fv(1);
    $("#dlg_nuevo_fv").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:   FECHA DE VENCIMIENTO  :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Guardar",
            "class": "btn btn-success bg-color-green",
            click: function () {

                guardar_editar_fv(1);
            }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }],
    });
    
    $("#dlg_nuevo_fv").dialog('open');
    
}

function actualizar_fv()
{
     var idarray1 = jQuery('#tabla_fecha_vencimiento').jqGrid('getDataIDs');
                    if (idarray1.length == '') {
                        mostraralertasconfoco('* La tabla esta vacia, no puede usar esta opcion...');
                        }
    else{ 
    $("#id_anio").val($("#select_anio").val());
    limpiar_dl_fv(1);
    $("#dlg_nuevo_fv").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  EDITAR FECHA DE VENCIMIENTO  :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Guardar",
            "class": "btn btn-success bg-color-green",
            click: function () {
                guardar_editar_fv(2);
            }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }],
    });
    $("#dlg_nuevo_fv").dialog('open');


    MensajeDialogLoadAjax('dlg_nuevo_fv', '.:: Cargando ...');

    id = $("#current_id").val();
    $.ajax({url: 'fecha_vencimiento/'+id,
        type: 'GET',
        success: function(r)
        {
            $("#id_pag").val(r[0].id_pag);
            $("#dlg_anio").val(r[0].anio);
            $("#select_trim").val(r[0].trimestre);
            $("#fec_ven").val(r[0].fecha_vencim);
            MensajeDialogLoadAjaxFinish('dlg_nuevo_fv');

        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            MensajeDialogLoadAjaxFinish('dlg_nuevo_fv');
        }
    });
    }
}

function guardar_editar_fv(tipo) {

    id_anio = $("id_anio").val();
    anio = $("#select_anio").val();
    trim = $("#select_trim").val();
    fecha = $("#fec_ven").val();

    if (fecha == '') {
        mostraralertasconfoco('* El campo fecha es obligatorio...');
        return false;
    }

    if (tipo == 1) {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'insertar_nuevo_fv',
            type: 'POST',
            data: {
                id_anio: anio,
                trimestre: trim,
                fecha_vencim: fecha,
                anio: id_anio
            },
            success: function (data) {
                dialog_close('dlg_nuevo_fv');
                fn_actualizar_grilla('tabla_fecha_vencimiento');
                MensajeExito('La FECHA DE VENCIMIENTO se ha creado correctamente.');
            },
            error: function (data) {
                mostraralertas('* Contactese con el Administrador...');
            }
        });
    }
    else if (tipo == 2) {
        
        id = $("#current_id").val();
        MensajeDialogLoadAjax('dlg_nuevo_fv', '.:: CARGANDO ...');
        $.confirm({
            title: '.:Cuidado... !',
            content: 'Los Cambios no se podran revertir...',
            buttons: {
                Confirmar: function () {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: 'modificar_fv',
                        type: 'POST',
                        data: {
                            id_pag: id,
                            id_anio: anio,
                            trimestre: trim,
                            fecha_vencim: fecha,
                            anio: id_anio
                        },
                        success: function (data) {
                            MensajeExito('Editar FECHA DE VENCIMIENTO', 'FECHA DE VENCIMIENTO: '+ id + '  -  Ha sido Modificado.');
                            fn_actualizar_grilla('tabla_fecha_vencimiento');
                            dialog_close('dlg_nuevo_fv');
                            MensajeDialogLoadAjaxFinish('dlg_nuevo_fv', '.:: CARGANDO ...');
                        },
                        error: function (data) {
                            mostraralertas('* Contactese con el Administrador...');
                            MensajeAlerta('Editar FECHA DE VENCIMIENTO','Ocurrio un Error en la Operacion.');
                            dialog_close('dlg_nuevo_fv');
                            MensajeDialogLoadAjaxFinish('dlg_nuevo_fv', '.:: CARGANDO ...');
                        }
                    });
                },
                Cancelar: function () {
                    MensajeAlerta('Editar FECHA DE VENCIMIENTO','Operacion Cancelada.');
                    MensajeDialogLoadAjaxFinish('dlg_nuevo_fv', '.:: CARGANDO ...');
                    
                }
            }
        });

    }
}

function eliminar_fv() {
    
    var idarray1 = jQuery('#tabla_fecha_vencimiento').jqGrid('getDataIDs');
                    if (idarray1.length == '') {
                        mostraralertasconfoco('* La tabla esta vacia, no puede usar esta opcion...');
                        }
    else{  
    id = $("#current_id").val();
    $.confirm({
        title: '.:Cuidado... !',
        content: 'Los Cambios no se podran revertir...',
        buttons: {
            Confirmar: function () {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: 'eliminar_fv',
                    type: 'POST',
                    data: {id_pag: id},
                    success: function (data) {
                        fn_actualizar_grilla('tabla_fecha_vencimiento');
                        MensajeExito('Eliminar FECHA DE VENCIMIENTO', id + ' - Ha sido Eliminado');
                    },
                    error: function (data) {
                        MensajeAlerta('Eliminar FECHA DE VENCIMIENTO', id + ' - No se pudo Eliminar.');
                    }
                });
            },
            Cancelar: function () {
                MensajeAlerta('Eliminar FECHA DE VENCIMIENTO','Operacion Cancelada.');
            }

        }
    });
    }
}

function selecciona_anio(){
    
    aniox = $("#select_anio").val();

    jQuery("#tabla_fecha_vencimiento").jqGrid('setGridParam', {
         url: 'listar_fecha_vencimiento?anio=' + aniox 
    }).trigger('reloadGrid');

}