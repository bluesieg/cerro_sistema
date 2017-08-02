
function dialog_emi_rec_pag_varios() {
    $("#vw_emision_rec_pag_varios").dialog({
        autoOpen: false, modal: true, width: 850, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.: RECIBOS VARIOS :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-fax'></i>&nbsp; Generar Recibo",
                "class": "btn btn-primary",
                click: function () {
                    insert_Recibos_Master();
                }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {
                    $(this).dialog("close");
                }
            }],
        open: function(){
            limpiar_form_rec_varios();
        }       
    }).dialog('open');
    $("#vw_emi_rec_txt_selec_tip_doc").val('02');
    $("#vw_emi_rec_txt_nro_doc").attr('maxlength',8);
}

function emi_rec_select_tipo_recibo(tipo){
    if(tipo=='00'){
        $("#vw_emi_rec_txt_nro_doc").attr('maxlength',11);
    }else if(tipo=='02'){
        $("#vw_emi_rec_txt_nro_doc").attr('maxlength',8);
    }
}

function autocomplete_tributo(textbox, soles) {
    tributo = $("#vw_emi_rec_txt_tributo").val();
    $.ajax({
        type: 'GET',
        url: 'autocompletar_tributo?tributo=' + tributo.toUpperCase(),
        success: function (data) {
            var $local_sourcetributo = data;
            $("#" + textbox).autocomplete({
                source: $local_sourcetributo,
                focus: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hidden" + textbox).val(ui.item.value);
                    $("#" + textbox).attr('maxlength', ui.item.label.length);
                    $("#" + soles).val(ui.item.soles);
                    return false;
                },
                select: function (event, ui) {
                    $("#" + textbox).val(ui.item.p_recibo);
                    $("#hidden" + textbox).val(ui.item.value);

                    return false;
                }
            });
        }
    });
}
cont = 0;
detalle_total = 0;
function detalle_recibo() {
    val_soles = parseFloat($("#vw_emi_rec_txt_valor").val()) || 0.00;
    cantidad = parseFloat($("#vw_emi_rec_txt_cantidad").val());
    tributo = $("#vw_emi_rec_txt_tributo").val();
    id_tributo = $("#hiddenvw_emi_rec_txt_tributo").val();
    glosa = $("#vw_emi_rec_txt_glosa").val();
    if (tributo == '') {
        mostraralertasconfoco('Ingrese Tributo...', '#vw_emi_rec_txt_tributo');
        return false;
    }
    if (glosa == '') {
        mostraralertasconfoco('Ingrese Glosa...', '#vw_emi_rec_txt_glosa');
        return false;
    }
    if (isNaN(cantidad)) {
        mostraralertasconfoco('Ingrese Cantidad...', '#vw_emi_rec_txt_cantidad');
        return false;
    }
    total = val_soles * cantidad;

    cont++;
    $('#t_dina_det_recibo').append(
            "<tr>\n\
            <td>" + cont + "</td>\n\
            <td><label class='input'>\n\
            <input id='id_tributo_" + cont + "' type='hidden' value='" + id_tributo + "'>\n\
            <input id='glosa_din_" + cont + "' type='text' value='" + (tributo).toUpperCase() + "' disabled='' class='input-xs'></label></td>\n\
            <td><label class='input'><input id='sub_tot_din_" + cont + "' type='text' value='" + formato_numero(total, 3, '.', ',') + "' disabled='' class='input-xs text-align-right' style='font-size:12px'></label></td>\n\
            <td align='center'><button onclick='btn_borrar_detalle(" + cont + ");' class='btn_din' id='btn_eliminar_din_" + cont + "' title='Eliminar'> <img src='img/trash.png' style='width:19px' ></img></button></td>\n\
        </tr>"
            );

    detalle_total = (detalle_total + total);
    $("#vw_em_rec_txt_detalle_total").val(formato_numero(detalle_total, 3, '.', ','));
    for (i = 1; i <= cont; i++) {
        if (i == cont) {
            $("#btn_eliminar_din_" + i).show();
        } else {
            $("#btn_eliminar_din_" + i).hide();
        }
    }
}

function insert_Recibos_Master() {
    MensajeDialogLoadAjax('vw_emision_rec_pag_varios', 'Generando Recibo...');
    $.ajax({
        url: 'emi_recibo_master/create',
        type: 'GET',
        data: {
            id_est_rec: 1,
            glosa: ($("#vw_emi_rec_txt_glosa").val()).toUpperCase(),
            total: $("#vw_em_rec_txt_detalle_total").val().replace(',', ''),
            id_pers:$("#vw_emi_rec_txt_id_pers").val(),
            clase_recibo:1
        },
        success: function (data) {
            if (data) {
                array_detalle_rec(data);
            } else {
                mostraralertas('* Ha Ocurrido un Error al Generar Recibo.<br>* Actualice el Sistema e Intentelo Nuevamente.');
            }
        },
        error: function (data) {
            mostraralertas('* Error al Generar Recibo.<br>* Contactese con el Administrador.');
            MensajeDialogLoadAjaxFinish('vw_emision_rec_pag_varios');
        }
    });
}
function array_detalle_rec(id_recibo) {

    for (i = 1; i <= cont; i++) {
        btn_insert_detalle(i, id_recibo);
        console.log(i);
    }
    MensajeDialogLoadAjaxFinish('vw_emision_rec_pag_varios');
    dialog_close('vw_emision_rec_pag_varios');
    fn_actualizar_grilla('table_Resumen_Recibos', 'grid_Resumen_recibos?fecha=' + $("#vw_emision_reg_pag_fil_fecha").val());
    MensajeExito('Nuevo Recibo', 'El Recibo Ha sido Generado.');
}

