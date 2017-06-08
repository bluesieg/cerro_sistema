var global_id_via;
function open_dialog_new_edit_Val_Arancel(tipo, id_arancel) {
    limpiar_ctrl('dialog_new_edit_Val_Arancel');

    $("#val_arancel_nom_via").val('');
    $("#dialog_new_edit_Val_Arancel").dialog({
        autoOpen: false, modal: true, height: 380, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: " + tipo + " ARANCEL :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-primary bg-color-blue",
                click: function () {
                    valores_arancelarios_save_edit(tipo, id_arancel);
                }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-primary bg-color-blue",
                click: function () {
                    $(this).dialog("close");
                }
            }],
        close: function (event, ui) {

        }
    }).dialog('open');
    if (tipo == 'NUEVO') {
        $("#val_arancel_cod_via").prop('disabled', false);
        sector = $("#vw_val_arancel_cb_sector").val();
        mzna = $("#vw_val_arancel_cb_mzna").val();
        $("#val_arancel_sec").val(sector);
        $("#val_arancel_mzna").val(mzna);
    } else if (tipo == 'EDITAR') {
        $("#val_arancel_cod_via").prop('disabled', true);
        sector = $("#vw_val_arancel_cb_sector").val();
        mzna = $("#vw_val_arancel_cb_mzna").val();
        $("#val_arancel_sec").val(sector);
        $("#val_arancel_mzna").val(mzna);
        $("#val_arancel_cod_via").val($.trim($("#table_Val_Arancel").getCell(id_arancel, "cod_via")));
        $("#val_arancel_nom_via").val($.trim($("#table_Val_Arancel").getCell(id_arancel, "nom_via")));
        $("#val_arancel_val_ara").val($.trim($("#table_Val_Arancel").getCell(id_arancel, "val_ara")));
        global_id_via = $("#table_Val_Arancel").getCell(id_arancel, "id_via");
    }

}

function eliminar_val_arancel(id_arancel) {
    
    filtro = $("#vw_val_arancel_cb_anio").val() + $("#val_arancel_sec").val() + $("#val_arancel_mzna").val();
    $.confirm({
        type: 'red',
        title: '.:Cuidado... !',
        content: 'Los Cambios no se podran revertir...',
        buttons: {
            Confirmar: function () {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: 'delete_valor_arancel',
                    type: 'POST',
                    data: {id_arancel: id_arancel},
                    success: function (data) {                        
                        fn_actualizar_grilla('table_Val_Arancel', 'grid_val_arancel?filtro='+filtro);
                    },
                    error: function (data) {
                        alert(' Error al traer Tipo de Documentos');                        
                    }
                });
            },
            Cancelar: function () {}
        }
    });
}

function valores_arancelarios_save_edit(tipo, id_arancel) {
    MensajeDialogLoadAjax('dialog_new_edit_Val_Arancel', '.:: CARGANDO ...');
    filtro = $("#vw_val_arancel_cb_anio").val() + $("#val_arancel_sec").val() + $("#val_arancel_mzna").val();

    if (tipo === 'NUEVO' && id_arancel === undefined) {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'insert_valor_arancel',
            type: 'POST',
            data: {
                anio: $("#vw_val_arancel_cb_anio").val(),
                sec: $("#val_arancel_sec").val(),
                mzna: $("#val_arancel_mzna").val(),
                cod_via: $("#val_arancel_cod_via").val(),
                val_ara: $("#val_arancel_val_ara").val(),
                id_via: global_id_via
            },
            success: function (data) {
                fn_actualizar_grilla('table_Val_Arancel', 'grid_val_arancel?filtro=' + filtro);
                dialog_close('dialog_new_edit_Val_Arancel');
                MensajeDialogLoadAjaxFinish('dialog_new_edit_Val_Arancel', '.:: CARGANDO ...');
            },
            error: function (data) {
                alert(' Error al Guardar Arancel...');
                MensajeDialogLoadAjaxFinish('dialog_new_edit_Val_Arancel', '.:: CARGANDO ...');
            }
        });
    } else if (tipo === 'EDITAR' && id_arancel != undefined) {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'update_valor_arancel',
            type: 'POST',
            data: {
                id_arancel: id_arancel,
                val_ara: $("#val_arancel_val_ara").val()
            },
            success: function (data) {
                fn_actualizar_grilla('table_Val_Arancel', 'grid_val_arancel?filtro=' + filtro);
                dialog_close('dialog_new_edit_Val_Arancel');
                MensajeDialogLoadAjaxFinish('dialog_new_edit_Val_Arancel', '.:: CARGANDO ...');
            },
            error: function (data) {
                alert(' Error al Editar Arancel... !');
                MensajeDialogLoadAjaxFinish('dialog_new_edit_Val_Arancel', '.:: CARGANDO ...');
            }
        });
    }

}

function buscar_val_arancel() {
    MensajeDialogLoadAjax('content', '.:: CARGANDO ...');
    filtro = $('#vw_val_arancel_cb_anio').val() + $('#vw_val_arancel_cb_sector').val() + $('#vw_val_arancel_cb_mzna').val();
    fn_actualizar_grilla('table_Val_Arancel', 'grid_val_arancel?filtro=' + filtro);
    MensajeDialogLoadAjaxFinish('content', '.:: CARGANDO ...');
}

function llenar_combo_mzna(id_sec) {
    MensajeDialogLoadAjax('content', '.:: CARGANDO ...');
    document.getElementById('vw_val_arancel_cb_mzna').options.length = 1;
    anio = $("#vw_val_arancel_cb_anio").val();

    $.ajax({
        url: 'get_mzna_val_arancel',
        type: 'GET',
        data: {id_sec: id_sec},
        success: function (data) {
            for (i = 0; i <= data.length - 1; i++) {
                $('#vw_val_arancel_cb_mzna').append('<option value=' + data[i].codi_mzna + '>' + data[i].codi_mzna + '</option>');
            }
            MensajeDialogLoadAjaxFinish('content', '.:: CARGANDO ...');
            $("#btn_vw_valores_arancelarios_Buscar").click();
        },
        error: function (data) {
            alert(' Error al llenar combo Manzana...');
            MensajeDialogLoadAjaxFinish('content', '.:: CARGANDO ...');
        }
    });

}

function val_arancel_auto_nom_via(cod_via) {
    MensajeDialogLoadAjax('dialog_new_edit_Val_Arancel', '.:: CARGANDO ...');
    get_global_cod_via('val_arancel_nom_via',cod_via);
    MensajeDialogLoadAjaxFinish('dialog_new_edit_Val_Arancel', '.:: CARGANDO ...');
//    $.ajax({
//        url: 'autocomplete_nom_via?cod_via=' + cod_via,
//        type: 'GET',
//        success: function (data) {
//            if (data.msg == 'si') {
//                global_id_via = data.id_via;
//                $("#val_arancel_nom_via").val(data.via_compl);
//                $("#val_arancel_nom_via").attr('maxlength', data.via_compl.length);
//            } else {
//                mensaje_sis('mensajesis', '* El Codigo Ingresado no Existe ... !', ':. Mensaje del Sistema ...!!!');
//            }
//
//        },
//        error: function (data) {
//            alert(' Error al llenar combo Manzana...');
//        }
//    });
}

function click_btn_buscar() {
    $("#btn_vw_valores_arancelarios_Buscar").click();
}




//    $.confirm({        
//        buttons: {
//            Aceptar: {
//                btnClass: 'btn-green',
//                action: function () {}
//            },
//            Cerrar: {
//                btnClass: 'btn-green'
//            }
//        }
//    });