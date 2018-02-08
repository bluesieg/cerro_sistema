function limpiar_dl_ipm(tip)
{
    if(tip==1)
    {
        $('#dlg_codigo_uso').val("");
        $('#dlg_descripcion_uso').val(""); 
    }
}

function nuevo_uso_catastrato()
{

    limpiar_dl_ipm(1);
    $("#dlg_nuevo_uso_catastro").dialog({
        autoOpen: false, modal: true, width: 700, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  USO CATASTRAL  :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Guardar",
            "class": "btn btn-success bg-color-green",
            click: function () {

                guardar_editar_uso_catastral(1);
            }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }],
    });
    $("#dlg_nuevo_uso_catastro").dialog('open');
}

function actualizar_uso_catastro()
{
    limpiar_dl_ipm(1);
    $("#dlg_nuevo_uso_catastro").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  EDITAR USO CATASTRO  :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Guardar",
            "class": "btn btn-success bg-color-green",
            click: function () {
                guardar_editar_uso_catastral(2);
            }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }],
    });
    $("#dlg_nuevo_uso_catastro").dialog('open');


    MensajeDialogLoadAjax('dlg_nuevo_uso_catastro', '.:: Cargando ...');

    id = $("#current_id").val();
    $.ajax({url: 'usos_catastrales/'+id,
        type: 'GET',
        success: function(r)
        {
            $("#id_uso_catastro").val(r[0].id_uso);
            $("#dlg_codigo_uso").val(r[0].codi_uso);
            $("#dlg_descripcion_uso").val(r[0].desc_uso);
            MensajeDialogLoadAjaxFinish('dlg_nuevo_uso_catastro');

        },
        error: function(data) {
            mostraralertas("Hubo un Error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            MensajeDialogLoadAjaxFinish('dlg_nuevo_uso_catastro');
        }
    });
    
}

function guardar_editar_uso_catastral(tipo) {

    codigo = $("#dlg_codigo_uso").val();
    descripcion = $("#dlg_descripcion_uso").val();

    if (codigo == '') {
        mostraralertasconfoco('* El campo Codigo es obligatorio...', 'codigo');
        return false;
    }
    if (descripcion == '') {
        mostraralertasconfoco('* El campo Descripcion es obligatorio...', 'descripcion');
        return false;
    }

    if (tipo == 1) {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'usos_catastrales/create',
            type: 'GET',
            data: {
                codi_uso: codigo,
                desc_uso: descripcion
            },
            success: function (data) {
                dialog_close('dlg_nuevo_uso_catastro');
                fn_actualizar_grilla('tabla_uso_catastro');
                MensajeExito('NUEVO USO CATASTRAL', 'El Uso se a creado correctamente.');
            },
            error: function (data) {
                mostraralertas('* Contactese con el Administrador...');
            }
        });
    }
    else if (tipo == 2) {
        id = $("#current_id").val();
        MensajeDialogLoadAjax('dlg_nuevo_uso_catastro', '.:: CARGANDO ...');
        $.confirm({
            title: '.:Cuidado... !',
            content: 'Los Cambios no se podran revertir...',
            buttons: {
                Confirmar: function () {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: 'usos_catastrales/'+id+'/edit',
                        type: 'GET',
                        data: {
                            id_uso:id,
                            codi_uso: codigo,
                            desc_uso: descripcion
                        },
                        success: function (data) {
                            MensajeExito('Editar Uso Catastro', 'USO: '+ id + '  -  Ha sido Modificado.');
                            fn_actualizar_grilla('tabla_uso_catastro');
                            dialog_close('dlg_nuevo_uso_catastro');
                            MensajeDialogLoadAjaxFinish('dlg_nuevo_uso_catastro', '.:: CARGANDO ...');
                        },
                        error: function (data) {
                            mostraralertas('* Contactese con el Administrador...');
                            MensajeAlerta('Editar Uso Catastro','Ocurrio un Error en la Operacion.');
                            dialog_close('dlg_nuevo_uso_catastro');
                            MensajeDialogLoadAjaxFinish('dlg_nuevo_uso_catastro', '.:: CARGANDO ...');
                        }
                    });
                },
                Cancelar: function () {
                    MensajeAlerta('Editar Uso Catastro','Operacion Cancelada.');
                    MensajeDialogLoadAjaxFinish('dlg_nuevo_uso_catastro', '.:: CARGANDO ...');
                    
                }
            }
        });

    }
}

function eliminar_uso_catastro() {
    

    id = $("#current_id").val();

    $.confirm({
        title: '.:Cuidado... !',
        content: 'Los Cambios no se podran revertir...',
        buttons: {
            Confirmar: function () {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: 'usos_catastrales/destroy',
                    type: 'POST',
                    data: {_method: 'delete', id_uso: id},
                    success: function (data) {
                        fn_actualizar_grilla('tabla_uso_catastro');
                        MensajeExito('Eliminar Uso Catastral', id + ' - Ha sido Eliminado');
                    },
                    error: function (data) {
                        MensajeAlerta('Eliminar Uso Catastral', id + ' - No se pudo Eliminar.');
                    }
                });
            },
            Cancelar: function () {
                MensajeAlerta('Eliminar Uso Catastral','Operacion Cancelada.');
            }

        }
    });
    
}
