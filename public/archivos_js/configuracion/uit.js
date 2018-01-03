function load_list_UIT() {
    jQuery("#table_vw_uit").jqGrid({
        url: 'list_uit',
        datatype: 'json', mtype: 'GET',
        autowidth: true, height: 'auto',

        colNames: ['pk_uit', 'Año', 'UIT', 'Uit Alcab %', 'Tasa Alcab', 'Formatos', '% Min Ivpp', '% Min O Inst'],
        rowNum: 15, sortname: 'pk_uit', sortorder: 'desc', viewrecords: true,
        colModel: [
            {name: 'pk_uit', index: 'pk_uit', hidden: true},
            {name: 'anio', index: 'anio', align: 'center'},
            {name: 'uit', index: 'uit', align: 'center'},
            {name: 'uit_alc', index: 'uit_alc', align: 'center'},
            {name: 'tas_alc', index: 'uit_alc', align: 'center'},
            {name: 'formatos', index: 'formatos', align: 'center'},
            {name: 'porc_min_ivpp', index: 'porc_min_ivpp', align: 'center'},
            {name: 'porc_ot_ins', index: 'porc_ot_ins', align: 'center'},
        ],
        pager: '#pager_table_vw_uit',
        rowList: [15, 25],
        onSelectRow: function (Id) {},
        ondblClickRow: function (Id) {}
    });

    $(window).on('resize.jqGrid', function () {
        $("#table_vw_uit").jqGrid('setGridWidth', $("#content").width());
    });
}

function open_tabla() {
    load_list_UIT();
}
function limpiar_uit(){
    $("#txt_anio").val('');
    $("#txt_uit").val('');
    $("#txt_uit_alc").val('');
    $("#txt_tas_alc").val('');
    $("#txt_formatos").val('');
    $("#txt_15uit").val('');
    $("#txt_60uit").val('');
    $("#txt_60mas").val('');
    $("#txt_min_ivpp").val('');
    $("#txt_ot_ins").val('');
}
function open_dialog_nuevo_uit(tipe, Id)
{   
    Id=$('#table_vw_uit').jqGrid ('getGridParam', 'selrow');
    $("#dialog_open_list_uit").dialog({
        autoOpen: false, modal: true, height: 370, width: 480, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp.: " + tipe + " UIT :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-success bg-color-green",
                click: function () {
                    if (tipe == 'NUEVO') {
                        guardar_uit();                        
                    }
                    if (tipe == 'EDITAR') {
                        modificar_uit(Id);                        
                    }                    
                }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {
                    $(this).dialog("close");
                }
            }],
        close: function (event, ui) {
            limpiar_uit();
        }
    }).dialog('open');


    if (tipe == 'NUEVO') {
        $("#txt_15uit").val(0.2);
        $("#txt_60uit").val(0.6);
        $("#txt_60mas").val(1.0);
        $("#txt_finstalaciones").val("");

    } else {
        $("#txt_anio").val($.trim($("#table_vw_uit").getCell(Id, "anio")));
        $("#txt_uit").val($.trim($("#table_vw_uit").getCell(Id, "uit")));
        $("#txt_uit_alc").val($.trim($("#table_vw_uit").getCell(Id, "uit_alc")));
        $("#txt_tas_alc").val($.trim($("#table_vw_uit").getCell(Id, "tas_alc")));
        $("#txt_formatos").val($.trim($("#table_vw_uit").getCell(Id, "formatos")));
        $("#txt_15uit").val($.trim($("#table_vw_uit").getCell(Id, "deoa15")));
        $("#txt_60uit").val($.trim($("#table_vw_uit").getCell(Id, "de15a60")));
        $("#txt_60mas").val($.trim($("#table_vw_uit").getCell(Id, "mas60")));
        $("#txt_min_ivpp").val($.trim($("#table_vw_uit").getCell(Id, "porc_min_ivpp")));
        $("#txt_ot_ins").val($.trim($("#table_vw_uit").getCell(Id, "porc_ot_ins")));
        $("#txt_finstalaciones").val($.trim($("#table_vw_uit").getCell(Id, "factor_instalaciones")));
    }
}


