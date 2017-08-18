  
function dialog_emi_rec_pag_imp_predial() {    
    $("#vw_emision_rec_pag_imp_predial").dialog({
        autoOpen: false, modal: true, width: 815, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.: RECIBO IMPUESTO PREDIAL :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-fax'></i>&nbsp; Generar Recibo",
                "class": "btn btn-primary",
                click: function () {
                    gen_recibo_imp_predial();
                }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () { $(this).dialog("close"); }
            }],
        open: function () {limpiar_form_rec_imp_predial();},
        close: function () {limpiar_form_rec_imp_predial();}
    }).dialog('open');
//    $("#vw_emi_rec_txt_selec_tip_doc").val('02');
//    $("#vw_emi_rec_txt_nro_doc").attr('maxlength',8);
}
function gen_recibo_imp_predial(){
    var Seleccionados = new Array();
    $('input[type=checkbox][name=chk_trim]:checked').each(function() {
        Seleccionados.push($(this).val());
    });
    s_checks = Seleccionados.join('');
    
    var formatosSeleccionados = new Array();
    $('input[type=checkbox][name=chk_trim_form]:checked').each(function() {
        formatosSeleccionados.push($(this).val());
    });
    formatos_checks = formatosSeleccionados.join('');
//    alert("Dias seleccionados => " + s_checks);
//    return false;
    if($("#vw_emi_rec_imp_pre_contrib").val()==''){
        mostraralertasconfoco('Ingrese un Contribuyente','#vw_emi_rec_imp_pre_contrib');
        return false;
    }
    if($("#vw_emi_rec_imp_pred_glosa").val()==''){
        mostraralertasconfoco('Ingrese Glosa del recibo.','#vw_emi_rec_imp_pred_glosa');
        return false;
    }
    if(select_check==0){
        mostraralertasconfoco('Haga click en un Trimestre para Generar el Recibo','#vw_emi_rec_imp_pre_contrib');
        return false;
    }
    
    $.confirm({
        title: '.:Recibo:.',
        content: 'Generar Recibo por '+select_check+' trimestre(s)',
        buttons: {
            Confirmar: function () {
                MensajeDialogLoadAjax('vw_emision_rec_pag_imp_predial', 'Generando Recibo...');
                $.ajax({
                    url: 'emi_recibo_master/create',
                    type: 'GET',
                    data: {
                        id_est_rec: 1,
                        glosa: ($("#vw_emi_rec_imp_pred_glosa").val()).toUpperCase(),
                        total: $("#vw_emision_rec_pago_imp_pred_total_trimestre").val().replace(',', ''),
                        id_pers:$("#vw_emi_rec_imp_pre_id_pers").val(),
                        clase_recibo:0,
                        pred_check : s_checks,
                        form_pred_check:formatos_checks || '0'
                    },
                    success: function (data) {
                        if (data) {
                            imp_pred_insert_detalle(data);
                        } else {
                            mostraralertas('* Ha Ocurrido un Error al Generar Recibo.<br>* Actualice el Sistema e Intentelo Nuevamente.');
                        }
                    },
                    error: function (data) {
                        mostraralertas('* Error de Red.<br>* Contactese con el Administrador.');
                        MensajeDialogLoadAjaxFinish('vw_emision_rec_pag_imp_predial');
                    }
                });

            },
            Cancelar: function () {}
        }
    });
}
function imp_pred_insert_detalle(id_recibo){
    rowId=$('#table_cta_cte2').jqGrid ('getGridParam', 'selrow');
    
    monto = (parseFloat($("#vw_emision_rec_pago_imp_pred_total_trimestre").val().replace(',', ''))-parseFloat($("#table_cta_cte2").getCell(104, 'saldo')));
    $.ajax({
        url: 'emi_recibo_detalle/create',
        type: 'GET',
        data: {
            id_rec_master: id_recibo,
            id_trib: rowId,
            monto: monto,
            cant: select_check,
            p_unit:$("#vw_emis_re_pag_pre_x_trim").val().replace(',', '')
        },
        success: function (data) {
            if (data) {
                pago_im_formatos=$("#table_cta_cte2").getCell(104, 'saldo');
                if(pago_im_formatos!=0){
                    $.ajax({
                        url: 'emi_recibo_detalle/create',
                        type: 'GET',
                        data: {
                            id_rec_master: id_recibo,
                            id_trib: 104,
                            monto: pago_im_formatos,
                            cant: 4,
                            p_unit: (pago_im_formatos/4)
                        }                   
                    });
                }                
                MensajeDialogLoadAjaxFinish('vw_emision_rec_pag_imp_predial');
                dialog_close('vw_emision_rec_pag_imp_predial');
                fn_actualizar_grilla('table_Resumen_Recibos', 'grid_Resumen_recibos?fecha=' + $("#vw_emision_reg_pag_fil_fecha").val());
                MensajeExito('Nuevo Recibo', 'El Recibo Ha sido Generado.');                
            }
        },
        error: function (data) {
            return false;
        }
    });
}

