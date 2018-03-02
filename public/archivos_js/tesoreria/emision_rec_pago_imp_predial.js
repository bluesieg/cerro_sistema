 
function dialog_emi_rec_pag_imp_predial() {    
    $("#vw_emision_rec_pag_imp_predial").dialog({
        autoOpen: false, modal: true, width: 1100, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.: RECIBO IMPUESTO PREDIAL :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-fax'></i>&nbsp; Generar Recibo",
                "class": "btn btn-primary",
                click: function () {
                    verificar_fraccionamiento();
                }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () { $(this).dialog("close"); }
            }],
        open: function () {limpiar_form_rec_imp_predial();},
        close: function () {limpiar_form_rec_imp_predial();}
    }).dialog('open');
}
interrup=0;
function gen_recibo_imp_predial(){
    var Seleccionados = new Array();
    anio = $('#vw_emi_rec_imp_pre_contrib_anio').val();
    $('input[type=checkbox][name=chk_trim]:checked').each(function() {
        Seleccionados.push($(this).val());
    });
    s_checks = Seleccionados.join('');
    ss_checks = Seleccionados.join('-');
    
    if($("#vw_emi_rec_imp_pred_glosa").val()==''){
        $("#vw_emi_rec_imp_pred_glosa").val("-");
//        mostraralertasconfoco('Ingrese Glosa del recibo.','#vw_emi_rec_imp_pred_glosa');
//        return false;
    }
    if($("#vw_emi_rec_imp_pre_contrib").val()==''){
        mostraralertasconfoco('Ingrese un Contribuyente','#vw_emi_rec_imp_pre_contrib');
        return false;
    }
    
    tot_trim = parseFloat($("#vw_emision_rec_pago_imp_pred_total_trimestre").val());
    if(tot_trim==0){
        mostraralertas('No hay trimestres para seleccionar o Todos los Trimestres estan pagados');
        return false;
    }
    if(s_checks==0){
        mostraralertasconfoco('Haga click en un Trimestre para Generar el Recibo','#vw_emi_rec_imp_pre_contrib');
        return false;
    }
    
    $.ajax({
        url:'verif_est_cta_coactiva?anio=' + anio,
        type:'GET',
        data:{check:s_checks,id_contrib:$("#vw_emi_rec_imp_pre_contrib_hidden").val()},
        success: function(data){
            if(data.length>0){                
                mostraralertas('No se Puede Generar El Recibo<br>Trimestre(s): '+data+', Estan en Cobranza Coactiva'); 
            }else{
                gen_rec_imp_pred(ss_checks);
            }
        }        
    });
    
}


function verificar_fraccionamiento(){
    $.ajax({
        url:'verif_est_cta_fraccionamiento',
        type:'GET',
        data:{id_contrib:$("#vw_emi_rec_imp_pre_contrib_hidden").val()},
        success: function(data){
            if (data.msg == 'si'){
                
                    mostraralertas('No se Puede Generar El Recibo<br>, La Deuda esta en Fraccionamiento'); 
                    
                }else{
                    gen_recibo_imp_predial();
                }
        }        
    });
}