function open_dialog_new_edit_Oficinas(tipe, id) {
    id=$('#table_vw_oficinas').jqGrid ('getGridParam', 'selrow');
    $("#dialog_open_list_oficinas").dialog({
        autoOpen: false, modal: true, height: 250, width: 440, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4><i class='fa fa-home'></i>&nbsp.: " + tipe + " OFICINA :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-success bg-color-green",
                click: function () {
                    modificar_oficina(tipe, id);
                }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {
                    $(this).dialog("close");
                }
            }]
    }).dialog('open');
    if (tipe === 'NUEVO' && id == undefined) {
        $("#ofi_txt_nombre_textarea").val('');
    } else if (tipe === 'EDITAR' && id != undefined) {
        $("#ofi_txt_nombre_textarea").val($.trim($("#table_vw_oficinas").getCell(id, "nombre")));
    }

}

function guardar_uit() {

    v_anio = $("#txt_anio").val();
    v_uit = $("#txt_uit").val();
    v_uit_alc = $("#txt_uit_alc").val();
    v_tas_alc = $("#txt_tas_alc").val();
    v_formatos = $("#txt_formatos").val();
    v_base_01 = 0;
    v_deoa15 = $("#txt_15uit").val();
    v_tram_01 = 0;
    v_base_02 = 0;
    v_de15a60 = $("#txt_60uit").val();
    v_tram_02 = 0;
    v_base_03 = 0;
    v_mas60 = $("#txt_60mas").val();
    v_porc_min_ivpp = $("#txt_min_ivpp").val();
    v_porc_ot_ins = $("#txt_ot_ins").val();
    fact_instalaciones = $("#txt_finstalaciones").val();

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: 'uit_save',
        data: {anio: v_anio,
            uit: v_uit,
            uit_alc: v_uit_alc,
            tas_alc: v_tas_alc,
            formatos: v_formatos,
            base_01: v_base_01,
            deoa15: v_deoa15.replace(',', '.'),
            tram_01: v_tram_01,
            base_02: v_base_02,
            de15a60: v_de15a60.replace(',', '.'),
            tram_02: v_tram_02,
            base_03: v_base_03,
            mas60: v_mas60.replace(',', '.'),
            porc_min_ivpp: v_porc_min_ivpp,
            porc_ot_ins: v_porc_ot_ins,
            factor_instalaciones: fact_instalaciones
        },
        success: function (data) {
            if (data.msg == 'si') {
                recargar_uit();
                dialog_close('dialog_open_list_uit');
            } else {
                mostraralertas('* Error al Guardar UIT...!');
            }
        }, error: function (data) {
            mostraralertas('* Error de Conexion UIT...!');
        }
    });
}

function modificar_uit(Id) {

    v_anio = $("#txt_anio").val();
    v_uit = $("#txt_uit").val();
    v_uit_alc = $("#txt_uit_alc").val();
    v_tas_alc = $("#txt_tas_alc").val();
    v_formatos = $("#txt_formatos").val();
    v_base_01 = 0;
    v_deoa15 = $("#txt_15uit").val();
    v_tram_01 = 0;
    v_base_02 = 0;
    v_de15a60 = $("#txt_60uit").val();
    v_tram_02 = 0;
    v_base_03 = 0;
    v_mas60 = $("#txt_60mas").val();
    v_porc_min_ivpp = $("#txt_min_ivpp").val();
    v_porc_ot_ins = $("#txt_ot_ins").val();
    f_instalaciones = $("#txt_finstalaciones").val();

    $.confirm({
        type: 'green',
        title: '.:Cuidado... !',
        content: 'Los Cambios no se podran revertir...',
        buttons: {
            Confirmar: function () {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'POST',
                    url: 'uit_mod',
                    data: {pk_uit: Id, anio: v_anio, uit: v_uit, uit_alc: v_uit_alc, tas_alc: v_tas_alc, formatos: v_formatos, base_01: v_base_01, deoa15: v_deoa15, tram_01: v_tram_01, base_02: v_base_02, de15a60: v_de15a60, tram_02: v_tram_02, base_03: v_base_03, mas60: v_mas60, porc_min_ivpp: v_porc_min_ivpp, porc_ot_ins: v_porc_ot_ins, factor_instalaciones: f_instalaciones},
                    success: function (data) {
                        if (data.msg == 'si') {
                            recargar_uit();
                            dialog_close('dialog_open_list_uit');
                        } else {
                            mostraralertas('* Error al modificar UIT...!');
                        }
                    }, error: function (data) {
                        mostraralertas('* Error de Conexion...!');
                    }
                });
            },
            Cancelar: function () {}
        }
    });

}


