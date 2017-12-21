function limpiar_dl_aran_rust(tip)
{
    if(tip==1)
    {
        $("#select_cat_rus").val(0);
        $("#select_grup_tierra").val(0);
        $('#arancel_rustico').val("");
    }
}

function clicknewcatrust()
{
    limpiar_dl_aran_rust(1);
    $("#dlg_new_edit_aran_rust").dialog({
        autoOpen: false, modal: true, width: 350, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  NUEVO ARANCEL RÚSTICO :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Guardar",
            "class": "btn btn-success bg-color-green",
            click: function () {

                save_edit_aran_rust(1);
            }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }],
    });
    $("#dlg_new_edit_aran_rust").dialog('open');
}

function clickmod_aranrust()
{
    limpiar_dl_aran_rust(1);
    $("#dlg_new_edit_aran_rust").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  EDITAR ARANCEL RÚSTICO  :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Guardar",
            "class": "btn btn-success bg-color-green",
            click: function () {
                save_edit_aran_rust(2);
            }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }],
    });
    $("#dlg_new_edit_aran_rust").dialog('open');


    MensajeDialogLoadAjax('dlg_new_edit_aran_rust', '.:: Cargando ...');

    id = $("#current_id").val();
    $.ajax({url: 'catastro_aran_rust/'+id,
        type: 'GET',
        success: function(r)
        {
            $("#id_ara_p_r").val(r[0].id_ara_p_r);
            $("#select_grup_tierra").val(r[0].id_gpo_tierra);
            $("#select_cat_rus").val(r[0].id_gpo_cat);
            $("#arancel_rustico").val(r[0].arancel_rustico);
            MensajeDialogLoadAjaxFinish('dlg_new_edit_aran_rust');

        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            MensajeDialogLoadAjaxFinish('dlg_new_edit_aran_rust');
        }
    });
}

function save_edit_aran_rust(tipo) {

    anio = $("#select_anio").val();
    grup_tierra = $("#select_grup_tierra").val();
    cat_rus = $("#select_cat_rus").val();
    arancel_rustico = $("#arancel_rustico").val();

   // alert(id_sect);
    if (arancel_rustico == '') {
        mostraralertasconfoco('* El campo arancel es obligatorio...', 'arancel_rustico');
        return false;
    }

    if (tipo == 1) {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'insert_new_pred_rust',
            type: 'POST',
            data: {
                anio: anio,
                id_gpo_tierra: grup_tierra,
                id_gpo_cat: cat_rus,
                arancel_rustico:arancel_rustico
            },
            success: function (data) {
                dialog_close('dlg_new_edit_aran_rust');
                fn_actualizar_grilla('tabla_aran_rust', 'list_aran_pred_rust?anio=' + anio );
                MensajeExito('Nuevo Arancel Rústico', 'La item se a creado correctamente.');
            },
            error: function (data) {
                mostraralertas('* Contactese con el Administrador...');
            }
        });
    }
    else if (tipo == 2) {
        id = $("#current_id").val();
        MensajeDialogLoadAjax('dialog_new_edit_Contribuyentes', '.:: CARGANDO ...');
        $.confirm({
            title: '.:Cuidado... !',
            content: 'Los Cambios no se podran revertir...',
            buttons: {
                Confirmar: function () {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: 'update_pred_rust',
                        type: 'POST',
                        data: {
                            id_ara_p_r:id,
                            anio: anio,
                            id_gpo_tierra: grup_tierra,
                            id_gpo_cat: cat_rus,
                            arancel_rustico:arancel_rustico

                        },
                        success: function (data) {
                            MensajeExito('Editar Arancel Rústico', 'Arancel Rústico : '+ id + '  -  Ha sido Modificado.');
                            fn_actualizar_grilla('tabla_aran_rust', 'list_aran_pred_rust?anio=' + anio);
                            dialog_close('dlg_new_edit_aran_rust');
                            MensajeDialogLoadAjaxFinish('dlg_new_edit_aran_rust', '.:: CARGANDO ...');
                        },
                        error: function (data) {
                            mostraralertas('* Contactese con el Administrador...');
                            MensajeAlerta('Editar Sector','Ocurrio un Error en la Operacion.');
                            dialog_close('dlg_new_edit_aran_rust');
                            MensajeDialogLoadAjaxFinish('dlg_new_edit_aran_rust', '.:: CARGANDO ...');
                        }
                    });
                },
                Cancelar: function () {
                    MensajeAlerta('Editar Sector','Operacion Cancelada.');
                }
            }
        });

    }
}

function delete_aranrust() {
    id = $("#current_id").val();
    anio = $("#select_anio").val();

    $.confirm({
        title: '.:Cuidado... !',
        content: 'Los Cambios no se podran revertir...',
        buttons: {
            Confirmar: function () {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: 'delete_pred_rust',
                    type: 'POST',
                    data: {id_ara_p_r: id},
                    success: function (data) {
//                        $.alert(raz_soc + '- Ha sido Eliminado');
                        fn_actualizar_grilla('tabla_aran_rust', 'list_aran_pred_rust?anio=' + anio);
                        dialog_close('dlg_new_edit_aran_rust');
                        MensajeExito('Eliminar Arancel Rústico', id + ' - Ha sido Eliminado');
                    },
                    error: function (data) {
                        MensajeAlerta('Eliminar Arancel Rústico', id + ' - No se pudo Eliminar.');
//                        mostraralertas('* Error al Eliminar Contribuyente...');
                    }
                });
            },
            Cancelar: function () {
                MensajeAlerta('Eliminar Sector','Operacion Cancelada.');
            }

        }
    });
}

function seleccionar_anio(){
    
    anio = $("#select_anio").val();

    jQuery("#tabla_aran_rust").jqGrid('setGridParam', {
         url: 'list_aran_pred_rust?anio=' + anio 
    }).trigger('reloadGrid');

}
