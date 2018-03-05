
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
    autocomplete_tributo('vw_emi_rec_txt_tributo', 'vw_emi_rec_txt_valor');
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
                    $("#" + textbox).val(ui.item.p_recibo);
                    $("#hidden" + textbox).val(ui.item.value);
                    return false;
                },
                select: function (event, ui) {
                    $("#" + textbox).val(ui.item.p_recibo);
                    $("#hidden" + textbox).val(ui.item.value);
                    $("#vw_emi_rec_txt_nrecibo").val('0');
                    $("#vw_emi_rec_txt_glosa").val('');
                     $("#vw_emi_rec_txt_direc").val('');
                    traer_tributos_sin_valor();
                    traer_alcabala();
                    return false;
                }
            });
        }
    });
}

function traer_tributos_sin_valor(){
    
    valor = $('#hiddenvw_emi_rec_txt_tributo').val();
    
    $.ajax({
        url: 'traer_tributos_sin_valor?valor=' + valor,
        type: 'GET',
        success: function (data) {
            if (data.msg == 'si'){
                    $("#vw_emi_rec_txt_valor").removeAttr('disabled');            
                }else{
                    $("#vw_emi_rec_txt_valor").attr('disabled', 'disabled');  
                }
        },
        error: function (data) {
            mostraralertas('* Error al Generar Recibo.<br>* Contactese con el Administrador.');
            MensajeDialogLoadAjaxFinish('vw_emision_rec_pag_varios');
        }
    }); 
}


function traer_alcabala(){
    
    alcabala = $('#hiddenvw_emi_rec_txt_tributo').val();
    
    $.ajax({
        url: 'traer_alcabala?alcabala=' + alcabala,
        type: 'GET',
        success: function (data) {
            if (data.msg == 'si'){
                    $("#vw_emi_rec_txt_valor").removeAttr('disabled');
                    $("#vw_emi_rec_txt_nrecibo").removeAttr('disabled');
                    $("#btn_agregar_insertar").attr('disabled', 'disabled'); 
                    
                }else{
                    $("#vw_emi_rec_txt_valor").attr('disabled', 'disabled');  
                    $("#vw_emi_rec_txt_nrecibo").attr('disabled', 'disabled');
                    $("#btn_agregar_insertar").removeAttr('disabled');
                }
        },
        error: function (data) {
            mostraralertas('* Error al Generar Recibo.<br>* Contactese con el Administrador.');
            MensajeDialogLoadAjaxFinish('vw_emision_rec_pag_varios');
        }
    }); 
}

function validacion_alcabala(){
    
    nro_recibo = $('#vw_emi_rec_txt_nrecibo').val();
    
    $.ajax({
        url: 'validar_alcabala?nro_recibo=' + nro_recibo,
        type: 'GET',
        beforeSend: function(){
              MensajeDialogLoadAjax('vw_emision_rec_pag_varios', '.:: Cargando ...');                 
        },
        success: function (data) {
         
            if (data.msg === 'PAGADO'){
                    mostraralertasconfoco("EL RECIBO ESTA PAGADO","#vw_emi_rec_txt_nrecibo"); 
                    MensajeDialogLoadAjaxFinish('vw_emision_rec_pag_varios');
                    $("#btn_agregar_insertar").attr('disabled', 'disabled');          
                }else if (data.msg === 'VIGENTE'){
                    MensajeExito('Estado del Recibo: ', data.msg);
                    MensajeDialogLoadAjaxFinish('vw_emision_rec_pag_varios');
                    $("#btn_agregar_insertar").removeAttr('disabled');
                }else if(data.msg === 'no-existe'){
                    mostraralertasconfoco("EL NUMERO DE RECIBO NO EXISTE","#vw_emi_rec_txt_nrecibo");
                    $("#vw_emi_rec_txt_glosa").val('EL NUMERO DE RECIBO NO EXISTE');
                    MensajeDialogLoadAjaxFinish('vw_emision_rec_pag_varios');
                    $("#btn_agregar_insertar").attr('disabled', 'disabled');    
                }else{
                    mostraralertasconfoco("EL NUMERO DE RECIBO NO EXISTE","#vw_emi_rec_txt_nrecibo");
                    $("#vw_emi_rec_txt_glosa").val('EL NUMERO DE RECIBO NO EXISTE');
                    MensajeDialogLoadAjaxFinish('vw_emision_rec_pag_varios');
                    $("#btn_agregar_insertar").attr('disabled', 'disabled');
                }
        },
        error: function (data) {
            mostraralertas('* Error al Generar Recibo.<br>* Contactese con el Administrador.');
            MensajeDialogLoadAjaxFinish('vw_emision_rec_pag_varios');
        }
    }); 
}