var global_tot_a_pagar = 0;
var select_check=0;
var select_check_form=0;
var inter=0;
function calc_tot_a_pagar(num){
    rowId=103;
    if(inter==0){inter=1;global_tot_a_pagar = parseFloat($("#vw_emision_rec_pago_imp_pred_total_trimestre").val());}
    pre_x_trim = parseFloat($("#table_cta_cte2").getCell(rowId, 'ivpp'));                    
    pre_x_trim = (pre_x_trim/4);
   
    if($("#chk_calc_pag_"+num).is(':checked')){
        select_check++;
        global_tot_a_pagar=(global_tot_a_pagar+pre_x_trim);
        $("#vw_emision_rec_pago_imp_pred_total_trimestre").val(formato_numero(global_tot_a_pagar,2,'.',','));
    } else {
        select_check--;        
        global_tot_a_pagar=(global_tot_a_pagar-pre_x_trim);
        $("#vw_emision_rec_pago_imp_pred_total_trimestre").val(formato_numero(global_tot_a_pagar,2,'.',','));
    }   
}
var select_check_2=0;
function calc_tot_a_pagar_form(num){
    rowId=104;
    pre_x_trim = parseFloat($("#table_cta_cte2").getCell(rowId, 'ivpp'));                    
    pre_x_trim = (pre_x_trim/4);
//    alert(pre_x_trim);
    if($("#chk_calc_form_imp_"+num).is(':checked')){
//        select_check_2++;
        global_tot_a_pagar=(global_tot_a_pagar+pre_x_trim);
        $("#vw_emision_rec_pago_imp_pred_total_trimestre").val(formato_numero(global_tot_a_pagar,2,'.',','));
    } else {
//        select_check_2--;        
        global_tot_a_pagar=(global_tot_a_pagar-pre_x_trim);
        $("#vw_emision_rec_pago_imp_pred_total_trimestre").val(formato_numero(global_tot_a_pagar,2,'.',','));
    }
}

function calcular_tot_a_pagar(){
//    alert($("#vw_emi_rec_imp_pred_hora_act").val());
}

function fn_bus_contrib(){
    if($("#vw_emi_rec_imp_pre_contrib").val()=="")
    {
        mostraralertasconfoco("Ingrese un Contribuyente para Buscar","#dlg_contri"); 
        return false;
    }
    if($("#vw_emi_rec_imp_pre_contrib").val().length<4)
    {
        mostraralertasconfoco("Ingresar al menos 4 caracteres de busqueda","#dlg_contri"); 
        return false;
    }
    fn_actualizar_grilla('table_contrib','obtiene_cotriname?dat='+$("#vw_emi_rec_imp_pre_contrib").val());                  
//    jQuery("#table_contrib").jqGrid('setGridParam', {url: 'obtiene_cotriname?dat='+$("#vw_emi_rec_imp_pre_contrib").val()}).trigger('reloadGrid');
    jQuery('#table_contrib').jqGrid('bindKeys', {"onEnter":function( rowid ){fn_bus_contrib_list(rowid);} } ); 
    $("#dlg_bus_contr").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Busqueda de Contribuyente :.</h4></div>"       
        }).dialog('open');
       
}
function fn_bus_contrib_list(per){
    $("#vw_emi_rec_imp_pre_id_pers").val(per);
    
    $("#vw_emi_rec_imp_pre_cod_contrib").val($('#table_contrib').jqGrid('getCell',per,'id_per'));    
    $("#vw_emi_rec_imp_pre_contrib").val($('#table_contrib').jqGrid('getCell',per,'contribuyente'));
    tam=($('#table_contrib').jqGrid('getCell',per,'contribuyente')).length;
    anio=$("#vw_emi_rec_imp_pre_anio").val();
    
    $("#vw_emi_rec_imp_pre_contrib").attr('maxlength',tam);
    id_pers=$('#table_contrib').jqGrid('getCell',per,'id_pers');
    fn_actualizar_grilla('table_cta_cte2','get_grid_cta_cte2?id_pers='+id_pers+'&ano_cta='+anio);
    $("#dlg_bus_contr").dialog("close");    
}

function limpiar_form_rec_imp_predial(){
    $("#vw_emi_rec_imp_pre_contrib").val('');
    $("#vw_emi_rec_imp_pre_cod_contrib").val('');
    $("#vw_emi_rec_imp_pre_id_pers").val('');
    $("#vw_emi_rec_imp_pred_glosa").val('');
    global_tot_a_pagar=0;
    select_check=0;
    inter=0;
    fn_actualizar_grilla('table_cta_cte2','get_grid_cta_cte2?id_pers=0&ano_cta=0');
}