function gen_rec_imp_pred(ss_checks){
    
    
    detalle_trimestres="";
    $('input[type=checkbox][name=chk_trim]:checked').each(function() {
            detalle_trimestres=detalle_trimestres+$(this).val()+", ";
    });
    $.confirm({
        title: '.:Recibo:.',
        content: 'Generar Recibo por '+ss_checks+' trimestre(s)',
        buttons: {
            Confirmar: function () {
                formatos = ($("#vw_emi_rec_imp_pre_contrib_anio option:selected").attr("formatos"));
                MensajeDialogLoadAjax('vw_emision_rec_pag_imp_predial', 'Generando Recibo...');
                $.ajax({
                    url: 'emi_recibo_master/create',
                    type: 'GET',
                    data: {
                        id_est_rec: 1,
                        glosa: ($("#vw_emi_rec_imp_pred_glosa").val()).toUpperCase(),
                        total: $("#vw_emision_rec_pago_imp_pred_total_trimestre").val().replace(',', ''),
                        id_pers:$("#vw_emi_rec_imp_pre_contrib_hidden").val(),
                        periodo:$("#vw_emi_rec_imp_pre_contrib_anio").val(),
                        clase_recibo:0,
                        id_trib_pred:$("#vw_emi_rec_imp_pre_contrib_anio option:selected").attr("predial"),
                        id_trib_form:formatos,
                        montopre: (parseFloat($("#vw_emision_rec_pago_imp_pred_total_trimestre").val().replace(',', ''))-parseFloat($("#table_cta_cte2").getCell(formatos, 'saldo'))),
                        montoform: $("#table_cta_cte2").getCell(formatos, 'saldo'),
                        trimestres:ss_checks,
                        detalle_trimestres:detalle_trimestres
                        
                    },
                    success: function (data) {
                        if (data) {
                            fn_actualizar_grilla('table_Resumen_Recibos','grid_Resumen_recibos?fecha=' + $("#vw_emision_reg_pag_fil_fecha").val());
                            $.confirm({
                                title: 'Codigo de Caja',
                                content: '<center><h3 style="margin-top:0px;font-size:40px">'+data+'</h3></center>',
                                buttons: {
                                    Aceptar: function ()
                                    {
                                        MensajeDialogLoadAjaxFinish('vw_emision_rec_pag_imp_predial');
                                        $("#vw_emision_rec_pag_imp_predial").dialog('close');
                                    }                                
                                }
                            });
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


var global_tot_a_pagar = 0;
var select_check=0;
var select_check_form=0;
var inter=0;
function calc_tot_a_pagar_predial(num,esto){
    if($(esto).not(':checked')){
        $('input[type=checkbox][name=chk_total]').prop('checked', false);
    }
    rowId=($("#vw_emi_rec_imp_pre_contrib_anio option:selected").attr("predial"));
    pre_x_trim = parseFloat($("#table_cta_cte2").getCell(rowId, 'ivpp'));                    
    pre_x_trim = (pre_x_trim/4);
   
    total=0;
    $('input[type=checkbox][name=chk_trim]:checked').each(function() {
        total=parseFloat(total)+parseFloat(pre_x_trim);
       
    });
    var formatos = ($("#vw_emi_rec_imp_pre_contrib_anio option:selected").attr("formatos"));
    form = $("#table_cta_cte2").getCell(formatos, 'saldo') || '0.00';
    $("#vw_emision_rec_pago_imp_pred_total_trimestre").val(formato_numero(parseFloat(total)+parseFloat(form),2,'.',','));

}
var select_check_2=0;

var globalinputcontri="";
function fn_bus_contrib_predial(input){
    globalinputcontri=input;
    if($("#"+input).val()=="")
    {
        mostraralertasconfoco("Ingrese un Contribuyente para Buscar","#"+input); 
        return false;
    }
    if($("#"+input).val().length<4)
    {
        mostraralertasconfoco("Ingresar al menos 4 caracteres de busqueda","#"+input); 
        return false;
    }
    fn_actualizar_grilla('table_contrib','obtiene_cotriname?dat='+$("#"+input).val());
    jQuery('#table_contrib').jqGrid('bindKeys', {"onEnter":function( rowid ){fn_bus_contrib_list(rowid);} } ); 
    $("#dlg_bus_contr").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Busqueda de Contribuyente :.</h4></div>"       
        }).dialog('open');       
}
function fn_bus_contrib_list(per){
    $("#"+globalinputcontri+"_hidden").val(per);
    
    $("#"+globalinputcontri+"_cod").val($('#table_contrib').jqGrid('getCell',per,'id_per'));    
    $("#"+globalinputcontri).val($('#table_contrib').jqGrid('getCell',per,'contribuyente'));
    tam=($('#table_contrib').jqGrid('getCell',per,'contribuyente')).length;
    anio=$("#"+globalinputcontri+"_anio").val();
    $("#"+globalinputcontri).attr('maxlength',tam);
    id_contrib=per;
    if(globalinputcontri=='vw_emi_rec_imp_pre_contrib')
    {
    fn_actualizar_grilla('table_cta_cte2','get_grid_cta_cte2?id_contrib='+id_contrib+'&ano_cta='+anio);
    }
    if(globalinputcontri='vw_emi_rec_imp_pre_contrib')
    {
        fn_actualizar_grilla('table_Predios_Arbitrios','grid_pred_arbitrios?id_contrib='+id_contrib+'&anio='+anio);
    }
    $("#dlg_bus_contr").dialog("close");    
}
function filter_anio(anio){
    //rowId=($("#vw_emi_rec_imp_pre_contrib_anio option:selected").attr("formatos"));
    //alert(rowId);
    //ACTUALIZA GRILLA
    fn_actualizar_grilla('table_cta_cte2','get_grid_cta_cte2?id_contrib='+id_contrib+'&ano_cta='+anio);
}

function limpiar_form_rec_imp_predial(){
    $("#vw_emi_rec_imp_pre_contrib").val('');
    $("#vw_emi_rec_imp_pre_contrib_cod").val('');
    $("#vw_emi_rec_imp_pre_contrib_hidden").val('');
    $("#vw_emi_rec_imp_pred_glosa").val('');
    global_tot_a_pagar=0;
    select_check=0;
    inter=0;
    fn_actualizar_grilla('table_cta_cte2','get_grid_cta_cte2?id_contrib=0&ano_cta=0');
}

function marcar_todos_predial(valor,esto,id){
    if($(esto).is(':checked')){
        $('input[type=checkbox][name=chk_trim]').prop('checked', true);
    }
    $total=0;
    $('input[type=checkbox][name=chk_trim]:checked').each(function(){
        $total=parseFloat(redondeo($total,2))+parseFloat(redondeo($(this).val(),2));
    });

    var formatos = ($("#vw_emi_rec_imp_pre_contrib_anio option:selected").attr("formatos"));
    form = $("#table_cta_cte2").getCell(formatos, 'saldo') || '0.00';
    $("#vw_emision_rec_pago_imp_pred_total_trimestre").val(formato_numero(parseFloat(valor)+parseFloat(form),2,'.',','));

}