function traer_glosa(){
    
    nro_recibo = $('#vw_emi_rec_txt_nrecibo').val();
    
    $.ajax({
        url: 'traer_glosa?nro_recibo=' + nro_recibo,
        type: 'GET',
        success: function (data) {
            
            if (data){
                    $("#vw_emi_rec_txt_glosa").val("IMPUESTO DE ALCABALA N° " + data); 
                }else if(data === 'no-existe'){
                    $("#vw_emi_rec_txt_glosa").val('EL NUMERO DE RECIBO NO EXISTE'); 
                }else{
                    $("#vw_emi_rec_txt_glosa").val('EL NUMERO DE RECIBO NO EXISTE'); 
                }
        },
        error: function (data) {
            mostraralertas('* Error al Generar Recibo.<br>* Contactese con el Administrador.');
            MensajeDialogLoadAjaxFinish('vw_emision_rec_pag_varios');
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
    direccion = $("#vw_emi_rec_txt_direc").val();
    $("#vw_emi_rec_txt_valor").attr('disabled', 'disabled');  
    $("#vw_emi_rec_txt_nrecibo").attr('disabled', 'disabled');
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
            <td><label class='input'><input id='sub_tot_din_" + cont + "' type='text' value='" + formato_numero(total, 3, '.', '') + "' disabled='' class='input-xs text-align-right' style='font-size:12px'></label></td>\n\
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
    rowCount = $('#t_dina_det_recibo > tbody > tr').length;
    if(rowCount==0){        
        return false;
    }    
    MensajeDialogLoadAjax('vw_emision_rec_pag_varios', 'Generando Recibo...');
    $.ajax({
        url: 'emi_recibo_master/create',
        type: 'GET',
        data: {
            id_est_rec: 1,
            direccion: ($("#vw_emi_rec_txt_direc").val()).toUpperCase(),
            glosa: ($("#vw_emi_rec_txt_glosa").val()).toUpperCase(),
            total: $("#vw_em_rec_txt_detalle_total").val().replace(',', ''),
            recibo: $("#vw_emi_rec_txt_nrecibo").val(),
            id_pers:$("#vw_emi_rec_txt_id_pers").val(),
            clase_recibo:1
        },
        success: function (data) {
            if (data) {
                array_detalle_rec(data);
                $.confirm({
                    title: 'Codigo de Caja',
                    content: '<center><h3 style="margin-top:0px;font-size:40px">'+data+'</h3></center>',
                    buttons: {
                        Aceptar: function () {}                                
                    }
                });
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
                $("#vw_emi_rec_txt_nombres_raz_soc").val(result.contrib);
                $("#vw_emi_rec_txt_id_pers").val(result.id_pers);
               $("#vw_emi_rec_txt_direc").val(result.direccion);  
                MensajeDialogLoadAjaxFinish('vw_emi_rec_txt_nombres_raz_soc');
            } else if (result.msg == 'no') {
                dlg_new_persona(nro_doc);
               
            }
        },
        error: function (result) {
            MensajeDialogLoadAjaxFinish('vw_emi_rec_txt_nombres_raz_soc');
            mostraralertas('* Error de Red.<br>* No se puede ingresar a la Base de Datos.');
        }
    });
}
function dlg_new_persona(nro_doc){
    $("#dialog_Personas").dialog({
        autoOpen: false, modal: true, width: 700, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: PERSONAS :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-success bg-color-green",
                click: function () { new_persona(); }                
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () { $(this).dialog("close"); }
            }],
        close: function (event, ui) { limpiar_personas();},
        open: function (){ limpiar_personas(); }
    }).dialog('open');
    $("#cb_tip_doc_3").val($("#vw_emi_rec_txt_selec_tip_doc").val());
    $("#pers_nro_doc").val(nro_doc);
    tipo = $("#cb_tip_doc_3").val();
    if(tipo=='02'){
        get_datos_dni();
        $("#pers_pat,#pers_mat,#pers_nombres").removeAttr('disabled');
        $("#pers_raz_soc").removeAttr('disabled');        
        $("#pers_raz_soc").attr('disabled',true);
        $("#pers_nro_doc").removeAttr('maxlength');
        $("#pers_nro_doc").attr('maxlength',8);        
    }else if (tipo=='00'){
        get_datos_ruc();
        $("#pers_raz_soc").removeAttr('disabled');
        $("#pers_raz_soc").val('');
        $("#pers_pat,#pers_mat,#pers_nombres").attr('disabled',true);
        $("#pers_nro_doc").removeAttr('maxlength');
        $("#pers_nro_doc").attr('maxlength',11);        
    }
}

function new_persona(){
    if($("#cb_tip_doc_3").val()=='02'){
        if($("#pers_sexo").val()=='-'){
            mostraralertasconfoco('Ingrese Sexo','#pers_sexo');
            return false;
        }
//        if($("#pers_fnac").val()==''){
//            mostraralertasconfoco('Ingrese Fecha Nacimiento','#pers_fnac');
//            return false;
//        }
    }
    
    $.ajax({  
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'insert_personas',
        type: 'POST',
        data:{
            pers_ape_pat : $("#pers_pat").val() || '-',
            pers_ape_mat : $("#pers_mat").val() || '-',
            pers_nombres : $("#pers_nombres").val() || '-',
            pers_raz_soc : $("#pers_raz_soc").val() || '-',
            pers_tip_doc : $("#cb_tip_doc_3").val() || '-',
            pers_nro_doc : $("#pers_nro_doc").val() || '-',
            pers_sexo : $("#pers_sexo").val() || '-',
            pers_fnac : $("#pers_fnac").val() || '1900-01-01'
        },
        success: function (data) {
            consultar_persona();
            dialog_close('dialog_Personas');
        },
        error: function (data) {
            MensajeAlerta('* Error de Red...<br>* Contactese con el Administrador...');
        }
    });    
}
function limpiar_personas(){
    $("#pers_nro_doc,#pers_pat,#pers_mat,#pers_nombres,#pers_raz_soc,#pers_fnac").val('');
}

function limpiar_form_rec_varios() {
    $("#vw_emi_rec_txt_nro_doc").val('');
    $("#vw_emi_rec_txt_nombres_raz_soc").val('');

    $("#vw_emi_rec_txt_tributo").val('');
    $("#hiddenvw_emi_rec_txt_tributo").val('');
    $("#vw_emi_rec_txt_cantidad").val('');
    $("#vw_emi_rec_txt_valor").val('');
    $("#vw_emi_rec_txt_nrecibo").val('');
    $("#vw_emi_rec_txt_glosa").val('');
     $("#vw_emi_rec_txt_direc").val('');
    $("#vw_em_rec_txt_detalle_total").val('000.000');
    cont = 0;
    detalle_total = 0;
    $("#t_dina_det_recibo > tbody > tr").remove();
}