function btn_insert_detalle(num, id_recibo) {
    $.ajax({
        url: 'emi_recibo_detalle/create',
        type: 'GET',
        data: {
            id_rec_master: id_recibo,
            id_trib: $("#id_tributo_" + num).val(),
            monto: $("#sub_tot_din_" + num).val(),
            cant: $("#vw_emi_rec_txt_cantidad").val(),
            p_unit:$("#vw_emi_rec_txt_valor").val()
        },
        success: function (data) {
            if (data) {
                return true;
            }
        },
        error: function (data) {
            return false;
        }
    });
}

function btn_borrar_detalle(num) {
    cont--;
    ultimo_soles = $("#sub_tot_din_" + num).val();
    ultimo_soles = ultimo_soles.replace(',', '');

    detalle_total = (detalle_total - ultimo_soles);
    $("#vw_em_rec_txt_detalle_total").val(formato_numero(detalle_total, 3, '.', ','));
    document.getElementById("t_dina_det_recibo").deleteRow(num);
    $("#btn_eliminar_din_" + cont).show();
}

function consultar_persona() {
    nro_doc = $("#vw_emi_rec_txt_nro_doc").val();
    tip_doc = $("#vw_emi_rec_txt_selec_tip_doc").val();
    if (tip_doc == '02' && nro_doc.length <= 7) {
        mostraralertasconfoco('* Dni Incorrecto.', '#vw_emi_rec_txt_nro_doc');
        return false;
    }
    if (tip_doc == '00' && nro_doc.length <= 10) {
        mostraralertasconfoco('* Ruc Incorrecto.', '#vw_emi_rec_txt_nro_doc');
        return false;
    }
    raz_soc = '';
    MensajeDialogLoadAjax('vw_emi_rec_txt_nombres_raz_soc', 'Buscando...');

    $.ajax({
        type: 'GET',
        url: 'emi_recib_buscar_persona?nro_doc=' + nro_doc,
        success: function (result) {
            if (result.msg == 'si') {
                $("#vw_emi_rec_txt_nombres_raz_soc").val(result.raz_soc);
                $("#vw_emi_rec_txt_id_pers").val(result.id_pers);
                MensajeDialogLoadAjaxFinish('vw_emi_rec_txt_nombres_raz_soc');
            } else if (result.msg == 'no') {
                if ($("#vw_emi_rec_txt_selec_tip_doc").val() == '02') {
                    $.ajax({
                        type: 'GET',
                        url: 'http://py-devs.com/api/dni/' + nro_doc + '/?format=json',
                        datatype: 'json',
                        success: function (data) {
                            if(data!==undefined){
                                raz_soc = data.ape_paterno + ' ' + data.ape_materno + ' ' + data.nombres;
                                $("#vw_emi_rec_txt_nombres_raz_soc").val(raz_soc);
                                guardar_new_persona();
                            }                            
                            MensajeDialogLoadAjaxFinish('vw_emi_rec_txt_nombres_raz_soc');                            
                        },
                        error: function (data) {
                            MensajeDialogLoadAjaxFinish('vw_emi_rec_txt_nombres_raz_soc');
                            mostraralertas('* No se Encontró el DNI<br>* Error de Red.<br>* Ingrese los datos Manualmente.');
                        }
                    });
                } else if ($("#vw_emi_rec_txt_selec_tip_doc").val() == '00') {
                    $.ajax({
                        type: 'GET',
                        url: 'http://py-devs.com/api/ruc/' + nro_doc + '/?format=json',
                        datatype: 'json',
                        success: function (data) {
                            if(data!==undefined){
                                $("#vw_emi_rec_txt_nombres_raz_soc").val(data.nombre);
                                guardar_new_persona();
                            }                            
                            MensajeDialogLoadAjaxFinish('vw_emi_rec_txt_nombres_raz_soc');
                        },
                        error: function (data) {
                            mostraralertas('* No se Encontró el RUC<br>Porfavor Ingrese los Datos Manualmente...');
                            MensajeDialogLoadAjaxFinish('vw_emi_rec_txt_nombres_raz_soc');
                        }
                    });
                }
            }
        },
        error: function (result) {
            MensajeDialogLoadAjaxFinish('vw_emi_rec_txt_nombres_raz_soc');
            mostraralertas('* Error de Red.<br>* No se puede ingresar a la Base de Datos.');
        }
    });
}
function guardar_new_persona() {    
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: 'insert_new_persona',
        data: {
            pers_nro_doc:$("#vw_emi_rec_txt_nro_doc").val(),
            pers_raz_soc:$("#vw_emi_rec_txt_nombres_raz_soc").val()
        },
        success: function(data){
            $("#vw_emi_rec_txt_id_pers").val(data);
        }
    });
}

function limpiar_form_rec_varios() {
    $("#vw_emi_rec_txt_nro_doc").val('');
    $("#vw_emi_rec_txt_nombres_raz_soc").val('');

    $("#vw_emi_rec_txt_tributo").val('');
    $("#hiddenvw_emi_rec_txt_tributo").val('');
    $("#vw_emi_rec_txt_cantidad").val('');
    $("#vw_emi_rec_txt_valor").val('');
    $("#vw_emi_rec_txt_glosa").val('');
    $("#vw_em_rec_txt_detalle_total").val('000.000');
    cont = 0;
    detalle_total = 0;
    $("#t_dina_det_recibo > tbody > tr").remove();
}