function modificar_oficina(tipo, Id) {

    v_nombre = ($("#ofi_txt_nombre_textarea").val()).toUpperCase();
    if(v_nombre==''){
        mostraralertasconfoco('* Ingrese el Nombre de la Oficina...','#ofi_txt_nombre_textarea');
        return false;
    }
    if (tipo === 'NUEVO' && Id === undefined) {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: 'oficinas_insert_new',
            data: {nombre: v_nombre, cod_oficina: Id},
            success: function (data) {
                if (data.msg == 'si') {
                    recargar_oficinas();
                    dialog_close('dialog_open_list_oficinas');
                } else {
                    mostraralertas('* Ha ocurrido un error al guardar...!');
                }
            }, error: function (data) {
                mostraralertas('* Error de Conexion...!');
            }
        });
    } else if (tipo === 'EDITAR' && Id != undefined) {
        $.confirm({
            type: 'green',
            title: '.:Cuidado... !',
            content: 'Los Cambios no se podran revertir...',
            buttons: {
                Confirmar: function () {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        type: 'POST',
                        url: 'oficinas_mod',
                        data: {id_ofi: Id, nombre: v_nombre, cod_oficina: Id},
                        success: function (data) {
                            if (data.msg == 'si') {
                                recargar_oficinas();
                                dialog_close('dialog_open_list_oficinas');
                            } else {
                                mostraralertas('* Ha ocurrido un error al momento de guardar...');
                            }
                        }, error: function (data) {
                            mostraralertas('* Error de Conexion...!');
                        }
                    });
                },
                Cancelar: function () {}
            }
        });
    }
}

function config_eliminar_oficina() {
    id=$('#table_vw_oficinas').jqGrid ('getGridParam', 'selrow');
    $.confirm({
        type: 'red',
        title: '.:Cuidado... !',
        content: 'Los Cambios no se podran revertir...',
        buttons: {
            Confirmar: function () {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'POST',
                    url: 'oficinas_delete',
                    data: {id_ofi: id},
                    success: function (data) {
                        if (data.msg == 'si') {
                            recargar_oficinas();
                        } else {
                            mostraralertas('* Ha ocurrido un error al momento de Eliminar...');
                        }
                    }, error: function (data) {
                        mostraralertas('* Error de Conexion...!');
                    }
                });
            },
            Cancelar: function () {}
        }
    });
}

function open_dialog_quitar_uit(Id) {
    Id=$('#table_vw_uit').jqGrid ('getGridParam', 'selrow');
    $.confirm({
        type: 'red',
        title: '.:Cuidado... !',
        content: 'Los Cambios no se podran revertir...',
        buttons: {
            Confirmar: function () {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'POST',
                    url: 'uit_quitar',
                    data: {pk_uit: Id},
                    success: function (data) {
                        if (data.msg == 'si') {
                            recargar_uit();
                        } else {
                            mostraralertas('* Error al Eliminar UIT...!');
                        }
                    }, error: function (data) {
                        mostraralertas('* Error conexion...');
                    }
                });
            },
            Cancelar: function () {}
        }
    });
}

function quitar_uit(Id) {
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: 'uit_quitar',
        data: {pk_uit: Id},
        success: function (data) {
            if (data.msg == 'si') {
                recargar_uit();
                dialog_close('dialog_open_msg_eliminar');
            } else {
                mostraralertas('* Error al Eliminar UIT...!');
            }
        }, error: function (data) {
            mostraralertas('* Error conexion...');
        }
    });
}

function recargar_uit() {
    jQuery("#table_vw_uit").jqGrid('setGridParam', {
        url: 'list_uit'
    }).trigger('reloadGrid');
}

function recargar_oficinas() {
    jQuery("#table_vw_oficinas").jqGrid('setGridParam', {
        url: 'list_oficinas'
    }).trigger('reloadGrid');
}